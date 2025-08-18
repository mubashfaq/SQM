@include('Website.Includes.header', ['meta_Details' => $meta_details])
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    $logged_in_user = Auth::user();
    $default_collection = 0;
    $collection_index = 1;
@endphp
<style>
    .pagination .page-item.active .page-link {
        background-color: #E59041;
        color: white !important;
    }

    .image-container {
        width: 100%;
        height: 110px;
        background-size: cover;
        background-position: center;
    }

    .collection_id,
    .collection_id:focus,
    .collection_id:active {
        margin-left: auto;
        border-radius: 10px;
        padding-left: 7px;
        padding-right: 7px;
        border-color: var(--color-orange) !important;
        color: var(--color-orange) !important;
        outline: 0;
    }
</style>
<!-- MULTITAB SECTION -->
<section class="mtop-50">
    <div class="container pt-3">
        <!-- LATEAST TOOL STORE -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex">
                    <div class="collection-view-top-section">
                        <h1 class="collection-view-title"> Collection </h1>
                        <p class="mb-0"><a href="#" class="collection-view-linked-desc">Home - Collection</a></p>
                    </div>

                    @if ($collectionproducts->isNotEmpty())
                        <select id="collectionSelect" class="collection_id" name="collection_id">
                            <option value="" disabled>Select a Collection</option>
                            @foreach ($allCollections as $collection)
                                @if (!is_null($collection))
                                    @if ($collection->products->isNotEmpty())
                                        @if ($collection_index == 1)
                                            @php
                                                $default_collection = $selectedCollection ? $selectedCollection->id : 0;
                                            @endphp
                                            <option value="{{ $collection ? $collection->id : 0 }}"
                                                {{ $collection->id == $default_collection ? 'selected' : '' }}>
                                                {{ ucwords($collection->title) }}
                                            </option>
                                        @else
                                            <option value="{{ $collection->id }}">{{ ucwords($collection->title) }}
                                            </option>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>
        @if ($collectionproducts->isEmpty())
            <div class="text-center latest-product-title">No Collection Found</div>
        @endif
        <div class="row ptop-30 pt-3" id="collection_data">
        </div>
    </div>
</section>
@include('Website.Includes.footer')

<script>
    $(document).ready(function() {
        default_collection({{ $default_collection }});
        $('#collectionSelect').on('change', function() {
            var collectionId = $(this).val();
            default_collection(collectionId);
        });
    });

    function default_collection(collection_id) {
        var collectionId = collection_id;
        if (collectionId) {
            $.ajax({
                url: '{{ route('get.collection.products') }}',
                type: 'get',
                data: {
                    collection_id: collectionId
                },
                success: function(response) {
                    var html = response.html;
                    $('#collection_data').html(html);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        } else {
            $('#collection_data').empty();
        }
    }
</script>
