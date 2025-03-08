<style>
    .table td,
    .table th {
        font-size: 13px;
    }

    .fixed-table-toolbar {
       top: 17px;
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
                            <div class="row col-md-12">
                                <div class="col-md-6">&nbsp;</div>
                                <div class="form-group col-md-4">
                                    <label>Search by Name/ ID / Category</label>
                                    <input type="text" id="search_field" name="search_field" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                    <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                    <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                </div>
                            </div>

                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/view_seller_account_items') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["product_view_url"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="product_id" data-sortable='false' data-footer-formatter="totalFormatter">Product ID</th>
                                        <th data-field="name" data-sortable='false'>Name</th>
                                        <th data-field="hsn_no" data-sortable='false' data-visible='true'>HSN</th>
                                        <th data-field="category_name" data-sortable='false'>Category</th>
                                        <th data-field="price" data-sortable='false'>Price</th>
                                        <th data-field="mrp" data-sortable='false' data-visible='true'>MRP</th>
                                        <th data-field="gst" data-sortable='false' data-visible='true'>GST</th>
                                        <th data-field="product_view_url" data-sortable='false' data-visible='true'>Action</th>
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