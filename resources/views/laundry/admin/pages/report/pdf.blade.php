<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Transaksi Periode : {{ $date[0] }} - {{ $date[1] }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/icon.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>
<body>
    <div class="tm_container">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1" id="tm_download_section">
                <div class="tm_invoice_in">
                    <div class="tm_invoice_head tm_align_center tm_mb20">
                        <div class="tm_invoice_left">
                            <div class="tm_logo tm_size1">
                                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo">
                            </div>
                        </div>
                    </div>
                    <div class="tm_invoice_info_2 tm_mb20">
                        <p class="tm_invoice_date tm_m0">Periode: <b class="tm_primary_color">{{ $date[0] }} - {{ $date[1] }}</b></p>
                    </div>
                    <div class="tm_table tm_style1 tm_mb40">
                        <div class="tm_round_border">
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_00 tm_semi_bold tm_primary_color tm_gray_bg">Faktur</th>
                                            <th class="tm_width_000 tm_semi_bold tm_primary_color tm_gray_bg">Pelanggan</th>
                                            <th class="tm_width_000 tm_semi_bold tm_primary_color tm_gray_bg">Paket</th>
                                            <th class="tm_width_0 tm_semi_bold tm_primary_color tm_gray_bg">Berat</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Keseluruhan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @forelse ($transaction as $data)
                                            @foreach ($data->details as $row)
                                                <tr class="tm_table_baseline">
                                                    <td class="tm_width_00 tm_primary_color">{{ $data->invoice }}</td>
                                                    <td class="tm_width_000">
                                                        {{ ucwords($row->customer->name) }} <br>
                                                        {{ chunk_split($row->customer['phone'], 4) }}
                                                    </td>
                                                    <td class="tm_width_000">{{ ucwords($row->package->type) }}</td>
                                                    <td class="tm_width_0">{{ $data->weight }} kg</td>
                                                    <td class="tm_width_1 tm_text_right">IDR {{ number_format($data->price) }}</td>
                                                </tr>
                                                @php $total += $data->getTotalAttribute(); @endphp
                                            @endforeach
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tm_invoice_footer">
                            <div class="tm_left_footer"></div>
                            <div class="tm_right_footer">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Total</td>
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">IDR {{ number_format($total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tm_invoice_btns tm_hide_print">
                <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <circle cx="392" cy="184" r="24" fill='currentColor' />
                        </svg>
                    </span>
                    <span class="tm_btn_text">Cetak</span>
                </a>
                <button id="tm_download_btn" class="tm_invoice_btn tm_color2">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                        </svg>
                    </span>
                    <span class="tm_btn_text">Unduh</span>
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
