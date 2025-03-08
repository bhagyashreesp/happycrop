<style>
    .table td,
    .table th {
        font-size: 13px;
    }

    .fixed-table-toolbar {
        display: none;
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
                                <ul class="nav nav-tabs extsys mt-2 pb-1">
                                    <li class="active mx-2 "><a class="active  btn" data-toggle="tab" href="#business_system">System</a></li>
                                    <li class="mx-2 "><a class="  btn" data-toggle="tab" href="#business_external">External</a></li>
                                </ul>
                                <div class="tab-content mt-2">
                                    <div id="business_system" class="tab-pane fade show in active">
                                        <div class="row col-md-12">
                                            <div class="col-md-6">&nbsp;</div>
                                            <div class="form-group col-md-4">
                                                <label>Search by Retailer Name</label>
                                                <input type="text" id="search_field" name="search_field" class="form-control" />
                                            </div>
                                            <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                                <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                                <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                            </div>
                                        </div>

                                        <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/business_get_order_statement_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="a.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                            <thead>
                                                <tr>
                                                    <th data-field="user_id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                                    <th data-field="name" data-sortable='false'>Retailer Name</th>
                                                    <th data-field="state_name" data-sortable='false'>State</th>
                                                    <th data-field="email" data-sortable='false'>Email</th>
                                                    <th data-field="mobile" data-sortable='false'>Mobile</th>
                                                    <!-- <th data-field="operate">Action</th> -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="business_external" class="tab-pane fade">
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
                                        <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('admin/orders/get_external_parties_list?retailer_status=2') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["state"] }' data-query-params="external_orders_query_params">
                                            <thead>
                                                <tr class="dark-blue-bg">
                                                    <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                                    <th data-field="party_name" data-sortable='false'>Name</th>
                                                    <th data-field="email" data-sortable='false' data-visible='true'>Email</th>
                                                    <th data-field="mobile" data-sortable='false' data-visible='true'>Mobile</th>
                                                    <!-- <th data-field="actionseller" data-sortable='false' data-visible='true'>Action</th> -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="shop_owner" class="tab-pane fade">
                                <ul class="nav nav-tabs extsys mt-2 pb-1">
                                    <li class="active mx-2 "><a class="active  btn" data-toggle="tab" href="#shop_owner_system">System</a></li>
                                    <li class="mx-2 "><a class="  btn" data-toggle="tab" href="#shop_owner_external">External</a></li>
                                </ul>
                                <div class="tab-content mt-2">
                                    <div id="shop_owner_system" class="tab-pane fade show in active">
                                        <div class="gaps-1-5x row d-flex adjust-items-center">
                                            <div class="form-group col-md-12">
                                                <div class="row ">
                                                    <div class="col-md-6">&nbsp;</div>
                                                    <div class="col-md-4">
                                                        <label>Search by Mfg Name</label>
                                                        <input type="text" id="shop_search_field" name="search_field" class="form-control" />
                                                    </div>
                                                    <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                                        <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('admin/orders/show_owner_get_order_statement_list/') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["state"] }' data-query-params="shop_sys_orders_query_params">
                                            <thead>
                                                <tr class="dark-blue-bg">
                                                    <th data-field="seller_id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                                    <th data-field="seller" data-sortable='false'>MFG Name</th>
                                                    <th data-field="state_name" data-sortable='false'>Place</th>
                                                    <th data-field="email" data-sortable='false'>Email</th>
                                                    <th data-field="mobile" data-sortable='false'>Mobile</th>
                                                    <!-- <th data-field="operate" data-sortable='false' data-visible='true'>Actions</th> -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="shop_owner_external" class="tab-pane fade">
                                        <div class="row col-md-12">
                                            <div class="col-md-6">

                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Search by Name</label>
                                                <input type="text" id="shopext_search_field" name="search_field" class="form-control" />
                                            </div>
                                            <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                                <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                                <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                            </div>
                                        </div>
                                        <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('admin/orders/get_external_parties_list?retailer_status=1') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["state"] }' data-query-params="shop_ext_orders_query_params">
                                            <thead>
                                                <tr class="dark-blue-bg">
                                                    <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                                    <th data-field="party_name" data-sortable='false'>Name</th>
                                                    <th data-field="email" data-sortable='false' data-visible='true'>Email</th>
                                                    <th data-field="mobile" data-sortable='false' data-visible='true'>Mobile</th>
                                                    <!-- <th data-field="actionseller" data-sortable='false' data-visible='true'>Action</th> -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>