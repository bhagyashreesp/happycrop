<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1>Orders</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'Orders' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<style>
.light-blue-bg {background: #cfd5eb;}
.dark-blue-bg {background: #4473c5;color:#FFF;}
.small-btn{font-size: 0.8rem !important;}
.small-btn i{font-size: 1.2rem !important;}
.btn.btn-sm{padding: 0.77em 0.8em !important;}
.delivered-state{
  background: green;
  color: #FFF;
  padding: 2px;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: block;
  text-align: center;
}

.active-state{
  background: #4473c5;
  color: #4473c5;
  padding: 5px;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: block;
  text-align: center;
}

.issue-state{
  background: #f93;
  color: #f93;
  padding: 5px;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: block;
  text-align: center;
}

.cancelled-state{
  background: #ff0000;
  color: #ff0000;
  padding: 5px;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: block;
  text-align: center;
}
</style>
<section class="my-account-section">
    <div class="main-content container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-2">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>

            <div class="col-md-10 col-12">
                
                <div class="card content-area border-none p-4">
                    <div class="card-innr">
                        <div class="gaps-1-5x row d-flex adjust-items-center">
                            <div class="form-group col-md-4">
                                <label>Date and time range:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="datepicker">
                                    <input type="hidden" id="start_date" class="form-control float-right">
                                    <input type="hidden" id="end_date" class="form-control float-right">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Filter By status</label>
                                        <select id="order_status" name="order_status" placeholder="Select Status" required="" class="form-control">
                                            <option value="">All Orders</option>
                                            <option value="received">Order Placed</option>
                                            <?php /* ?>
                                            <option value="qty_update">Qty Updated</option>
                                            <option value="qty_approved">Qty Approved</option>
                                            <option value="qty_rejected">Qty Rejected</option>
                                            <?php */ ?>
                                            <option value="payment_demand">Payment requested by manufacturer</option>
                                            <option value="payment_ack">Transaction details shared with manufacturer</option>
                                            <option value="send_payment_confirmation">Payment receipt received</option>
                                            <option value="send_invoice">Order is in transit, E-way bill and invoices received from manufacturer</option>
                                            <option value="complaint">Issue raised</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                            <?php /* ?>
                                            <option value="returned">Returned</option>
                                            <?php */ ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Search by Mfg Name / Order ID</label>
                                        <input type="text" id="search_field" name="search_field" class="form-control" />
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <button type="button" class="btn btn-default mt-2" onclick="status_date_wise_search()">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class='table-striped' data-toggle="table" data-url="<?= base_url('my-account/get_order_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                            <thead>
                                <tr class="dark-blue-bg">
                                    <?php /* ?>
                                    <th data-field="sr_no" data-sortable='false'>Sr. No.</th>
                                    <?php */ ?>
                                    <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">Order ID</th>
                                    <?php /* ?>
                                    <!--<th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>-->
                                    <!--<th data-field="qty" data-sortable='true' data-visible="false">Qty</th>-->
                                    <?php */ ?>
                                    <th data-field="color_state" data-sortable='false'></th>
                                    <th data-field="sellers" data-sortable='false'>Manufacturer Name</th>
                                    <?php /* ?>
                                    <!--<th data-field="sellers" data-sortable='true'>Manufacturer</th>-->
                                    <?php */ ?>
                                    <th data-field="mobile" data-sortable='false' data-visible='false'>Mobile</th>
                                    <th data-field="billing_address" data-sortable='false' data-visible='false'>Billing Address</th>
                                    <th data-field="address" data-sortable='false' data-visible='false'>Shipping Address</th>
                                    <th data-field="date_added" data-sortable='true'>Order Date</th>
                                    <th data-field="schedule_delivery_date" data-sortable='true'>Schedule Date</th>
                                    <?php /* ?>
                                    <!--<th data-field="notes" data-sortable='false' data-visible='true'>O. Notes</th>-->
                                    <!--<th data-field="items" data-sortable='true' data-visible="false">Items</th>-->
                                    <!--<th data-field="total" data-sortable='true' data-visible="true">Total(<?= $curreny ?>)</th>-->
                                    <!--<th data-field="delivery_charge" data-sortable='true' data-footer-formatter="delivery_chargeFormatter">D.Charge</th>-->
                                    <!--<th data-field="wallet_balance" data-sortable='true' data-visible="true">Wallet Used(<?= $curreny ?>)</th>-->
                                    <!--<th data-field="promo_code" data-sortable='true' data-visible="false">Promo Code</th>
                                    <th data-field="promo_discount" data-sortable='true' data-visible="true">Promo disc.(<?= $curreny ?>)</th>-->
                                    <!--<th data-field="discount" data-sortable='true' data-visible="true">Discount <?= $curreny ?>(%)</th>-->
                                    <?php */ ?>
                                    <th data-field="final_total" data-sortable='true'>Total Amount</th>
                                    <?php /* ?>
                                    <!--<th data-field="payment_method" data-sortable='true' data-visible="true">Payment Method</th>
                                    <th data-field="delivery_date" data-sortable='true' data-visible='false'>Delivery Date</th>
                                    <th data-field="delivery_time" data-sortable='true' data-visible='false'>Delivery Time</th>-->
                                    <?php */ ?>
                                    <th data-field="order_status" data-sortable='true' data-visible='true'>Status</th>
                                    <th data-field="operate">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- .card-innr -->
                </div><!-- .card -->
               
                <?php /* ?>
                <div class='card border-0'>
                    <div class="card-header bg-white">
                        <h3><?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'Orders' ?></h3>
                    </div>
                    <div class="card-body orders-section p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="dark-blue-bg">
                                    <th>Sr. No.</th>
                                    <th>Order ID</th>
                                    <th>Manufacturer Name</th>
                                    <th>Order Date</th>
                                    <th>Schedule Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th style="width: 12%;">View Details</th>
                                </tr>
                                <?php 
                                $i = 1;
                                $or_state = array('delivered','cancelled');
                                
                                $order_msg = array('received'=>'Order Placed','qty_update'=>'Quantity updated and approval request received.','qty_approved'=>'Quantity approval accepted by you.','payment_demand'=>'Payment request received.','payment_ack'=>'Transaction details shared.','schedule_delivery'=>'Order Scheduled.','shipped'=>'Order shipped.','send_invoice'=>'Invoices received.','delivered'=>'Order received.','cancelled'=>'Order cancelled.');
                                
                                foreach ($orders['order_data'] as $row) 
                                {  
                                    $row_class = 'light-blue-bg';
                                    if(in_array($row['order_status'], $or_state))
                                    {
                                        $row_class = '';
                                    }
                                    ?>
                                    <tr class="<?php echo $row_class; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo ($row['company_name']!='') ? $row['company_name'] : $row['seller_name']; ?></td>
                                        <td><?php echo date('d/m/Y g:i A', strtotime($row['date_added'])) ?></td>
                                        <td><?php echo ($row['schedule_delivery_date'] !=null && $row['schedule_delivery_date']!='0000-00-00') ? date('d/m/Y', strtotime($row['schedule_delivery_date'])) : '' ?></td>
                                        <td><?php echo number_format($row['final_total'], 2); ?></td>
                                        <td><?php echo ($row['order_status']!='') ? $order_msg[$row['order_status']] : ''; ?></td>
                                        <td>
                                            <a href="<?= base_url('my-account/order-details/' . $row['id']) ?>" class='button button-primary-outline'><?= !empty($this->lang->line('view_details')) ? $this->lang->line('view_details') : 'View Details' ?></a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $i++;
                                } 
                                ?>
                            </table>
                        </div>
                        <div class="text-center">
                            <?= $links ?>
                        </div>
                        <?php /* ?>
                        <?php foreach ($orders['order_data'] as $row) {  ?>
                            <div class="mb-4 card border-0">
                                <div class="card-header bg-white p-2">
                                    <div class="row justify-content-between">
                                        <div class="col">
                                            <p class="text-muted"> <?= !empty($this->lang->line('order_id')) ? $this->lang->line('order_id') : 'Order ID' ?> <span class="font-weight-bold text-dark"> : <?= $row['id'] ?></span></p>
                                            <p class="text-muted"> <?= !empty($this->lang->line('place_on')) ? $this->lang->line('place_on') : 'Place On' ?> <span class="font-weight-bold text-dark"> : <?= $row['date_added'] ?></span> </p>
                                            <?php if ($row['otp'] != 0) { ?>
                                                <p class="text-muted"> <?= !empty($this->lang->line('otp')) ? $this->lang->line('otp') : 'OTP' ?> <span class="font-weight-bold text-dark"> : <?= $row['otp'] ?></span> </p>
                                            <?php } ?>
                                        </div>
                                        <div class="flex-col my-auto">
                                            <h6 class="ml-auto mr-3"> <a href="<?= base_url('my-account/order-details/' . $row['id']) ?>" class='button button-primary-outline'><?= !empty($this->lang->line('view_details')) ? $this->lang->line('view_details') : 'View Details' ?></a> </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="media flex-column flex-sm-row">
                                        <div class="media-body ">
                                            <?php foreach ($row['order_items'] as $key => $item) { ?>
                                                <h5 class="bold"><?= ($key + 1) . '. ' . $item['name'] ?></h5>
                                                <p class="text-muted"> <?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?> : <?= $item['quantity'] ?></p>
                                            <?php } ?>
                                            <h4 class="mt-3 mb-4 bold"> <span class="mt-5"><i><?= $settings['currency'] ?></i></span> <?= number_format($row['final_total'], 2) ?> <span class="small text-muted"> <?= !empty($this->lang->line('via')) ? $this->lang->line('via') : 'via' ?> (<?= $row['payment_method'] ?>) </span></h4>
                                        </div>
                                        <?php if (count($row['order_items']) == 1) { ?>
                                            <img class="align-self-center img-fluid" src="<?= $row['order_items'][0]['image_sm'] ?>" width="180 " height="180">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <ul id="progressbar">
                                            <?php
                                            $status = array('received', 'processed', 'shipped', 'delivered');
                                            $i = 1;
                                            foreach ($item['status'] as $value) { ?>
                                                <?php
                                                $class = '';
                                                if ($value[0] == "cancelled" || $value[0] == "returned") {
                                                    $class = 'cancel';
                                                    $status = array();
                                                } elseif (($ar_key = array_search($value[0], $status)) !== false) {
                                                    unset($status[$ar_key]);
                                                }
                                                ?>
                                                <li class="active <?= $class ?>" id="step<?= $i ?>">
                                                    <p><?= strtoupper($value[0]) ?></p>
                                                    <p><?= $value[1] ?></p>
                                                </li>
                                            <?php
                                                $i++;
                                            } ?>
                                            <?php foreach ($status as $value) { ?>
                                                <li id="step<?= $i ?>">
                                                    <p><?= strtoupper($value) ?></p>
                                                </li>
                                            <?php $i++;
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-footer bg-white px-sm-3 pt-sm-4 px-0">
                                    <div class="row text-center ">
                                        <?php if ($row['is_cancelable'] && !$row['is_already_cancelled']) { ?>
                                            <div class="col my-auto">
                                                <h5>
                                                    <a class="update-order block button-sm buttons btn-6-1 mt-3 m-0" data-status="cancelled" data-order-id="<?= $row['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></a>
                                                </h5>
                                            </div>
                                        <?php } ?>
                                        <?php if ($row['is_returnable'] && !$row['is_already_returned']) { ?>
                                            <div class="col my-auto ">
                                                <h5><a class="update-order block button-sm buttons btn-6-3 mt-3 m-0" data-status="returned" data-order-id="<?= $row['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></a></h5>
                                            </div>
                                        <?php } ?>
                                        <?php if ($row['payment_method'] == 'Bank Transfer') { ?>
                                            <div class="col my-auto ">
                                                <h5>
                                                    <a class="block button-sm buttons btn-6-5 mt-3 m-0" href="<?= base_url('my-account/order-details/' . $row['id']) ?>" > Send Bank Payment Receipt</i>
                                                        <!-- <input type="file"  name="receipt" class="form-control"/>  -->
                                                    </a>
                                                </h5>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="text-center">
                            <?= $links ?>
                        </div>
                        <?php *//* ?>
                    </div>
                </div>
                <?php */ ?>
                
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
<script type="text/javascript">
function re_order_the_order(order_id)
{
    Swal.fire({
        title: 'Are You Sure!',
        text: "You want to re-order the order.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, order it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?php echo base_url('cart/re_order') ?>",
                    type: 'POST',
                    cache: false,
                    data:{order_id:order_id},
                    dataType: "json",
                    error: function (xhr, status, error) {
                        //alert(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong.'
                        });
                        swal.close();
                    },
                    success: function (result) {
                        if (result['error'] == false) {
                            Toast.fire({
                                icon: 'success',
                                title: result['message']
                            });
                            window.location = base_url + 'cart';
                            
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result['message']
                            });
                        }
                        swal.close();
                    }  
                });
            });
        },
        allowOutsideClick: false
    });
}
</script>