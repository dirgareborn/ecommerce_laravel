@extends('admin.layout.app')


@section('title', isset($room) ? 'Edit Room' : 'Create Room')

@section('content')
<form action="{{ isset($room) ? route('rooms.update',$room) : route('rooms.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($room)) @method('PUT') @endif

    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name',$room->name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ old('description',$room->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label>Capacity</label>
        <input type="number" name="capacity" class="form-control" value="{{ old('capacity',$room->capacity ?? '') }}">
    </div>

    <div class="form-group">
        <label>Thumbnail</label>
        <input type="file" name="image_thumbnail" class="form-control">
        @if(isset($room) && $room->image_thumbnail)
            <img src="{{ asset('storage/'.$room->image_thumbnail) }}" width="80">
        @endif
    </div>

    <div class="form-group">
        <label>Status</label>
        <input type="checkbox" name="status" value="1" {{ old('status',$room->status ?? 1) ? 'checked' : '' }}>
    </div>

    <hr>
    <h5>Virtual Tours</h5>
    <div id="virtualToursWrapper">
        @if(isset($room))
            @foreach($room->virtualTours as $tour)
                <div class="tour-item mb-2">
                    <select name="virtual_tour[{{ $loop->index }}][tour_type]" class="form-control">
                        <option value="360" {{ $tour->tour_type=='360'?'selected':'' }}>360°</option>
                        <option value="video" {{ $tour->tour_type=='video'?'selected':'' }}>Video</option>
                    </select>
                    <input type="text" name="virtual_tour[{{ $loop->index }}][tour_url]" class="form-control" value="{{ $tour->tour_url }}">
                    <input type="hidden" name="virtual_tour[{{ $loop->index }}][id]" value="{{ $tour->id }}">
                    <button type="button" class="btn btn-sm btn-danger removeTour">Remove</button>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" id="addTour" class="btn btn-sm btn-success">Add Virtual Tour</button>

    <br><br>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('addTour').addEventListener('click', function(){
    let index = document.querySelectorAll('.tour-item').length;
    let wrapper = document.getElementById('virtualToursWrapper');
    let div = document.createElement('div');
    div.classList.add('tour-item','mb-2');
    div.innerHTML = `
        <select name="virtual_tour[${index}][tour_type]" class="form-control">
            <option value="360">360°</option>
            <option value="video">Video</option>
        </select>
        <input type="text" name="virtual_tour[${index}][tour_url]" class="form-control" placeholder="URL">
        <button type="button" class="btn btn-sm btn-danger removeTour">Remove</button>
    `;
    wrapper.appendChild(div);
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeTour')){
        e.target.parentElement.remove();
    }
});
</script>
@endpush

