@extends('laundry.owner.layouts.app')
@section('title', 'Laundry')
@section('active-data-report', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">LAPORAN'S</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.report') }}" class="d-flex justify-content-between align-items-center mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent">
                                <div class="form-fs-md">
                                    <div class="rounded position-relative">
                                        <input type="text" name="date" id="date" class="form-control pe-5 bg-secondary bg-opacity-10">
                                        <button type="submit" class="btn btn-link bg-transparent px-2 py-0 position-absolute top-50 end-0 translate-middle-y text-dark-hover">
                                            <i class="fa-sharp fa-solid fa-magnifying-glass fs-6"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3 text-end">
                            <a target="_blank" class="btn btn-dark w-100px rounded-0" id="exportpdf">CETAK</a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">FAKTUR</th>
                                    <th scope="col" class="border-0">PELANGGAN</th>
                                    <th scope="col" class="border-0">PAKET</th>
                                    <th scope="col" class="border-0">HARI/TANGGAL</th>
                                    <th scope="col" class="border-0">AKSI</th>
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
                                                    <span class="fw-normal small h6">Nama: {{ ucwords($row->customer_name) }}</span>
                                                    <span class="fw-normal small h6">Telepon: {{ chunk_split($row->customer_phone, 4) }}</span>
                                                </div>
                                            </td>
                                            <td> <span class="fw-normal small h6">{{ ucwords($row->package) }}</span> </td>
                                            <td> <span class="fw-normal small h6">{{ \Carbon\Carbon::parse($row->date)->translatedFormat('l, d F Y') }}</span> </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        <li>
                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $row->invoice }}">
                                                                <i class="fa-sharp fa-regular fa-circle-info me-2"></i>Rincian
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal fade" id="detailModal-{{ $row->invoice }}" tabindex="-1" aria-labelledby="detailModallabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content border rounded-0">
                                                            <div class="modal-header border-0">
                                                                <span class="fw-normal text-center h5 mb-0">RINCIAN TRANSAKSI</span>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row px-xxl-2 mb-3">
                                                                    <div class="col-md-6">
                                                                        <ul class="list-group list-group-borderless">
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Faktur:</span>
                                                                                <span class="fw-normal mb-0">{{ $row->invoice }}</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Nama Pelanggan:</span>
                                                                                <span class="fw-normal mb-0">{{ ucwords($row->customer_name) }}</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Telepon Pelanggan:</span>
                                                                                <span class="fw-normal mb-0">{{ chunk_split($row['customer_phone'], 4); }}</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Tanggal Transaksi:</span>
                                                                                <span class="fw-normal mb-0">{{ \Carbon\Carbon::parse($row->date)->translatedFormat('l, d F Y') }}</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <ul class="list-group list-group-borderless">
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Paket:</span>
                                                                                <span class="fw-normal mb-0">{{ ucwords($row->package) }}</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Berat:</span>
                                                                                <span class="fw-normal mb-0">{{ $row->weight }} kg</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Total:</span>
                                                                                <span class="fw-normal mb-0">IDR {{ number_format($row->price) }}</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Jumlah Yang Dibayar:</span>
                                                                                <span class="fw-normal mb-0">IDR {{ number_format($row->detail->amount) }}</span>
                                                                            </li>
                                                                            <li class="list-group-item d-grid mb-3">
                                                                                <span class="fw-normal h6 mb-0">Status:</span>
                                                                                <span class="fw-normal mb-0">{!! $row->amount_non_label !!}</span>
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
            </div>
        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let start = moment().startOf('month');
        let end = moment().endOf('month');

        function formatDateForLink(date) {
            return date.format('DD MMMM YYYY');
        }

        $('#date').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'DD MMMM YYYY',
            }
        }, function(first, last) {
            $('#exportpdf').attr('href', '/owner/report/pdf/' + formatDateForLink(first) + '+' + formatDateForLink(last));
        });

        $('#exportpdf').attr('href', '/owner/report/pdf/' + formatDateForLink(start) + '+' + formatDateForLink(end));
    });
    </script>
@endsection
