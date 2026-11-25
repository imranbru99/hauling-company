<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Blacktrail will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'driveway-paving' => [
                'title' => 'Residential Driveway Paving',
                'summary' => 'Full-depth hot-mix driveways with compacted stone base and clean edges.',
                'body' => 'Blacktrail excavates failed pavement, rebuilds base to design depth, and lays hot-mix asphalt with proper cross-slope so water leaves the drive — not pools in the middle. Ideal for Columbus and suburban residential properties that need more than a thin overlay.',
                'image' => 'https://images.unsplash.com/photo-1590674899484-d5640e854abe?auto=format&fit=crop&w=1600&q=80',
            ],
            'parking-lot-paving' => [
                'title' => 'Commercial Parking Lot Paving',
                'summary' => 'Lot rebuilds engineered for drainage, stall layout, and delivery traffic.',
                'body' => 'From retail pads to office campuses, we phase paving to keep entrances open, correct ponding, and deliver a dense mat ready for striping. Optional coordination with restriping contractors after cure.',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80',
            ],
            'mill-overlay' => [
                'title' => 'Mill & Overlay',
                'summary' => 'Remove the failed wearing course, restore profile, pave a new lift.',
                'body' => 'When base is sound but the surface is oxidized or rutted, mill & overlay restores ride quality without a full-depth rebuild. We mill to depth, clean the deck, tack, and pave a calibrated overlay.',
                'image' => 'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=1600&q=80',
            ],
            'asphalt-patching' => [
                'title' => 'Asphalt Patching & Repair',
                'summary' => 'Saw-cut patches for potholes, utility cuts, and alligator sections.',
                'body' => 'We saw-cut to sound asphalt, rebuild base where needed, and compact hot mix in lifts. Better than throw-and-go cold patch when you want a lasting repair before winter.',
                'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1600&q=80',
            ],
            'sealcoating' => [
                'title' => 'Sealcoating',
                'summary' => 'Protective emulsion coats that refresh oxidized asphalt and slow aging.',
                'body' => 'On structurally sound pavements we clean, crack-fill, and apply professional-grade sealcoat. Timing matters — we do not sealcoat brand-new hot mix until it has cured enough for the emulsion to bond.',
                'image' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1600&q=80',
            ],
            'road-resurfacing' => [
                'title' => 'Road & Street Resurfacing',
                'summary' => 'Light municipal and HOA street overlays with traffic control planning.',
                'body' => 'Neighborhood streets, HOA roads, and private lanes get milled or leveled and overlaid with mix suited to the traffic class. We plan phasing so residents keep access during the pour window.',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
