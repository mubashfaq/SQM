<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\CategorySlider;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use File;

class CategorysliderController extends Controller
{

public function create()
{
    $categories = Category::all();
    return view('Admin.CategorySliders.create',compact('categories'));
}
public function index()
{
    // $sliders = CategorySlider::all();
    $activeStatuses = [0, 1, 2];
    $catSliders = CategorySlider::whereIn('active', $activeStatuses)->get();
    return view('Admin.CategorySliders.index', compact('catSliders'));
}
public function edit(CategorySlider $catslider)
{
    $categories = Category::all();
    return view('Admin.CategorySliders.edit', compact('catslider','categories'));
}
public function store(Request $request)
{
    
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'created_by' => 'required|integer',
        'category_id' => 'required|integer',
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
            ->fit(720, 800) // Adjust the dimensions as needed
            ->encode();

        # Store the optimized image in the products folder
        $optimizedImage->save(public_path('CategorySliders') . '/' . $imageName);

        # Remove the temporary image
        unlink(public_path('temp') . '/' . $imageName);

        # Set the image path in the validated data
        $validatedData['slider_image'] = 'CategorySliders/' . $imageName;
    }
    
    $slider = CategorySlider::create($validatedData);
    return redirect()->route('catsliders.create')->with('success', 'Slider created successfully.');
}
public function update(Request $request,$id)
{
    $validatedData =  $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'required|integer',
        'active' => 'required|integer',
    ]);


    $slider = CategorySlider::find($id);

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
            ->fit(720, 800) // Adjust the dimensions as needed
            ->encode();

        # Store the optimized image in the products folder
        $optimizedImage->save(public_path('CategorySliders') . '/' . $imageName);

        # Remove the temporary image
        unlink(public_path('temp') . '/' . $imageName);

        # Set the image path in the validated data
        $validatedData['slider_image'] = 'CategorySliders/' . $imageName;
    }

    $slider->update($validatedData);
    return redirect()->route('catsliders.edit', ['catslider' => $slider])->with('success', 'Slider updated successfully.');
}
public function destroy($id)
{
    $slider = CategorySlider::find($id);
    if (!$slider) {
        return Redirect::back()->withErrors(['Slider not found.']);
    }
    $slider->delete();
    return redirect()->route('catsliders.index')->with('success', 'Slider deleted successfully.');
}
public function updateStatus(Request $request, CategorySlider $catslider)
{
    $request->validate([
        'active' => 'required',
    ]);
    $catslider->active = $request->active;
    $catslider->save();
    return response()->json([
        'status' => 200,
        'message' => 'Status updated successfully.',
    ]);
}
public function trash()
{
    $catSliders = CategorySlider::where('active',3)->get();
    return view('Admin.CategorySliders.trash', compact('catSliders'));
}

}
