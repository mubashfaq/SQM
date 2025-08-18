<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AreaGroup;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Menus;
use App\Models\MenusMeta;

class HeaderController extends Controller
{
    public function index(){
        $menus = Menus::with('menuMeta')->get();
        return view('Admin.Header.index', compact('menus'));
    }

    public function save(Request $request){
        try {
            Menus::truncate();
            MenusMeta::truncate();
            $navItemTitles = $request->input('nav_item_title');
            $navItemUrls = $request->input('nav_item_url');
            $navItemNumbers = $request->input('nav_item_number');
            $submenuItemTitles = $request->input('submenue_item_title');
            $submenuItemUrls = $request->input('submenue_item_url');

            foreach ($navItemTitles as $key => $navItemTitle) {
                $menu = Menus::create([
                    'title' => $navItemTitle,
                    'url' => $navItemUrls[$key],
                    'number' => $navItemNumbers[$key],
                ]);
                if (isset($submenuItemTitles[$key]) && is_array($submenuItemTitles[$key])) {
                    foreach ($submenuItemTitles[$key] as $subMenuKey => $submenuTitle) {
                        $menuMeta = MenusMeta::create([
                            'title' => $submenuTitle,
                            'url' => $submenuItemUrls[$key][$subMenuKey],
                            'menu_id' => $menu->id,
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Menu items added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while adding menu items: ' . $e->getMessage());
        }
    }
}
