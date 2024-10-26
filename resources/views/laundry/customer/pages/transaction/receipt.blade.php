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
