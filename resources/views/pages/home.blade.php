@extends('layouts.app')

@section('title', 'Blacktrail Paving Co.')
@section('meta_description', 'Hot-mix asphalt driveways, parking lots, and road overlays installed across Columbus and Central Ohio by Blacktrail Paving Co.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Blacktrail<br>Paving Co.</h1>
        <p>Proper base, tight compaction, and clean edges. Driveways, lots, and municipal overlays built for Ohio freeze–thaw — not thin cold-patch cover-ups.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a site visit</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">View paving services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Paving services</h2>
        <p class="lede">Full-depth installs, mill & overlay, patching, and sealcoating — scoped to residential, commercial, and light municipal work.</p>
        <div class="grid-3">
            @foreach([
                ['driveway-paving','Driveway Paving','Hot-mix residential drives with compacted base and crisp edges.','https://images.unsplash.com/photo-1590674899484-d5640e854abe?auto=format&fit=crop&w=1200&q=80'],
                ['parking-lot-paving','Parking Lot Paving','Commercial lots rebuilt for drainage, stalls, and truck traffic.','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80'],
                ['mill-overlay','Mill & Overlay','Mill out failed lift, restore profile, pave a new wearing course.','https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Blacktrail</h2>
        <p class="lede">Central Ohio crews that treat base depth and compaction density as non-negotiable — because asphalt fails from the bottom up.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Base-first builds</h3>
                <p>We verify subgrade, stone depth, and drainage before the first ton of hot mix hits the mat.</p>
            </div>
            <div class="panel">
                <h3>Matched mix designs</h3>
                <p>Driveway, lot, and overlay mixes selected for traffic load — not one “black pile” for every job.</p>
            </div>
            <div class="panel">
                <h3>ERP-run jobs</h3>
                <p>Quotes, paving days, tonnage, invoices, and follow-ups live in one operations stack.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Blacktrail ERP used to schedule crews, track mix inventory, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>18</strong> Active jobs</div>
                    <div class="metric"><strong>$242k</strong> Pipeline</div>
                    <div class="metric"><strong>14</strong> Site visits</div>
                </div>
                <div class="bars">
                    <span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span>
                    <span style="height:80%"></span><span style="height:55%"></span><span style="height:90%"></span>
                    <span style="height:70%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);text-transform:uppercase;margin-top:0;font-size:1.4rem">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & paving schedules</li>
                    <li>Mix / emulsion inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the paving yard</h2>
        <p class="lede">Guides and field notes for homeowners and facility managers.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
