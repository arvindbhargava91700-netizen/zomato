@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">My Feedback</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Feedback</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- profile section starts -->
    <section class="profile-section section-b-space">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-3">
                @include('manage.front.partials.profile-sidebar')
                <div class="col-lg-9">
                    <div class="change-profile-content bg-white p-4 rounded-4 shadow-sm">
                        <div class="title mb-4 border-bottom pb-3">
                            <h3 class="mb-0 fw-bold">My Ratings & Feedback</h3>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle custom-table">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Order Details</th>
                                        <th>Restaurant Rating</th>
                                        <th>Food Rating</th>
                                        <th>Delivery Rating</th>
                                        <th>Feedback Comment</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">#{{ $review->order_id }}</div>
                                            <div class="text-muted small">{{ $review->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-truncate" style="max-width: 120px;" title="{{ $review->restaurant?->restaurant_name }}">{{ $review->restaurant?->restaurant_name ?? 'N/A' }}</div>
                                            <div class="d-flex text-warning mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="{{ $i <= $review->restaurant_rating ? 'ri-star-fill' : 'ri-star-line text-muted opacity-25' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-transparent" style="visibility: hidden;">Food</div>
                                            <div class="d-flex text-warning mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="{{ $i <= $review->food_rating ? 'ri-star-fill' : 'ri-star-line text-muted opacity-25' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-truncate" style="max-width: 120px;" title="{{ $review->deliveryPartner?->name }}">{{ $review->deliveryPartner?->name ?? 'N/A' }}</div>
                                            <div class="d-flex text-warning mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="{{ $i <= $review->delivery_rating ? 'ri-star-fill' : 'ri-star-line text-muted opacity-25' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            @if($review->comment)
                                                <div class="bg-light p-2 rounded small fst-italic text-muted" style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $review->comment }}">
                                                    "{{ $review->comment }}"
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted fw-normal">No comment</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-inline-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle action-btn" data-bs-toggle="modal" data-bs-target="#editReviewModal{{ $review->id }}" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle action-btn" data-bs-toggle="modal" data-bs-target="#deleteReviewModal{{ $review->id }}" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Review Modal -->
                                    <div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <form method="POST" action="{{ route('review.update', $review->id) }}" onsubmit="let b=this.querySelector('button[type=submit]'); b.style.pointerEvents='none'; b.style.opacity='0.7'; b.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Updating...';">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Edit Feedback (Order #{{ $review->order_id }})</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-4">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="bg-light p-3 rounded-3">
                                                                    <label class="form-label fw-semibold mb-1">Restaurant Rating</label>
                                                                    <select name="restaurant_rating" class="form-select border-0 shadow-sm">
                                                                        <option value="">No Rating</option>
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <option value="{{ $i }}" {{ $review->restaurant_rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="bg-light p-3 rounded-3">
                                                                    <label class="form-label fw-semibold mb-1">Food Rating</label>
                                                                    <select name="food_rating" class="form-select border-0 shadow-sm">
                                                                        <option value="">No Rating</option>
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <option value="{{ $i }}" {{ $review->food_rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="bg-light p-3 rounded-3">
                                                                    <label class="form-label fw-semibold mb-1">Delivery Rating</label>
                                                                    <select name="delivery_rating" class="form-select border-0 shadow-sm">
                                                                        <option value="">No Rating</option>
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <option value="{{ $i }}" {{ $review->delivery_rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="bg-light p-3 rounded-3">
                                                                    <label class="form-label fw-semibold mb-1">Your Comment</label>
                                                                    <textarea name="comment" rows="3" class="form-control border-0 shadow-sm" placeholder="Write your experience here...">{{ $review->comment }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn theme-btn rounded-pill px-4 m-0 save-rating">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Review Modal -->
                                    <div class="modal fade" id="deleteReviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content border-0 shadow-lg rounded-4 text-center">
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 70px; height: 70px;">
                                                            <i class="ri-delete-bin-line" style="font-size: 32px;"></i>
                                                        </div>
                                                    </div>
                                                    <h5 class="fw-bold mb-2">Delete Feedback?</h5>
                                                    <p class="text-muted small mb-4">Are you sure you want to permanently delete this feedback? This action cannot be undone.</p>
                                                    <form method="POST" action="{{ route('review.destroy', $review->id) }}" onsubmit="let b=this.querySelector('button[type=submit]'); b.style.pointerEvents='none'; b.style.opacity='0.7'; b.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Deleting...';">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4 delete-rating">Yes, Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ri-chat-3-line" style="font-size: 40px; display: block; margin-bottom: 10px; color: #dee2e6;"></i>
                                                <h6 class="fw-normal text-secondary">You haven't submitted any feedback yet.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $reviews->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
