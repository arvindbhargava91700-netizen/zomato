@extends('layouts.front.main')

@section('title', ($blog->title ?? 'Food Story') . ' - Zomato Blog')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Blog Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('blog.list') }}">Blogs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        blog Details
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
                <div class="row g-3">
                    <div class="col-lg-3 order-lg-0 order-1">
                        <div class="left-box wow fadeInUp">
                            <div class="shop-left-sidebar">
                                <div class="search-box">
                                    <form action="{{ route('blog.list') }}" method="GET">
                                        <div class="form-input position-relative">
                                            <input type="search" class="form-control search" id="search" name="search" placeholder="Search" value="{{ request('search') }}">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </form>
                                </div>
                                <div class="accordion sidebar-accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Categories Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="dark-text">Categories</span>
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
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
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                <span class="dark-text">Recent Post</span>
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
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
                                                                <img class="img-fluid img" src="{{ asset('front/assets/images/blog/2.png') }}" alt="post">
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
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                <span class="dark-text">Tags</span>
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
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



                    <!-- Right Column: Blog Details & Comments -->
                    <div class="col-lg-9">
                        <div class="blog-wrap">
                            <!-- Blog Section Start -->
                            <div class="blog-box blog-detail ratio_40">
                                <div class="img-box bg-size" style="
                                    background-image: url({{ $blog->featured_image ? asset($blog->featured_image) : asset('front/assets/images/blog/2.png') }});
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    display: block;
                                ">
                                    <img class="bg-img w-100" src="{{ $blog->featured_image ? asset($blog->featured_image) : asset('front/assets/images/blog/2.png') }}" alt="{{ $blog->title }}" style="display: none;">
                                </div>
                                <div class="content-box">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            @if($blog->category)
                                                <a href="{{ route('blog.list', ['category_id' => $blog->category->id]) }}" class="badge bg-primary fs-12 text-white text-decoration-none px-3 py-1">
                                                    {{ $blog->category->name }}
                                                </a>
                                            @endif
                                            @if($blog->cuisine)
                                                <a href="{{ route('blog.list', ['cuisine_id' => $blog->cuisine->id]) }}" class="badge bg-secondary fs-12 text-white text-decoration-none px-3 py-1">
                                                    {{ $blog->cuisine->name }}
                                                </a>
                                            @endif
                                            <h5 class="blog-title mb-0">
                                                {{ $blog->title }}
                                            </h5>
                                        </div>
                                        <span class="content-color fs-13">{{ $blog->created_at->format('d F Y') }}</span>
                                    </div>

                                    @if($blog->short_description)
                                        <p class="fw-semibold text-dark fs-15 mt-3 mb-3" style="line-height: 1.6;">
                                            {{ $blog->short_description }}
                                        </p>
                                    @endif

                                    <div class="blog-content-body mt-3 fs-15 lh-lg text-dark" style="line-height: 1.8;">
                                        {!! nl2br(e($blog->content)) !!}
                                    </div>

                                    <!-- Modern Engagement & Reaction Hero Bar -->
                                    <div class="blog-engagement-bar p-3 p-md-4 mt-4 rounded-4 shadow-sm border">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                            <!-- Author Info & Read Stats -->
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="position-relative">
                                                    <img class="img-fluid rounded-circle border border-2 border-primary shadow-sm"
                                                         src="{{ $blog->restaurant?->logo ? asset($blog->restaurant->logo) : asset('front/assets/images/icons/p1.png') }}"
                                                         alt="{{ $blog->author?->name ?? 'Chef' }}"
                                                         style="width: 48px; height: 48px; object-fit: cover;">
                                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-15 d-flex align-items-center gap-1">
                                                        <span>{{ $blog->author?->name ?? 'Chef' }}</span>
                                                        <span class="badge bg-soft-primary text-primary fs-11 px-2 py-0 rounded-pill">Author</span>
                                                    </div>
                                                    <div class="fs-12 text-muted mt-1 d-flex align-items-center gap-2">
                                                        <span><i class="ri-restaurant-line text-warning"></i> {{ $blog->restaurant?->restaurant_name ?? 'Restaurant' }}</span>
                                                        <span>•</span>
                                                        <span><i class="ri-eye-line text-info"></i> {{ $blog->views_count }} Views</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reaction & Quick Action Buttons -->
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <button type="button" class="btn btn-reaction-pill d-flex align-items-center gap-2 {{ ($userReaction ?? null) === 'like' ? 'active-like' : '' }}"
                                                        id="blogLikeBtn"
                                                        data-blog-id="{{ $blog->id }}"
                                                        data-reaction="like">
                                                    <i class="{{ ($userReaction ?? null) === 'like' ? 'ri-heart-3-fill' : 'ri-heart-3-line' }} fs-16" id="blogLikeIcon"></i>
                                                    <span class="fw-bold" id="blogLikeCount">{{ $blog->likes_count ?? 0 }}</span>
                                                    <span class="reaction-label d-none d-sm-inline">Likes</span>
                                                </button>

                                                <button type="button" class="btn btn-reaction-pill d-flex align-items-center gap-2 {{ ($userReaction ?? null) === 'dislike' ? 'active-dislike' : '' }}"
                                                        id="blogDislikeBtn"
                                                        data-blog-id="{{ $blog->id }}"
                                                        data-reaction="dislike">
                                                    <i class="{{ ($userReaction ?? null) === 'dislike' ? 'ri-thumb-down-fill' : 'ri-thumb-down-line' }} fs-16" id="blogDislikeIcon"></i>
                                                    <span class="fw-bold" id="blogDislikeCount">{{ $blog->dislikes_count ?? 0 }}</span>
                                                </button>

                                                <button type="button" class="btn btn-reaction-pill d-flex align-items-center gap-1" id="shareArticleBtn" title="Share Story">
                                                    <i class="ri-share-forward-line fs-16 text-primary"></i>
                                                    <span class="d-none d-md-inline">Share</span>
                                                </button>

                                                <a href="#leaveComment" class="btn btn-theme-gradient d-flex align-items-center gap-1 shadow-sm">
                                                    <i class="ri-chat-1-line"></i>
                                                    <span>Join Discussion</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Blog Section End -->

                            <!-- Comments Section Start -->
                            <div class="community-discussion-wrap mt-5" id="commentsSection">
                                <div class="discussion-header-card p-3 p-sm-4 rounded-4 mb-4 border">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h4 class="mb-0 fw-bold text-dark">
                                                    Foodie Reviews & Comments
                                                </h4>
                                                <span class="badge bg-primary rounded-pill px-3 py-1 fs-12 fw-bold shadow-sm">
                                                    {{ $blog->rootComments->count() }}
                                                </span>
                                            </div>
                                            <p class="text-muted fs-13 mb-0 mt-1">
                                                Share your culinary experiences, questions, or recipe tips with fellow food lovers.
                                            </p>
                                        </div>
                                        <a href="#leaveComment" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-flex align-items-center gap-1">
                                            <i class="ri-pencil-line"></i> Write a Comment
                                        </a>
                                    </div>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show rounded-4 mt-3 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                                        <i class="ri-checkbox-circle-fill fs-18 text-success"></i>
                                        <div>{{ session('success') }}</div>
                                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show rounded-4 mt-3 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                                        <i class="ri-error-warning-fill fs-18 text-danger"></i>
                                        <div>{{ session('error') }}</div>
                                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <!-- Comments List -->
                                <div class="comments-flow-container mt-4">
                                    @if($blog->rootComments->isNotEmpty())
                                        <div class="comment-thread-list">
                                            @foreach($blog->rootComments as $comment)
                                                <div class="comment-card-item mb-4" id="comment-{{ $comment->id }}">
                                                    <!-- Main Comment Card -->
                                                    <div class="comment-bubble-box p-3 p-sm-4 rounded-4 bg-white border shadow-sm">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <!-- Avatar -->
                                                            <div class="position-relative flex-shrink-0">
                                                                <img class="img-fluid rounded-circle border shadow-2xs"
                                                                     src="{{ $comment->user?->profile_image ? asset($comment->user->profile_image) : asset('front/assets/images/icons/p' . (($loop->index % 3) + 1) . '.png') }}"
                                                                     alt="{{ $comment->name }}"
                                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                                            </div>

                                                            <!-- Content Body -->
                                                            <div class="w-100">
                                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 border-bottom border-light">
                                                                    <div>
                                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                            <span class="fw-bold text-dark fs-15">{{ $comment->name }}</span>
                                                                            @if($comment->user_id && $blog->restaurant && $comment->user_id == $blog->restaurant->user_id)
                                                                                <span class="badge-tag-custom badge-restaurant">
                                                                                    <i class="ri-restaurant-2-fill"></i> Restaurant Host
                                                                                </span>
                                                                            @elseif($comment->user_id && $comment->user_id == $blog->created_by)
                                                                                <span class="badge-tag-custom badge-author">
                                                                                    <i class="ri-quill-pen-fill"></i> Story Author
                                                                                </span>
                                                                            @elseif($comment->user_id)
                                                                                <span class="badge-tag-custom badge-verified">
                                                                                    <i class="ri-shield-check-fill"></i> Verified Foodie
                                                                                </span>
                                                                            @else
                                                                                <span class="badge-tag-custom badge-guest">
                                                                                    <i class="ri-user-smile-line"></i> Reader
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="fs-12 text-muted mt-1">
                                                                            <i class="ri-time-line me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                                                            <span class="mx-1">•</span>
                                                                            <span>{{ $comment->created_at->format('M d, Y') }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Action Controls -->
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <button type="button" class="btn-bubble-action reply-action"
                                                                                onclick="toggleReplyForm({{ $comment->id }})">
                                                                            <i class="ri-reply-line"></i> <span>Reply</span>
                                                                        </button>

                                                                        @if($comment->isAuthoredByCurrentViewer())
                                                                            <button type="button" class="btn-bubble-action edit-action"
                                                                                    onclick="toggleEditForm({{ $comment->id }})">
                                                                                <i class="ri-edit-line"></i> <span>Edit</span>
                                                                            </button>

                                                                            <form action="{{ route('blog.comments.destroy', $comment->id) }}" method="POST" class="d-inline"
                                                                                  onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn-bubble-action delete-action" title="Delete">
                                                                                    <i class="ri-delete-bin-6-line"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <!-- Comment Text -->
                                                                <div class="comment-content-text mt-3 text-secondary" id="comment-text-{{ $comment->id }}">
                                                                    {{ $comment->comment }}
                                                                </div>

                                                                <!-- Inline Edit Form (Toggled) -->
                                                                @if($comment->isAuthoredByCurrentViewer())
                                                                    <form action="{{ route('blog.comments.update', $comment->id) }}" method="POST"
                                                                          id="edit-form-{{ $comment->id }}" class="mt-3 d-none p-3 bg-light rounded-3 border">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                                            <label class="form-label fw-bold fs-13 text-primary mb-0">
                                                                                <i class="ri-edit-line me-1"></i> Edit Your Comment
                                                                            </label>
                                                                            <span class="fs-11 text-muted">Update your review</span>
                                                                        </div>
                                                                        <textarea name="comment" class="form-control mb-2 fs-14 rounded-3 border" rows="3" required>{{ $comment->comment }}</textarea>
                                                                        <div class="d-flex justify-content-end gap-2">
                                                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" onclick="toggleEditForm({{ $comment->id }})">Cancel</button>
                                                                            <button type="submit" class="btn btn-sm btn-theme-gradient rounded-pill px-4">Save Changes</button>
                                                                        </div>
                                                                    </form>
                                                                @endif

                                                                <!-- Inline Reply Form (Toggled) -->
                                                                <form action="{{ route('blog.comments.store', $blog->id) }}" method="POST"
                                                                      id="reply-form-{{ $comment->id }}" class="mt-3 d-none p-3 p-sm-4 rounded-4 reply-drawer-box border">
                                                                    @csrf
                                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                                        <div class="fw-bold fs-14 text-dark">
                                                                            <i class="ri-reply-all-line text-primary me-1"></i> Replying to <span class="text-primary">{{ $comment->name }}</span>
                                                                        </div>
                                                                        <button type="button" class="btn-close btn-sm" onclick="toggleReplyForm({{ $comment->id }})"></button>
                                                                    </div>

                                                                    @guest
                                                                        <div class="row g-2 mb-2">
                                                                            <div class="col-sm-6">
                                                                                <input name="name" type="text" class="form-control form-control-sm rounded-3" placeholder="Your Full Name" required>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <input name="email" type="email" class="form-control form-control-sm rounded-3" placeholder="Your Email Address" required>
                                                                            </div>
                                                                        </div>
                                                                    @endguest

                                                                    <div class="mb-3">
                                                                        <textarea name="comment" class="form-control form-control-sm rounded-3" rows="3" placeholder="Write your reply message here..." required></textarea>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end gap-2">
                                                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" onclick="toggleReplyForm({{ $comment->id }})">Cancel</button>
                                                                        <button type="submit" class="btn btn-sm btn-theme-gradient rounded-pill px-4">Submit Reply</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Nested Replies (Branch Thread) -->
                                                    @if($comment->replies->isNotEmpty())
                                                        <div class="nested-replies-thread ps-3 ps-sm-4 mt-3">
                                                            @foreach($comment->replies as $reply)
                                                                <div class="reply-card-box p-3 rounded-4 mb-2 border shadow-2xs" id="comment-{{ $reply->id }}">
                                                                    <div class="d-flex align-items-start gap-3">
                                                                        <div class="flex-shrink-0">
                                                                            <img class="img-fluid rounded-circle border shadow-2xs"
                                                                                 src="{{ $reply->user?->profile_image ? asset($reply->user->profile_image) : ($reply->user_id && $blog->restaurant && $reply->user_id == $blog->restaurant->user_id && $blog->restaurant->logo ? asset($blog->restaurant->logo) : asset('front/assets/images/icons/p3.png')) }}"
                                                                                 alt="{{ $reply->name }}"
                                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                                        </div>
                                                                        <div class="w-100">
                                                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-1 border-bottom border-light">
                                                                                <div>
                                                                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                                        <span class="fw-bold text-dark fs-14">{{ $reply->name }}</span>
                                                                                        @if($reply->user_id && $blog->restaurant && $reply->user_id == $blog->restaurant->user_id)
                                                                                            <span class="badge-tag-custom badge-restaurant">
                                                                                                <i class="ri-restaurant-2-fill"></i> Host
                                                                                            </span>
                                                                                        @elseif($reply->user_id && $reply->user_id == $blog->created_by)
                                                                                            <span class="badge-tag-custom badge-author">
                                                                                                <i class="ri-quill-pen-fill"></i> Author
                                                                                            </span>
                                                                                        @elseif($reply->user_id)
                                                                                            <span class="badge-tag-custom badge-verified">
                                                                                                <i class="ri-shield-check-fill"></i> Verified
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <span class="text-muted fs-11">{{ $reply->created_at->diffForHumans() }}</span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    @if($reply->isAuthoredByCurrentViewer())
                                                                                        <button type="button" class="btn-bubble-action edit-action"
                                                                                                onclick="toggleEditForm({{ $reply->id }})">
                                                                                            <i class="ri-edit-line"></i>
                                                                                        </button>
                                                                                        <form action="{{ route('blog.comments.destroy', $reply->id) }}" method="POST" class="d-inline"
                                                                                              onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="btn-bubble-action delete-action">
                                                                                                <i class="ri-delete-bin-6-line"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="comment-content-text mt-2 text-secondary fs-13" id="comment-text-{{ $reply->id }}">
                                                                                {{ $reply->comment }}
                                                                            </div>

                                                                            @if($reply->isAuthoredByCurrentViewer())
                                                                                <form action="{{ route('blog.comments.update', $reply->id) }}" method="POST"
                                                                                      id="edit-form-{{ $reply->id }}" class="mt-2 d-none p-2 bg-white rounded-3 border">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <textarea name="comment" class="form-control form-control-sm mb-2 rounded-2" rows="2" required>{{ $reply->comment }}</textarea>
                                                                                    <div class="d-flex justify-content-end gap-2">
                                                                                        <button type="button" class="btn btn-xs btn-light border rounded-pill px-2" onclick="toggleEditForm({{ $reply->id }})">Cancel</button>
                                                                                        <button type="submit" class="btn btn-xs btn-theme-gradient rounded-pill px-3">Save</button>
                                                                                    </div>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Empty State Card -->
                                        <div class="empty-comments-hero p-5 text-center rounded-4 border bg-white shadow-sm mb-4">
                                            <div class="empty-icon-circle mx-auto mb-3">
                                                <i class="ri-chat-smile-2-line"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-1">No comments posted yet!</h5>
                                            <p class="text-muted fs-14 mb-3" style="max-width: 420px; margin: 0 auto;">
                                                Be the trendsetter! Share what you think about this article, recommend changes, or express your love for the recipe.
                                            </p>
                                            <a href="#leaveComment" class="btn btn-theme-gradient rounded-pill px-4 shadow-sm">
                                                <i class="ri-pencil-line me-1"></i> Start the Conversation
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Comments Section End -->

                            <!-- Modern Leave a Comment Hero Form Start -->
                            <div class="comment-compose-card mt-5 p-4 p-sm-5 rounded-4 border shadow-sm" id="leaveComment">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-3 border-bottom">
                                    <div>
                                        <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                            <i class="ri-chat-new-line text-primary"></i> Leave Your Review or Tip
                                        </h4>
                                        <p class="text-muted fs-13 mb-0">Your email address will remain private and secured.</p>
                                    </div>
                                    <span class="badge bg-soft-warning text-warning-dark rounded-pill px-3 py-1 fs-12 fw-semibold">
                                        <i class="ri-sparkling-fill me-1"></i> Community Guidelines Active
                                    </span>
                                </div>

                                <form action="{{ route('blog.comments.store', $blog->id) }}" method="POST" class="theme-form" id="mainBlogCommentForm">
                                    @csrf

                                    @auth
                                        <!-- Logged-in User Profile Banner -->
                                        <div class="logged-user-banner p-3 rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="position-relative">
                                                    <img src="{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : asset('front/assets/images/icons/p1.png') }}"
                                                         class="rounded-circle border border-2 border-white shadow-sm"
                                                         alt="{{ auth()->user()->name }}"
                                                         style="width: 48px; height: 48px; object-fit: cover;">
                                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-15 d-flex align-items-center gap-2">
                                                        <span>{{ auth()->user()->name }}</span>
                                                        <span class="badge bg-success-subtle text-success fs-11 rounded-pill px-2">Verified</span>
                                                    </div>
                                                    <div class="fs-12 text-muted">
                                                        Posting as <span class="text-primary fw-semibold">{{ auth()->user()->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="fs-12 text-muted d-flex align-items-center gap-1">
                                                <i class="ri-checkbox-circle-fill text-success"></i> Instant Publishing
                                            </span>
                                        </div>
                                    @else
                                        <!-- Guest Input Fields -->
                                        <div class="row g-3 g-sm-4 mb-4">
                                            <div class="col-sm-6">
                                                <div class="form-floating-custom">
                                                    <label class="form-label fw-bold fs-13 text-dark mb-1">
                                                        <i class="ri-user-3-line text-primary me-1"></i> Your Full Name <span class="text-danger">*</span>
                                                    </label>
                                                    <input name="name" id="guest_name" type="text" class="form-control form-control-lg rounded-3 fs-14"
                                                           placeholder="E.g. Priya Sharma" value="{{ old('name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-floating-custom">
                                                    <label class="form-label fw-bold fs-13 text-dark mb-1">
                                                        <i class="ri-mail-line text-primary me-1"></i> Email Address <span class="text-danger">*</span>
                                                    </label>
                                                    <input name="email" id="guest_email" type="email" class="form-control form-control-lg rounded-3 fs-14"
                                                           placeholder="name@example.com" value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    @endauth

                                    <!-- Quick Emoji Reactions Selector -->
                                    <div class="quick-emoji-bar mb-3">
                                        <span class="fs-12 fw-bold text-muted me-2">Quick Tags:</span>
                                        <button type="button" class="btn-emoji-chip" onclick="insertEmoji('😋 Delicious recipe!')">😋 Delicious!</button>
                                        <button type="button" class="btn-emoji-chip" onclick="insertEmoji('🔥 Must try!')">🔥 Must try!</button>
                                        <button type="button" class="btn-emoji-chip" onclick="insertEmoji('❤️ Loved this story!')">❤️ Loved it!</button>
                                        <button type="button" class="btn-emoji-chip" onclick="insertEmoji('⭐ 5/5 Stars!')">⭐ 5 Stars!</button>
                                        <button type="button" class="btn-emoji-chip" onclick="insertEmoji('🤤 Craving this right now!')">🤤 Craving this!</button>
                                        <button type="button" class="btn-emoji-chip" onclick="insertEmoji('👨‍🍳 Chef special perfection!')">👨‍🍳 Chef special</button>
                                    </div>

                                    <!-- Textarea Input Box -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold fs-13 text-dark mb-1" for="comment_content">
                                            <i class="ri-message-3-line text-primary me-1"></i> Write Your Review or Feedback <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="comment" class="form-control form-control-lg rounded-4 fs-14 comment-main-textarea"
                                                  id="comment_content" cols="30" rows="5"
                                                  placeholder="What did you think of this recipe, taste experience, or story? Write your honest feedback here..."
                                                  maxlength="2000"
                                                  oninput="updateCharCount(this)" required>{{ old('comment') }}</textarea>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="fs-12 text-muted"><i class="ri-information-line me-1"></i>Be respectful and helpful to the community</span>
                                            <span class="fs-12 text-muted fw-semibold" id="charCountLabel">0 / 2000</span>
                                        </div>
                                    </div>

                                    <!-- Submit Action -->
                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-theme-gradient btn-lg rounded-pill px-4 px-sm-5 shadow-sm d-flex align-items-center gap-2 fw-bold" type="submit">
                                            <span>Post Review</span>
                                            <i class="ri-send-plane-fill"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Reply Form Section End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog section end -->

    <!-- Toast Notification Box -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        <div id="blogToast" class="toast align-items-center text-white bg-dark border-0 rounded-4 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2" id="blogToastMessage">
                    <i class="ri-check-double-line text-success fs-18"></i> Action completed!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Enhanced Aesthetic CSS -->
    <style>
        /* Blog Engagement Bar */
        .blog-engagement-bar {
            background: linear-gradient(135deg, #ffffff 0%, #fff9f4 100%);
            border: 1px solid #ffe6d4 !important;
            transition: all 0.3s ease;
        }
        .blog-engagement-bar:hover {
            box-shadow: 0 8px 24px rgba(255, 141, 47, 0.08) !important;
        }

        /* Pill Buttons */
        .btn-reaction-pill {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            border-radius: 30px;
            padding: 7px 16px;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }
        .btn-reaction-pill:hover {
            background: #fff6ee;
            border-color: #ff8d2f;
            color: #ff8d2f;
            transform: translateY(-1px);
        }
        .btn-reaction-pill.active-like {
            background: linear-gradient(135deg, #ff4757, #ff6b81);
            border-color: #ff4757;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(255, 71, 87, 0.35);
        }
        .btn-reaction-pill.active-dislike {
            background: linear-gradient(135deg, #6c757d, #495057);
            border-color: #6c757d;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(108, 117, 125, 0.35);
        }

        /* Theme Gradient Button */
        .btn-theme-gradient {
            background: linear-gradient(135deg, #ff8d2f 0%, #ff6b3d 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .btn-theme-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35) !important;
            color: #ffffff;
        }

        /* Discussion Header */
        .discussion-header-card {
            background: linear-gradient(135deg, #ffffff 0%, #fdf6ee 100%);
            border: 1px solid #ffe8d6 !important;
        }

        /* Comment Bubble Boxes */
        .comment-bubble-box {
            border: 1px solid #f0f0f3 !important;
            border-radius: 18px !important;
            transition: all 0.25s ease;
        }
        .comment-bubble-box:hover {
            border-color: #ffcba4 !important;
            box-shadow: 0 8px 25px rgba(255, 141, 47, 0.07) !important;
        }

        /* Custom Badge Tags */
        .badge-tag-custom {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            letter-spacing: 0.2px;
        }
        .badge-restaurant {
            background: #fff1e0;
            color: #d96300;
            border: 1px solid #ffd2a6;
        }
        .badge-author {
            background: #f3e8ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
        }
        .badge-verified {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .badge-guest {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        /* Comment Bubble Actions */
        .btn-bubble-action {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #6c757d;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-bubble-action:hover {
            background: #fff0e6;
            border-color: #ff8d2f;
            color: #ff8d2f;
        }
        .btn-bubble-action.delete-action:hover {
            background: #ffebe6;
            border-color: #ff4757;
            color: #ff4757;
        }

        /* Comment Content Text */
        .comment-content-text {
            font-size: 14.5px;
            line-height: 1.68;
            color: #374151 !important;
            word-break: break-word;
        }

        /* Nested Thread */
        .nested-replies-thread {
            position: relative;
            border-left: 3px solid #ffba88;
            margin-left: 24px;
        }
        .reply-card-box {
            background: #fbf9f6;
            border: 1px solid #f1e4d5 !important;
            border-radius: 14px !important;
        }

        /* Scrollable Comment Thread List */
        .comment-thread-list {
            max-height: 520px;
            overflow-y: auto;
            padding-right: 12px;
            padding-left: 2px;
            padding-top: 4px;
            padding-bottom: 8px;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: #ffd0b0 #fdfbf9;
        }

        /* Sleek Custom Scrollbar for Webkit */
        .comment-thread-list::-webkit-scrollbar {
            width: 6px;
        }
        .comment-thread-list::-webkit-scrollbar-track {
            background: #fdfbf9;
            border-radius: 10px;
        }
        .comment-thread-list::-webkit-scrollbar-thumb {
            background: #ffd0b0;
            border-radius: 10px;
            transition: background 0.25s ease;
        }
        .comment-thread-list::-webkit-scrollbar-thumb:hover {
            background: #ff8d2f;
        }

        /* Empty State */
        .empty-comments-hero {
            background: linear-gradient(135deg, #ffffff 0%, #fffcf9 100%);
            border: 2px dashed #ffcba4 !important;
        }
        .empty-icon-circle {
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 50%;
            background: #fff0e2;
            color: #ff8d2f;
            font-size: 32px;
            text-align: center;
        }

        /* Compose Card */
        .comment-compose-card {
            background: linear-gradient(135deg, #ffffff 0%, #fffaf5 100%);
            border: 1px solid #ffe3cb !important;
            border-radius: 20px !important;
        }
        .logged-user-banner {
            background: #ffffff;
            border: 1px solid #ffe6d4;
            box-shadow: 0 4px 15px rgba(255, 141, 47, 0.05);
        }
        .comment-main-textarea {
            border: 1.5px solid #e5e7eb;
            background: #ffffff;
            transition: all 0.25s ease;
        }
        .comment-main-textarea:focus {
            border-color: #ff8d2f !important;
            box-shadow: 0 0 0 4px rgba(255, 141, 47, 0.15) !important;
        }

        /* Emoji Chips */
        .btn-emoji-chip {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 500;
            color: #4b5563;
            margin-right: 4px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }
        .btn-emoji-chip:hover {
            background: #fff5ec;
            border-color: #ff8d2f;
            color: #ff8d2f;
            transform: translateY(-1px);
        }

        .bg-soft-primary { background-color: rgba(255, 141, 47, 0.12) !important; color: #ff8d2f !important; }
        .bg-soft-warning { background-color: rgba(245, 158, 11, 0.12) !important; }
        .text-warning-dark { color: #b45309 !important; }
        .shadow-2xs { box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    </style>
@endsection

@push('scripts')
<script>
    function showToast(message, isSuccess = true) {
        const toastEl = document.getElementById('blogToast');
        const msgEl = document.getElementById('blogToastMessage');
        if (toastEl && msgEl) {
            msgEl.innerHTML = (isSuccess ? '<i class="ri-checkbox-circle-fill text-success fs-18"></i> ' : '<i class="ri-error-warning-fill text-danger fs-18"></i> ') + message;
            const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
            toast.show();
        }
    }

    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (form) {
            form.classList.toggle('d-none');
            if (!form.classList.contains('d-none')) {
                form.querySelector('textarea')?.focus();
            }
        }
    }

    function toggleEditForm(commentId) {
        const form = document.getElementById('edit-form-' + commentId);
        if (form) {
            form.classList.toggle('d-none');
            if (!form.classList.contains('d-none')) {
                form.querySelector('textarea')?.focus();
            }
        }
    }

    function insertEmoji(text) {
        const textarea = document.getElementById('comment_content');
        if (textarea) {
            const currentVal = textarea.value;
            textarea.value = currentVal ? (currentVal.trim() + ' ' + text) : text;
            updateCharCount(textarea);
            textarea.focus();
        }
    }

    function updateCharCount(el) {
        const count = el.value.length;
        const label = document.getElementById('charCountLabel');
        if (label) {
            label.textContent = count + ' / 2000';
            if (count > 1800) {
                label.className = 'fs-12 text-danger fw-bold';
            } else {
                label.className = 'fs-12 text-muted fw-semibold';
            }
        }
    }

    // Blog Post Like & Dislike Ajax Handling
    document.addEventListener('DOMContentLoaded', function () {
        const likeBtn = document.getElementById('blogLikeBtn');
        const dislikeBtn = document.getElementById('blogDislikeBtn');
        const shareBtn = document.getElementById('shareArticleBtn');

        if (shareBtn) {
            shareBtn.addEventListener('click', function () {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(window.location.href)
                        .then(() => showToast('Article link copied to clipboard! 📋'))
                        .catch(() => showToast('Could not copy link', false));
                } else {
                    showToast('Sharing ' + window.location.href);
                }
            });
        }

        function sendBlogReaction(type) {
            const url = '{{ route("blog.react", $blog->id) }}';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reaction_type: type })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('blogLikeCount').textContent = data.likes_count;
                    document.getElementById('blogDislikeCount').textContent = data.dislikes_count;

                    const likeIcon = document.getElementById('blogLikeIcon');
                    const dislikeIcon = document.getElementById('blogDislikeIcon');

                    if (data.user_reaction === 'like') {
                        likeBtn.classList.add('active-like');
                        dislikeBtn.classList.remove('active-dislike');
                        likeIcon.className = 'ri-heart-3-fill fs-16';
                        dislikeIcon.className = 'ri-thumb-down-line fs-16';
                        showToast('Thank you for liking this story! ❤️');
                    } else if (data.user_reaction === 'dislike') {
                        dislikeBtn.classList.add('active-dislike');
                        likeBtn.classList.remove('active-like');
                        dislikeIcon.className = 'ri-thumb-down-fill fs-16';
                        likeIcon.className = 'ri-heart-3-line fs-16';
                        showToast('Feedback noted! 👍');
                    } else {
                        likeBtn.classList.remove('active-like');
                        dislikeBtn.classList.remove('active-dislike');
                        likeIcon.className = 'ri-heart-3-line fs-16';
                        dislikeIcon.className = 'ri-thumb-down-line fs-16';
                        showToast('Reaction updated');
                    }
                }
            })
            .catch(err => {
                console.error('Reaction Error:', err);
                showToast('Failed to update reaction', false);
            });
        }

        if (likeBtn) {
            likeBtn.addEventListener('click', () => sendBlogReaction('like'));
        }
        if (dislikeBtn) {
            dislikeBtn.addEventListener('click', () => sendBlogReaction('dislike'));
        }
    });
</script>
@endpush

