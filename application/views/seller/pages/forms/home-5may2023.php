<style>
.icon-comments {font-size: 44px;padding: 20px;background: #CCC;}
.msg-div{padding-top: 10px;}
.msg-div h3{font-size:18px;}
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-3">
            <div class="card  content-area p-2">    
                <div class="row">
                    <div class="col-md-2 text-center">
                        <?php if(file_exists($seller_data['logo']) && $seller_data['logo']!='') { ?> 
                        <div class="logo brand-link p-2">
                            <img class="brand-image mb-3" src="<?php echo base_url().$seller_data['logo'];?>" alt="<?php echo $seller_data['company_name'];?>"/>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-10 bg-white">
                        <h4 class="h6 mt-3">Hello <?php echo $seller_data['company_name'];?></h4>
                    </div>
                </div>
            </div>
            <div class="card  content-area p-3  bg-secondary">
                <div class="card-innr">
                    <h5 class="col">Order Outlines</h5>
                    <div class="row dash-bg-gray">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dash-widget-1 small-box bg-white min-height-162 mt-3 mb-3">
                                        <i class="fa fa-money-bill-alt"></i>
                                        <h3><i class="fa fa-rupee-sign"></i> <?= number_format($status_counts['total_sales']) ?></h3>
                                        <h4 class="">Total Sales</h4>
                                    </div>
                                </div>
                                <!--
                                <div class="col-md-6">
                                    <div class="dash-widget-1 min-height-162 mt-3 mb-3">
                                        <i class="fa fa-chart-bar"></i>
                                        <h3>250</h3>
                                        <h4 class="">Total Volume</h4>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="dash-widget-1 mt-3 mb-3">
                                        <a href="<?php echo base_url('seller/orders');?>" target="_blank">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?= number_format($status_counts['total_orders']) ?></h5>
                                            <h5 class="">Total Orders</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dash-widget-1 mt-3 mb-3">
                                        <a href="<?php echo base_url('seller/orders/show_orders/1');?>" target="_blank">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $status_counts['in_process_orders']; ?></h5>
                                            <h5 class="">In Process Orders</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dash-widget-1 mt-3 mb-3">
                                        <a href="<?php echo base_url('seller/orders/show_orders/2');?>" target="_blank">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $status_counts['shipped_orders']; ?></h5>
                                            <h5 class="">Shipped Orders</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="dash-widget-1 mb-3">
                                        <a href="<?php echo base_url('seller/orders/show_orders/3');?>" target="_blank">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $status_counts['issue_raised_orders']; ?></h5>
                                            <h5 class="">Issue Raised</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dash-widget-1 mb-3">
                                        <a href="<?php echo base_url('seller/orders/show_orders/4');?>" target="_blank">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $status_counts['cancelled_orders']; ?></h5>
                                            <h5 class="">Cancelled Orders</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dash-widget-1 mb-3">
                                        <a href="<?php echo base_url('seller/orders/show_orders/5');?>" target="_blank">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $status_counts['delivered_orders']; ?></h5>
                                            <h5 class="">Delivered Orders</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            
            <?php /* ?>
            <div class="row pt-4">
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="card ">
                        <div class="row">
                            <div class="col-md-2">
                                <i class="font-24 fa fa-comments icon-comments"></i>
                            </div>
                            <div class="col-md-10">
                                <h3 class=" mt-3 mb-0 text-center">83</h3>
                                <h6 class=" mt-0 text-center">Messages</h6>
                            </div>
                        </div>
                        <div class="card-body border-top pt-1 pb-1" style="height: 192px;overflow: auto;">
                            <div class="row msg-div border-bottom">
                                <div class="col-md-9">
                                    <h3 class="mb-0">Sachin Jadhav</h3>
                                    <h6 class="text-gray mb-1">Nipani Karnataka</h6>
                                    <p>Message text here...</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-gray pt-lg-3 text-right">
                                        <i class="fa fa-clock"></i> 30th May
                                    </div>
                                </div>
                            </div>
                            <div class="row msg-div border-bottom">
                                <div class="col-md-9">
                                    <h3 class="mb-0">Sachin Jadhav</h3>
                                    <h6 class="text-gray mb-1">Nipani Karnataka</h6>
                                    <p>Message text here...</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-gray pt-lg-3 text-right">
                                        <i class="fa fa-clock"></i> 30th May
                                    </div>
                                </div>
                            </div>
                            <div class="row msg-div border-bottom">
                                <div class="col-md-9">
                                    <h3 class="mb-0">Sachin Jadhav</h3>
                                    <h6 class="text-gray mb-1">Nipani Karnataka</h6>
                                    <p>Message text here...</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-gray pt-lg-3 text-right">
                                        <i class="fa fa-clock"></i> 30th May
                                    </div>
                                </div>
                            </div>
                            <div class="row msg-div border-bottom">
                                <div class="col-md-9">
                                    <h3 class="mb-0">Sachin Jadhav</h3>
                                    <h6 class="text-gray mb-1">Nipani Karnataka</h6>
                                    <p>Message text here...</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-gray pt-lg-3 text-right">
                                        <i class="fa fa-clock"></i> 30th May
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center pt-2 pb-2">
                                <a href="#">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="card ">
                        <h3 class="card-title mb-0 mt-2 text-center">Orders</h3>
                        <div class="card-body pt-1 pb-1">
                            <div class="row">
                                <ul class="col-md-12">
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    New Orders
                                    <span class="badge badge-primary badge-pill"><?= $status_counts['received'] ?></span>
                                  </li>
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    In Process Orders
                                    <span class="badge badge-primary badge-pill"><?= $status_counts['processed'] ?></span>
                                  </li>
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    Total Orders
                                    <span class="badge badge-primary badge-pill"><?= $order_counter ?></span>
                                  </li>
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    Shipped Orders
                                    <span class="badge badge-primary badge-pill"><?= $status_counts['shipped'] ?></span>
                                  </li>
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    Delivered Orders
                                    <span class="badge badge-primary badge-pill"><?= $status_counts['delivered'] ?></span>
                                  </li>
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    Return Orders
                                    <span class="badge badge-primary badge-pill"><?= $status_counts['returned'] ?></span>
                                  </li>
                                  <li class="p-1 d-flex justify-content-between align-items-center">
                                    Cancelled Orders
                                    <span class="badge badge-primary badge-pill"><?= $status_counts['cancelled'] ?></span>
                                  </li>
                                </ul>
                            </div>
                            <?php /* ?>
                            <div class="row border-bottom pb-3 ">
                                <div class="col-md-12">
                                    <div class="">
                                        <div class="" style="min-height: 200px;margin-top: -18px;">
                                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/home/seller_recent_orders') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="false" data-page-list="[3, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "order-items-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">ID</th>
                                                        <th data-field="order_item_id" data-sortable='true'>Order Item ID</th>
                                                        <th data-field="order_id" data-sortable='true'>Order ID</th>
                                                        <th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>
                                                        <!--<th data-field="seller_id" data-sortable='true' data-visible="false">Seller ID</th>
                                                        <th data-field="is_credited" data-sortable='true' data-visible="false">Commission</th>-->
                                                        <th data-field="quantity" data-sortable='true' data-visible="false">Quantity</th>
                                                        <!--<th data-field="username" data-sortable='true'>User Name</th>
                                                        <th data-field="seller_name" data-sortable='true'>Seller Name</th>-->
                                                        <th data-field="product_name" data-sortable='true'>Product Name</th>
                                                        <th data-field="mobile" data-sortable='true'>Mobile</th>
                                                        <th data-field="sub_total" data-sortable='true' data-visible="true">Total(<?= $curreny ?>)</th>
                                                        <!--<th data-field="delivery_boy" data-sortable='true' data-visible='false'>Deliver By</th>
                                                        <th data-field="delivery_boy_id" data-sortable='true' data-visible='false'>Delivery Boy Id</th>-->
                                                        <th data-field="product_variant_id" data-sortable='true' data-visible='false'>Product Variant Id</th>
                                                        <!--<th data-field="delivery_date" data-sortable='true' data-visible='false'>Delivery Date</th>
                                                        <th data-field="delivery_time" data-sortable='true' data-visible='false'>Delivery Time</th>-->
                                                        <th data-field="status" data-sortable='true' data-visible='false'>Status</th>
                                                        <th data-field="active_status" data-sortable='true' data-visible='true'>Active Status</th>
                                                        <th data-field="date_added" data-sortable='true'>Order Date</th>
                                                        <th data-field="operate">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div><!-- .card-innr -->
                                    </div><!-- .card -->
                                </div>
                            </div>
                            <?php *//* ?>
                            <div class="row">
                                <div class="col-md-12 text-center pt-2 pb-2">
                                    <a href="<?php echo base_url('seller/orders'); ?>">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div class="card ">
                        <h3 class="card-title mb-2 mt-2 text-center">Products</h3>
                        <div class="card-body pt-1 pb-1">
                            <div class="row">
                                <div class="col-md-3">
                                    <div id="product_state_chart"></div>
                                </div>
                                <div class="col-md-3">
                                    <div id="product_price_chart"></div>
                                </div>
                                <div class="col-md-3">
                                    <div id="product_specification_chart"></div>
                                </div>
                                <div class="col-md-3">
                                    <div id="product_image_chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-12" id="ecommerceChartView">
                    <div class="card card-shadow chart-height">
                        <div class="m-3">Product Sales</div>
                        <div class="card-header card-header-transparent py-20 border-0">
                            <ul class="nav nav-pills nav-pills-rounded chart-action float-right btn-group" role="group">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#scoreLineToDay">Day</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToWeek">Week</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToMonth">Month</a></li>
                            </ul>
                        </div>
                        <div class="widget-content tab-content bg-white p-20">
                            <div class="ct-chart tab-pane active scoreLineShadow" id="scoreLineToDay"></div>
                            <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToWeek"></div>
                            <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToMonth"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Category Wise Product's Sales -->
                    <div class="card ">
                        <h3 class="card-title m-3">Category Wise Product's Count</h3>
                        <div class="card-body">
                            <div id="piechart_3d" class='piechat_height'></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <?php */ ?>
        </div>
    </section>
</div>
<?php /* ?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-3">
            <div class="row pt-4">
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center text-danger">
                                        <i class="ion-ios-cart-outline display-4"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h5 class="text-muted text-bold-500">Orders</h5>
                                        <h3 class="text-bold-600"><?= $order_counter ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center text-info">
                                        <i class="ion-ios-albums-outline display-4 display-4"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h5 class="text-muted text-bold-500">Products</h5>
                                        <h3 class="text-bold-600"><?= $products ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center text-warning">
                                        <i class="ion-ios-star-outline display-4 display-4"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h5 class="text-muted text-bold-500">Rating</h5>
                                        <h3 class="text-bold-600"><?= intval($ratings[0]['rating']) . "/" . $ratings[0]['no_of_ratings']; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center text-success">
                                        <i class="ion-cash display-4"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h5 class="text-muted text-bold-500">Balance</h5>
                                        <h3 class="text-bold-600"><?= $curreny . ' ' . number_format($balance, 2) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-12" id="ecommerceChartView">
                    <div class="card card-shadow chart-height">
                        <div class="m-3">Product Sales</div>
                        <div class="card-header card-header-transparent py-20 border-0">
                            <ul class="nav nav-pills nav-pills-rounded chart-action float-right btn-group" role="group">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#scoreLineToDay">Day</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToWeek">Week</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToMonth">Month</a></li>
                            </ul>
                        </div>
                        <div class="widget-content tab-content bg-white p-20">
                            <div class="ct-chart tab-pane active scoreLineShadow" id="scoreLineToDay"></div>
                            <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToWeek"></div>
                            <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToMonth"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Category Wise Product's Sales -->
                    <div class="card ">
                        <h3 class="card-title m-3">Category Wise Product's Count</h3>
                        <div class="card-body">
                            <div id="piechart_3d" class='piechat_height'></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h6><i class="icon fa fa-info"></i> <?= $count_products_availability_status ?> Product(s) sold out!</h6>
                        <a href="<?= base_url('seller/product/?flag=sold') ?>" class="text-decoration-none small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <?php $settings = get_settings('system_settings', true); ?>
                <div class="col-md-6 col-xs-12">
                    <div class="alert alert-primary alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h6><i class="icon fa fa-info"></i> <?= $count_products_low_status ?> Product(s) low in stock!<small> (Low stock limit <?= isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : '5' ?>)</small></h6>
                        <a href="<?= base_url('seller/product/?flag=low') ?>" class="text-decoration-none small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <h5 class="col">Order Outlines</h5>
                <div class="row col-12 d-flex">
                    <div class="col-3">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3><?= $status_counts['awaiting'] ?></h3>
                                <p>Awaiting</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-history"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?= $status_counts['received'] ?></h3>
                                <p>Received</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-level-down-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $status_counts['processed'] ?></h3>
                                <p>Processed</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-people-carry"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $status_counts['shipped'] ?></h3>
                                <p>Shipped</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-shipping-fast"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $status_counts['delivered'] ?></h3>
                                <p>Delivered</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $status_counts['cancelled'] ?></h3>
                                <p>Cancelled</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $status_counts['returned'] ?></h3>
                                <p>Returned</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-xs fa-level-up-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-innr">
                            <div class="gaps-1-5x row d-flex adjust-items-center">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-4">
                                        <label>Date and time range:</label>
                                        <div class="input-group col-md-12">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="datepicker">
                                            <input type="hidden" id="start_date" class="form-control float-right">
                                            <input type="hidden" id="end_date" class="form-control float-right">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div>
                                            <label>Filter By status</label>
                                            <select id="order_status" name="order_status" placeholder="Select Status" required="" class="form-control">
                                                <option value="">All Orders</option>
                                                <option value="awaiting">Awaiting</option>
                                                <option value="received">Received</option>
                                                <option value="processed">Processed</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="delivered">Delivered</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="returned">Returned</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 d-flex align-items-center pt-4">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Filter</button>
                                    </div>
                                </div>
                            </div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/view_order_items') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "order-items-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
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
                                        <th data-field="seller_name" data-sortable='true'>Seller Name</th>
                                        <th data-field="product_name" data-sortable='true'>Product Name</th>
                                        <th data-field="mobile" data-sortable='true'>Mobile</th>
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
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
        </div>
    </section>
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
</div>
<?php */ ?>