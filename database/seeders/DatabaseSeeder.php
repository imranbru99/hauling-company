<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(614) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@blacktrailpaving.com'],
            [
                'name' => 'Sam Rivera',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(614) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'crew@blacktrailpaving.com'],
            [
                'name' => 'Nate Brooks',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(614) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Blacktrail Paving Co.',
            'company_phone' => '(614) 555-0187',
            'company_email' => 'hello@blacktrailpaving.com',
            'whatsapp' => '16145550187',
            'service_area' => 'Columbus, Dublin, Westerville & Central Ohio',
            'tax_rate' => '7.50',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Hot-Mix Asphalt — Surface Course (ton)', 'sku' => 'HMA-SUR-T', 'category' => 'hotmix', 'unit_cost' => 78, 'unit_price' => 125, 'stock_qty' => 220, 'unit' => 'ton'],
            ['name' => 'Hot-Mix Asphalt — Binder Course (ton)', 'sku' => 'HMA-BIN-T', 'category' => 'hotmix', 'unit_cost' => 72, 'unit_price' => 115, 'stock_qty' => 180, 'unit' => 'ton'],
            ['name' => '#57 Crushed Stone Base (ton)', 'sku' => 'AGG-57-T', 'category' => 'aggregate', 'unit_cost' => 28, 'unit_price' => 48, 'stock_qty' => 340, 'unit' => 'ton'],
            ['name' => 'Tack Coat Emulsion (gal)', 'sku' => 'TCK-EML-G', 'category' => 'emulsion', 'unit_cost' => 3.2, 'unit_price' => 6.5, 'stock_qty' => 900, 'unit' => 'gal'],
            ['name' => 'Sealcoat Emulsion (gal)', 'sku' => 'SEA-EML-G', 'category' => 'sealcoat', 'unit_cost' => 2.8, 'unit_price' => 5.9, 'stock_qty' => 1200, 'unit' => 'gal'],
            ['name' => 'Crack Fill Rubberized (box)', 'sku' => 'CRK-RUB-B', 'category' => 'crackfill', 'unit_cost' => 42, 'unit_price' => 78, 'stock_qty' => 45, 'unit' => 'box'],
            ['name' => 'Geotextile Fabric Roll', 'sku' => 'GEO-FAB-R', 'category' => 'fabric', 'unit_cost' => 95, 'unit_price' => 160, 'stock_qty' => 16, 'unit' => 'roll'],
            ['name' => 'Plate Compactor Rental Day', 'sku' => 'TL-PLT-D', 'category' => 'tool', 'unit_cost' => 35, 'unit_price' => 75, 'stock_qty' => 6, 'unit' => 'day'],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                $product + ['is_active' => true]
            );
        }

        $customers = collect([
            ['name' => 'Allison Kent', 'email' => 'allison@example.com', 'phone' => '(614) 555-2201', 'company' => null, 'city' => 'Columbus', 'state' => 'OH', 'zip' => '43215', 'address' => '812 German Village Ave'],
            ['name' => 'Derek Cho', 'email' => 'derek@northloopretail.com', 'phone' => '(614) 555-2210', 'company' => 'North Loop Retail', 'city' => 'Dublin', 'state' => 'OH', 'zip' => '43017', 'address' => '4500 Metro Center Dr'],
            ['name' => 'Priya Shah', 'email' => 'ops@midwestlogistics.com', 'phone' => '(740) 555-2299', 'company' => 'Midwest Logistics', 'city' => 'Groveport', 'state' => 'OH', 'zip' => '43125', 'address' => '2100 Commerce Parkway'],
            ['name' => 'Marcus Lee', 'email' => 'marcus.lee@example.com', 'phone' => '(614) 555-2188', 'company' => null, 'city' => 'Westerville', 'state' => 'OH', 'zip' => '43081', 'address' => '119 Meadowbrook Ln'],
            ['name' => 'Helen Ortiz', 'email' => 'helen@oakridgehoa.org', 'phone' => '(614) 555-2177', 'company' => 'Oakridge HOA', 'city' => 'Hilliard', 'state' => 'OH', 'zip' => '43026', 'address' => '88 Oakridge Commons'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Two-car driveway full-depth', 'service_type' => 'driveway', 'status' => 'scheduled', 'sq_ft' => 640, 'quoted_amount' => 5120],
            ['customer' => 1, 'title' => 'Retail lot mill & overlay', 'service_type' => 'overlay', 'status' => 'in_progress', 'sq_ft' => 28000, 'quoted_amount' => 98000],
            ['customer' => 2, 'title' => 'Warehouse apron rebuild', 'service_type' => 'parking_lot', 'status' => 'quoted', 'sq_ft' => 12000, 'quoted_amount' => 72000],
            ['customer' => 3, 'title' => 'Driveway patch + sealcoat', 'service_type' => 'sealcoat', 'status' => 'completed', 'sq_ft' => 520, 'quoted_amount' => 2100, 'final_amount' => 2250],
            ['customer' => 4, 'title' => 'HOA street resurfacing phase 1', 'service_type' => 'road', 'status' => 'invoiced', 'sq_ft' => 18000, 'quoted_amount' => 54000, 'final_amount' => 53200],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for Blacktrail ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.075, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.075, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.075, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Driveway measure — Kent'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'measure',
                'starts_at' => now()->addDays(2)->setTime(9, 0),
                'ends_at' => now()->addDays(2)->setTime(10, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Retail lot paving day 1'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(9)->setTime(6, 30),
                'ends_at' => now()->addDays(9)->setTime(16, 30),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Why Thin Overlays Fail on Soft Driveway Bases',
                'excerpt' => 'Paving over pumping clay is the fastest way to alligator cracks — here is how Blacktrail prevents it.',
                'cover' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=1400&q=80',
                'body' => "Ohio freeze–thaw punishes weak bases. If water sits under a thin asphalt skin, the mat flexes, cracks, and potholes within a season or two.\n\nBlacktrail probes soft spots, rebuilds aggregate base to design depth, and only then lays hot mix with proper compaction. If a contractor quotes an overlay without talking about base, ask what happens after the next spring thaw.",
            ],
            [
                'title' => 'Mill & Overlay vs Full-Depth Rebuild: Choosing the Right Scope',
                'excerpt' => 'Sound base favors milling. Failed base needs excavation. Matching scope saves money twice.',
                'cover' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1400&q=80',
                'body' => "Mill & overlay restores ride quality when the structure underneath is still competent. Full-depth rebuilds dig out failed pavement and stone so the new mat has something to sit on.\n\nOn commercial lots we often combine both: rebuild soft islands and entrances, mill the remainder, then pave a continuous wearing course. The site visit decides — not a one-line email quote.",
            ],
            [
                'title' => 'When to Sealcoat New Asphalt in Central Ohio',
                'excerpt' => 'Seal too early and the emulsion peels. Wait too long and UV oxidizes the binder.',
                'cover' => 'https://images.unsplash.com/photo-1494412579283-8b6ee3eadd18?auto=format&fit=crop&w=1400&q=80',
                'body' => "Fresh hot mix needs time to cure before sealcoat. Applying emulsion too soon traps oils and can peel. Waiting years lets UV and water age the binder faster than necessary.\n\nBlacktrail typically recommends sealcoating after the pavement has cured and oil spots are cleaned — then crack-fill first. Ask us for a maintenance calendar with your closeout packet.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Alligator cracking across the drive', 'body' => "1. Confirm whether cracks are surface-only or connected to soft base.\n2. Probe edges for pumping fines after rain.\n3. Saw-cut to sound asphalt; rebuild stone where base failed.\n4. Compact hot mix in lifts — do not thin-overlay alligator zones."],
            ['category' => 'issues', 'title' => 'Ponding water in parking lot low spots', 'body' => "Low spots usually mean settlement or poor cross-slope. Survey elevations, mill or build leveling course, and pave to positive drainage. Sealcoat will not fix ponding."],
            ['category' => 'guides', 'title' => 'Driveway paving day timeline', 'body' => "Day 0: Clear cars and keep pets inside.\nMorning: Excavate / mill, haul spoils, place and compact base.\nMidday: Tack (if overlay) and pave hot mix.\nAfternoon: Roll to density, check edges and transitions.\n+24–72h: Careful vehicle traffic depending on thickness and weather."],
            ['category' => 'guides', 'title' => 'How to care for new asphalt', 'body' => "Avoid sharp steering while the mat is warm. Clean oil drips promptly. Do not park heavy trailers on soft edges in summer. Delay sealcoat until the pavement has cured enough for emulsion to bond."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with sq ft and quote.\n3. Schedule appointments for measure/pave days.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job running over tonnage estimate', 'body' => "Check actual sq ft and depth vs quote. Confirm extra base stone for soft spots. Log additional tons on the job notes, create a change-order invoice line, and update inventory stock quantities."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
