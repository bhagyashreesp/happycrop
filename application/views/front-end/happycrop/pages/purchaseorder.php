<!-- breadcrumb -->
<style>
    .light-blue-bg {
        background: #cfd5eb;
    }

    .dark-blue-bg {
        background: #4473c5;
        color: #FFF;
    }
</style>
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1>Accounts</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('accounts')) ? $this->lang->line('accounts') : 'Accounts' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-2">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>

            <div class="col-md-10 col-12">
                <div class="">
                    <?php $this->load->view('front-end/' . THEME . '/pages/account_subheader') ?>

                </div>
                <div class="pt-3 pr-lg-2">
                    <ul class="nav nav-tabs extsys mt-2">
                        <li class="active mx-2 "><a class="p-3 active btn" data-toggle="tab" href="#system">System</a></li>
                        <li class="mx-2 "><a class="p-3  btn" data-toggle="tab" href="#external">External</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="system" class="tab-pane fade  in active">
                            <div class="gaps-1-5x row d-flex adjust-items-center">
                                <div class="form-group col-md-12">
                                    <div class="row ">
                                        <div class="col-md-6">&nbsp;</div>
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
                            <style>
                                .fixed-table-toolbar {
                                    display: none;
                                }
                            </style>
                            <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('my-account/get_order_account_list_filter/?order_status=received') ?>" data-order_status="send_invoice" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr class="dark-blue-bg">
                                        <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">Invoice #</th>
                                        <th data-field="sellers" data-sortable='false'>MFG Name</th>
                                        <th data-field="date_added" data-sortable='false'>Order Date</th>
                                        <th data-field="final_total" data-sortable='false'>Amount INR</th>
                                        <th data-field="order_status" data-sortable='false' data-visible='true'>Status</th>
                                        <th data-field="purchase_order" data-sortable='false' data-visible='true'>Invoice</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="external" class="tab-pane fade">
                            <div class="gaps-1-5x row d-flex adjust-items-center">
                                <div class="form-group col-md-12">
                                    <div class="row ">
                                        <div class="col-md-6">
                                            <a href="<?php echo base_url() . 'my-account/external-purchase-order'; ?>" class='button-- button-danger-outline-- btn btn-primary btn-sm d-inline-block p-3'>Add Purchase</a>
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

                            <style>
                                .fixed-table-toolbar {
                                    display: none;
                                }
                            </style>
                            <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('my-account/get_external_purchasebill_ist/?order_status=2') ?>" data-order_status="send_invoice" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr class="dark-blue-bg">
                                        <th data-field="order_number" data-sortable='false' data-footer-formatter="totalFormatter">Invoice #</th>
                                        <th data-field="party_name" data-sortable='false'>MFG Name</th>
                                        <th data-field="date" data-sortable='false'>Order Date</th>
                                        <th data-field="amount" data-sortable='false'>Amount INR</th>
                                        <th data-field="purchase_order" data-sortable='false' data-visible='true'>Invoice</th>
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