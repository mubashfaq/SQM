<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contactu;
class ContactlistController extends Controller
{
public function index()
    {
        $contacts = Contactu::orderBy('created_at', 'desc')->get();
        return view('Admin.ContactUs.index', compact('contacts'));
    }
    public function destroy($id)
    {
        $contact = Contactu::find($id); 
        if (!$contact) {
            return redirect()->back()->withErrors(['Record not found.']);
        }
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Record deleted successfully.');
    }   
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        Contactu::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Selected records deleted successfully']);
    }
    public function getcontactdetails($id)
    {
        $contactdetails = Contactu::find($id);
        return response()->json($contactdetails);
    }
}
