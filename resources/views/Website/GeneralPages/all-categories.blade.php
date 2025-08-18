@include('Website.Includes.header', ['meta_Details' => $meta_details])
<style>
    .pagination .page-item.active .page-link {
        background-color: #E59041;
        color: white !important;
    }
</style>
<!-- MULTITAB SECTION -->
<section class="mtop-50">
    <div class="container">
        <!-- LATEAST TOOL STORE -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="collection-view-top-section">
                    <h1 class="collection-view-title"> All Categories </h1>
                    <p class="mb-0"><a href="#" class="collection-view-linked-desc">Home - All Categories</a></p>
                </div>
            </div>
        </div>
        <div class="row collection-ptop-20px">
            @foreach ($categories as $category)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card col-border-top-r">
                        <img src="{{ $category->thumbnail_image }}" class="img-fluid" alt="Category Image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->title }}</h5>
                            <a href="{{ url('/') . '/category/' . $category->sku }}" class="w-100 btn text-light"
                                style="background: #E59041">See Products</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($categories->total() > $perPage)
            <div class="pagination-section">
                <nav aria-label="Page navigation ms-auto">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->previousPageUrl() }}"
                                aria-label="Previous">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </span>
                            </a>
                        </li>


                        @if ($categories->currentPage() > 3)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif

                        @foreach (range(max($categories->currentPage() - 2, 1), min($categories->lastPage() - 1, $categories->currentPage() + 2)) as $page)
                            @if ($page == $categories->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $categories->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($categories->currentPage() < $categories->lastPage() - 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif

                        @if ($categories->lastPage() > 1)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $categories->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a>
                            </li>
                        @endif

                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</section>
@include('Website.Includes.footer')
