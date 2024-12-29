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
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
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
                        <?php $this->load->view("seller/pages/tables/seller_subheader"); ?>

                    </div>
                    <div class="card content-area pt-4">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs extsys mt-2">
                                <li class="active mx-2 "><a class="btn active" data-toggle="tab" href="#system">System</a></li>
                                <li class="mx-2 "><a class="btn" data-toggle="tab" href="#external">External</a></li>
                            </ul>


                            <div class="tab-content">
                                <div id="system" class="tab-pane fade show in active">
                                    <div class="row col-md-12">
                                        <div class="col-md-7">&nbsp;</div>
                                        <div class="form-group col-md-4">
                                            <label>Search by Retailer Name</label>
                                            <input type="text" id="search_field" name="search_field" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                            <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                            <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                        </div>
                                    </div>

                                    <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/view_seller_statement_orders') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="a.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                        <thead>
                                            <tr>
                                                <th data-field="user_id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                                <th data-field="name" data-sortable='false'>Retailer Name</th>
                                                <th data-field="state_name" data-sortable='false'>State</th>
                                                <th data-field="email" data-sortable='false'>Email</th>
                                                <th data-field="Mobile" data-sortable='false'>Mobile</th>
                                                <th data-field="operate">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="external" class="tab-pane fade">
                                    <div class="row col-md-12">
                                        <div class="col-md-7">
                                            <a href="<?php echo base_url() . 'seller/orders/external-parties'; ?>" class='button-- button-danger-outline-- btn btn-primary btn-sm d-inline-block '>Add Parties</a>

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Search by Retailer Name</label>
                                            <input type="text" id="search_field" name="search_field" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                            <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                            <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                        </div>
                                    </div>
                                    <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('my-account/get_external_parties_list/') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                        <thead>
                                            <tr class="dark-blue-bg">
                                                <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                                <th data-field="party_name" data-sortable='false'>MFG Name</th>
                                                <th data-field="email" data-sortable='false' data-visible='true'>Email</th>
                                                <th data-field="mobile" data-sortable='false' data-visible='true'>Mobile</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <?php /* ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped ">
                                    <thead>
                                        <tr class="dark-blue-bg">
                                            <th>#</th>
                                            <th>Retailer Name</th>
                                            <th>Actions</th>
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