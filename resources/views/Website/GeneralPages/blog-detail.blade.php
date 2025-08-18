@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
<section class="about-sec1">
    <img src="{{ url('/') . '/' . $mainBlog->cover_image}}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <span style="font-size: 20px;" class="badge primary-color rounded-pill mb-3 align-self-start">
                {{ $mainBlog->created_at->format('F j, Y') }}
            </span>
            <h2>{{$mainBlog->title ?? '' }}</h2>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
       {!! $mainBlog->content !!}
    </div>
</div>

@if($relatedBlogs && $relatedBlogs->count() > 0)
<div style="background: #F8F9FA;">
    <div class="container py-5">
        <div class="row g-4">
            <h2 class="text-center">Related Blogs</h2>
            @foreach($relatedBlogs as $blog)
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
                        <span class="badge primary-color rounded-pill mb-3 align-self-start">
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
            @endforeach
        </div>
    </div>
</div>
@endif
<style>
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
    @media screen and (max-width: 767px) {
        .about-sec1 h2 {
            font-size: 30px !important;
        }
        .about-sec1 {
            height: 200px;
        }
    }
</style>

@endsection