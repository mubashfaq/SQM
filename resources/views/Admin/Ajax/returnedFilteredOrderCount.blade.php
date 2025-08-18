<div class="d-flex border-left col-lg-2 col-sm-6">
    <div id="col"class="col py-2" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h1 id="col-h1">Orders</h1>
        <br>
        <p class="mb-0" id="col-h1">{{ $totalOrders ?? "0" }}</p>
    </div>
</div>
<div class="d-flex border-left col-lg-2 col-sm-6">
    <div id="col"class="col py-2" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h1 id="col-h1">Pending Orders</h1>
        <br>
        <p class="mb-0" id="col-h1">{{ $pendingOrders ?? '0' }}</p>
    </div>
</div>
<div class="d-flex border-left col-lg-3 col-sm-6">
    <div id="col"class="col py-2" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h1 id="col-h1">In Progress Orders</h1>
        <br>
        <p class="mb-0" id="col-h1">{{ $inProcessOrders ?? '0' }}</p>
    </div>
</div>
<div class="d-flex border-left col-lg-2 col-sm-6">
    <div id="col"class="col py-2" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h1 id="col-h1">Completed Orders</h1>
        <br>
        <p class="mb-0" id="col-h1">{{ $completedOrders ?? '0' }}</p>
    </div>
</div>
<div class="d-flex border-left col-lg-2 col-sm-6">
    <div id="col"class="col py-2" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h1 id="col-h1">Cancelled Orders</h1>
        <br>
        <p class="mb-0" id="col-h1">{{ $cancelledOrders ?? '0' }}</p>
    </div>
</div>
