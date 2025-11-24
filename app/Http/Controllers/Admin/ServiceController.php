<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Service;
use App\Models\ServicesImage;
use App\Models\ServicesPrice;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['unit.department'])->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create', [
            'units' => Unit::with('department')->get(),
            'locations' => Location::all(),
            'service' => new Service,
            'slides' => collect(),
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'location_id' => 'nullable|exists:locations,id',
            'name' => 'required|string|max:200',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_price_per_type' => 'nullable|boolean',
            'customer_type.*' => 'nullable|in:umum,civitas,mahasiswa',
            'price.*' => 'nullable|numeric|min:0',
            'facility' => 'nullable|string',
        ]);

        // Create Service
        $service = new Service;
        $service->unit_id = $request->unit_id;
        $service->location_id = $request->location_id;
        $service->name = $request->name;
        $service->description = $request->description;
        $service->base_price = $request->base_price ?? 0;
        $service->status = $request->status ? 1 : 0;
        $service->is_price_per_type = $request->boolean('is_price_per_type');
        $service->slug = Str::slug($request->name).'-'.Str::random(5);

        // facility → JSON
        if ($request->facility) {
            $service->facility = collect(explode(',', $request->facility))->map(fn ($i) => ['name' => trim($i)]);
        }

        // Upload cover image
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = 'cover-'.Str::slug($request->name).'-'.Str::random(3).'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('uploads/services', $file, $filename);
            $service->image = $filename;
        }

        $service->save();

        // Upload slide images
        if ($request->hasFile('slides')) {
            foreach ($request->file('slides') as $img) {
                $name = 'slide-'.Str::slug($request->name).'-'.Str::random(4).'.'.$img->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('uploads/services/slides', $img, $name);
                $service->slides()->create([
                    'service_id' => $service->id,
                    'image' => $name,
                ]);
            }
        }

        // Save price per customer type
        if ($request->is_price_per_type == true) {
            foreach ($request->customer_type ?? [] as $key => $type) {
                if (! $type || empty($request->price[$key])) {
                    continue;
                }

                ServicesPrice::create([
                    'service_id' => $service->id,
                    'customer_type' => $type,
                    'price' => $request->price[$key],
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created');
    }

    public function edit(Service $service)
    {
        // dd($service->facility);
        return view('admin.services.edit', [
            'service' => $service,
            'units' => Unit::with('department')->get(),
            'locations' => Location::all(),
            'slides' => $service->slides,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'location_id' => 'nullable|exists:locations,id',
            'name' => 'required|string|max:200',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'facility' => 'nullable|string',
            'customer_type.*' => 'nullable|in:umum,civitas,mahasiswa',
            'price.*' => 'nullable|numeric|min:0',
        ]);

        // Update service
        $service->unit_id = $request->unit_id;
        $service->location_id = $request->location_id;
        $service->name = $request->name;
        $service->description = $request->description;
        $service->base_price = $request->base_price ?? 0;
        $service->is_price_per_type = $request->boolean('is_price_per_type');
        $service->status = $request->status ? 1 : 0;

        if ($request->facility) {
            $service->facility = collect(explode(',', $request->facility))
                ->map(fn ($i) => ['name' => trim($i)]);
        }

        if ($request->hasFile('cover')) {
            Storage::disk('public')->delete('uploads/services/'.$service->image);
            $file = $request->file('cover');
            $filename = 'cover-'.Str::slug($request->name).'-'.Str::random(3).'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('uploads/services', $file, $filename);
            $service->image = $filename;
        }

        $service->save();

        // Replace slide images if new uploaded
        if ($request->hasFile('slides')) {
            foreach ($request->file('slides') as $img) {
                $name = 'slide-'.Str::slug($request->name).'-'.Str::random(3).'.'.$img->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('uploads/services/slides', $img, $name);
                $service->slides()->create([
                    'service_id' => $service->id,
                    'image' => $name,

                ]);
            }
        }

        // Replace price details
        ServicesPrice::where('service_id', $service->id)->delete();

        if ($request->is_price_per_type == 1) {
            foreach ($request->customer_type ?? [] as $key => $type) {
                if (! $type || empty($request->price[$key])) {
                    continue;
                }

                ServicesPrice::create([
                    'service_id' => $service->id,
                    'customer_type' => $type,
                    'price' => $request->price[$key],
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated');
    }

    public function deleteImageSlide($id)
    {
        $imgSlide = ServicesImage::select('image')->where('id', $id)->first();
        Storage::disk('public')->delete('uploads/services/'.$imgSlide);
        ServicesImage::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'Foto Slide  Berhasil dihapus');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Service deleted');
    }
}
