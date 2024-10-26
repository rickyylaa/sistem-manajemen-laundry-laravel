@extends('laundry.admin.layouts.app')
@section('title', 'Laundry')
@section('active-other-review', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">ULASAN'S</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    @php
                        $averageRating = $averageRating ? number_format($averageRating, 1) : '0.0';
                        $totalReview = $totalReview;
                    @endphp
                    <div class="card bg-light p-4 mb-3 rounded-0">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <h2 class="mb-0">{{ $averageRating }}</h2>
                                    <p class="mb-2">Berdasarkan {{ $totalReview }} Ulasan</p>
                                    <ul class="list-inline mb-0">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($averageRating >= $i)
                                                <li class="list-inline-item me-0">
                                                    <i class="fa-sharp fa-solid fa-star text-warning"></i>
                                                </li>
                                            @elseif ($averageRating >= $i - 0.5)
                                                <li class="list-inline-item me-0">
                                                    <i class="fa-sharp fa-solid fa-star-half-stroke text-warning"></i>
                                                </li>
                                            @else
                                                <li class="list-inline-item me-0">
                                                    <i class="fa-sharp fa-regular fa-star text-warning"></i>
                                                </li>
                                            @endif
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="reviews-container">
                        <div class="vstack gap-4 mt-5">
                            @foreach($review as $row)
                                <div class="row border-top g-3 g-lg-3 mb-3">
                                    <div class="col-md-4 col-xxl-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xl me-2 flex-shrink-0">
                                                <img src="{{ asset('storage/profiles/' . ($row->customer->image ?? 'avatar.png')) }}" alt="avatar" class="avatar-img rounded-circle">
                                            </div>
                                            <div class="ms-2">
                                                <h5 class="mb-1">{{ $row->customer->name ?? 'Anonymous' }}</h5>
                                                <p class="mb-0">{{ $row->customer->review->count() ?? 0 }} Ulasan tertulis</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xxl-9">
                                        <ul class="list-inline mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($row->rating >= $i)
                                                    <li class="list-inline-item me-0">
                                                        <i class="fa-sharp fa-solid fa-star text-warning"></i>
                                                    </li>
                                                @else
                                                    <li class="list-inline-item me-0">
                                                        <i class="fa-sharp fa-regular fa-star text-warning"></i>
                                                    </li>
                                                @endif
                                            @endfor
                                        </ul>
                                        <h6><span class="text-body fw-light">Ditinjau pada:</span> {{ $row->created_at->translatedFormat('d M Y') }}</h6>
                                        <p>{{ $row->comment }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if ($row->reply == NULL)
                                                <div>
                                                    <a class="btn btn-sm btn-dark rounded-0 mb-0" data-bs-toggle="collapse" href="#collapseComment-{{ $row->id }}" role="button" aria-expanded="false" aria-controls="collapseComment-{{ $row->id }}">
                                                        <i class="fa-sharp fa-regular fa-paper-plane me-2"></i>BALAS
                                                    </a>
                                                </div>
                                            @endif
                                            <div>
                                                <form method="POST" id="delete-form-{{ $row->id }}" action="{{ route('admin.reviewDestroy', $row->id) }}">
                                                    @csrf @method('DELETE')
                                                    <a href="javasciprt:;" class="btn btn-sm btn-danger rounded-0 mb-0" onclick="confirmDelete('{{ $row->id }}')">
                                                        <i class="fa-sharp fa-regular fa-trash me-2"></i>HAPUS
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapseComment-{{ $row->id }}">
                                            <form method="POST" action="{{ route('admin.reviewUpdate', $row->id) }}">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="admin_id" value="{{ Auth::user()->id }}">
                                                <div class="d-flex mt-3">
                                                    <textarea class="form-control mb-0" name="reply" placeholder="Tambahkan balasan..." rows="2" spellcheck="false">{{ $row->reply }}</textarea>
                                                    <button type="submit" class="btn btn-sm btn-dark rounded-0 ms-2 px-4 mb-0 flex-shrink-0">
                                                        <i class="fa-sharp fa-regular fa-paper-plane fs-5"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        @if($row->reply)
                                            <div class="mt-2">
                                                <div class="d-md-flex p-3 bg-light rounded-0">
                                                    <img src="{{ asset('storage/profiles/' . ($row->admin->image ?? 'avatar.png')) }}" class="avatar avatar-sm rounded-circle me-3" alt="avatar">
                                                    <div class="mt-2 mt-md-0">
                                                        <h6 class="mb-1">{{ $row->admin->name ?? 'Anonymous' }}</h6>
                                                        <p class="mb-0">{{ $row->reply }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-center mt-4" id="load-more-container">
                        @if($review->hasMorePages())
                            <button class="btn btn-lg btn-dark mb-0 rounded-0" id="load-more">LIHAT SEMUA</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>

    .reviews-container {
        max-height: 200px;
        overflow-y: auto;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('script')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat memulihkan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0b0a12',
            cancelButtonColor: '#747579',
            cancelButtonText: 'BATAL',
            confirmButtonText: 'LANJUTKAN',
            willOpen: () => {
                const popup = Swal.getPopup();
                if (popup) {
                    popup.style.borderRadius = '0';

                    const confirmButton = Swal.getConfirmButton();
                    const cancelButton = Swal.getCancelButton();

                    if (confirmButton) {
                        confirmButton.style.borderRadius = '0';
                        confirmButton.style.fontWeight = 'bold';
                    }
                    if (cancelButton) {
                        cancelButton.style.borderRadius = '0';
                        cancelButton.style.fontWeight = 'bold';
                    }

                    const actions = Swal.getActions();
                    if (actions) {
                        actions.style.flexDirection = 'row-reverse';
                        actions.style.justifyContent = 'space-between';
                    }

                    const title = Swal.getTitle();
                    if (title) {
                        title.style.color = '#000';
                    }

                    const warningIcon = Swal.getIcon();
                    if (warningIcon) {
                        warningIcon.style.color = '#0b0a12';
                        warningIcon.style.borderColor = '#0b0a12';
                        warningIcon.style.fontWeight = 'bold';
                    }
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
    </script>

    <script>
        $(document).ready(function() {
            let page = 1;
            let loading = false;

            $('#load-more').on('click', function() {
                if (loading) return;
                loading = true;

                page++;
                $.ajax({
                    url: '{{ route('admin.reviewLoadMore') }}',
                    method: 'GET',
                    data: { page: page },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data.length > 0) {
                            let html = '';
                            $.each(response.data, function(index, review) {
                                let reviewsCount = review.customer.reviews_count || 0;
                                let adminImage = review.admin ? `/storage/profiles/${review.admin.image}` : '/storage/profiles/avatar.png';
                                let replySection = review.reply ? `
                                    <div class="mt-2">
                                        <div class="d-md-flex p-3 bg-light rounded-0">
                                            <img src="${adminImage}" class="avatar avatar-sm rounded-circle me-3" alt="avatar">
                                            <div class="mt-2 mt-md-0">
                                                <h6 class="mb-1">${review.admin ? review.admin.name : 'Anonymous'}</h6>
                                                <p class="mb-0">${review.reply}</p>
                                            </div>
                                        </div>
                                    </div>
                                ` : '';

                                html += `
                                <div class="row border-top g-3 g-lg-3 mb-3">
                                    <div class="col-md-4 col-xxl-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xl me-2 flex-shrink-0">
                                                <img src="/storage/profiles/${review.customer.image ? review.customer.image : 'avatar.png'}" alt="avatar" class="avatar-img rounded-circle">
                                            </div>
                                            <div class="ms-2">
                                                <h5 class="mb-1">${review.customer.name ? review.customer.name : 'Anonymous'}</h5>
                                                <p class="mb-0">${reviewsCount} Ulasan tertulis</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xxl-9">
                                        <ul class="list-inline mb-2">
                                            ${[1, 2, 3, 4, 5].map(i => `
                                                <li class="list-inline-item me-0">
                                                    <i class="fa-sharp fa-${review.rating >= i ? 'solid' : 'regular'} fa-star text-warning"></i>
                                                </li>
                                            `).join('')}
                                        </ul>
                                        <h6><span class="text-body fw-light">Ditinjau pada:</span> ${new Date(review.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</h6>
                                        <p>{{ $row->comment }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            ${!review.reply ? `
                                                <div>
                                                    <a href="#collapseComment-${review.id}" class="btn btn-sm btn-dark rounded-0 mb-0" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseComment-${review.id}">
                                                        <i class="fa-sharp fa-regular fa-paper-plane me-2"></i>BALAS
                                                    </a>
                                                </div>
                                            ` : ''}
                                            <div>
                                                <form method="POST" id="delete-form-${review.id}" action="/admin/review/${review.id}">
                                                    @csrf @method('DELETE')
                                                    <a href="javasciprt:;" class="btn btn-sm btn-danger rounded-0 mb-0" onclick="confirmDelete('${review.id}')">
                                                        <i class="fa-sharp fa-regular fa-trash me-2"></i>HAPUS
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapseComment-${review.id}">
                                            <form method="POST" action="/admin/review/${review.id}">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="admin_id" value="{{ Auth::user()->id }}">
                                                <div class="d-flex mt-3">
                                                    <textarea class="form-control mb-0" name="reply" placeholder="Tambahkan balasan..." rows="2" spellcheck="false">${review.reply || ''}</textarea>
                                                    <button type="submit" class="btn btn-sm btn-dark rounded-0 ms-2 px-4 mb-0 flex-shrink-0">
                                                        <i class="fa-sharp fa-regular fa-paper-plane fs-5"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        ${replySection}
                                    </div>
                                </div>
                                `;
                            });
                            $('#reviews-container .vstack').append(html);
                            loading = false;

                            if (!response.next_page_url) {
                                $('#load-more-container').hide();
                            }
                        } else {
                            $('#load-more-container').hide();
                        }
                    },
                    error: function() {
                        loading = false;
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endsection
