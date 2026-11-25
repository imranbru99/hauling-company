@extends('layouts.app')
@section('title', 'Asphalt Paving Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Blacktrail installs driveways, parking lots, mill & overlays, patches, sealcoats, and light municipal resurfacing across Central Ohio.</p>
        <div class="grid-3">
            @foreach([
                ['driveway-paving','Driveway Paving','https://images.unsplash.com/photo-1590674899484-d5640e854abe?auto=format&fit=crop&w=1200&q=80'],
                ['parking-lot-paving','Parking Lot Paving','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80'],
                ['mill-overlay','Mill & Overlay','https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=1200&q=80'],
                ['asphalt-patching','Asphalt Patching & Repair','https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1200&q=80'],
                ['sealcoating','Sealcoating','https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1200&q=80'],
                ['road-resurfacing','Road & Street Resurfacing','https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
