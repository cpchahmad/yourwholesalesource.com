<table class="table js-table-sections table-hover table-borderless table-striped table-vcenter">
    <thead>
    <tr>
        <th>SKU</th>
    </tr>
    </thead>


    <tbody class="">
    @foreach($variants as $variant)
        <tr>
            <td class="font-w600">{{ $variant->sku }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
