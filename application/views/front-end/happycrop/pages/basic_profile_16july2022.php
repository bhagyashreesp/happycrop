<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content container">
        <div class="row mt-5 mb-5">
            <div class="col-md-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div id="account-dashboard" class="col-md-9 col-12 row">
                <div class="login-popup bg-white p-1">
                    <div class="">
                        <div class="col-md-12">
                        <?php if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2 && $seller_data->is_finish == 1) { ?>
                        <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                            Your account is under approval process. After approval, you can add products.
                        </div>
                        <?php } else if($this->ion_auth->member_status() == 2 && $seller_data->is_finish == 1) { ?>
                        <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                            Your account is under approval process. After approval, you can add products.
                        </div>
                        <?php } ?>
                        <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                            <ul class="nav">
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/basic-profile/'.$is_seller) ?>" class="nav-link2 active">Basic Details</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/business-details/'.$is_seller) ?>" class="nav-link2">Business Details</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/bank-details/'.$is_seller) ?>" class="nav-link2">Bank Details</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/gst-details/'.$is_seller) ?>" class="nav-link2">GST</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/license-details/'.$is_seller) ?>" class="nav-link2">License</a>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-12" id="sign-in">
                            
                                <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('my-account/save_step3') ?>' method="post">
                                    <div class="form-group">
                                        <label>Your Name *</label>
                                        <input type="text" class='form-control' placeholder="Your Name" id="username" name="username" value="<?php echo $user->username; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile No. *</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile number" value="<?php echo $user->mobile; ?>" readonly=""/>
                                    </div>
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Old Password *</label>
                                        <input type="password" class="form-control" name="old" id="old" placeholder="Password" />
                                    </div>
                                    <div class="form-group">
                                        <label>New Password *</label>
                                        <input type="password" class="form-control" name="new" id="new" placeholder="Password" />
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm New Password *</label>
                                        <input type="password" class="form-control" name="new_confirm" id="new_confirm" placeholder="Confirm Password" />
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                        <button type="submit" class="submit_btn btn btn-primary btn-block">Update</button>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-group" id="error_box2"></div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>