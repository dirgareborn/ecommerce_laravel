<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\VirtualTour;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('virtualTours')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'image_thumbnail' => 'nullable|image',
            'status' => 'nullable|boolean',
            'virtual_tour.*.tour_type' => 'required_with:virtual_tour.*.tour_url|string|in:360,video',
            'virtual_tour.*.tour_url' => 'required_with:virtual_tour.*.tour_type|string',
        'virtual_tour.*.name' => 'nullable|string|max:255', // optional, bisa diisi
    ]);

        if($request->hasFile('image_thumbnail')){
            $data['image_thumbnail'] = $request->file('image_thumbnail')->store('rooms','public');
        }

        $room = Room::create($data);

        if($request->has('virtual_tour')){
            foreach($request->virtual_tour as $index => $tour){
                $tour['name'] = $tour['name'] ?? $room->name . ' Scene ' . ($index + 1);
                $room->virtualTours()->create($tour);
            }
        }

        return redirect()->route('rooms.index')->with('success','Room created successfully');
    }


    public function edit(Room $room)
    {
        return view('admin.rooms.form', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'image_thumbnail' => 'nullable|image',
            'status' => 'nullable|boolean',
            'virtual_tour.*.id' => 'nullable|exists:virtual_tours,id',
            'virtual_tour.*.tour_type' => 'required_with:virtual_tour.*.tour_url|string|in:360,video',
            'virtual_tour.*.tour_url' => 'required_with:virtual_tour.*.tour_type|string',
        ]);

        if($request->hasFile('image_thumbnail')){
            if($room->image_thumbnail){
                Storage::disk('public')->delete($room->image_thumbnail);
            }
            $data['image_thumbnail'] = $request->file('image_thumbnail')->store('rooms','public');
        }

        $room->update($data);

        // Update or create virtual tours
        if($request->has('virtual_tour')){
            foreach($request->virtual_tour as $tour){
                if(isset($tour['id'])){
                    $room->virtualTours()->where('id',$tour['id'])->update($tour);
                } else {
                    $room->virtualTours()->create($tour);
                }
            }
        }

        return redirect()->route('rooms.index')->with('success','Room updated successfully');
    }

    public function destroy(Room $room)
    {
        // Delete thumbnail
        if($room->image_thumbnail){
            Storage::disk('public')->delete($room->image_thumbnail);
        }
        $room->virtualTours()->delete();
        $room->delete();
        return redirect()->route('rooms.index')->with('success','Room deleted successfully');
    }
}
