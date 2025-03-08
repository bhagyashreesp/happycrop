<style>
    .table td,
    .table th {
        font-size: 13px;
    }

    .fixed-table-toolbar {
        top: 77px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $page_title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content ">
                    <div class="card- card-info mb-0">
                        <?php $this->load->view("admin/pages/tables/admin_subheader"); ?>
                    </div>
                    <div class="card- card-info mb-0 py-2">
                        <?php $this->load->view("admin/pages/tables/admin_account_subheader"); ?>
                    </div>
                    <div class="card content-area pt-4">
                        <ul class="nav nav-tabs extsys mt-2 pb-1">
                            <li class="active mx-2 "><a class="active  btn" data-toggle="tab" href="#business">Business</a></li>
                            <li class="mx-2 "><a class="  btn" data-toggle="tab" href="#shop_owner">Shop Owner</a></li>
                        </ul>


                        <div class="tab-content mt-2">
                            <div id="business" class="tab-pane fade show in active">
                                <div class="row col-md-12">
                                    <div class="col-md-6">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Search by Name</label>
                                        <input type="text" id="search_field" name="search_field" class="form-control" />
                                    </div>
                                    <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                        <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                        <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                    </div>
                                </div>
                                <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/get_business_items_list?retailer_status=2') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                    <thead>
                                        <tr>
                                            <th data-field="product_id" data-sortable='false' data-footer-formatter="totalFormatter">Product ID</th>
                                            <th data-field="name" data-sortable='false'>Name</th>
                                            <th data-field="hsn_no" data-sortable='false' data-visible='true'>HSN</th>
                                            <th data-field="category_name" data-sortable='false'>Category</th>
                                            <th data-field="price" data-sortable='false'>Price</th>
                                            <th data-field="mrp" data-sortable='false' data-visible='true'>MRP</th>
                                            <th data-field="gst" data-sortable='false' data-visible='true'>GST</th>
                                            <!-- <th data-field="product_view_url" data-sortable='false' data-visible='true'>Action</th> -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="shop_owner" class="tab-pane fade">
                                <div class="row col-md-12">
                                    <div class="col-md-6">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Search by Name</label>
                                        <input type="text" id="ext_search_field" name="search_field" class="form-control" />
                                    </div>
                                    <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                        <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                        <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                    </div>
                                </div>
                                <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/get_business_items_list?retailer_status=1') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["state"] }' data-query-params="external_orders_query_params">
                                    <thead>
                                        <tr class="dark-blue-bg">
                                            <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter"> ID</th>
                                            <th data-field="product_id" data-sortable='false' data-footer-formatter="totalFormatter">Product ID</th>
                                            <th data-field="product_name" data-sortable='false'>Product Name</th>
                                            <th data-field="hsn_no" data-sortable='false'>HSN </th>
                                            <th data-field="category_name" data-sortable='false'>Category</th>
                                            <th data-field="price" data-sortable='false'>HC Price</th>
                                            <th data-field="mrp" data-sortable='false'>MRP</th>
                                            <th data-field="gst" data-sortable='false'>GST</th>
                                            <!-- <th data-field="product_view_url" data-sortable='false'>View</th> -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>