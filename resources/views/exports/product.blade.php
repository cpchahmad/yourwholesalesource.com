<table>
    <thead>
    <tr>
        <th>Handle</th>
        <th>Title</th>
        <th>Body (HTML)</th>
        <th>Vendor</th>
        <th>Type</th>
        <th>Tags</th>
        <th>Published</th>
        <th>Option1 Name</th>
        <th>Option1 Value</th>
        <th>Option2 Name</th>
        <th>Option2 Value</th>
        <th>Option3 Name</th>
        <th>Option3 Value</th>
        <th>Variant SKU</th>
        <th>Variant Grams</th>
{{--        <th>Variant Inventory Tracker</th>--}}
{{--        <th>Variant Inventory Qty</th>--}}
{{--        <th>Variant Inventory Policy</th>--}}
{{--        <th>Variant Fulfillment Service</th>--}}
        <th>Variant Price</th>
{{--        <th>Variant Compare At Price</th>--}}
{{--        <th>Variant Requires Shipping</th>--}}
{{--        <th>Variant Taxable</th>--}}
        <th>Variant Barcode</th>
        <th>Image Src</th>
{{--        <th>Image Position</th>--}}
{{--        <th>Image Alt Text</th>--}}
        <th>Variant Image</th>
{{--        <th>Variant Weight Unit</th>--}}
{{--        <th>Variant Weight</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product['handle'] }}</td>
            <td>{{ $product['title'] }}</td>
            <td>{{ $product['body_html'] }}</td>
            <td>{{ $product['vendor'] }}</td>
            <td>{{ $product['product_type'] }}</td>
            <td>{{ $product['tags'] }}</td>
            <td>{{ $product['published'] }}</td>
            <td>{{ $product['option1_name'] }}</td>
            <td>{{ $product['variant_option1'] }}</td>
            <td>{{ $product['option2_name'] }}</td>
            <td>{{ $product['variant_option2'] }}</td>
            <td>{{ $product['option3_name'] }}</td>
            <td>{{ $product['variant_option3'] }}</td>
            <td>{{ $product['variant_sku'] }}</td>
            <td>{{ $product['variant_grams'] }}</td>
{{--            <td>{{ $product['variant_inventory_tracker'] }}</td>--}}
{{--            <td>{{ $product['variant_qty'] }}</td>--}}
{{--            <td>{{ $product['variant_policy'] }}</td>--}}
{{--            <td>{{ $product['variant_fulfillment_service'] }}</td>--}}
            <td>{{ $product['variant_price'] }}</td>
{{--            <td>{{ $product['variant_compare_price'] }}</td>--}}
{{--            <td>{{ $product['variant_shipping'] }}</td>--}}
{{--            <td>{{ $product['variant_taxable'] }}</td>--}}
            <td>{{ $product['variant_barcode'] }}</td>
            <td>{{ $product['image_src'] }}</td>
{{--            <td>{{ $product['image_position'] }}</td>--}}
{{--            <td>{{ $product['image_alt'] }}</td>--}}
            <td>{{ $product['variant_image'] }}</td>
{{--            <td>{{ $product['variant_weight_unit'] }}</td>--}}
{{--            <td>{{ $product['variant_weight'] }}</td>--}}

        </tr>
    @endforeach
    </tbody>
</table>
