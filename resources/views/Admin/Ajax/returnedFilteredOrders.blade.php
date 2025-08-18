@php
    use Carbon\Carbon;
    use App\General\InvoiceClass;
@endphp
<table class="col-12 table-responsive" style="outline:none;border:none">
    <thead>
        <tr class="col-12"style="background-color: white">
            <th><input type="checkbox" id="ajax_all_checked" name="checkall" value=""></th>
            <th class="col-1">Order</th>
            <th class="col-1">Date</th>
            <th class="col-2">Customer</th>
            <th class="col-1">Channel</th>
            <th class="col-1">Total</th>
            <th class="col-2">Payment Status</th>
            <th class="col-2">Fulfillment Status</th>
            <th class="col-1">Item</th>
            <th class="col-1">Delivery</th>
            <th class="col-1">Action</th>
        </tr>
    </thead>
    <tbody class="myTableBody col-12" id="myTable">
        @if ($orders->isNotEmpty())
            @foreach ($orders as $order)
                <tr>
                    <td><input type="checkbox" data-id="{{ $order->id }}" name="check_it" class="checkbox"
                            value=""></td>
                    <td>{{ $order->invoice_number }}</td>
                    <td>
                        @php
                            $carbonDate = Carbon::parse($order->created_at);
                        @endphp
                        {{ InvoiceClass::convertDateToDDMMYYYY($carbonDate->toDateString()) }}
                    </td>
                    <td>{{ $order->f_name . ' ' . $order->l_name ?? '' }}</td>
                    <td>{{ $order->channel }}</td>
                    <td>${{ $order->grand_total }}</td>
                    <td><span
                            style="background-color: rgb(224, 219, 219);border-radius:10px;padding:5px 10px 5px 10px">{{ ucwords($order->payment_status) ?? '' }}</span>
                    </td>
                    <td>{{ ucwords($order->status) }}</td>
                    <td>{{ count($order->orderMeta) }} items</td>
                    <td>${{ $order->delivery_charges ?? 0 }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                            <button type="button" class="btn badge btn-warning"
                                onclick="printInvoice({{ $order->id }})"><i class="fa fa-print"></i></button>
                            <button class="btn btn-primary see_details" data-id="{{ $order->id }}"
                                type="button">Details</button>
                            <button class="btn btn-danger deletetButton" type="submit"
                                data-id="{{ $order->id }}">Delete</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <td colspan="11" class="text-center">No orders found.</td>
        @endif
    </tbody>
</table>
<div class="w-100 px-5 d-flex justify-content-end p-2">
    <div class="d-flex">
        {!! $orders->links('pagination::bootstrap-4') !!}
    </div>
</div>
