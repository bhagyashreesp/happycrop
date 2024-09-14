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
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step3/'.$is_seller) ?>" class="nav-link2 ">Basic Details</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="<?php echo base_url('register/step4/'.$is_seller) ?>" class="nav-link2">Business Details</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a href="<?php echo base_url('register/step5/'.$is_seller) ?>" class="nav-link2 active">Bank Details</a>
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
                                    <div class="alert alert-icon alert-warning alert-bg alert-inline show-code-action">
                                        Bank account should be in the name of registered business name or trade name as per GSTIN
                                    </div>
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step5/') ?>' method="post">
                                        <div class="form-group">
                                            <label>Account Number *</label>
                                            <input type="text" class='form-control' placeholder="Account Number" id="account_number" name="account_number" value="<?php echo $seller_data->account_number;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Account Number * </label>
                                            <input type="text" class="form-control" name="confirm_account_number" id="confirm_account_number" placeholder="Confirm Account Number" value="<?php echo $seller_data->account_number;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>IFSC Code</label>
                                            <input type="text" class="form-control" name="bank_code" id="bank_code" placeholder="IFSC Code" value="<?php echo $seller_data->bank_code;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" value="<?php echo $seller_data->bank_name;?>" />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Branch </label>
                                                <input type="text" class="form-control" name="bank_branch" id="bank_branch" placeholder="Branch" value="<?php echo $seller_data->bank_branch;?>"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="bank_city" id="bank_city" placeholder="City" value="<?php echo $seller_data->bank_city;?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="bank_state" id="bank_state" placeholder="State" value="<?php echo $seller_data->bank_state;?>" />
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