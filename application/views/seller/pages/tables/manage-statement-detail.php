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
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
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
                        <ul class="nav nav-tabs pb-0" id="myTab" >
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/orders/accounts/') ?>">Accounts</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link btn btn-primary" href="<?php echo base_url('seller/orders/statements/') ?>">Statements</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card content-area pt-4">
                        <div class="col-md-12">
                            <h5>Upload New Statement</h5>
                            <hr />
                            <form class="form-horizontal " id="send_statement_form" action="<?= base_url('seller/orders/send_statement/'); ?>" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Retailer Name</label>
                                            <p><?php echo $retailer_info['company_name'];?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Retailer Address</label>
                                            <p>
                                            <?php echo ($retailer_info['plot_no']!='') ? $retailer_info['plot_no'].', ' : '';?>
                                            <?php echo ($retailer_info['street_locality']!='') ? $retailer_info['street_locality'].', ' : '';?>
                                            <?php echo ($retailer_info['landmark']!='') ? $retailer_info['landmark'].', ' : '';?>
                                            <?php echo ($retailer_info['pin']!='') ? $retailer_info['pin'] : '';?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Retailer Licence</label>
                                            <p>
                                            <?php echo ($retailer_info['have_gst_no']) ? 'GST: '.$retailer_info['gst_no'] : 'PAN No.: '.$retailer_info['pan_number'];?><br />
                                            <?php echo ($retailer_info['have_fertilizer_license']) ? 'Fertilizer Licence No: '.$retailer_info['fertilizer_license_no'].'<br />' : '';?>
                                            <?php echo ($retailer_info['have_pesticide_license_no']) ? 'Pesticide Licence No: '.$retailer_info['pesticide_license_no'].'<br />' : '';?>
                                            <?php echo ($retailer_info['have_seeds_license_no']) ? 'Seeds Licence No: '.$retailer_info['seeds_license_no'] : '';?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input class="form-control" type="date" id="from_date" name="from_date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input class="form-control" type="date" id="to_date" name="to_date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>File</label>
                                            <label class="btn btn-warning btn-sm btn-block" for="file">Browse</label>
                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                <input type="file" class="form-control" name="attachments[]" id="file" required="" style="padding:0px;min-height: 28px;" required="" onchange="$('#file_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                            </div>
                                            <p class=""><span id="file_text"></span></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label><br />
                                            <input type="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>" />
                                            <button id="submit_statement_btn" class="btn btn-primary btn-sm" type="submit">Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <h5>Statements</h5>
                            <hr />
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/view_seller_retailer_statements/'.$retailer_id) ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                        <th data-field="from_date" data-sortable='false'>From Date</th>
                                        <th data-field="to_date" data-sortable='false'>To Date</th>
                                        <th data-field="created_date" data-sortable='false'>Uploaded Date</th>
                                        <th data-field="operate">Action</th>
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