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
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs extsys mt-2 pb-2">
                                <li class="active mx-2 "><a class="btn active" data-toggle="tab" href="#system">System</a></li>
                                <li class="mx-2 "><a class="btn" data-toggle="tab" href="#external">External</a></li>
                            </ul>

                            <div class="tab-content mt-2">
                                <div id="system" class="tab-pane fade show in active">
                                    <div class="row col-md-12">
                                        <div class="col-md-7">&nbsp;</div>
                                        <div class="form-group col-md-4">
                                            <label>Search by MFG Name / Order ID</label>
                                            <input type="text" id="search_field" name="search_field" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                            <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                            <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                        </div>
                                    </div>

                                    <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/get_external_purchasenbill_list?order_status=send_invoice') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">Invoice #</th>
                                                <th data-field="sellers" data-sortable='false'>MFG Name</th>
                                                <th data-field="date_added" data-sortable='false'>Order Date</th>
                                                <th data-field="final_total" data-sortable='false'>Amount INR</th>
                                                <th data-field="order_status" data-sortable='false' data-visible='true'>Status</th>
                                                <th data-field="invoice_receipt" data-sortable='false' data-visible='true'>Invoice</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="external" class="tab-pane fade">
                                    <div class="row col-md-12">
                                        <div class="col-md-7">

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Search by MFG Name / Order ID</label>
                                            <input type="text" id="search_field" name="search_field" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                            <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                            <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                        </div>
                                    </div>

                                    <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('admin/orders/get_external_purchasesale_ist/?order_status=1&retailer_type=1') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                        <thead>
                                            <tr class="dark-blue-bg">
                                                <th data-field="invoice_number" data-sortable='false' data-footer-formatter="totalFormatter">Invoice #</th>
                                                <th data-field="party_name" data-sortable='false'>MFG Name</th>
                                                <th data-field="date" data-sortable='false'>Order Date</th>
                                                <th data-field="amount" data-sortable='false'>Amount INR</th>
                                                <?php if ($delivery_challan == "1") { ?>
                                                    <th data-field="delivery_challan" data-sortable='false' data-visible='true'>Delivery Challan</th>
                                                <?php } else { ?>
                                                    <th data-field="invoice_receipt" data-sortable='false' data-visible='true'>Invoice</th>
                                                <?php } ?>
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
    </section>
</div>