@extends('front.layouts.app')

@push('style')
<style>
    #viewer { width: 100%; height: 600px; }
</style>
@endpush

@section('content')
<div class="container">
    <h2>{{ $room->name }}</h2>
    <p>{{ $room->description }}</p>

    <div id="viewer" data-panorama='@json($room->virtualTours->where("tour_type","360")->map(fn($tour) => url($tour->tour_url)))'></div>
    <!-- <div id="viewer" data-panorama='@json([ asset("storage/rooms/google.png") ])'></div> -->

    @foreach($room->virtualTours->where('tour_type','video') as $tour)
        <video controls width="100%" class="my-3">
            <source src="{{ asset('storage/'.$tour->tour_url) }}" type="video/mp4">
        </video>
    @endforeach
</div>
@endsection

@push('scripts')
<!-- Three.js versi kompatibel -->
<script src="https://cdn.jsdelivr.net/npm/three@0.110.0/build/three.min.js"></script>
<!-- Panolens.js -->
<script src="https://cdn.jsdelivr.net/npm/panolens@0.12.1/build/panolens.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewerContainer = document.getElementById('viewer');
    const panoramaUrls = JSON.parse(viewerContainer.dataset.panorama);

    if (!panoramaUrls.length) return;

    const viewer = new PANOLENS.Viewer({ container: viewerContainer });
    const panoramas = panoramaUrls.map(url => new PANOLENS.ImagePanorama(url));

    // Tambahkan semua panorama ke viewer
    panoramas.forEach(pano => viewer.add(pano));

    // Contoh hotspot antar panorama jika ada lebih dari satu
    if (panoramas.length > 1) {
        for (let i = 0; i < panoramas.length; i++) {
            const nextIndex = (i + 1) % panoramas.length;
            panoramas[i].link(panoramas[nextIndex], new THREE.Vector3(5000, 0, 0));
        }
    }
});
</script>
@endpush
