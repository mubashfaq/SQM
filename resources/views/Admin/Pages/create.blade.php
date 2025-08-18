@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp

<style>
    .img-preview {
        max-width: 200px;
        height: auto;
        margin: 10px;
    }


    .btn-remove {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
    }

    .preview-wrapper {
        position: relative;
        display: inline-block;
        border: 2px solid transparent;
        margin: 5px;
    }

    .preview-wrapper.selected {
        border-color: #007bff;
    }

    .radio-button {
        position: absolute;
        top: 5px;
        left: 5px;
    }


    .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .multiselect {
        font-size: 15px;
        border-radius: 3px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: 0.2s;
        outline: none;
    }

    .multiselect:hover {
        border: 1px solid rgba(0, 0, 0, 0.3);
    }

    .multiselect.active {
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
        border-bottom: 1px solid transparent;
    }

    .multiselect>.title {
        cursor: pointer;
        height: 30px;
        padding: 6px;
    }

    .multiselect>.title>.text {
        max-height: 25px;
        display: block;
        float: left;
        overflow: hidden;
        line-height: 1.3em;
    }

    .multiselect>.title>.expand-icon,
    .multiselect>.title>.close-icon {
        float: right;
        border-radius: 50%;
        padding: 0 4px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        font-weight: 700;
        transition: 0.2s;
        display: none;
    }

    .multiselect.selection>.title>.expand-icon {
        display: none;
    }

    .multiselect>.title>.expand-icon,
    .multiselect.selection>.title>.close-icon {
        display: block;
    }

    .multiselect>.title>.close-icon:hover {
        border: 1px solid rgba(0, 0, 0, 0.3);
        background: rgb(203, 32, 32);
        color: #fff;
    }

    .multiselect>.container {
        max-height: 200px;
        overflow: auto;
        margin-top: 4px;
        margin-left: -1px;
        background: #fff;
        border: 1px solid transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .multiselect.active>.container {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        border-top: 0;
    }

    .multiselect:hover>.container {
        border-top-color: rgba(0, 0, 0, 0.3);
    }

    .multiselect.active:hover>.container {
        border-color: rgba(0, 0, 0, 0.3);
    }

    .multiselect>.container>optgroup {
        display: none;
        cursor: pointer;
        transition: 0.2s;
        border-top: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

    .multiselect>.container>optgroup.selected {
        background: rgb(122, 175, 233);
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    .multiselect>.container>option:hover {
        background: rgba(0, 0, 0, 0.1);
        color: #000;
    }

    .multiselect.active>.container>optgroup {
        display: block;
    }

    .selected-option {
        background-color: aliceblue;
    }

    /* test */
</style>
<!-- Page Inner Start -->
<!--================================-->
<div class="page-content">
            <!--================================-->
            <!-- Breadcrumb Start -->
            <!--================================-->
            <div class="card shadow-sm mb-4 p-3 pageheader pd-t-25 pd-b-35">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h4 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Create Page') }}</h4>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a class="link-primary text-decoration-none" href="{{ route('Admin.Dashboard') }}">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="link-primary text-decoration-none" href="{{ route('pages.index') }}">
                                        Pages
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Create
                                </li>
                            </ol>
                        </nav>
                        
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if (Session::has('permissions') && in_array('Product List', Session::get('permissions')))
                            <a href="{{ route('pages.index') }}" class="btn btn-primary">
                                <i class="fa-solid fa-list-ul me-1"></i> Page List
                            </a>
                        @endif
                       
                    </div>
                </div>
            </div>
            <!--/ Breadcrumb End -->
            <!--================================-->
            <!--================================-->
    <div class="col-md-12 col-lg-12 px-0">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }} | <a href="{{ session('productUrl') }}" target="_blank">View Page</a>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="product-create-form" method="POST" action="{{ route('pages.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">{{ __('Page Details (Compulsory)') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-control-label active">Title: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-lg restrict-input-alphabets_space addTitle"
                                    type="text" name="title" placeholder="Page Title"
                                    value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label active">Permalink: <span
                                        class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span
                                            class="input-group-text input-group-text">{{ url('/') }}/page/</span>
                                    </div>
                                    <input class="form-control form-control restrict-input-alphabets permalink"
                                        id="permalink" type="text" name="permalink" value="{{ old('permalink') }}"
                                        placeholder="page-title" required>
                                </div>
                                <div class="text-danger permalink_error"></div>
                                <input type="hidden" class="permalink_status" name="permalink_status"
                                    value="{{ old('permalink_status') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label active">Main Heading: <span
                                        class="tx-danger">*</span></label>
                                <input class="form-control form-control-lg restrict-input-alphabets_space"
                                    type="text" name="hero_heading" placeholder="Heading"
                                    value="{{ old('hero_heading') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label active">Long Description: </label>
                               <textarea name="content" class="form-control rich-editor" rows="15">{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">
                                        {{ __('Page SEO Details (Optional)') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-control-label active">SEO Title: </label>
                                <input type="text" class="form-control" name="seo_title"
                                    value="{{ old('seo_title') }}" placeholder="SEO Title...">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label active">SEO Tags: </label>
                                <input type="text" name="seo_tags" value="{{ old('seo_tags') }}"
                                    class="form-control " placeholder="SEO Tag" id="seo_tags">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label active">SEO Description: </label>
                                <textarea class="form-control" name="seo_description" placeholder="SEO Description...">{{ old('seo_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--/  Order Active dataTable End -->
    <!--================================-->
</div>
<!--/ Page Inner End -->








<!-- Initialize Summernote -->
<script>
    $(document).ready(function() {
        // Initialize rich text editor
        $('.rich-editor').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Write your blog content here...'
        });
    });
</script>
<script>
    $('.addTitle').on('input', function() {
        var title = $(this).val();
        var sku = title.replace(/\s+/g, '-').toLowerCase();
        $('.permalink').val(sku);
        validate_permalink();
    });
    $('.permalink').on('input', function() {
        validate_permalink();
    });

    // $('#long_description').summernote({
    //     placeholder: 'Long Description...',
    //     tabsize: 2,
    //     height: 250,
    //     fontFamily: "'Plus Jakarta Sans', 'Roboto Condensed', Arial, sans-serif",
    //     toolbar: [
    //         ['style', ['bold', 'italic', 'underline', 'clear']],
    //         ['fontname', ['fontname']],
    //         ['fontsize', ['fontsize']],
    //         ['color', ['color']],
    //         ['para', ['ul', 'ol', 'paragraph']],
    //         ['height', ['height']],
    //         ['insert', ['link']],
    //         ['view', ['codeview']],
    //     ],
    //     fontNames: [
    //         'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue',
    //         'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 
    //         'Verdana', 'monospace', 'Plus Jakarta Sans', 'Roboto Condensed'
    //     ],
    //     fontNamesIgnoreCheck: ['Plus Jakarta Sans', 'Roboto Condensed'],
    //     callbacks: {
    //         onInit: function() {
    //             // Inject Google Fonts
    //             $('head').append(`
    // <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    //             `);
    //         }
    //     }
    // });
    
    // $('#seo_tags').tagsinput({
    //     tagClass: 'badge badge-primary'
    // });

    var imageInput = document.getElementById('featuredimageInput');
    var imagePreview = document.getElementById('featuredimagePreview');
    imageInput.addEventListener('change', function(event) {
        var selectedFile = event.target.files[0];
        if (selectedFile) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageElement = document.createElement('img');
                imageElement.src = event.target.result;
                imageElement.style.width = '100%';
                imageElement.style.height = 'auto';
                imagePreview.innerHTML = '';
                imagePreview.appendChild(imageElement);
            }
            reader.readAsDataURL(selectedFile);
        } else {
            imagePreview.innerHTML = '';
        }
    });

    function validate_permalink() {
        var permalink = $('#permalink').val();

        $.ajax({
            url: '{{ route('Admin.Pages.permalink_check') }}',
            method: 'POST',
            data: {
                permalink: permalink,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                $('.permalink_status').val('1');
                $('.permalink_error').html('');
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                $('.permalink_status').val('');
                $('.permalink_error').html(
                    '<small>This permalink already in used or field is empty!, Please change the permalink.</small>'
                );
            }
        });
    }
    $(function() {
        $('.select2-demo').each(function() {
            $(this)
                .wrap('<div class="position-relative"></div>')
                .select2({
                    placeholder: 'Select value',
                    dropdownParent: $(this).parent()
                });
        })
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        imageInput.addEventListener('change', function(event) {
            const selectedImages = event.target.files;
            // Convert the selectedImages FileList to an array to use .forEach
            const imagesArray = Array.from(selectedImages);

            // Check if any images have been added previously and get their URLs
            const existingImages = Array.from(imagePreviewContainer.querySelectorAll('img')).map(img =>
                img.src);

            // Remove any previous previews that are no longer selected
            imagePreviewContainer.querySelectorAll('.preview-wrapper').forEach(wrapper => {
                const img = wrapper.querySelector('img');
                if (!imagesArray.includes(img.src)) {
                    wrapper.remove();
                }
            });

            // Create new previews for newly added images
            imagesArray.forEach(image => {
                if (!existingImages.includes(URL.createObjectURL(image))) {
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgPreview = document.createElement('img');
                        imgPreview.src = reader.result;
                        imgPreview.classList.add('img-thumbnail', 'img-preview');

                        // Add a radio button for featured image selection
                        const radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'featuredImage';
                        // radioInput.value = reader.result;
                        radioInput.value = '1';
                        radioInput.classList.add('radio-button');
                        radioInput.addEventListener('click', function() {
                            setFeaturedImage(reader.result);
                        });

                        // Add a "Remove" button for removing the image
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.classList.add('btn', 'btn-sm', 'btn-danger', 'badge',
                            'btn-remove');
                        removeButton.textContent = 'X';
                        removeButton.addEventListener('click', function() {
                            const previewWrapper = removeButton.parentNode;
                            // removeImageAndFile(removeButton);
                            if (previewWrapper.classList.contains('selected')) {
                                setFeaturedImage(null);
                            }
                            previewWrapper.remove();
                        });

                        // Wrap the image, radio button, and remove button in a container
                        const previewWrapper = document.createElement('label');
                        previewWrapper.classList.add('preview-wrapper');
                        previewWrapper.appendChild(imgPreview);
                        previewWrapper.appendChild(radioInput);
                        previewWrapper.appendChild(removeButton);

                        imagePreviewContainer.appendChild(previewWrapper);
                    }

                    reader.readAsDataURL(image);
                }
            });
        });


        function setFeaturedImage(imageSrc) {
            // Remove any existing selection
            const prevSelected = document.querySelector('.preview-wrapper.selected');
            if (prevSelected) {
                prevSelected.classList.remove('selected');
                const prevRadio = prevSelected.querySelector('.radio-button');
                prevRadio.checked = false;
            }

            // Set the new featured image
            if (imageSrc) {
                const selectedWrapper = document.querySelector(`.preview-wrapper [value="${imageSrc}"]`)
                    .parentNode;
                selectedWrapper.classList.add('selected');
                const selectedRadio = selectedWrapper.querySelector('.radio-button');
                selectedRadio.checked = true;
            }
        }
    });


    function uploadImages() {
        const fileInput = $('#imageInput');
        if (fileInput[0].files.length === 0) {
            alert('Please select at least one image.');
            return;
        }

        const radioInput = $('.radio-button:checked');
        if (radioInput.length === 0) {
            alert('Please select a featured image.');
            return;
        }

        const formData = new FormData();

        const selectedFiles = fileInput[0].files;
        for (let i = 0; i < selectedFiles.length; i++) {
            formData.append('gallery_media[]', selectedFiles[i]);
        }

        formData.append('featuredImage', radioInput.val());
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        $.ajax({
            url: '/admin/products/add_product_variation',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('.response_section').html('<div class="alert alert-success">' + response.message +
                    '</div>');
            },
            error: function(xhr, status, error) {
                $('.response_section').html(
                    '<div class="alert alert-danger">Error saving data to the server.</div>');
            },
        });
    }


    function dataURItoBlob(dataURI) {
        const byteString = atob(dataURI.split(',')[1]);
        const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], {
            type: mimeString
        });
    }

    Array.prototype.search = function(elem) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == elem) return i;
        }

        return -1;
    };

    var Multiselect = function(selector) {
        if (!$(selector)) {
            console.error("ERROR: Element %s does not exist.", selector);
            return;
        }

        Multiselect.prototype.toggleBackgroundColor = function(option) {
            $(option).toggleClass("selected-option");
        };

        this.selector = selector;
        this.selections = [];

        (function(that) {
            that.events();
        })(this);
    };



    Multiselect.prototype = {
        open: function(that) {
            var target = $(that).parent().attr("data-target");
            if (!this.selections) {
                this.selections = [];
            }

            $(this.selector + ".multiselect").toggleClass("active");
        },



        close: function() {
            $(this.selector + ".multiselect").removeClass("active");
        },

        events: function() {
            var that = this;

            $(document).on("click", that.selector + ".multiselect > .title", function(e) {
                if (e.target.className.indexOf("close-icon") < 0) {
                    that.open();
                }
            });

            $(document).on("click", that.selector + ".multiselect option", function(e) {
                var selection = $(this).text();
                var target = $(this).parent().parent().attr("data-target");

                var io = that.selections.search(selection);

                if (io < 0) {
                    that.selections.push(selection);
                    that.toggleBackgroundColor(this);
                } else {
                    that.selections.splice(io, 1);
                    that.toggleBackgroundColor(this);
                }

                that.selectionStatus();
                that.setSelectionsString();
            });

            $(document).on("click", that.selector + ".multiselect > .title > .close-icon", function(e) {
                that.clearSelections();
            });

            $(document).click(function(e) {
                if (e.target.className.indexOf("title") < 0) {
                    if (e.target.className.indexOf("text") < 0) {
                        if (e.target.className.indexOf("-icon") < 0) {
                            if (e.target.className.indexOf("selected") < 0 ||
                                e.target.localName != "option") {
                                that.close();
                            }
                        }
                    }
                }
            });
        },

        selectionStatus: function() {
            var obj = $(this.selector + ".multiselect");

            if (this.selections.length) obj.addClass("selection");
            else obj.removeClass("selection");
        },

        clearSelections: function() {
            this.selections = [];
            this.selectionStatus();
            this.setSelectionsString();
        },

        getSelections: function() {
            return this.selections;
        },

        setSelectionsString: function() {
            var selects = this.getSelectionsString().split(", ");
            $(this.selector + ".multiselect > .title").attr("title", selects);

            var opts = $(this.selector + ".multiselect option");

            if (selects.length > 3) {
                var _selects = this.getSelectionsString().split(", ");
                _selects = _selects.splice(0, 3);
                $(this.selector + ".multiselect > .title > .text")
                    .text(_selects + " [...]");
            } else {
                $(this.selector + ".multiselect > .title > .text")
                    .text(selects);
            }

            for (var i = 0; i < opts.length; i++) {
                $(opts[i]).removeClass("selected");
            }

            for (var j = 0; j < selects.length; j++) {
                var select = selects[j];

                for (var i = 0; i < opts.length; i++) {
                    if ($(opts[i]).text() == select) {
                        $(opts[i]).addClass("selected");
                        break;
                    }
                }
            }
        },

        getSelectionsString: function() {
            if (this.selections.length > 0)
                return this.selections.join(", ");
            else return "Select";
        },

        setSelections: function(arr) {
            if (!arr[0]) {
                error("ERROR: This does not look like an array.");
                return;
            }

            this.selections = arr;
            this.selectionStatus();
            this.setSelectionsString();
        },
    };

    $(document).ready(function() {
        var multi = new Multiselect("#countries");
    });
</script>



