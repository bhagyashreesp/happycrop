<style>.logo img {width: 150px;}.login-popup .nav-item .nav-link{font-size: 1.4rem;}</style>
<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="login-popup big border-radius-10 bg-white p-1">
                        <div class="p-4 pb-0">
                            <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step3/'.$is_seller) ?>" class="nav-link2 ">Basic Details</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="<?php echo base_url('register/step4/'.$is_seller) ?>" class="nav-link2">Business Details</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="<?php echo base_url('register/step5/'.$is_seller) ?>" class="nav-link2 ">Bank Details</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="<?php echo base_url('register/step6/'.$is_seller) ?>" class="nav-link2 ">GST</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a href="<?php echo base_url('register/step7/'.$is_seller) ?>" class="nav-link2 active">License</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-4 pt-1">
                            <div class="row">
                                <div class="col-md-12" id="sign-in">
                                    <?php if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2 && $seller_data->is_finish == 1) { ?>
                                    <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                                        Your account is under approval process. After approval, you can add products.
                                    </div>
                                    <?php } else if($this->ion_auth->member_status() == 2 && $seller_data->is_finish == 1) { ?>
                                    <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                                        Your account is under approval process. After approval, you can browse products.
                                    </div>
                                    <?php } ?>
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step7/') ?>' method="post">
                                        <div class="form-group">
                                            <label>
                                            I have Fertilizer License
                                            <select class='have_license_no float-lg-right' placeholder="Fertilizer License" id="have_fertilizer_license" name="have_fertilizer_license">
                                                <option value="0" <?php echo ($seller_data->have_fertilizer_license==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($seller_data->have_fertilizer_license==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
                                            </label>
                                        </div>
                                        <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                            <label>Fertilizer License Number*</label>
                                            <input type="text" class='form-control' placeholder="Fertilizer License Number" id="fertilizer_license_no" name="fertilizer_license_no" value="<?php echo $seller_data->fertilizer_license_no;?>"/>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                    <label>Expiry Date*</label>
                                                    <input type="date" class='form-control' placeholder="Expiry Date" id="fert_lic_expiry_date" name="fert_lic_expiry_date" value="<?php echo $seller_data->fert_lic_expiry_date;?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                    <label>Upload Photo*</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="btn btn-primary btn-rounded btn-md btn-block--" for="fertilizer_license_photo">Select File</label>
                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                <input type="file" class="form-control" name="fertilizer_license_photo" id="fertilizer_license_photo" style="padding:0px;min-height: 28px;" onchange="$('#fertilizer_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                            </div>
                                                            <p class=""><span id="fertilizer_license_photo_text"></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <?php
                                                        if(file_exists($seller_data->fertilizer_license_photo) && $seller_data->fertilizer_license_photo!='')
                                                        {
                                                            ?>
                                                            <a class="btn btn-primary btn-rounded btn-md btn-block--" href="<?php echo base_url().$seller_data->fertilizer_license_photo; ?>" target="_blank">View File</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                            I have Pesticide License
                                            <select class='have_pesticide_license_no float-lg-right' placeholder="Pesticide License" id="have_pesticide_license_no" name="have_pesticide_license_no">
                                                <option value="0" <?php echo ($seller_data->have_pesticide_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($seller_data->have_pesticide_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
                                            </label>
                                        </div>
                                        <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                            <label>Pesticide License Number*</label>
                                            <input type="text" class='form-control' placeholder="Pesticide License Number" id="pesticide_license_no" name="pesticide_license_no" value="<?php echo $seller_data->pesticide_license_no;?>"/>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                    <label>Expiry Date*</label>
                                                    <input type="date" class='form-control' placeholder="Expiry Date" id="pest_lic_expiry_date" name="pest_lic_expiry_date" value="<?php echo $seller_data->pest_lic_expiry_date;?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                    <label>Upload Photo*</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="btn btn-primary btn-rounded btn-md btn-block--" for="pesticide_license_photo">Select File</label>
                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                <input type="file" class="form-control" name="pesticide_license_photo" id="pesticide_license_photo" style="padding:0px;min-height: 28px;" onchange="$('#pesticide_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                            </div>
                                                            <p class=""><span id="pesticide_license_photo_text"></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <?php
                                                        if(file_exists($seller_data->pesticide_license_photo) && $seller_data->pesticide_license_photo!='')
                                                        {
                                                            ?>
                                                            <a class="btn btn-primary btn-rounded btn-md btn-block--" href="<?php echo base_url().$seller_data->pesticide_license_photo; ?>" target="_blank">View File</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                            I have Seeds License
                                            <select class='have_seeds_license_no float-lg-right' placeholder="Seeds License" id="have_seeds_license_no" name="have_seeds_license_no">
                                                <option value="0" <?php echo ($seller_data->have_seeds_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($seller_data->have_seeds_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
                                            </label>
                                        </div>
                                        <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                            <label>Seeds License Number*</label>
                                            <input type="text" class='form-control' placeholder="Seeds License Number" id="seeds_license_no" name="seeds_license_no" value="<?php echo $seller_data->seeds_license_no;?>"/>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                    <label>Expiry Date*</label>
                                                    <input type="date" class='form-control' placeholder="Expiry Date" id="seeds_lic_expiry_date" name="seeds_lic_expiry_date" value="<?php echo $seller_data->seeds_lic_expiry_date;?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                    <label>Upload Photo*</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="btn btn-primary btn-rounded btn-md btn-block--" for="seeds_license_photo">Select File</label>
                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                <input type="file" class="form-control" name="seeds_license_photo" id="seeds_license_photo" style="padding:0px;min-height: 28px;" onchange="$('#seeds_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                            </div>
                                                            <p class=""><span id="seeds_license_photo_text"></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <?php
                                                        if(file_exists($seller_data->seeds_license_photo) && $seller_data->seeds_license_photo!='')
                                                        {
                                                            ?>
                                                            <a class="btn btn-primary btn-rounded btn-md btn-block--" href="<?php echo base_url().$seller_data->seeds_license_photo; ?>" target="_blank">View File</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex mb-3 justify-content-center">
                                            <input type="hidden" name="is_finish" value="1" />
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="btn btn-primary btn-rounded btn-block--"><?php echo ($this->lang->line('submit')!='') ? $this->lang->line('submit') : 'Submit' ?></button>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group" id="error_box2" style="display: none;"></div>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php /* ?>
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
                <?php */ ?>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function (){
   $(".have_license_no").change(function (){
    
        var inputValue = $(this).val();//.attr("value");
        
        if(inputValue == 1)
        {
            jQuery(".fertilizer_license_div").show();
        }
        else if(inputValue == 0)
        {
            jQuery(".fertilizer_license_div").hide();
        }
   }); 

   $(".have_pesticide_license_no").change(function (){
    
        var inputValue = $(this).val();//.attr("value");
        
        if(inputValue == 1)
        {
            jQuery(".pesticide_license_div").show();
        }
        else if(inputValue == 0)
        {
            jQuery(".pesticide_license_div").hide();
        }
   }); 
    $(".have_seeds_license_no").change(function (){
    
        var inputValue = $(this).val();//.attr("value");
        
        if(inputValue == 1)
        {
            jQuery(".seeds_license_div").show();
        }
        else if(inputValue == 0)
        {
            jQuery(".seeds_license_div").hide();
        }
   }); 
});
</script>