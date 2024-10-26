@extends('laundry.admin.layouts.app')
@section('title', 'Laundry')
@section('active-page-owner', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">PEMILIK'S</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ownerUpdate', $owner->username) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row px-xxl-2">
                            <div class="col-md-6">
                                <label for="name" class="fw-medium small h6">NAMA</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $owner->name) }}" required>
                                </div>
                                <p class="text-danger small">{{ $errors->first('name') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="fw-medium small h6">STATUS</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="status" id="status" class="form-control js-choice h6" required>
                                        <option value="active" {{ old('status', $owner->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $owner->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('status') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="fw-medium small h6">JENIS KELAMIN</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="gender" id="gender" class="form-control js-choice h6" required>
                                        <option value="male" {{ old('gender', $owner->gender) == 'male' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="female" {{ old('gender', $owner->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('gender') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_create" class="fw-medium small h6">TELEPON</label>
                                <div class="form-border-bottom form-control-transparent position-relative">
                                    <input type="text" name="phone" id="phone_create" class="form-control ms-3" maxlength="15" value="{{ old('phone', $owner->phone) }}" required>
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
                                    <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address', $owner->address) }}</textarea>
                                </div>
                                <p class="text-danger small">{{ $errors->first('address') }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-0">
                            <a href="{{ route('admin.owner') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-3">KEMBALI</a>
                            <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">LANJUTKAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
@endsection

@section('script')
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
