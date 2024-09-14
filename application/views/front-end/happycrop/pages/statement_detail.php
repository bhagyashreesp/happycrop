<!-- breadcrumb -->
<style>
.light-blue-bg {background: #cfd5eb;}
.dark-blue-bg {background: #4473c5;color:#FFF;}
</style>
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1>Orders</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'Orders' ?></a></li>
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
                    <ul class="nav prof-nav">
                        <li class="nav-item active">
                            <a href="<?php echo base_url('my-account/accounts/') ?>" class="nav-link2 btn mr-2">Invoices</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('my-account/statements/') ?>" class="nav-link2 btn btn-primary text-white">Statements</a>
                        </li>
                       </li>
                    </ul>
                </div>
                <div class="pt-4 pr-lg-2">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mfg Name</label>
                                <p><?php echo $seller_info['company_name'];?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mfg Address</label>
                                <p>
                                <?php echo ($seller_info['plot_no']!='') ? $seller_info['plot_no'].', ' : '';?>
                                <?php echo ($seller_info['street_locality']!='') ? $seller_info['street_locality'].', ' : '';?>
                                <?php echo ($seller_info['landmark']!='') ? $seller_info['landmark'].', ' : '';?>
                                <?php echo ($seller_info['pin']!='') ? $seller_info['pin'] : '';?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mfg Licence</label>
                                <p>
                                <?php echo ($seller_info['have_gst_no']) ? 'GST: '.$seller_info['gst_no'] : 'PAN No.: '.$seller_info['pan_number'];?><br />
                                <?php echo ($seller_info['have_fertilizer_license']) ? 'Fertilizer Licence No: '.$seller_info['fertilizer_license_no'].'<br />' : '';?>
                                <?php echo ($seller_info['have_pesticide_license_no']) ? 'Pesticide Licence No: '.$seller_info['pesticide_license_no'].'<br />' : '';?>
                                <?php echo ($seller_info['have_seeds_license_no']) ? 'Seeds Licence No: '.$seller_info['seeds_license_no'] : '';?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <table class='table-striped' data-toggle="table" data-url="<?= base_url('my-account/view_retailer_statement_details/'.$seller_id) ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="a.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                        <thead>
                            <tr class="dark-blue-bg">
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
</section>