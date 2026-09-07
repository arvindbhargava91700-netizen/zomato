@extends('layouts.front.main')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Blog List</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        blog List
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- blog section starts -->
    <section class="section-b-space">
        <div class="container">
            <div class="blog-boxs">
                <div class="row g-4">
                    <div class="col-lg-3 order-lg-0 order-1">
                        <div class="left-box wow fadeInUp">
                            <div class="shop-left-sidebar">
                                <div class="search-box">
                                    <form action="{{ route('blog.list') }}" method="GET">
                                        <div class="form-input position-relative">
                                            <input type="search" class="form-control search" id="search" name="search"
                                                placeholder="Search" value="{{ request('search') }}">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </form>
                                </div>
                                <div class="accordion sidebar-accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Categories Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <span class="dark-text">Categories</span>
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="category-list custom-padding custom-height scroll-bar">
                                                    @if(isset($sidebarCategories) && $sidebarCategories->isNotEmpty())
                                                        @foreach($sidebarCategories as $cat)
                                                            <li>
                                                                <a href="{{ route('blog.list', ['category_id' => $cat->id]) }}">
                                                                    <div class="form-check ps-0 m-0 category-list-box {{ request('category_id') == $cat->id ? 'active' : '' }}">
                                                                        <div class="form-check-label">
                                                                            <span class="name">{{ $cat->name }}</span>
                                                                            <span class="number">({{ str_pad($cat->blogs_count ?? ($cat->foods_count ?? 0), 2, '0', STR_PAD_LEFT) }})</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <li>
                                                            <a href="{{ route('blog.list', ['search' => 'Fruits & Vegetables']) }}">
                                                                <div class="form-check ps-0 m-0 category-list-box">
                                                                    <div class="form-check-label">
                                                                        <span class="name">Fruits &amp; Vegetables</span>
                                                                        <span class="number">(15)</span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('blog.list', ['search' => 'Bakery']) }}">
                                                                <div class="form-check ps-0 m-0 category-list-box">
                                                                    <div class="form-check-label">
                                                                        <span class="name">Bakery, Cake &amp; Dairy</span>
                                                                        <span class="number">(12)</span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recent Posts Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseThree" aria-expanded="true"
                                                aria-controls="collapseThree">
                                                <span class="dark-text">Recent Post</span>
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="post-wrap">
                                                    @if(isset($recentBlogs) && $recentBlogs->isNotEmpty())
                                                        @foreach($recentBlogs as $rb)
                                                            <a href="{{ route('blog.show', ['slug' => $rb->slug]) }}" class="post-box">
                                                                <div class="img-box">
                                                                    <img class="img-fluid img"
                                                                         src="{{ $rb->featured_image ? asset($rb->featured_image) : asset('front/assets/images/blog/' . (($loop->index % 4) + 2) . '.png') }}"
                                                                         alt="{{ $rb->title }}">
                                                                </div>
                                                                <div class="content-box">
                                                                    <h6 title="{{ $rb->title }}">{{ Str::limit($rb->title, 24) }}</h6>
                                                                    <span>{{ $rb->created_at->format('d M, Y') }}</span>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <a href="blog-details.html" class="post-box">
                                                            <div class="img-box">
                                                                <img class="img-fluid img" src="{{ asset('front/assets/images/blog/2.png') }}"
                                                                    alt="post">
                                                            </div>
                                                            <div class="content-box">
                                                                <h6>John wike</h6>
                                                                <span>20 Sep, 2024</span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tags Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseTwo" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                <span class="dark-text">Tags</span>
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="filter-item-list">
                                                    @if(isset($sidebarTags) && $sidebarTags->isNotEmpty())
                                                        @foreach($sidebarTags as $tag)
                                                            <li>
                                                                <a href="{{ route('blog.list', ['cuisine_id' => $tag->id]) }}" class="{{ request('cuisine_id') == $tag->id ? 'active fw-bold text-primary' : '' }}">{{ $tag->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <li><a href="{{ route('blog.list', ['search' => 'Vegetable']) }}">Vegetable</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Pharmacy']) }}">Pharmacy</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Grocery']) }}">Grocery</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Milk']) }}">Milk</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Fruit']) }}">Fruit</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Organic']) }}">Organic</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Health']) }}">Health</a></li>
                                                        <li><a href="{{ route('blog.list', ['search' => 'Covid care']) }}">Covid care</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  


                    <!-- Right Grid Section -->
                    <div class="col-lg-9 ratio3_2">
                        <div class="row g-4">
                            @forelse($blogs as $blog)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="blog-box">
                                        <div class="blog-image">
                                            <a href="{{ route('blog.show', ['slug' => $blog->slug]) }}" class="bg-size" style="
                                                background-image: url({{ $blog->featured_image ? asset($blog->featured_image) : asset('front/assets/images/blog/1.png') }});
                                                background-size: cover;
                                                background-position: center;
                                                background-repeat: no-repeat;
                                                display: block;
                                            ">
                                                <img class="img-fluid bg-img" src="{{ $blog->featured_image ? asset($blog->featured_image) : asset('front/assets/images/blog/1.png') }}" alt="{{ $blog->title }}" style="display: none;">
                                            </a>
                                        </div>
                                        <div class="blog-details">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6>By {{ $blog->author?->name ?? 'Chef' }} in {{ $blog->restaurant?->restaurant_name ?? 'Food' }}</h6>
                                                <div class="d-flex align-items-center gap-1">
                                                    @if($blog->category)
                                                        <span class="badge bg-soft-primary text-primary fs-11">{{ $blog->category->name }}</span>
                                                    @endif
                                                    @if($blog->cuisine)
                                                        <span class="badge bg-soft-secondary text-secondary fs-11">{{ $blog->cuisine->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="border-0">
                                                <a href="{{ route('blog.show', ['slug' => $blog->slug]) }}">
                                                    <h5>{{ $blog->title }}</h5>
                                                </a>
                                            </div>
                                            <p>on {{ $blog->created_at->format('F d, Y') }} By {{ $blog->author?->name ?? 'Chef' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <h5 class="text-muted">No blog posts found.</h5>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        @if($blogs->hasPages())
                            <nav class="custom-pagination mt-4">
                                {{ $blogs->links() }}
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog section end -->
@endsection
