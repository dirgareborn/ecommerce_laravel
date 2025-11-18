@extends('admin.layout.app')

@section('title', 'Rooms')

@section('content_header')
<h1>Rooms</h1>
<a href="{{ route('rooms.create') }}" class="btn btn-primary">Add Room</a>
@stop

@section('content')
<table class="table table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Thumbnail</th>
    <th>Capacity</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($rooms as $room)
<tr>
    <td>{{ $room->id }}</td>
    <td>{{ $room->name }}</td>
    <td>
        @if($room->image_thumbnail)
            <img src="{{ asset('storage/'.$room->image_thumbnail) }}" width="80">
        @endif
    </td>
    <td>{{ $room->capacity }}</td>
    <td>{{ $room->status ? 'Active' : 'Inactive' }}</td>
    <td>
        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('rooms.destroy', $room) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
@stop
