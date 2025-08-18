<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use File;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
class DiscountController extends Controller
{

public function index()
{
    $activeStatuses = [0, 1, 2];
    $discounts = Discount::whereIn('active', $activeStatuses)->get();
    return view('Admin.Discounts.index', compact('discounts'));
}
public function create()
{
    
    return view('Admin.Discounts.create');
}
public function store(Request $request)
{
    $validated_data = $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'active' => 'required|integer',
        'discount_for' => 'required|integer', 
        'limited' => 'required|integer',
        'discount_type' => 'required|integer',
        'discount_value' => 'required|string',
        'badge' => 'nullable|string',
        'badge_text' => 'required|string',
        'badge_color' => 'required|string',
        'badge_image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'from_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:from_date',
    ]);

    $logged_in_user = Auth::user();
    $validated_data['created_by'] = $logged_in_user->id;

    if ($request->hasFile('badge_image')) {
        $images = $request->file('badge_image');
        $imagePaths = [];

        foreach ($images as $image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $tempDirectory = public_path('temp');
            $badgesDirectory = public_path('badges');
            if (!is_dir($tempDirectory)) {
                mkdir($tempDirectory, 0777, true);  // Create the 'temp' directory with proper permissions
            }
            if (!is_dir($badgesDirectory)) {
                mkdir($badgesDirectory, 0777, true);  // Create the 'badges' directory with proper permissions
            }
            $image->move($tempDirectory, $imageName);
            # Optimize the image using Intervention Image
            $manager = new ImageManager(new GdDriver());
            $optimizedImage = $manager->read($tempDirectory . '/' . $imageName)
                ->resize(1920, 700) // Adjust the dimensions as needed
                ->encode();
            # Store the optimized image in the badges folder
            $optimizedImage->save($badgesDirectory . '/' . $imageName);
            # Remove the temporary image
            unlink($tempDirectory . '/' . $imageName);
            # Add the image path to the array
            $imagePaths[] = 'badges/' . $imageName;
        }
        

        # Set the image paths in the validated data as a comma-separated string
        $validated_data['badge_image'] = implode(',', $imagePaths);
    }

    $discount = Discount::create($validated_data);

    return redirect()->route('discounts.create')->with('success', 'Discount created successfully.');
}
public function edit(Discount $discount)
{
    return view('Admin.Discounts.edit', compact('discount'));
}
public function update(Request $request, $id)
{

    $validated_data = $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'active' => 'required|integer',
        'discount_for' => 'nullable|integer',
        'limited' => 'required|integer',
        'discount_type' => 'required|integer',
        'discount_value' => 'required|string',
        'badge' => 'nullable|string',
        'badge_text' => 'required|string',
        'badge_color' => 'required|string',
        'badge_image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'from_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:from_date',
    ]);

    $discount = Discount::find($id);
    if (!$discount) {
        return Redirect::back()->withErrors(['Discount not found.']);
    }

    if ($request->hasFile('badge_image')) {
        $images = $request->file('badge_image');
        $imagePaths = [];

        foreach ($images as $image) {
            # Generate a unique image name
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('temp'), $imageName);
            $manager = new ImageManager(new GdDriver());
            $optimizedImage = $manager->read(public_path('temp') . '/' . $imageName)
                ->resize(1920, 700) // Adjust the dimensions as needed
                ->encode();
            $optimizedImage->save(public_path('badges') . '/' . $imageName);
            unlink(public_path('temp') . '/' . $imageName);
            $imagePaths[] = 'badges/' . $imageName;
        }

        # Set the image paths in the validated data as a comma-separated string
        $validated_data['badge_image'] = implode(',', $imagePaths);
    }

    $discount->update($validated_data);
    return redirect()->route('discounts.edit', ['discount' => $discount])->with('success', 'Discount updated successfully.');
}
public function destroy($id)
{
    $discount = Discount::find($id); 
    if (!$discount) {
        return Redirect::back()->withErrors(['Discount not found.']);
    }
    $discount->delete();
    return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');
    }
    public function updateStatus(Request $request, Discount $discount)
    {
    $request->validate([
        'active' => 'required',
    ]);

    $discount->active = $request->active;
    $discount->save();

    return response()->json([
        'status' => 200,
        'message' => 'status updated successfully.',
    ]);
    }
    public function trash()
    {
    $discounts = Discount::where('active',3)->get();
    return view('Admin.Discounts.trash', compact('discounts'));
}

}
