<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'how long asphalt cure dry time drive',
            'a' => 'Hot-mix asphalt is usually firm enough for careful vehicle traffic within 24–72 hours depending on thickness and temperature. Avoid sharp turns and heavy trucks for several days. Full hardening continues as the mix cools and densifies.',
        ],
        [
            'q' => 'driveway cost price estimate sq ft',
            'a' => 'Most residential driveway paving projects in Central Ohio run roughly $4–$10 per sq ft installed, depending on excavation depth, base stone, drainage, and mix. Request a free on-site measure for an exact quote.',
        ],
        [
            'q' => 'mill overlay patch sealcoat services',
            'a' => 'Blacktrail offers full-depth driveway paving, commercial parking lot paving, mill & overlay, saw-cut patching, sealcoating, and light municipal / HOA street resurfacing across Columbus and Central Ohio.',
        ],
        [
            'q' => 'base stone compaction drainage',
            'a' => 'Every Blacktrail full-depth job verifies subgrade, places compacted aggregate base to design depth, and sets cross-slope so water sheds off the pavement. Soft base is the #1 reason asphalt fails early.',
        ],
        [
            'q' => 'warranty maintenance clean sealcoat',
            'a' => 'Residential installs include a workmanship warranty spelled out in your contract. Keep oil drips cleaned, sealcoat when the pavement is structurally sound, and avoid parking heavy trailers on soft edges in summer heat.',
        ],
        [
            'q' => 'commercial parking lot industrial',
            'a' => 'For commercial lots we design for drainage, delivery traffic, and phased access so businesses stay open. After cure, lots can be restriped. Ask about mill & overlay vs full rebuild during the site visit.',
        ],
        [
            'q' => 'schedule appointment quote contact',
            'a' => 'Call or text (614) 555-0187, use the contact form, or WhatsApp us. Site visits for Columbus, Dublin, Westerville, and surrounding Central Ohio towns are usually available within 2–3 business days.',
        ],
        [
            'q' => 'pothole alligator cracking freeze thaw',
            'a' => 'Potholes and alligator cracking usually signal base failure or water intrusion. We saw-cut to sound asphalt, rebuild base when needed, and compact hot mix in lifts — not just dump cold patch and hope.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for an asphalt paving company about: {$prompt}",
            'description' => "Write a compelling product/service description for asphalt paving: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for an asphalt paving SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this asphalt paving operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Blacktrail Paving Co. We pave driveways, parking lots, and mill & overlays across Columbus and Central Ohio. Ask about cure times, pricing, sealcoat timing, patching, or scheduling—or call (614) 555-0187 for a free site visit.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Blacktrail Paving Co. assistant for a Columbus, Ohio asphalt paving contractor. Be concise, practical, and focused on driveways, parking lots, mill & overlay, patching, sealcoating, and Central Ohio freeze–thaw conditions. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are Blacktrail Paving Co. assistant (Columbus asphalt paving). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
