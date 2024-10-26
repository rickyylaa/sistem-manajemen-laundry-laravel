<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Struk</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/receipt.css') }}">
</head>
<body>
    <div class="tm_container">
        <div class="tm_pos_invoice_wrap" id="tm_download_section">
            <div class="tm_pos_invoice_top">
                <div class="tm_pos_company_logo">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo">
                </div>
                <div class="tm_pos_company_name">Easy Wash Laundry Coin</div>
                <div class="tm_pos_company_address">Jl. Maulana Malik Ibrahim, lrg. Remaja</div>
                <div class="tm_pos_company_mobile">Kontak: 0838 7196 3589</div>
            </div>
            <div class="tm_pos_invoice_body">
                <div class="tm_pos_invoice_heading">
                    <span>Struk</span>
                </div>
                <ul class="tm_list tm_style1">
                    <li>
                        <div class="tm_list_title">Nama:</div>
                        <div class="tm_list_desc">{{ $transaction->customer_name }}</div>
                    </li>
                    <li class="text-right">
                        <div class="tm_list_title">Faktur:</div>
                        <div class="tm_list_desc">{{ $transaction->invoice }}</div>
                    </li>
                    <li>
                        <div class="tm_list_title">Telepon:</div>
                        <div class="tm_list_desc">{{ chunk_split($transaction['customer_phone'], 4); }}</div>
                    </li>
                    <li class="text-right">
                        <div class="tm_list_title">Tanggal:</div>
                        <div class="tm_list_desc">{{ $transaction->date }}</div>
                    </li>
                </ul>
                <table class="tm_pos_invoice_table">
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Berat</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $index => $row)
                            @php
                                $pricePerKg = $row->package->price;
                                $totalPrice = $pricePerKg * $transaction->weight;
                            @endphp
                            <tr>
                                <td>{{ $row->package->type }}</td>
                                <td>{{ $transaction->weight }} kg</td>
                                <td>IDR {{ number_format($pricePerKg * $transaction->weight) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="tm_bill_list">
                    <div class="tm_bill_list_in">
                        <div class="tm_bill_title">Keseluruhan:</div>
                        <div class="tm_bill_value">
                            @php
                                $totalPrice = $pricePerKg * $transaction->weight;
                            @endphp
                            IDR {{ number_format($totalPrice, 0) }}
                        </div>
                    </div>
                    <div class="tm_bill_list_in">
                        <div class="tm_bill_title">Kupon:</div>
                        <div class="tm_bill_value">
                            @php
                                $couponDiscount = $transaction->coupon ? $pricePerKg : 0;
                            @endphp
                            IDR {{ number_format($couponDiscount, 0) }}
                        </div>
                    </div>
                    <div class="tm_invoice_seperator"></div>
                    <div class="tm_bill_list_in">
                        <div class="tm_bill_title tm_bill_focus">Total yang harus dibayar:</div>
                        <div class="tm_bill_value tm_bill_focus">
                            @php
                                $totalPayable = $totalPrice - $couponDiscount;
                            @endphp
                            IDR {{ number_format($totalPayable, 0) }}
                        </div>
                    </div>
                </div>
                <div class="tm_pos_invoice_footer">Terima kasih.</div>
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

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>

    <script>
        $('#tm_download_btn').on('click', function() {
            var downloadSection = $('#tm_download_section');
            var cWidth = downloadSection.width();
            var cHeight = downloadSection.height();
            var topLeftMargin = 0;
            var pdfWidth = cWidth + topLeftMargin * 2;
            var pdfHeight = cHeight;
            var canvasImageWidth = cWidth;
            var canvasImageHeight = cHeight;
            var totalPDFPages = Math.ceil(cHeight / pdfHeight) - 1;
            html2canvas(downloadSection[0], {
                allowTaint: true
            }).then(function(canvas) {
                canvas.getContext('2d');
                var imgData = canvas.toDataURL('image/png', 1.0);
                var pdf = new jsPDF('p', 'pt', [pdfWidth, pdfHeight]);
                pdf.addImage(imgData, 'PNG', topLeftMargin, topLeftMargin, canvasImageWidth, canvasImageHeight);
                for (var i = 1; i <= totalPDFPages; i++) {
                    pdf.addPage(pdfWidth, pdfHeight);
                    pdf.addImage(imgData, 'PNG', topLeftMargin, -(pdfHeight * i) + topLeftMargin * 0, canvasImageWidth, canvasImageHeight);
                }
                pdf.save('download.pdf');
            });
        });
    </script>
</body>
</html>
