@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
<div class="container page-heading-center" >
        <div>
            <h2>Blogs</h2>
        </div>
        <div>    
            <p><span>Sydney Quality Meats:</span> Blogs</p>
        </div>
    </div>
</section>
<!-- Section 1 End -->

<div class="container py-5">
    <div class="row g-4">
        @forelse($blogs as $blog)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 rounded-3">
                {{-- Display blog image if available, otherwise use placeholder --}}
                @if($blog->cover_image && file_exists(public_path($blog->cover_image)))
                <img src="{{ url('/') . '/' . $blog->cover_image}}"
                    class="card-img-top rounded-top-3"
                    alt="{{ $blog->title }}"
                    style="height: 250px; object-fit: cover;">
                @else
                <div class="card-img-top rounded-top-3 bg-light d-flex align-items-center justify-content-center"
                    style="height: 250px;">
                    <span class="text-muted">No Image</span>
                </div>
                @endif

                <div class="card-body d-flex flex-column">
                    {{-- Display formatted creation date --}}
                    <span style="font-size: 12px;" class="badge primary-color rounded-pill mb-3 align-self-start">
                        {{ $blog->created_at->format('F j, Y') }}
                    </span>
                    <a href="{{ route('blog.permalink', $blog->permalink ) }}" class="blog-title text-decoration-none">
                        <h3 class="card-title fw-bold text-dark mb-3" style="line-height: 1.2; min-height: 48px;">
                            {{ Str::limit($blog->title, 60, '...') }}
                        </h3>
                    </a>

                    {{-- Display blog excerpt or truncated content --}}
                    <p class="card-text text-muted flex-grow-1 " style="line-height: 1.4;">
                        @if($blog->description)
                        {{ Str::limit(strip_tags($blog->description), 180, '...') }}
                        @else
                        {{ Str::limit(strip_tags($blog->content ?? 'No description available'), 100, '...') }}
                        @endif
                    </p>



                    {{-- Categories display (if you want to show them) --}}
                    @if($blog->categories && $blog->categories->count() > 0)
                    <div class="mb-3">
                        @foreach($blog->categories->take(2) as $category)
                        <span class="badge bg-secondary me-1 small">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Read more button with link to individual blog --}}
                    <a href="{{ route('blog.permalink', $blog->permalink ) }}"
                        class="btn primary-color rounded-pill px-4 align-self-start mt-auto">
                        Read More
                    </a>
                </div>
            </div>
        </div>
        @empty
        {{-- Display message when no blogs are found --}}
        <div class="col-12 text-center">
            <div class="card shadow-sm border-0 rounded-3 p-5">
                <h4 class="text-muted mb-3">No Blogs Available</h4>
                <p class="text-muted">Check back soon for new blog posts!</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination links --}}
    @if($blogs->hasPages())
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            {{ $blogs->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Additional CSS to fix any remaining issues --}}
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-body {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        white-space: normal;
        word-wrap: break-word;
    }

    .card:hover .card-title {
        color: #A42125 !important;
    }
    .primary-color{
        background-color: #A42125;
        color: white;
    }
    .primary-color:hover{
        background-color: #000000;
        color: white;
    }
</style>
@endsection