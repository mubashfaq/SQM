<?php

namespace App\Http\Controllers\Admin;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use File;

class SliderController extends Controller
{
    
public function create()
{
    $brands = Brand::where('active', '1')->get();
    return view('Admin.Sliders.create', compact('brands'));
}
public function index()
{
    $activeStatuses = [0, 1, 2];
    $sliders = Slider::whereIn('active', $activeStatuses)->get();
    return view('Admin.Sliders.index', compact('sliders'));
}
public function edit(Slider $slider)
{
    $brands = Brand::all();
    return view('Admin.Sliders.edit', compact('brands','slider'));
}
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'brand_id' => 'required|integer',
        'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'created_by' => 'required|integer',
        'active' => 'required|integer',
    ]);
    $logged_in_user = Auth::user();
    $validatedData['created_by'] = $logged_in_user->id;

    if ($request->hasFile('slider_image')) {
        $image = $request->file('slider_image');

        # Generate a unique image name
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        # Move the image to a temporary location
        $image->move(public_path('temp'), $imageName);

        # Optimize the image using Intervention Image
        $manager = new ImageManager();
        $optimizedImage = $manager->make(public_path('temp') . '/' . $imageName)
            ->fit(1920, 700) // Adjust the dimensions as needed
            ->encode();

        # Store the optimized image in the products folder
        $optimizedImage->save(public_path('sliders') . '/' . $imageName);

        # Remove the temporary image
        unlink(public_path('temp') . '/' . $imageName);

        # Set the image path in the validated data
        $validatedData['slider_image'] = 'sliders/' . $imageName;
    }

    $slider = Slider::create($validatedData); 
    return redirect()->route('sliders.create')->with('success', 'Slider created successfully.');
}
public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'brand_id' => 'required|integer',
        'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'active' => 'required|integer',
    ]);

    $slider = Slider::find($id); 

    if (!$slider) {
        return Redirect::back()->withErrors(['Slider not found.']); 
    }

    if ($request->hasFile('slider_image')) {
        $image = $request->file('slider_image');

        # Generate a unique image name
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        # Move the image to a temporary location
        $image->move(public_path('temp'), $imageName);

        # Optimize the image using Intervention Image
        $manager = new ImageManager();
        $optimizedImage = $manager->make(public_path('temp') . '/' . $imageName)
            ->fit(1920, 700) // Adjust the dimensions as needed
            ->encode();

        # Store the optimized image in the products folder
        $optimizedImage->save(public_path('sliders') . '/' . $imageName);

        # Remove the temporary image
        unlink(public_path('temp') . '/' . $imageName);

        # Set the image path in the validated data
        $validatedData['slider_image'] = 'sliders/' . $imageName;
    }
    $slider->update($validatedData);
    return redirect()->route('sliders.edit', ['slider' => $slider])->with('success', 'Slider updated successfully.');
}
public function destroy($id)
{
    $slider = Slider::find($id); 
    if (!$slider) {
        return Redirect::back()->withErrors(['Slider not found.']);
    }
    $slider->delete();

    return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
}
public function updateStatus(Request $request, Slider $slider)
{
    $request->validate([
        'active' => 'required',
    ]);
    $slider->active = $request->active;
    $slider->save();
    return response()->json([
        'status' => 200,
        'message' => 'status updated successfully.',
    ]);
}
public function trash()
{
    $sliders = Slider::where('active',3)->get();
    return view('Admin.Sliders.trash', compact('sliders'));
}
    
}
