<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Illuminate\Http\Request;

class DynamicPageController extends Controller
{
    public function index()
    {
        $pages = DynamicPage::where('active', '!=', 3)->get();
        return view('Admin.Pages.index', compact('pages'));
    }
    public function create()
    {
        $pages = DynamicPage::all();
        return view('Admin.Pages.create', compact('pages'));
    }

    public function edit($id)
    {
        $page = DynamicPage::findOrFail($id);
        return view('Admin.Pages.edit', compact('page'));
    }

    public function check_permalink(Request $request)
    {
        $request->validate([
            'permalink' => 'required|unique:dynamic_pages,permalink',
        ]);
        return response()->json([
            'status' => true,
        ]);
    }

    public function store(Request $request)
    {
        $page = DynamicPage::create([
            'title' => $request->title,
            'permalink' => $request->permalink,
            'permalink_status' => $request->permalink_status,
            'hero_heading' => $request->hero_heading,
            'content' => $request->content,
            'active' => 0,
            'seo_title' => $request->seo_title,
            'seo_tags' => $request->seo_tags,
            'seo_description' => $request->seo_description
        ]);

        return redirect()->route('pages.index')->with(['success' => 'Page created successfully!']);
    }

     public function update(Request $request, $id)
    {
        $permalinkstatus = $request->permalink_status ?? 1;
        $page = DynamicPage::where('id', $id)->update([
            'title' => $request->title,
            'permalink' => $request->permalink,
            'permalink_status' => $permalinkstatus,
            'hero_heading' => $request->hero_heading,
            'content' => $request->content,
            // 'active' => 0,
            'seo_title' => $request->seo_title,
            'seo_tags' => $request->seo_tags,
            'seo_description' => $request->seo_description
        ]);

        return redirect()->back()->with(['success' => 'Page updated successfully!']);
    }


    public function destroy(DynamicPage $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with(['success' => 'Page deleted successfully.']);
    }

    public function updateStatus(Request $request, DynamicPage $page)
    {
        $request->validate([
            'active' => 'required',
        ]);

        $page->active = $request->active;
        $page->save();

        return response()->json([
            'status' => 200,
            'message' => 'Page status updated successfully.',
        ]);
    }

    public function trash()
    {
        $pages = DynamicPage::where('active', 3)->get();
        return view('Admin.Pages.trash', compact('pages'));
    }
}
