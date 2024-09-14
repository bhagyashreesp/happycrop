<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $page_title;?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title;?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="transaction_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="user_name">Order Tracking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-info">
                                            <!-- form start -->
                                            <form class="form-horizontal " id="order_tracking_form" action="<?= base_url('seller/orders/update-order-tracking/'); ?>" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="order_id" id="order_id">
                                                <input type="hidden" name="order_item_id" id="order_item_id">
                                                <div class="card-body pad">
                                                    <div class="form-group ">
                                                        <label for="courier_agency">Courier Agency</label>
                                                        <input type="text" class="form-control" name="courier_agency" id="courier_agency" placeholder="Courier Agency" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="tracking_id">Tracking Id</label>
                                                        <input type="text" class="form-control" name="tracking_id" id="tracking_id" placeholder="Tracking Id" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="url">URL</label>
                                                        <input type="text" class="form-control" name="url" id="url" placeholder="URL" />
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button type="submit" class="btn btn-success" id="submit_btn">Save</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-group" id="error_box">
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                        <!--/.card-->
                                    </div>
                                    <!--/.col-md-12-->
                                </div>
                                <!-- /.row -->

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
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
                                        <?php if(!$condition) { ?> 
                                        <div class="col-md-4">
                                            <label>Filter By status</label>
                                            <select id="order_status" name="order_status" placeholder="Select Status" required="" class="form-control">
                                                <option value="">All Orders</option>
                                                <option value="received">Order Received</option>
                                                <?php /* ?>
                                                <option value="qty_update">Qty Updated</option>
                                                <option value="qty_approved">Qty Approved</option>
                                                <option value="qty_rejected">Qty Rejected</option>
                                                <?php */ ?>
                                                <option value="schedule_delivery">Order Scheduled</option>
                                                <option value="payment_demand">Payment request sent</option>
                                                <option value="payment_ack">Retailer shared transaction details with Happycrop</option>
                                                <option value="send_payment_confirmation">Payment confirmation sent to retailer by Happycrop</option>
                                                <option value="send_invoice">E-way bill and invoices sent to retailer</option>
                                                <?php /* ?>
                                                <option value="shipped">Shipped</option>
                                                <?php */ ?>
                                                <option value="delivered">Order delivered</option>
                                                <option value="send_mfg_payment_ack">Transaction details received from Happycrop</option>
                                                <option value="send_mfg_payment_confirmation">Payment confirmation sent to Happycrop</option>
                                                <option value="cancelled">Order cancelled</option>
                                                <?php /* ?>
                                                <option value="returned">Returned</option>
                                                <?php */ ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="col-md-5">
                                            <label>Search by Retailer Name / Order ID</label>
                                            <input type="text" id="search_field" name="search_field" class="form-control" />
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <input type="hidden" id="condition" name="condition" value="<?php echo $condition;?>"/>
                                            <button type="button" class="btn btn-default mt-2" onclick="status_date_wise_search()">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/view_seller_orders') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr>
                                        <?php /* ?>
                                        <th data-field="sr_no" data-sortable='false'>Sr. No.</th>
                                        <?php */ ?>
                                        <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">Order ID</th>
                                        <?php /* ?>
                                        <!--<th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>-->
                                        <!--<th data-field="qty" data-sortable='true' data-visible="false">Qty</th>-->
                                        <?php */ ?>
                                        <th data-field="name" data-sortable='true'>Retailer Name</th>
                                        <?php /* ?>
                                        <!--<th data-field="sellers" data-sortable='true'>Manufacturer</th>-->
                                        <?php */ ?>
                                        <th data-field="mobile" data-sortable='true' data-visible='false'>Mobile</th>
                                        <!--<th data-field="billing_address" data-sortable='true' data-visible='true'>Billing Address</th>
                                        <th data-field="address" data-sortable='true' data-visible='true'>Shipping Address</th>-->
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
                                        <th data-field="last_updated" data-sortable='true' data-visible='true'>Updated Date</th>
                                        <th data-field="operate">Action</th>
                                    </tr>
                                </thead>
                            </table>
                            <?php /* ?>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/view_order_items') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">ID</th>
                                        <th data-field="order_item_id" data-sortable='true'>Order Item ID</th>
                                        <th data-field="order_id" data-sortable='true'>Order ID</th>
                                        <th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>
                                        <th data-field="seller_id" data-sortable='true' data-visible="false">Seller ID</th>
                                        <th data-field="is_credited" data-sortable='true' data-visible="false">Commission</th>
                                        <th data-field="quantity" data-sortable='true' data-visible="false">Quantity</th>
                                        <th data-field="username" data-sortable='true'>User Name</th>
                                        <th data-field="seller_name" data-sortable='true' data-visible="false">Seller Name</th>
                                        <th data-field="product_name" data-sortable='true'>Product Name</th>
                                        <th data-field="mobile" data-sortable='true' data-visible='false'>Mobile</th>
                                        <th data-field="notes" data-sortable='true' data-visible='false'>Order Note</th>
                                        <th data-field="sub_total" data-sortable='true' data-visible="true">Total(<?= $curreny ?>)</th>
                                        <th data-field="delivery_boy" data-sortable='true' data-visible='false'>Deliver By</th>
                                        <th data-field="delivery_boy_id" data-sortable='true' data-visible='false'>Delivery Boy Id</th>
                                        <th data-field="product_variant_id" data-sortable='true' data-visible='false'>Product Variant Id</th>
                                        <th data-field="delivery_date" data-sortable='true' data-visible='false'>Delivery Date</th>
                                        <th data-field="delivery_time" data-sortable='true' data-visible='false'>Delivery Time</th>
                                        <th data-field="status" data-sortable='true' data-visible='false'>Status</th>
                                        <th data-field="active_status" data-sortable='true' data-visible='true'>Active Status</th>
                                        <th data-field="date_added" data-sortable='true'>Order Date</th>
                                        <th data-field="operate">Action</th>
                                    </tr>
                                </thead>
                            </table>
                            <?php */ ?>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>