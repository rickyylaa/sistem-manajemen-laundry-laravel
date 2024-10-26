@extends('laundry.customer.layouts.app')
@section('title', 'Laundry')
@section('active-home-dashboard', 'active')

@section('content')
    <div class="row mb-4 mb-sm-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">BERANDA'S</span>
            </div>
        </div>
    </div>
    <div class="row mb-4 mb-sm-5">
        <div class="col-12 mb-4">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <div class="marquee">
                        <span class="fw-bold h4 mb-1">Promo: Lakukan 10 transaksi dan dapatkan 10 kupon untuk 1 kg laundry!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 mb-4">
            <div class="card border rounded-0 h-100">
                <div class="card-header border-bottom">
                    <span class="card-header-title fw-normal small h6">RIWAYAT TRANSAKSI MINGGU INI</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">FAKTUR</th>
                                    <th scope="col" class="border-0">PAKET</th>
                                    <th scope="col" class="border-0">HARI/TANGGAL</th>
                                    <th scope="col" class="border-0">STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($transaction) > 0)
                                    @foreach ($transaction as $row)
                                        <tr>
                                            <td> </td>
                                            <td> <span class="fw-normal h6">{{ $row->invoice }}</span> </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6 mb-1">{{ ucwords($row->package) }}</span>
                                                </div>
                                            </td>
                                            <td> <span class="fw-normal small h6 mb-1">{{ \Carbon\Carbon::parse($row->date)->translatedFormat('l, d F Y') }}</span> </td>
                                            <td> {!! $row->status_label !!} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            <div class="col-12">
                                                <div class="text-center mt-4">
                                                    <h6 class="fw-lighter text-secondary small mb-2">Anda tidak memiliki data dalam tabel ini</h6>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-4">
            <div class="card border rounded-0 h-100">
                <div class="card-header border-bottom">
                    <span class="card-header-title fw-normal small h6">HARGA LAUNDRY DI EASY WASH LAUNDRY COIN</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">TIPE</th>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">HARGA</th>
                                    <th scope="col" class="border-0"></th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($package) > 0)
                                    @php
                                        $pageNumber = ($package->currentPage() - 1) * $package->perPage();
                                    @endphp
                                    @foreach ($package as $row)
                                        <tr>
                                            <td> </td>
                                                <td> <span class="fw-normal h6">{{ $row->id }}</span> </td>
                                                <td> <span class="fw-normal h6"></span> </td>
                                                <td> <span class="fw-normal h6">{{ $row->type }}</span> </td>
                                                <td> <span class="fw-normal h6"></span> </td>
                                                <td> <span class="fw-normal h6">IDR {{ number_format($row->price) }}</span> </td>
                                                <td> <span class="fw-normal h6"></span> </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            <div class="col-12">
                                                <div class="text-center mt-4">
                                                    <h6 class="fw-lighter text-secondary small mb-2">Anda tidak memiliki data dalam tabel ini</h6>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-header border-bottom">
                    <span class="card-header-title fw-normal small h6">ULASAN TENTANG LAYANAN KAMI</span>
                </div>
                <div class="card-body pt-4 px-4 p-0">
                    @php
                        $averageRating = $averageRating ? number_format($averageRating, 1) : '0.0';
                        $totalReview = $totalReview;
                    @endphp
                    <div class="card bg-light rounded-0 p-4 mb-3">
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
                    <form method="POST" action="{{ route('customer.reviewStore') }}" class="mb-3">
                        @csrf
                        <div class="form-control-bg-light mb-3">
                            <select name="rating" id="rating" class="form-control rounded-0" required>
                                <option value="5" selected>★★★★★ (5/5)</option>
                                <option value="4">★★★★☆ (4/5)</option>
                                <option value="3">★★★☆☆ (3/5)</option>
                                <option value="2">★★☆☆☆ (2/5)</option>
                                <option value="1">★☆☆☆☆ (1/5)</option>
                            </select>
                            <div class="form-text text-danger fw-bold">{{ $errors->first('rating') }}</div>
                        </div>
                        <div class="form-control-bg-light mb-3">
                            <textarea class="form-control rounded-0" name="comment" id="comment" placeholder="Ulasan Anda..." rows="3" required></textarea>
                            <div class="form-text text-danger fw-bold">{{ $errors->first('comment') }}</div>
                        </div>
                        <button type="submit" class="btn btn-lg btn-dark mb-0 rounded-0">
                            <i class="fa-sharp fa-regular fa-paper-plane fs-5 me-2"></i>KIRIM
                        </button>
                    </form>
                    <hr>
                    <div id="reviews-container">
                        @foreach($review as $row)
                            <div class="d-md-flex my-4">
                                <div class="avatar avatar-lg me-3 flex-shrink-0">
                                    <img src="{{ asset('storage/profiles/' . ($row->customer->image ?? 'avatar.png')) }}" alt="avatar" class="avatar-img rounded-circle">
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mt-1 mt-md-0">
                                        <div>
                                            <h6 class="me-3 mb-0">{{ $row->customer->name ?? 'Anonymous' }}</h6>
                                            <ul class="nav nav-divider small mb-0">
                                                <li class="nav-item">Ditinjau pada {{ $row->created_at->translatedFormat('d M Y') }}</li>
                                                <li class="nav-item">{{ $row->customer->review->count() ?? 0 }} Ulasan tertulis</li>
                                            </ul>
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
                                                <li class="list-inline-item me-0 small">(<span>{{ $row->rating }}</span>)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $row->comment }}</p>
                                </div>
                            </div>
                            @if($row->reply)
                                <div class="my-4 ps-2 ps-md-3">
                                    <div class="d-md-flex p-3 bg-light rounded-0">
                                        <img src="{{ asset('storage/profiles/' . ($row->admin->image ?? 'avatar.png')) }}" class="avatar avatar-sm rounded-circle me-3" alt="avatar">
                                        <div class="mt-2 mt-md-0">
                                            <h6 class="mb-1">{{ $row->admin->name ?? 'Anonymous' }}</h6>
                                            <p class="mb-0">{{ $row->reply }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <hr>
                        @endforeach
                    </div>
                    <div class="text-center mb-3" id="load-more-container">
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
    .marquee {
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        box-sizing: border-box;
        padding: 0;
    }

    .marquee span {
        display: inline-block;
        padding-left: 100%;
        animation: marquee 20s linear infinite;
    }

    @keyframes marquee {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    .reviews-container {
        max-height: 200px;
        overflow-y: auto;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
</style>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let page = 1;
        let loading = false;

        $('#load-more').on('click', function() {
            if (loading) return;
            loading = true;

            page++;
            $.ajax({
                url: '{{ route('customer.reviewLoadMore') }}',
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
                                <div class="my-4 ps-2 ps-md-3">
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
                                <div class="d-md-flex my-4">
                                    <div class="avatar avatar-lg me-3 flex-shrink-0">
                                        <img src="/storage/profiles/${review.customer ? review.customer.image : 'avatar.png'}" alt="avatar" class="avatar-img rounded-circle">
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between mt-1 mt-md-0">
                                            <div>
                                                <h6 class="me-3 mb-0">${review.customer ? review.customer.name : 'Anonymous'}</h6>
                                                <ul class="nav nav-divider small mb-0">
                                                    <li class="nav-item">Ditinjau pada ${new Date(review.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</li>
                                                    <li class="nav-item">${reviewsCount} Ulasan tertulis</li>
                                                </ul>
                                                <ul class="list-inline mb-2">
                                                    ${[1, 2, 3, 4, 5].map(i => `
                                                        <li class="list-inline-item me-0">
                                                            <i class="fa-sharp fa-${review.rating >= i ? 'solid' : 'regular'} fa-star text-warning"></i>
                                                        </li>
                                                    `).join('')}
                                                    <li class="list-inline-item me-0 small">(<span>${review.rating}</span>)</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="mb-0">${review.comment}</p>
                                    </div>
                                </div>
                                ${replySection}
                                <hr>
                            `;
                        });
                        $('#reviews-container').append(html);
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
