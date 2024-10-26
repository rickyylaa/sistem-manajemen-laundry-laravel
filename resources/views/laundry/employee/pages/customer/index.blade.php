@extends('laundry.employee.layouts.app')
@section('title', 'Laundry')
@section('active-page-customer', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">PELANGGAN'S</span>
                <button type="button" class="btn btn-dark w-100px rounded-0" data-bs-toggle="modal" data-bs-target="#customerModal">TAMBAH</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('employee.customer') }}" class="d-flex justify-content-between mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent">
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
                                    <th scope="col" class="border-0">FOTO</th>
                                    <th scope="col" class="border-0">NAMA</th>
                                    <th scope="col" class="border-0">TELEPON</th>
                                    <th scope="col" class="border-0">STATUS</th>
                                    <th scope="col" class="border-0">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($customer) > 0)
                                    @php
                                        $pageNumber = ($customer->currentPage() - 1) * $customer->perPage();
                                    @endphp
                                    @foreach ($customer as $row)
                                        <tr>
                                            <td> </td>
                                            <td> <span class="fw-normal h6">{{ ++$pageNumber }}</span> </td>
                                            <td>
                                                <div class="card card-element-hover card-overlay-hover overflow-hidden avatar-xl rounded-0">
                                                    <img src="{{ asset('storage/profiles/'. $row->image) }}" alt="avatar" class="avatar-img bg-light shadow rounded-0">
                                                    <a href="{{ asset('storage/profiles/'. $row->image) }}" class="hover-element position-absolute w-100 h-100" data-glightbox data-gallery="gallery">
                                                        <i class="fa-sharp fa-solid fa-expand fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-0 p-2 lh-1"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td> <span class="fw-normal h6">{{ ucwords($row->name) }}</span> </td>
                                            <td> <span class="fw-normal h6">{{ chunk_split($row['phone'], 4); }}</span> </td>
                                            <td> {!! $row->status_label !!} </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        <li class="mb-1">
                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#customerUpdateModal-{{ $row->username }}">
                                                                <i class="fa-sharp fa-regular fa-pen-to-square me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li class="mb-1">
                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $row->username }}">
                                                                <i class="fa-sharp fa-regular fa-circle-info me-2"></i>Rincian
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form method="POST" id="delete-form-{{ $row->username }}" action="{{ route('employee.customerDestroy', $row->username) }}">
                                                                @csrf @method('DELETE')
                                                                <button type="button" onclick="confirmDelete('{{ $row->username }}')" class="dropdown-item bg-danger-soft-hover">
                                                                    <i class="fa-sharp fa-regular fa-trash me-2"></i>Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal fade" id="detailModal-{{ $row->username }}" tabindex="-1" aria-labelledby="detailModallabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content border rounded-0">
                                                            <div class="modal-header border-0">
                                                                <span class="fw-normal text-center h5 mb-0">RINCIAN PELANGGAN</span>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row px-xxl-2 mb-3">
                                                                    <div class="col-md-6 col-xl-5 ms-3">
                                                                        <div class="card overflow-hidden rounded-0">
                                                                            <img src="{{ asset('storage/profiles/'. $row->image) }}" class="rounded-0" alt="profiles">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-xl-6">
                                                                        <ul class="list-group list-group-borderless">
                                                                            <li class="list-group-item mb-2">
                                                                                <h6 class="fw-normal">NAMA PENGGUNA : {{ $row->username }}</h6>
                                                                            </li>
                                                                            <li class="list-group-item mb-2">
                                                                                <h6 class="fw-normal">NAMA : {{ ucwords($row->name) }}</h6>
                                                                            </li>
                                                                            <li class="list-group-item mb-2">
                                                                                <h6 class="fw-normal">TELEPON : {{ chunk_split($row['phone'], 4); }}</h6>
                                                                            </li>
                                                                            <li class="list-group-item mb-2">
                                                                                <h6 class="fw-normal">JENIS KELAMIN : {!! $row->gender_label !!}</h6>
                                                                            </li>
                                                                            <li class="list-group-item mb-2">
                                                                                <h6 class="fw-normal">ALAMAT : {{ ucwords($row->address) }}</h6>
                                                                            </li>
                                                                            <li class="list-group-item mb-2">
                                                                                <h6 class="fw-normal">TERDAFTAR PADA : {{ $row->created_at->translatedFormat('d F Y') }}</h6>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center px-xxl-2">
                                                                    <button type="button" class="btn btn-secondary w-100 rounded-0" data-bs-dismiss="modal" aria-label="Close">TUTUP</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="customerUpdateModal-{{ $row->username }}" tabindex="-1" aria-labelledby="customerUpdateModallabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content border rounded-0">
                                                            <div class="modal-header border-0">
                                                                <span class="fw-normal text-center h5 mb-0">EDIT PELANGGAN</span>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="{{ route('employee.customerUpdate', $row->username) }}" enctype="multipart/form-data">
                                                                    @csrf @method('PUT')
                                                                    <div class="row px-xxl-2">
                                                                        <div class="col-md-6">
                                                                            <label for="name" class="fw-medium small h6">NAMA</label>
                                                                            <div class="form-border-bottom form-control-transparent">
                                                                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $row->name) }}" required>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('name') }}</p>
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
                                                                        <div class="col-md-6">
                                                                            <label for="gender" class="fw-medium small h6">JENIS KELAMIN</label>
                                                                            <div class="form-border-bottom form-control-transparent">
                                                                                <select name="gender" id="gender" class="form-control js-choice h6" required>
                                                                                    <option value="male" {{ old('gender', $row->gender) == 'male' ? 'selected' : '' }}>Laki-Laki</option>
                                                                                    <option value="female" {{ old('gender', $row->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                                                                </select>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('gender') }}</p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="phone_create" class="fw-medium small h6">TELEPON</label>
                                                                            <div class="form-border-bottom form-control-transparent position-relative">
                                                                                <input type="text" name="phone" id="phone_create" class="form-control ms-3" maxlength="15" value="{{ old('phone', $row->phone) }}" required>
                                                                                <span class="w-30px position-absolute top-50 end-60 translate-middle-y text-dark">+62</span>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('phone') }}</p>
                                                                        </div>
                                                                        <div class="col-12 position-relative">
                                                                            <label for="password" class="fw-medium small h6">KATA SANDI</label>
                                                                            <div class="form-border-bottom form-control-transparent">
                                                                                <input type="password" name="password" id="password" class="form-control fakepassword">
                                                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                                                                    <i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
                                                                                </span>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('password') }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label for="image" class="fw-medium small h6 mb-3">UPLOAD FOTO
                                                                                <a href="#" class="text-dark-hover h6 mb-0" role="button" id="info" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="fa-sharp fa-solid fa-circle-info"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu dropdown-w-sm dropdown-menu-start min-w-auto shadow rounded" aria-labelledby="info">
                                                                                    <li>
                                                                                        <div class="d-flex justify-content-between">
                                                                                            <span class="small">Hanya JPG, JPEG, dan PNG</span>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </label>
                                                                            <div class="form-border-bottom form-control-transparent d-flex align-items-center">
                                                                                <div class="flex-grow-1">
                                                                                    <div class="form-fs-md mb-3 px-lg-3">
                                                                                        <input type="file" name="image" id="image" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('image') }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label for="address" class="fw-medium small h6">ALAMAT</label>
                                                                            <div class="form-border-bottom form-control-transparent">
                                                                                <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address', $row->address) }}</textarea>
                                                                            </div>
                                                                            <p class="text-danger small">{{ $errors->first('address') }}</p>
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
                @if (count($customer) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">MENUNJUKKAN {{ $customer->firstItem() }} HINGGA {{ $customer->lastItem() }} DARI {{ $customer->total() }} ENTRI</span>
                            {!! $customer->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/css/glightbox.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('script')
    <script>
        function confirmDelete(username) {
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
                    let formId = 'delete-form-' + CSS.escape(username);
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var phoneInputCreate = document.getElementById('phone_create');
            var phoneInputEdit = document.getElementById('phone_edit');

            phoneInputCreate.addEventListener('input', function (e) {
                phoneInputCreate.value = phoneInputCreate.value.replace(/[^0-9]/g, '');
                if (phoneInputCreate.value.length > 15) {
                    phoneInputCreate.value = phoneInputCreate.value.slice(0, 7);
                }
            });

            phoneInputEdit.addEventListener('input', function (e) {
                phoneInputEdit.value = phoneInputEdit.value.replace(/[^0-9]/g, '');
                if (phoneInputEdit.value.length > 15) {
                    phoneInputEdit.value = phoneInputEdit.value.slice(0, 7);
                }
            });
        });
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModallabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border rounded-0">
                <div class="modal-header border-0">
                    <span class="fw-normal text-center h5 mb-0">TAMBAH PELANGGAN</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('employee.customerStore') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row px-xxl-2">
                            <div class="col-12">
                                <label for="name" class="fw-medium small h6">NAMA</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                                <p class="text-danger small">{{ $errors->first('name') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="fw-medium small h6">JENIS KELAMIN</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="gender" id="gender" class="form-control js-choice h6" required>
                                        <option value="male">Laki-Laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('gender') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_create" class="fw-medium small h6">TELEPON</label>
                                <div class="form-border-bottom form-control-transparent position-relative">
                                    <input type="text" name="phone" id="phone_create" class="form-control ms-3" maxlength="15" value="{{ old('phone') }}" required>
                                    <span class="w-30px position-absolute top-50 end-60 translate-middle-y text-dark">+62</span>
                                </div>
                                <p class="text-danger small">{{ $errors->first('phone') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="image" class="fw-medium small h6 mb-3">UPLOAD FOTO
                                    <a href="#" class="text-dark-hover h6 mb-0" role="button" id="info" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-sharp fa-solid fa-circle-info"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-w-sm dropdown-menu-start min-w-auto shadow rounded" aria-labelledby="info">
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <span class="small">Hanya JPG, JPEG, dan PNG</span>
                                            </div>
                                        </li>
                                    </ul>
                                </label>
                                <div class="form-border-bottom form-control-transparent d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="form-fs-md mb-3 px-lg-3">
                                            <input type="file" name="image" id="image" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <p class="text-danger small">{{ $errors->first('image') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="address" class="fw-medium small h6">ALAMAT</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                                </div>
                                <p class="text-danger small">{{ $errors->first('address') }}</p>
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

