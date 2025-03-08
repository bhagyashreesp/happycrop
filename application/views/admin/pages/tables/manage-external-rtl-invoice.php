<style>
    .table td,
    .table th {
        font-size: 13px;
    }

    .fixed-table-toolbar {
        top: 39px;
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
                    <div class="card content-area pt-4">
                        <div class="col-md-12">
                            <div class="row col-md-12 pt-4">
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-2">
                                    <label>From Date</label>
                                    <input type="date" id="start_date" class="form-control" />
                                </div>
                                <div class="col-md-2">
                                    <label>To Date</label>
                                    <input type="date" id="end_date" class="form-control" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Search by Mfg Name / Order ID</label>
                                    <input type="text" id="search_field" name="search_field" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                    <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                    <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                </div>
                            </div>

                            <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('admin/orders/get_external_purchasebill_ist/?order_status=1&retailer_type=1') ?>" data-order_status="send_invoice" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["invoice_receipt"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr class="dark-blue-bg">
                                        <th data-field="invoice_number" data-sortable='false' data-footer-formatter="totalFormatter">Invoice Number</th>
                                        <th data-field="party_name" data-sortable='false'>MFG Name</th>
                                        <th data-field="date" data-sortable='false'>Order Date</th>
                                        <th data-field="amount" data-sortable='false'>Amount INR</th>
                                        <th data-field="invoice_receipt" data-sortable='false' data-visible='true'>Invoice</th>
                                    </tr>
                                </thead>
                            </table>



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>