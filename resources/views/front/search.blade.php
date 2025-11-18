@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')
<div class="container py-5">
    <div class="row g-4">
    <h2 class="mb-4 text-center">Hasil pencarian untuk: <strong>{{ $query }}</strong></h2>
        <hr>
        @if(count($results) == 0)
        <p>Tidak ada hasil.</p>
        @endif

        <div class="list-group">
            @foreach($results as $r)
            <a href="{{ $r['url'] }}" class="list-group-item list-group-item-action d-flex">
                @if($r['image'])
                <img src="{{ is_img(asset('uploads/'.$r['image']), asset('front/img/no-image.webp')) }}" width="40" class="me-2 rounded">
                @endif

                <div>
                    <small class="text-muted">{{ $r['type'] }}</small><br>
                    {!! highlightHtmlMultiple($r['title'], explode(' ', $query)) !!}


                </div>
            </a>
            @endforeach
        </div>

    </div>
</div>

@endsection