@extends('laundry.admin.layouts.app')
@section('title', 'Laundry')
@section('active-page-package', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">PAKET'S</span>
                <button type="button" class="btn btn-dark w-100px rounded-0" data-bs-toggle="modal" data-bs-target="#packageModal">TAMBAH</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
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
                                    <th scope="col" class="border-0">STATUS</th>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">AKSI</th>
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
                                            <td> <span class="fw-normal h6">{{ ++$pageNumber }}</span> </td>
                                            <td> <span class="fw-normal h6"></span> </td>
                                            <td> <span class="fw-normal h6">{{ $row->type }}</span> </td>
                                            <td> <span class="fw-normal h6"></span> </td>
                                            <td> <span class="fw-normal h6">IDR {{ number_format($row->price) }}</span> </td>
                                            <td> {!! $row->status_label !!} </td>
                                            <td> <span class="fw-normal h6"></span> </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        <li class="mb-1">
                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#packageUpdateModal-{{ $row->id }}">
                                                                <i class="fa-sharp fa-regular fa-pen-to-square me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form method="POST" id="delete-form-{{ $row->id }}" action="{{ route('admin.packageDestroy', $row->id) }}">
                                                                @csrf @method('DELETE')
                                                                <button type="button" onclick="confirmDelete('{{ $row->id }}')" class="dropdown-item bg-danger-soft-hover">
                                                                    <i class="fa-sharp fa-regular fa-trash me-2"></i>Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal fade" id="packageUpdateModal-{{ $row->id }}" tabindex="-1" aria-labelledby="packageUpdateModallabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content border rounded-0">
                                                            <div class="modal-header border-0">
                                                                <span class="fw-normal text-center h5 mb-0">EDIT PAKET</span>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="{{ route('admin.packageUpdate', $row->id) }}" enctype="multipart/form-data">
                                                                    @csrf @method('PUT')
                                                                    <div class="row px-xxl-2">
                                                                        <div class="col-md-6">
                                                                            <label for="type" class="fw-medium small h6">TIPE</label>
                                                                            <div class="form-border-bottom form-control-transparent">
                                                                                <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $row->type) }}" required>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('type') }}</p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="status" class="fw-medium small h6">STATUS</label>
                                                                            <div class="form-border-bottom form-control-transparent">
                                                                                <select name="status" id="status" class="form-control" required>
                                                                                    <option value="active" {{ old('status', $row->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                                                                    <option value="inactive" {{ old('status', $row->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                                                                </select>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('status') }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label for="price_edit" class="fw-medium small h6">HARGA</label>
                                                                            <div class="form-border-bottom form-control-transparent position-relative">
                                                                                <input type="text" name="price" id="price_edit" class="form-control ms-3" maxlength="7" value="{{ old('price', $row->price) }}" required>
                                                                                <span class="w-30px position-absolute top-50 end-60 translate-middle-y text-dark">IDR</span>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('price') }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center align-items-center mb-0">
                                                                        <button type="button" class="btn btn-secondary w-50 rounded-0 mb-0 me-3" data-bs-dismiss="modal" aria-label="Close">TUTUP</button>
                                                                        <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">LANJUTKAN</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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
                @if (count($package) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">MENUNJUKKAN {{ $package->firstItem() }} HINGGA {{ $package->lastItem() }} DARI {{ $package->total() }} ENTRI</span>
                            {!! $package->links('pagination::bootstrap-4') !!}
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var priceInputCreate = document.getElementById('price_create');
            var priceInputEdit = document.getElementById('price_edit');

            priceInputCreate.addEventListener('input', function (e) {
                priceInputCreate.value = priceInputCreate.value.replace(/[^0-9]/g, '');
                if (priceInputCreate.value.length > 7) {
                    priceInputCreate.value = priceInputCreate.value.slice(0, 7);
                }
            });

            priceInputEdit.addEventListener('input', function (e) {
                priceInputEdit.value = priceInputEdit.value.replace(/[^0-9]/g, '');
                if (priceInputEdit.value.length > 7) {
                    priceInputEdit.value = priceInputEdit.value.slice(0, 7);
                }
            });
        });
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModallabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border rounded-0">
                <div class="modal-header border-0">
                    <span class="fw-normal text-center h5 mb-0">TAMBAH PAKET</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.packageStore') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row px-xxl-2">
                            <div class="col-md-6">
                                <label for="type" class="fw-medium small h6">TIPE</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <input type="text" name="type" id="type" class="form-control" value="{{ old('type') }}" required>
                                </div>
                                <p class="text-danger small">{{ $errors->first('type') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="fw-medium small h6">STATUS</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="status" id="status" class="form-control js-choice h6" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('status') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="price_create" class="fw-medium small h6">HARGA</label>
                                <div class="form-border-bottom form-control-transparent position-relative">
                                    <input type="text" name="price" id="price_create" class="form-control ms-3" maxlength="7" value="{{ old('price') }}" required>
                                    <span class="w-30px position-absolute top-50 end-60 translate-middle-y text-dark">IDR</span>
                                </div>
                                <p class="text-danger small">{{ $errors->first('price') }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-0">
                            <button type="button" class="btn btn-secondary w-50 rounded-0 mb-0 me-3" data-bs-dismiss="modal" aria-label="Close">TUTUP</button>
                            <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">LANJUTKAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

