@extends('laundry.admin.layouts.app')
@section('title', 'Laundry')
@section('active-page-package', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">PAKET'S</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.packageUpdate', $package->username) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row px-xxl-2">
                            <div class="col-md-6">
                                <label for="type" class="fw-medium small h6">TIPE</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $package->type) }}" required>
                                </div>
                                <p class="text-danger small">{{ $errors->first('type') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="fw-medium small h6">STATUS</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="status" id="status" class="form-control js-choice h6" required>
                                        <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
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
                            <a href="{{ route('admin.package') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-3">KEMBALI</a>
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
        var priceInputEdit = document.getElementById('price_edit');

        priceInputEdit.addEventListener('input', function (e) {
            priceInputEdit.value = priceInputEdit.value.replace(/[^0-9]/g, '');
            if (priceInputEdit.value.length > 7) {
                priceInputEdit.value = priceInputEdit.value.slice(0, 7);
            }
        });
    });
</script>
@endsection
