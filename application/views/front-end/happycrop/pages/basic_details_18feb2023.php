<style>.login-popup .nav-item .nav-link{font-size: 1.4rem;}</style>
<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="login-popup bg-white p-1">
                        <div class="p-4">
                            <?php if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2 && $seller_data->is_finish == 1) { ?>
                            <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                                Your account is under approval process. After approval, you can add products.
                            </div>
                            <?php } else if($this->ion_auth->member_status() == 2 && $seller_data->is_finish == 1) { ?>
                            <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                                Your account is under approval process. After approval, you can browse products.
                            </div>
                            <?php } ?>
                            <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                                <ul class="nav">
                                    <li class="nav-item active">
                                        <a href="<?php echo base_url('register/step3/'.$is_seller) ?>" class="nav-link2 active">Basic Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step4/'.$is_seller) ?>" class="nav-link2">Business Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step5/'.$is_seller) ?>" class="nav-link2">Bank Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step6/'.$is_seller) ?>" class="nav-link2">GST</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step7/'.$is_seller) ?>" class="nav-link2">License</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12" id="sign-in">
                                
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step3') ?>' method="post">
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
                <div class="col-lg-7">
                    <div class="iconbox-section mt-9 mb-9">
                        <div class="row">
                            <div class="col-md-6 mt-5 mb-5">
                                <div class="icon-box icon-box-side icon-colored-circle">
                                    <span class="icon-box-icon text-white">
                                        <i class="w-icon-store"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">10K+ Manufacturers</h4>
                                        <p>We ensure trusted 10k+ Manufacturers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5 mb-5">
                                <div class="icon-box icon-box-side icon-colored-circle">
                                    <span class="icon-box-icon text-white">
                                        <i class="w-icon-store"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">10K+ Retailers</h4>
                                        <p>We ensure trusted 10k+ Retailers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-5 mb-5">
                                <div class="icon-box icon-box-side icon-colored-circle">
                                    <span class="icon-box-icon text-white">
                                        <i class="w-icon-store"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">10K+ Manufacturers</h4>
                                        <p>We ensure trusted 10k+ Manufacturers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5 mb-5">
                                <div class="icon-box icon-box-side icon-colored-circle">
                                    <span class="icon-box-icon text-white">
                                        <i class="w-icon-store"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">10K+ Retailers</h4>
                                        <p>We ensure trusted 10k+ Retailers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-5">
                                <h4>All you need to sell in</h4>
                                <ul class="list-style-none list-type-check">
                                    <li>GST</li>
                                    <li>Bank Details</li>
                                    <li>License</li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>