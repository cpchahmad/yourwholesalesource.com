@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Shipping Mark
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Shipping Mark</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Shipping Mark for {{$drop_request->product_name}}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header p-4 d-flex justify-content-between">
                        <h5 class="block-title">{{$drop_request->product_name}}</h5>
                        <button class="btn btn-danger shipping-pdf-btn">Download</button>
                    </div>
                    <div class="block-content shipping-mark-body" id="pdfDownload">
                        <div class="p-4 bg-white">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material d-flex justify-content-between">
                                                <label for="" class="font-size-h5">Dropship Request Number</label>
                                                <span class="d-block font-w600"># {{$drop_request->id}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material d-flex justify-content-between">
                                                <label for="" class="font-size-h5">Store/User name</label>
                                                <span class="d-block font-w600">@if($drop_request->shop_id) {{ $drop_request->has_store->shopify_domain }} @else {{ $drop_request->has_user->name }} @endif</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material d-flex justify-content-between">
                                                <label for="" class="font-size-h5">Contact</label>
                                                <span class="d-block font-w600">@if($drop_request->shop_id) {{ $drop_request->has_store->has_user()->first()->email }} @else {{ $drop_request->has_user->email }} @endif</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material d-flex justify-content-between">
                                                <label for="" class="font-size-h5">Product title</label>
                                                <span class="d-block font-w600">{{$mark->dropship_product->title}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right align-self-center">
                                    <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" class="w-75"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <table class="table variants-div js-table-sections table-hover" style="overflow-x: hidden">
                                            <thead>
                                                <tr>
                                                    <th style="vertical-align: top;" class="w-25">SKU</th>
                                                    <th style="vertical-align: top;">Option</th>
                                                    <th style="vertical-align: top;">Inventory</th>
                                                </tr>
                                            </thead>
                                            <tbody class="product-details-body">
                                                @foreach($mark->dropship_product->dropship_product_variants as $variant)
                                                    <tr class="single-product-details">
                                                        <td class="d-flex align-items-center">
                                                            <img style="width: 100px; height: auto;" src="{{asset('shipping-marks')}}/{{$variant->image}}" alt="">
                                                            <span class="ml-2">{{ $variant->sku }}</span>
                                                        </td>
                                                        <td class="align-items-center" style="vertical-align: middle;">
                                                            <span>{{ $variant->option }}</span>
                                                        </td>
                                                        <td class="align-items-center" style="vertical-align: middle;">
                                                            <span>{{ $variant->inventory }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <span>{!! \Milon\Barcode\DNS1D::getBarcodeHTML($mark->barcode, "C128",2.0,42) !!}</span>
                                        <span>{{ $mark->barcode }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.7/dist/html2canvas.min.js"></script>
    <script>
        $('.shipping-pdf-btn').click(function () {
            var data = document.getElementById('pdfDownload');
            html2canvas(data).then(canvas => {
                //  Few necessary setting options
                var imgWidth = 208;
                var imgHeight = canvas.height * imgWidth / canvas.width;
                const contentDataURL = canvas.toDataURL('image/png')
                let pdf = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF
                var position = 0;
                pdf.addImage(contentDataURL, 'JPEG', 0, position, imgWidth, imgHeight);
                //  pdf.save('new-file.pdf');
                window.open(pdf.output('bloburl', { filename: 'report.pdf' }), '_blank');


            });
        });
    </script>

@endsection
