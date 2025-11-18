@extends('front.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($rooms as $room)
        <div class="col-md-4">
            <div class="card mb-3">
                <img src="{{ asset('storage/'.$room->image_thumbnail) }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $room->name }}</h5>
                    <p class="card-text">{{ $room->description }}</p>
                    <a href="{{ route('rooms.show',$room) }}" class="btn btn-primary">Lihat Virtual Tour</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@stop
