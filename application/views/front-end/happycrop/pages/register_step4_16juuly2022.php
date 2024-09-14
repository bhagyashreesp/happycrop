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
                                Your account is under approval process. After approval, you can add products.
                            </div>
                            <?php } ?>
                            <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step3/'.$is_seller) ?>" class="nav-link2 ">Basic Details</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a href="<?php echo base_url('register/step4/'.$is_seller) ?>" class="nav-link2 active">Business Details</a>
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
                                
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step4/'.$is_seller) ?>' method="post">
                                        <div class="form-group">
                                            <label>Company Name *</label>
                                            <input type="text" class='form-control' placeholder="Company Name" id="company_name" name="company_name" value="<?php echo $seller_data->company_name;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Name 1 </label>
                                            <input type="text" class='form-control' placeholder="Brand Name 1" id="brand_name_1" name="brand_name_1" value="<?php echo $seller_data->brand_name_1;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Name 2 </label>
                                            <input type="text" class='form-control' placeholder="Brand Name 2" id="brand_name_2" name="brand_name_2" value="<?php echo $seller_data->brand_name_2;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Name 3 </label>
                                            <input type="text" class='form-control' placeholder="Brand Name 3" id="brand_name_3" name="brand_name_3" value="<?php echo $seller_data->brand_name_3;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Plot No. / Floor / Building  </label>
                                            <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $seller_data->plot_no;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Street / Locality</label>
                                            <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality" value="<?php echo $seller_data->street_locality;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Land Mark</label>
                                            <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $seller_data->landmark;?>" />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Pin  </label>
                                                <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" value="<?php echo $seller_data->pin;?>"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City" value="<?php echo $seller_data->city;?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="state" id="state" placeholder="State" value="<?php echo $seller_data->state;?>" />
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="btn btn-primary btn-block"><?php echo ($this->lang->line('continue')!='') ? $this->lang->line('continue') : 'Continue' ?></button>
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