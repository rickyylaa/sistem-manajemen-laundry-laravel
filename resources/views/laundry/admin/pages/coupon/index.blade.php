@extends('laundry.admin.layouts.app')
@section('title', 'Laundry')
@section('active-data-coupon', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">KUPON'S</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.coupon') }}" class="d-flex justify-content-between mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent">
                                <select name="status" class="form-control js-choice h6">
                                    <option value="">Silakan pilih salah satu</option>
                                    <option value="not used">Belum Digunakan</option>
                                    <option value="used">Sudah Digunakan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent h6">
                                <div class="input-group float-right">
                                    <input type="text" name="q" class="form-control" value="{{ request()->q }}" placeholder="Cari...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn border-0 px-3 py-0 position-absolute top-50 end-0 translate-middle-y">
                                            <i class="fa-sharp fa-solid fa-magnifying-glass fs-6"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">PELANGGAN</th>
                                    <th scope="col" class="border-0">JUMLAH</th>
                                    <th scope="col" class="border-0">STATUS</th>
                                    <th scope="col" class="border-0">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($coupon) > 0)
                                    @php
                                        $pageNumber = ($coupon->currentPage() - 1) * $coupon->perPage();
                                    @endphp
                                    @foreach ($coupon as $row)
                                        <tr>
                                            <td> </td>
                                            <td> <span class="fw-normal h6">{{ ++$pageNumber }}</span> </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6">Nama: {{ ucwords($row->customer_name) }}</span>
                                                    <span class="fw-normal small h6">Telepon: {{ chunk_split($row->customer_phone, 4) }}</span>
                                                </div>
                                            </td>
                                            <td> <span class="fw-normal small h6">{{ $row->amount }} Kupon</span> </td>
                                            <td> {!! $row->status_label !!} </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        @if ($row->amount == 10 && $row->status == 'not used')
                                                            <li>
                                                                <form method="POST" action="{{ route('admin.couponReceive', $row->id) }}">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-regular fa-octagon-check me-2"></i>Terima
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <form method="POST" id="delete-form-{{ $row->id }}" action="{{ route('admin.couponDestroy', $row->id) }}">
                                                                @csrf @method('DELETE')
                                                                <button type="button" onclick="confirmDelete('{{ $row->id }}')" class="dropdown-item bg-danger-soft-hover">
                                                                    <i class="fa-sharp fa-regular fa-trash me-2"></i>Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
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
                @if (count($coupon) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">MENUNJUKKAN {{ $coupon->firstItem() }} HINGGA {{ $coupon->lastItem() }} DARI {{ $coupon->total() }} ENTRI</span>
                            {!! $coupon->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
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
                    let formId = 'delete-form-' + id;
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endsection
