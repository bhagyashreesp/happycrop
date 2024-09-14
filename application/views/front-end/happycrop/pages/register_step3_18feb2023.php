<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="login-popup bg-white">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#sign-in" class="nav-link border-none active"><?php echo ($is_seller) ? 'Manufacturer' : 'Retailer' ; ?> <br/>Sign Up</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-in">
                                    <form id="sign-up2-form" class="sign-up2-form form-submit-event2" action='<?php echo ($is_seller) ? base_url('seller/auth/create_custom_seller')  : base_url('auth/create_custom_user') ?>' method="post">
                                        <div class="form-group">
                                            <label>Your Name *</label>
                                            <input type="text" class='form-control' placeholder="Your Name" id="name" name="name"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile No. *</label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile number"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Confirm Password *</label>
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" />
                                        </div>
                                        <br />
                                        <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                        <button type="submit" class="submit_btn btn btn-primary btn-block"><?php echo ($this->lang->line('register')!='') ? $this->lang->line('register') : 'Register' ?></button>
                                        <br />
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group" id="error_box2"></div>
                                        </div>
                                    </form>
                                    <div class="mt-5 text-center">
                                        <a href="<?php echo base_url();?>register/step2/<?php echo $is_seller; ?>" class=" mt-5">Have a Account ? Sign In Now</a>
                                    </div>
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