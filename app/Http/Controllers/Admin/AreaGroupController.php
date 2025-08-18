<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AreaGroup;
use App\Models\GroupsMeta;

class AreaGroupController extends Controller
{
    public function index()
    {
        $areaGroups = AreaGroup::with('groupsMeta')->get();
        return view('Admin.AreaGroups.index', compact('areaGroups'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'sku' => 'required',
            'country' => 'required',
            'meta_csv' => 'file',
        ]);
        $areaGroup = new AreaGroup();
        $areaGroup->title = $validatedData['title'];
        $areaGroup->sku = $validatedData['sku'];
        $areaGroup->country = $validatedData['country'];
        // Save the AreaGroup instance first to generate an ID
        if ($areaGroup->save()) {
            $areaGroupId = $areaGroup->id;
            if ($request->hasFile('meta_csv')) {
                $file = $request->file('meta_csv');
                $csvData = file_get_contents($file);
                $rows = array_map('str_getcsv', explode("\n", $csvData));
                $header = array_shift($rows);
                $metaRecords = [];
                foreach ($rows as $row) {
                    $groupsMeta = new GroupsMeta();
                    $groupsMeta->area_group_id = $areaGroupId;
                    $groupsMeta->title = isset($row[0]) ? $row[0] : ''; // Set a default value for title if it's not provided
                    $groupsMeta->postcode = isset($row[1]) ? $row[1] : ''; // Set a default value for postcode if it's not provided
                    $groupsMeta->sku = isset($row[2]) ? $row[2] : ''; // Set a default value for SKU if it's not provided
                    $metaRecords[] = [
                        'area_group_id' => $groupsMeta->area_group_id,
                        'title' => $groupsMeta->title,
                        'postcode' => $groupsMeta->postcode,
                        'sku' => $groupsMeta->sku,
                    ];
                }
                // Insert group meta records in bulk
                GroupsMeta::insert($metaRecords);
            }
            return redirect()->route('area-groups.index')->with('success', 'Area Group created successfully!');
        }
        // Handle failure to save the AreaGroup
        return redirect()->back()->with('error', 'Failed to create Area Group.');
    }
    public function add_more_group_areas(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'meta_csv' => 'file'
        ]);
        if ($request->hasFile('meta_csv')) {
            $file = $request->file('meta_csv');
            $csvData = file_get_contents($file);
            $rows = array_map('str_getcsv', explode("\n", $csvData));
            $header = array_shift($rows);
            $metaRecords = [];
            foreach ($rows as $row) {
                if (isset($row[0]) && isset($row[1])) { // Check if both title and postcode are set
                    $groupsMeta = new GroupsMeta();
                    $groupsMeta->area_group_id = $validatedData['id'];
                    $groupsMeta->title = $row[0];
                    $groupsMeta->postcode = $row[1];
                    $metaRecords[] = [
                        'area_group_id' => $groupsMeta->area_group_id,
                        'title' => $groupsMeta->title,
                        'postcode' => $groupsMeta->postcode,
                    ];
                }
            }
            GroupsMeta::insert($metaRecords);
        }
        return redirect()->route('area-groups.index')->with('success', 'Area Group created successfully!');
    }
    public function show($id)
    {
        // Retrieve the area group based on the given ID
        $areaGroup = AreaGroup::findOrFail($id);
        // Return a response or view to display the area group details
        return view('area-groups.show', compact('areaGroup'));
    }
    public function destroy(AreaGroup $areaGroup)
    {
        $areaGroup->delete();
        // Return a success response
        return response()->json(['message' => 'Area Group deleted successfully'], 200);
    }
    public function update(Request $request, $area_group)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'sku' => 'required',
            'country' => 'required',
        ]);

        $areaGroup = AreaGroup::findOrFail($area_group);
        $areaGroup->title = $validatedData['title'];
        $areaGroup->sku = $validatedData['sku'];
        $areaGroup->country = $validatedData['country'];
        $areaGroup->update();

        return response()->json([
            'success' => true,
            'message' => 'Area group updated successfully.',
            'title' => $areaGroup->title,
            'sku' => $areaGroup->sku
        ]);
    }
    public function getCodeCount($areaGroupId)
    {
        $areaGroup = AreaGroup::find($areaGroupId);
        if (!$areaGroup) {
            return response()->json(['error' => 'Area group not found'], 404);
        }
        $codeCount = GroupsMeta::where('area_group_id', $areaGroupId)->count();
        return response()->json(['count' => $codeCount]);
    }
    public function viewCode($areaGroupId)
    {
        $areaGroup = AreaGroup::find($areaGroupId);
        if (!$areaGroup) {
            return response()->json(['error' => 'Area group not found'], 404);
        }
        $groupMeta = GroupsMeta::where('area_group_id', $areaGroupId)->get();
        return response()->json(['data' => $groupMeta]);
    }
    public function deleteCode($areaGroupId, $id)
    {
        // Find the area group and the corresponding code
        $areaGroup = AreaGroup::findOrFail($areaGroupId);
        $code = GroupsMeta::findOrFail($id);

        // Delete the code
        $code->delete();

        // Optionally, you can perform additional actions or validations here

        return response()->json(['success' => true]);
    }
    public function updateCode(Request $request, $areaGroupId, $id)
    {
        // Find the area group and the corresponding code
        $areaGroup = AreaGroup::findOrFail($areaGroupId);
        $code = GroupsMeta::findOrFail($id);

        // Validate the request data
        $request->validate([
            'title' => 'required|string',
            'postcode' => 'required|string',
        ]);
        // Update the code with the new title and postcode
        $code->title = $request->input('title');
        $code->postcode = $request->input('postcode');
        $code->save();

        // Optionally, you can perform additional actions or validations here
        return response()->json(['success' => true]);
    }
}
