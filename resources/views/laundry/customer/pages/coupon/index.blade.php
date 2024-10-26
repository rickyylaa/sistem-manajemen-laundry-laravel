@extends('laundry.customer.layouts.app')
@section('title', 'Laundry')
@section('active-data-coupon', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">RIWAYAT KUPON'S</span>
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
                                    <th scope="col" class="border-0">PELANGGAN</th>
                                    <th scope="col" class="border-0">JUMLAH</th>
                                    <th scope="col" class="border-0">STATUS</th>
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
