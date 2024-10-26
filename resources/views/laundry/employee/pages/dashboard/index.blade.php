@extends('laundry.employee.layouts.app')
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
    <div class="row g-4 mb-4 mb-sm-5">
        <div class="col-md-4">
            <div class="card card-body rounded-0 border p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $customers->count() }}</h5>
                        <span class="h6 fw-normal mb-0">TOTAL PELANGGAN</span>
                    </div>
                    <div class="icon-lg rounded-0 bg-dark text-white mb-0">
                        <i class="fa-sharp fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body rounded-0 border p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $dailyTransaction }}</h5>
                        <span class="h6 fw-normal mb-0">TOTAL TRANSAKSI HARI INI</span>
                    </div>
                    <div class="icon-lg rounded-0 bg-dark text-white mb-0">
                        <i class="fa-sharp fa-solid fa-clipboard-list-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body rounded-0 border p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">IDR {{ number_format($dailyIncome) }}</h5>
                        <span class="h6 fw-normal mb-0">TOTAL PENDAPATAN HARI INI</span>
                    </div>
                    <div class="icon-lg rounded-0 bg-dark text-white mb-0">
                        <i class="fa-sharp fa-solid fa-hand-holding-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 mb-sm-5">
        <div class="col-12">
            <div class="card card-body border overflow-hidden rounded-0">
                <div id="chart"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var transactions = @json($chartData);

            var seriesData = transactions.map(function(item) {
                return {
                    x: new Date(item.date),
                    y: item.total_price
                };
            });

            var options = {
                chart: {
                    type: 'line',
                    height: 350,
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Total Pendapatan',
                    data: seriesData
                }],
                xaxis: {
                    type: 'datetime',
                    labels: {
                        format: 'dd MMM yyyy'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy',
                        formatter: function(val) {
                            var date = new Date(val);
                            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                        }
                    },
                    y: {
                        formatter: function(val) {
                            return 'IDR ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
@endsection
