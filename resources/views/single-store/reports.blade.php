@extends('layout.single')
@section('content')

    <style>
        .daterangepicker .right{
            color: inherit !important;
        }
        .daterangepicker {
            width: 668px !important;
        }

    </style>
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Reports
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row mb-2" style="padding-bottom:1.875rem">
            <div class="col-md-4 d-flex">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span>{{$date_range}}</span> <i class="fa fa-caret-down"></i>
                </div>
                <button class="btn btn-primary filter_by_date" data-url="{{route('store.reports')}}" style="margin-left: 10px"> Filter </button>
                <button class="btn btn-danger report-pdf-btn"  style="margin-left: 10px">PDF</button>
            </div>
        </div>
        <div class="row" id="pdfDownload">
            <div class="col-md-12">
                <div class="text-center">
                    <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/Wefullfill.jpg?v=1598885447" alt="No Image" class="" style="width: 50%; height: 50vh;">
                </div>
                <div class="mt-3">
                    <h3>Dear {{ $shop }},<br>
                        Thank you for working with wefulfill, here below is your report from <span id="custom-date">{{$date_range}}</span></h3>
                </div>

            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Total Orders</div>
                        <div class="font-size-h2 font-w400 text-dark">{{$orders}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Cost</div>
                        <div class="font-size-h2 font-w400 text-dark">${{number_format($cost,2)}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>
                        <div class="font-size-h2 font-w400 text-dark">${{number_format($sales,2)}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Products</div>
                        <div class="font-size-h2 font-w400 text-dark">{{$products}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Profit</div>
                        <div class="font-size-h2 font-w400 text-dark">${{number_format($profit,2)}}</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-one-store" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-two-store" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-three-store" data-labels="{{json_encode($graph_three_labels)}}" data-values="{{json_encode($graph_three_values)}}"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-four-store" data-labels="{{json_encode($graph_four_labels)}}" data-values="{{json_encode($graph_four_values)}}"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Top Products</h3>
                    </div>
                    <div class="block-content ">
                        @if(count($top_products) > 0)
                            <table class="table table-striped table-hover table-borderless table-vcenter">
                                <thead>
                                <tr class="text-uppercase">
                                    <th class="font-w700">Product</th>
                                    <th class="d-none d-sm-table-cell font-w700 text-center" style="width: 80px;">Quantity</th>
                                    <th class="font-w700 text-center" style="width: 60px;">Sales</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($top_products as $product)
                                    <tr>
                                        <td class="font-w600">
                                            @foreach($product->has_images()->orderBy('position')->get() as $index => $image)
                                                @if($index == 0)
                                                    @if($image->isV == 0)
                                                        <img class="img-avatar img-avatar32" style="margin-right: 5px" src="{{asset('images')}}/{{$image->image}}" alt="">
                                                    @else
                                                        <img class="img-avatar img-avatar32" style="margin-right: 5px" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                                    @endif
                                                @endif
                                            @endforeach
                                            {{$product->title}}
                                        </td>
                                        <td class="d-none d-sm-table-cell text-center">
                                            {{$product->sold}}
                                        </td>
                                        <td class="">
                                            ${{number_format($product->selling_cost,2)}}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                @else
                                    <p  class="text-center"> No Top Users Found </p>
                                @endif
                            </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.7/dist/html2canvas.min.js"></script>
    <script>
        if($('body').find('#reportrange').length > 0){
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#custom-date').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            if($('#reportrange span').text() === ''){
                $('#reportrange span').html('Select Date Range');
            }


            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            // cb(start, end);
        }

        $('body').on('click','.filter_by_date', function() {
            let daterange_string = $('#reportrange').find('span').text();
            if(daterange_string !== '' && daterange_string !== 'Select Date Range'){
                window.location.href = $(this).data('url')+'?date-range='+daterange_string;
            }
            else{
                alertify.error('Please Select Range');
            }
        });

        // $('.report-pdf-btn').click(function () {
        //     var data = document.getElementById('pdfDownload');
        //     html2canvas(data).then(canvas => {
        //         Few necessary setting options
        //         var imgWidth = 208;
        //         var imgHeight = canvas.height * imgWidth / canvas.width;
        //         const contentDataURL = canvas.toDataURL('image/png')
        //         let pdf = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF
        //         var position = 0;
        //         pdf.addImage(contentDataURL, 'JPEG', 0, position, imgWidth, imgHeight);
        //         //  pdf.save('new-file.pdf');
        //         window.open(pdf.output('bloburl', { filename: 'new-file.pdf' }), '_blank');
        //
        //         // var img = canvas.toDataURL('image/png');
        //         // var doc = new jsPDF();
        //         // doc.addImage(img, 'JPEG', 20, 20);
        //         // doc.save('new-file.pdf');
        //     });
        //
        // });

        $('.report-pdf-btn').click(function () {
            console.log(324);
            var HTML_Width = $("#pdfDownload").width();
            var HTML_Height = $("#pdfDownload").height();
            // alert(HTML_Width); // HTML_Height=3800; // HTML_Width=1349;
            var top_left_margin = 15;
            var PDF_Width = HTML_Width + (top_left_margin * 2);
            var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 5);
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;
            var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
            html2canvas($("#pdfDownload")[0]).then(function (canvas) {
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                for (var i = 1; i <= totalPDFPages; i++) {
                    pdf.addPage(PDF_Width, PDF_Height);
                    pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 15), canvas_image_width, canvas_image_height);
                }
                pdf.save("calendar.pdf");
            });

        });



    </script>

    @endsection
