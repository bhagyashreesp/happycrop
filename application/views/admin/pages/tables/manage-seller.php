<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?php echo $page_title;?></h4>
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
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <?php /* ?>
                        <div class="card-header border-0">
                            <div class="card-tools row ">
                                <a href="<?= base_url() . 'admin/sellers/manage-new-seller' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Manufacturer </a>
                                <a href="#" id="create-slug" class="btn btn-block  btn-outline-primary btn-sm">Create Manufacturer Slug </a>
                            </div>
                        </div>
                        <?php */ ?>
                        <div class="card-innr">
                            <div class="row col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Search</label>
                                    <input type="text" id="search_field" name="search_field" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Search</button>
                                </div>
                            </div>
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='seller_table' data-toggle="table" data-url="<?= base_url('admin/sellers/view_sellers/'.$status) ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="sd.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <!--<th data-field="id" data-sortable="true">ID</th>-->
                                        <th data-field="mfg_no" data-sortable="true">Mfg ID</th>
                                        <th data-field="company_name" data-sortable="false">Company Name</th>
                                        <th data-field="name" data-sortable="false">Person Name</th>
                                        <th data-field="email" data-sortable="false">Email</th>
                                        <th data-field="mobile" data-sortable="false">Mobile No</th>
                                        <th data-field="city_name" data-sortable="false">Location</th>
                                        <!--<th data-field="address" data-sortable="true" data-visible="false">Address</th>
                                        <th data-field="balance" data-sortable="true">Balance</th>
                                        <th data-field="rating" data-sortable="true">Rating</th>
                                        
                                        <th data-field="account_number" data-sortable="true" data-visible="false">Account Number</th>
                                        <th data-field="account_name" data-sortable="true" data-visible="false">Account Name</th>
                                        <th data-field="bank_code" data-sortable="true" data-visible="false">Bank Code</th>
                                        <th data-field="bank_name" data-sortable="true" data-visible="false">Bank Name</th>
                                        <th data-field="latitude" data-sortable="true" data-visible="false">Latitude</th>
                                        <th data-field="longitude" data-sortable="true" data-visible="false">Longitude</th>
                                        <th data-field="tax_name" data-sortable="true" data-visible="false">Tax Name</th>
                                        <th data-field="tax_number" data-sortable="true" data-visible="false">Tax Number</th>
                                        <th data-field="pan_number" data-sortable="true" data-visible="false">Pan Number</th>-->
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <!--<th data-field="category_ids" data-sortable="true" data-visible="false">Category Ids</th>-->
                                        <!--<th data-field="logo" data-sortable="true">Logo</th>-->
                                        <!--<th data-field="national_identity_card" data-sortable="true" data-visible="false">National Identity Card</th>
                                        <th data-field="address_proof" data-sortable="true" data-visible="false">Address Proof</th>
                                        <th data-field="permissions" data-sortable="true" data-visible="false">Permissions</th>-->
                                        <!--<th data-field="date" data-sortable="true" data-visible="false">Date</th>-->
                                        <th data-field="operate">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>