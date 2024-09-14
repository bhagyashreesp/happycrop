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
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/basic-profile/'.$is_seller) ?>" class="nav-link2">Basic Details</a>
                                </li>
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/business-details/'.$is_seller) ?>" class="nav-link2 active">Business Details</a>
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
                            
                                <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('my-account/save_step4') ?>' method="post">
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
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>