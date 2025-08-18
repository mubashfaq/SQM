@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
 
<!-- Custom CSS -->
<style>
    .header-builder {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .menu-container {
        display: flex;
        overflow-x: auto;
        padding: 20px 0;
        gap: 15px;
        scrollbar-width: thin;
        scrollbar-color: #6c757d #f8f9fa;
    }
    
    .menu-container::-webkit-scrollbar {
        height: 8px;
    }
    
    .menu-container::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .menu-container::-webkit-scrollbar-thumb {
        background-color: #6c757d;
        border-radius: 10px;
    }
    
    .menu-card {
        min-width: 320px;
        max-width: 320px;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 10px;
        background-color: white;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .menu-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .menu-card-header {
        background-color: #f1f3f5;
        border-radius: 8px 8px 0 0;
        padding: 12px 15px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .menu-items-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        scrollbar-width: thin;
    }
    
    .menu-items-container::-webkit-scrollbar {
        width: 5px;
    }
    
    .menu-items-container::-webkit-scrollbar-track {
        background: #f8f9fa;
    }
    
    .menu-items-container::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 10px;
    }
    
    .submenu-item {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        position: relative;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    .submenu-item:hover {
        border-color: #6c757d;
    }
    
    .delete-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
    
    .card-actions {
        padding: 12px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        border-radius: 0 0 8px 8px;
    }
    
    .add-menu-column {
        min-width: 320px;
        height: 250px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: white;
        transition: all 0.3s ease;
    }
    
    .add-menu-column:hover {
        border-color: #6c757d;
        background-color: #f8f9fa;
    }
    
    .form-floating {
        margin-bottom: 15px;
    }
    
    .header-title {
        position: relative;
        padding-bottom: 10px;
    }
    
    .header-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100px;
        height: 3px;
        background-color: #dc3545;
    }
    
    .header-note {
        margin-bottom: 20px;
    }
    
    .btn-icon {
        display: flex;
        align-items: center;
        gap: 5px;
    }
</style>

<!-- Page Content -->
<div class="page-content">
    <div class="header-builder">
        <form action="{{ route('Admin.Header.Save') }}" method="post">
            @csrf
            
            <!-- Header Section -->
            <div class="row align-items-center mb-4">
                <div class="col-lg-8">
                   <h2 class="header-title mb-2 text-black dark:text-black">Website Header Builder</h2>

                    <p class="header-note text-danger mb-0">
                        <i class="fa fa-info-circle me-1"></i> Changes will be applied to the Product Website when you click the <strong>'Save'</strong> button
                    </p>
                </div>
                @if (Session::has('permissions') && in_array('Save Header', Session::get('permissions')))
                <div class="col-lg-4 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Save 
                    </button>
                </div>
                @endif
            </div>
            
            <!-- Menu Builder Area -->
            <div class="menu-container">
                @if (!$menus->isEmpty())
                    @for ($i = 0; $i < count($menus); $i++)
                        <div class="menu-card nav-item-counts">
                            <!-- Menu Card Header -->
                            <div class="menu-card-header">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="menuTitle{{$i}}" placeholder="Menu Title" 
                                        value="{{ $menus[$i]['title'] }}" name="nav_item_title[{{ $i }}]">
                                    <label for="menuTitle{{$i}}">Menu Item Title</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="menuUrl{{$i}}" placeholder="Menu URL" 
                                        value="{{ $menus[$i]['url'] }}" name="nav_item_url[{{ $i }}]">
                                    <label for="menuUrl{{$i}}">Menu Item URL</label>
                                </div>
                                
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="menuOrder{{$i}}" placeholder="Display Order" 
                                        value="{{ $menus[$i]['number'] }}" name="nav_item_number[{{ $i }}]">
                                    <label for="menuOrder{{$i}}">Display Order</label>
                                </div>
                            </div>
                            
                            <!-- Menu Items Container -->
                            <div class="menu-items-container">
                                @for ($j = 0; $j < count($menus[$i]->menuMeta); $j++)
                                    <div class="submenu-item">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="subMenuTitle{{$i}}{{$j}}" 
                                                placeholder="Submenu Title" value="{{ $menus[$i]->menuMeta[$j]['title'] }}" 
                                                name="submenue_item_title[{{ $i }}][{{ $j }}]">
                                            <label for="subMenuTitle{{$i}}{{$j}}">Sub Menu Title</label>
                                        </div>
                                        
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="subMenuUrl{{$i}}{{$j}}" 
                                                placeholder="Submenu URL" value="{{ $menus[$i]->menuMeta[$j]['url'] }}" 
                                                name="submenue_item_url[{{ $i }}][{{ $j }}]">
                                            <label for="subMenuUrl{{$i}}{{$j}}">Sub Menu URL</label>
                                        </div>
                                        
                                        @if (Session::has('permissions') && in_array('Delete Item', Session::get('permissions')))
                                        <button type="button" onclick="sub_menu_remove(this)" 
                                            class="delete-btn btn btn-sm btn-outline-danger rounded-circle" 
                                            title="Remove Sub Menu Item">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                            
                            <!-- Menu Card Actions -->
                            @if (Session::has('permissions') && (in_array('Delete Menu', Session::get('permissions')) || in_array('Add Item', Session::get('permissions'))))
                            <div class="card-actions d-flex justify-content-between">
                                @if (Session::has('permissions') && in_array('Delete Menu', Session::get('permissions')))
                                <button type="button" class="btn btn-outline-danger" onclick="delete_nav_item(this)">
                                    <i class="fa fa-trash me-1"></i> Delete Menu
                                </button>
                                @endif
                                
                                @if (Session::has('permissions') && in_array('Add Item', Session::get('permissions')))
                                <button type="button" class="btn btn-primary" onclick="add_menu_item(this)" nav-item-menu-index="{{ $i }}">
                                    <i class="fa fa-plus me-1"></i> Add Item
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>
                    @endfor
                @endif
                
                <!-- Add New Menu Column -->
                @if (Session::has('permissions') && in_array('Add New Menu', Session::get('permissions')))
                <div class="add-menu-column">
                    <button type="button" class="btn btn-primary btn-lg add_nav_item">
                        <i class="fa fa-plus me-2"></i> Add New Menu
                    </button>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

@include('Admin.Includes.footer')

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('.add_nav_item').click(function() {
            var nav_item_count = $('.nav-item-counts').length;
            $('.add-menu-column').before(`
                <div class="menu-card nav-item-counts">
                    <!-- Menu Card Header -->
                    <div class="menu-card-header">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="menuTitle${nav_item_count+1}" 
                                placeholder="Menu Title" name="nav_item_title[${nav_item_count+1}]">
                            <label for="menuTitle${nav_item_count+1}">Menu Item Title</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="menuUrl${nav_item_count+1}" 
                                placeholder="Menu URL" name="nav_item_url[${nav_item_count+1}]">
                            <label for="menuUrl${nav_item_count+1}">Menu Item URL</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="number" class="form-control" id="menuOrder${nav_item_count+1}" 
                                placeholder="Display Order" name="nav_item_number[${nav_item_count+1}]">
                            <label for="menuOrder${nav_item_count+1}">Display Order</label>
                        </div>
                    </div>
                    
                    <!-- Menu Items Container -->
                    <div class="menu-items-container">
                    </div>
                    
                    <!-- Menu Card Actions -->
                    @if (Session::has('permissions') && (in_array('Delete Menu', Session::get('permissions')) || in_array('Add Item', Session::get('permissions'))))
                    <div class="card-actions d-flex justify-content-between">
                        @if (Session::has('permissions') && in_array('Delete Menu', Session::get('permissions')))
                        <button type="button" class="btn btn-outline-danger" onclick="delete_nav_item(this)">
                            <i class="fa fa-trash me-1"></i> Delete Menu
                        </button>
                        @endif
                        
                        @if (Session::has('permissions') && in_array('Add Item', Session::get('permissions')))
                        <button type="button" class="btn btn-primary" onclick="add_menu_item(this)" nav-item-menu-index="${nav_item_count+1}">
                            <i class="fa fa-plus me-1"></i> Add Item
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            `);
        });
    });

    function delete_nav_item(obj_) {
        $(obj_).closest('.menu-card').fadeOut(300, function() {
            $(this).remove();
        });
    }

    function add_menu_item(obj_) {
        var index_nav_id = $(obj_).attr('nav-item-menu-index');
        var j = $(obj_).closest('.menu-card').find('.submenu-item').length;
        
        $(obj_).closest('.menu-card').find('.menu-items-container').append(`
            <div class="submenu-item">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="subMenuTitle${index_nav_id}${j}" 
                        placeholder="Submenu Title" name="submenue_item_title[${index_nav_id}][]">
                    <label for="subMenuTitle${index_nav_id}${j}">Sub Menu Title</label>
                </div>
                
                <div class="form-floating">
                    <input type="text" class="form-control" id="subMenuUrl${index_nav_id}${j}" 
                        placeholder="Submenu URL" name="submenue_item_url[${index_nav_id}][]">
                    <label for="subMenuUrl${index_nav_id}${j}">Sub Menu URL</label>
                </div>
                
                @if (Session::has('permissions') && in_array('Delete Item', Session::get('permissions')))
                <button type="button" onclick="sub_menu_remove(this)" 
                    class="delete-btn btn btn-sm btn-outline-danger rounded-circle" 
                    title="Remove Sub Menu Item">
                    <i class="fa fa-times"></i>
                </button>
                @endif
            </div>
        `);
    }

    function sub_menu_remove(obj_) {
        $(obj_).closest('.submenu-item').fadeOut(300, function() {
            $(this).remove();
        });
    }
</script>