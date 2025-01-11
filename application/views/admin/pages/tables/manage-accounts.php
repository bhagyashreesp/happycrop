<style>.table td, .table th{font-size: 13px;}.fixed-table-toolbar {display: none;}</style>
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
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title;?></li>
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
                        <div class="col-md-12">
                            <div class="row col-md-12">
                                <div class="col-md-7">&nbsp;</div>
                                <div class="form-group col-md-4">
                                    <label>Search by Retailer / Mfg Name / Order ID</label>
                                    <input type="text" id="search_field" name="search_field" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                    <input type="hidden" id="condition" name="condition" value="<?php echo $condition;?>"/>
                                    <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                </div>
                            </div>
                            
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/view_admin_account_orders') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">Order ID</th>
                                        <th data-field="name" data-sortable='false'>Retailer Name</th>
                                        <th data-field="sellers" data-sortable='false' data-visible='true'>MFG Name</th>
                                        <th data-field="date_added" data-sortable='false'>Order Date</th>
                                        <th data-field="final_total" data-sortable='false'>Total Amount</th>
                                        <th data-field="order_status" data-sortable='false' data-visible='true'>Status</th>
                                        <th data-field="payment_receipt" data-sortable='false' data-visible='true'>Retailer Receipt</th>
                                        <th data-field="invoice_receipt" data-sortable='false' data-visible='true'>MFG Invoice</th>
                                        <th data-field="hc_receipt" data-sortable='false' data-visible='true'>HC Receipt</th>
                                    </tr>
                                </thead>
                            </table>
                            
                            <?php /* ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped ">
                                    <thead>
                                        <tr class="dark-blue-bg">
                                            <th>Invoice #</th>
                                            <th>Retailer Name</th>
                                            <th>MFG Name</th>
                                            <th>Order Date</th>
                                            <th>Amount INR</th>
                                            <th>Status</th>
                                            <th>Retailer Receipt</th>
                                            <th>MFG Invoice</th>
                                            <th>HC Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="10">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php */ ?>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</div>