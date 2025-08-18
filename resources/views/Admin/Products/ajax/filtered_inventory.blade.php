@php
    use App\General\ProductClass;
@endphp

<thead>
    <tr>
        <th scope="col" style="width:45%">Product</th>
        <th scope="col">Sku</th>
        <th scope="col">Stock</th>
        <th scope="col">Price</th>
        <th scope="col">Status</th>
    </tr>
</thead>
<tbody>
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->title }}</td>
            <td>{{ $product->sku }}</td>
            <td><input type="number" class="form-control form-control-sm" name="_stock[{{ $product->id }}]"
                    value="{{ ProductClass::getOptionValue('_stock', $product->productMeta) }}"> </td>
            <td><input type="number" class="form-control form-control-sm" name="_price[{{ $product->id }}]"
                    value="{{ ProductClass::getOptionValue('_price', $product->productMeta) }}"> </td>
            <td>
                <select class="form-control-sm" name="active[{{ $product->id }}]">
                    <option value="0" @if ($product->active == 0) selected @endif>
                        Draft
                    </option>
                    <option value="1" @if ($product->active == 1) selected @endif>
                        Public
                    </option>
                    <option value="2" @if ($product->active == 2) selected @endif>
                        Private
                    </option>
                    <option value="3" @if ($product->active == 3) selected @endif>
                        Trash
                    </option>
                </select>
            </td>
        </tr>
    @endforeach
</tbody>
