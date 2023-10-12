@php
    use Carbon\Carbon;
    $address = json_decode($order->order_address);
    $province = json_decode($address->province)->label;
    $ward = json_decode($address->ward)->label;
    $district = json_decode($address->district)->label;
    $shipping = json_decode($order->shipping_method);
    $coupon = json_decode($order->coupon);
@endphp
@extends('admin.layouts.master')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Order Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Order Details</div>
            </div>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <div class="invoice-number">Order #{{$order->invoice_id}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        <b>Name</b>: {{$address->name}}<br>
                                        <b>Email</b>: {{$address->email}}<br>
                                        <b>Phone</b>: {{$address->phone}}<br>
                                        <b>Address</b>: {{$address->address}}, {{$ward}}, {{$district}}, {{$province}}.<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Shipping To:</strong><br>
                                        <b>Name</b>: {{$address->name}}<br>
                                        <b>Email</b>: {{$address->email}}<br>
                                        <b>Phone</b>: {{$address->phone}}<br>
                                        <b>Address</b>: {{$address->address}}, {{$ward}}, {{$district}}, {{$province}}.<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Payment information:</strong><br>
                                        <b>Method: </b>{{$order->payment_method}}<br>
                                        <b>Transaction_id: </b> {{$order->transaction->transaction_id}}<br>
                                        <b>Status: </b> {{$order->payment_status == 0 ? 'Pending' : 'Complete'}}<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Order Date:</strong><br>
                                        {{Carbon::parse($order->created_at)->format('d-m-Y')}}<br><br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <p class="section-lead">All items here cannot be deleted.</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr class="text-center">
                                        <th data-width="40">#</th>
                                        <th>Vendor</th>
                                        <th>Item</th>
                                        <th>Variant</th>
                                        <td>Price</td>
                                        <td>Quantity</td>
                                        <th>Totals</th>
                                    </tr>
                                    @foreach($order->orderProducts as $product)
                                      @php
                                          $variants = json_decode($product->variants);
                                      @endphp
                                        <tr class="text-center">
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$product->vendor->shop_name}}</td>
                                            @if(isset($product->product->slug))
                                                <td><a target="_blank" href="">{{$product->product_name}}</a></td>
                                            @else
                                                <td>{{$product->product_name}}</td>
                                            @endif

                                            <td>
                                                @if($variants)
                                                    @foreach($variants as $key=>$variant)
                                                        - <b>{{$key}}: </b>{{$variant->name}}
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{$product->unit_price}}$</td>
                                            <td>{{$product->quantity}}</td>
                                            <td>{{format(($product->unit_price * $product->quantity) + $product->variant_total)}}</td>
                                        </tr>
                                    @endforeach


                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">Payment Status</label>
                                            <select name="payment_status" data-id="{{$order->id}}" class="form-control payment_status">
                                                <option {{$order->payment_status == 0 ? 'selected' : ''}} value="0">Pending</option>
                                                <option {{$order->payment_status == 1 ? 'selected' : ''}} value="1">Completed</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                                <label for="">Order Status</label>
                                                <select name="order_status" data-id="{{$order->id}}" class="form-control order_status">
                                                    @foreach(config('order_status.order_status_admin') as $key=>$orderStatus)
                                                        <option {{$order->order_status == $key ? 'selected' : ''}} value="{{$key}}">{{$orderStatus['status']}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Subtotal</div>
                                        <div class="invoice-detail-value">{{format($order->sub_total)}}$</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping</div>
                                        <div class="invoice-detail-value">{{@$shipping->cost ? $shipping->cost : 0}}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Coupon</div>
                                        <div class="invoice-detail-value">{{@$discountValue == null ? 0 :format($discountValue)}}</div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">{{format($order->amount)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right">
                    <button class="btn btn-warning btn-icon icon-left print_invoice"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        window.addEventListener("DOMContentLoaded", (event) => {
            document.querySelector('.order_status').addEventListener('change', async (e) => {
                let status = e.target.value;
                let id = e.target.dataset.id;
                const res = await fetch(`./get-order-status/${id}/${status}`);
                const data = await res.json();
                if(data['status']){
                toastr.success(data.message);
                }
            })

            document.querySelector('.payment_status').addEventListener('change', async (e) => {
                let status = e.target.value;
                let id = e.target.dataset.id;
                const res = await fetch(`./payment-status/${id}/${status}`);
                const data = await res.json();
                if(data['status']){
                    toastr.success(data.message);
                }
            })

            document.body.addEventListener('click', async (e) => {
                if (e.target.classList.contains('print_invoice')) {
                    e.preventDefault();

                    let printArea = document.querySelector('.invoice-print');
                    let defaultBody = document.body.innerHTML;
                    document.body.innerHTML = printArea.innerHTML;
                    window.print();
                    document.body.innerHTML = defaultBody;
                }
            });

        });
    </script>
@endpush


