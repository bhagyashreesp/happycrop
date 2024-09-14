<style>.logo img {width: 150px;}.login-popup .nav-item .nav-link{font-size: 1.4rem;}label.error {display: inline;color: #ce0404;font-size: 12px;padding: 2px;margin-top: 2px;}</style>
<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="login-popup big border-radius-10 bg-white p-1">
                        <div class="p-4 pb-0">
                            <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                                <ul class="nav prof-nav">
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
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step7/') ?>' method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                    I have Fertilizer License
                                                    <select class='have_license_no float-lg-right' placeholder="Fertilizer License" id="have_fertilizer_license" name="have_fertilizer_license">
                                                        <option value="0" <?php echo ($seller_data->have_fertilizer_license==0) ? 'selected="selected"' : '';?>>No</option>
                                                        <option value="1" <?php echo ($seller_data->have_fertilizer_license==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                    </select>
                                                    </label>
                                                </div> 
                                            </div>
                                        </div>   
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                            <label>Fertilizer License Number*</label>
                                                            <input type="text" class='form-control' placeholder="Fertilizer License Number" id="fertilizer_license_no" name="fertilizer_license_no" value="<?php echo $seller_data->fertilizer_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date*</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="fert_lic_expiry_date" name="fert_lic_expiry_date" value="<?php echo $seller_data->fert_lic_expiry_date;?>"/>
                                                        </div>
                                                        <?php /* ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                                <label>Issue Date</label>
                                                                <input type="date" class='form-control' placeholder="Issue Date" id="fert_lic_issue_date" name="fert_lic_issue_date" value="<?php echo $seller_data->fert_lic_issue_date;?>"/>
                                                            </div>
                                                        </div>
                                                        <?php */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row fertilizer_license_div"  style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_fertilizer_license_photo" value="<?php echo $seller_data->fertilizer_license_photo; ?>" />
                                                        <input class="custom-file-input" id="fertilizer_license_photo" name="fertilizer_license_photo" type="file" onchange="readURL1(this);" accept="image/jpg, image/jpeg, image/png" />
                                                        <a style="margin-top: -25px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#fertilizer_license_photo').click();">Select Photo</a>
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    </div>
                                                    <div class="col-md-6 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($seller_data->fertilizer_license_photo) && $seller_data->fertilizer_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="fertilizer_license_photo_prev" src="<?php echo base_url().$seller_data->fertilizer_license_photo;?>" alt="" style="width: 200px;" />
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <img id="fertilizer_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                    I have Pesticide License
                                                    <select class='have_pesticide_license_no float-lg-right' placeholder="Pesticide License" id="have_pesticide_license_no" name="have_pesticide_license_no">
                                                        <option value="0" <?php echo ($seller_data->have_pesticide_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                        <option value="1" <?php echo ($seller_data->have_pesticide_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                    </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Pesticide License Number*</label>
                                                            <input type="text" class='form-control' placeholder="Pesticide License Number" id="pesticide_license_no" name="pesticide_license_no" value="<?php echo $seller_data->pesticide_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date*</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="pest_lic_expiry_date" name="pest_lic_expiry_date" value="<?php echo $seller_data->pest_lic_expiry_date;?>"/>
                                                        </div>
                                                        <?php /* ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                                <label>Issue Date</label>
                                                                <input type="date" class='form-control' placeholder="Issue Date" id="pest_lic_issue_date" name="pest_lic_issue_date" value="<?php echo $seller_data->pest_lic_issue_date;?>"/>
                                                            </div>
                                                        </div>
                                                        <?php */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_pesticide_license_photo" value="<?php echo $seller_data->pesticide_license_photo; ?>" />
                                                        <input class="custom-file-input" id="pesticide_license_photo" name="pesticide_license_photo" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                        <a style="margin-top: -25px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#pesticide_license_photo').click();">Select Photo</a>
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    </div>
                                                    <div class="col-md-6 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($seller_data->pesticide_license_photo) && $seller_data->pesticide_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="pesticide_license_photo_prev" src="<?php echo base_url().$seller_data->pesticide_license_photo;?>" alt="" style="width: 200px;" />
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <img id="pesticide_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                    I have Seeds License
                                                    <select class='have_seeds_license_no float-lg-right' placeholder="Seeds License" id="have_seeds_license_no" name="have_seeds_license_no">
                                                        <option value="0" <?php echo ($seller_data->have_seeds_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                        <option value="1" <?php echo ($seller_data->have_seeds_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                    </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Seeds License Number*</label>
                                                            <input type="text" class='form-control' placeholder="Seeds License Number" id="seeds_license_no" name="seeds_license_no" value="<?php echo $seller_data->seeds_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php /* ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                                <label>Issue Date</label>
                                                                <input type="date" class='form-control' placeholder="Issue Date" id="seeds_lic_issue_date" name="seeds_lic_issue_date" value="<?php echo $seller_data->seeds_lic_issue_date;?>"/>
                                                            </div>
                                                        </div>
                                                        <?php */ ?>
                                                        <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date*</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="seeds_lic_expiry_date" name="seeds_lic_expiry_date" value="<?php echo $seller_data->seeds_lic_expiry_date;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_seeds_license_photo" value="<?php echo $seller_data->seeds_license_photo; ?>" />
                                                        <input class="custom-file-input" id="seeds_license_photo" name="seeds_license_photo" type="file" onchange="readURL3(this);" accept="image/jpg, image/jpeg, image/png" />
                                                        <a style="margin-top: -25px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#seeds_license_photo').click();">Select Photo</a>
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    </div>
                                                    <div class="col-md-6 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($seller_data->seeds_license_photo) && $seller_data->seeds_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="seeds_license_photo_prev" src="<?php echo base_url().$seller_data->seeds_license_photo;?>" alt="" style="width: 200px;" />
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <img id="seeds_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php if($is_seller) { ?> 
                                        <div class="row">
                                            <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                <label>O form / Principle certificate *</label>
                                                <label>Upload Photo *</label>
                                                <div class="clearfix"></div>
                                                <input type="hidden" name="old_o_form_photo" value="<?php echo $seller_data->o_form_photo; ?>" />
                                                <input class="custom-file-input" id="o_form_photo" name="o_form_photo" type="file" onchange="readURL4(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#o_form_photo').click();" style="display: inline-block;">Select File</a>
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            </div>
                                            <div class="col-md-4 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->o_form_photo) && $seller_data->o_form_photo!='')
                                                    {
                                                        ?>
                                                        <img id="o_form_photo_prev" src="<?php echo base_url().$seller_data->o_form_photo;?>" alt="" style="width: 150px;" onclick="$('#o_form_photo').click();" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="o_form_photo_prev" src="<?php echo base_url();?>assets/o-form.png" alt="" style="width: 150px;" onclick="$('#o_form_photo').click();" />  
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
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
                                    <?php if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2 && $seller_data->is_finish == 1) { ?>
                                    <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                                        Your account is under approval process. After approval, you can add products.
                                    </div>
                                    <?php } else if($this->ion_auth->member_status() == 2 && $seller_data->is_finish == 1) { ?>
                                    <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                                        Your account is under approval process. After approval, you can browse products.
                                    </div>
                                    <?php } ?>
                                    <script src="<?php echo base_url();?>assets/front_end/happycrop/js/jquery.validate.min.js"></script>
                                    <script src="<?php echo base_url();?>assets/front_end/happycrop/js/additional-methods.min.js"></script>
                                    <script>
                                    $(document).ready (function () {  
                                        $("#basic-details-form").validate({
                                            rules: {
                                                fertilizer_license_no:{
                                                    required: function (){
                                                        return $("#have_fertilizer_license").val() == 1
                                                    },
                                                    minlength: 10
                                                },
                                                fert_lic_expiry_date:{
                                                    required: function (){
                                                        return $("#have_fertilizer_license").val() == 1
                                                    }
                                                },
                                                pesticide_license_no:{
                                                    required: function (){
                                                        return $("#have_pesticide_license_no").val() == 1
                                                    },
                                                    minlength: 10
                                                },
                                                pest_lic_expiry_date:{
                                                    required: function (){
                                                        return $("#have_pesticide_license_no").val() == 1
                                                    }
                                                },
                                                seeds_license_no:{
                                                    required: function (){
                                                        return $("#have_seeds_license_no").val() == 1
                                                    },
                                                    minlength: 10
                                                },
                                                seeds_lic_expiry_date:{
                                                    required: function (){
                                                        return $("#have_seeds_license_no").val() == 1
                                                    }
                                                }
                                            },
                                            messages: {
                                                fertilizer_license_no: {
                                                    required: 'Please enter Fertilizer License Number.',
                                                    minlength: 'Please enter valid Fertilizer License Number.'
                                                },
                                                fert_lic_expiry_date: {
                                                    required: 'Please enter Fertilizer License Expiry Date.',
                                                },
                                                pesticide_license_no: {
                                                    required: 'Please enter Pesticide License Number.',
                                                    minlength: 'Please enter valid Pesticide License Number.'
                                                },
                                                pest_lic_expiry_date: {
                                                    required: 'Please enter Pesticide License Expiry Date.',
                                                },
                                                seeds_license_no: {
                                                    required: 'Please enter Seeds License Number.',
                                                    minlength: 'Please enter valid Seeds License Number.'
                                                },
                                                seeds_lic_expiry_date: {
                                                    required: 'Please enter Seeds License Expiry Date.',
                                                }
                                            }
                                        });
                                    });
                                    </script>
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
<?php
$sliders = array();
//$sliders = get_sliders();
/*if($is_seller)
{
    $sliders = get_sliders('', '', '', 3);
}
else
{
    $sliders = get_sliders('', '', '', 2);
}*/
$sliders = get_sliders('', '', '', 1);
?>
<section class="intro-section">
    <div class="swiper-container banner-swiper swiper-theme nav-inner pg-inner swiper-nav-lg animation-slider pg-xxl-hide nav-xxl-show nav-hide">
        <div class="swiper-wrapper">
            <?php if (isset($sliders) && !empty($sliders)) { ?>
                <?php foreach ($sliders as $row) { ?>
                    <div class="swiper-slide center-swiper-slide">
                        <a href="<?= $row['link'] ?>">
                            <img src="<?= base_url($row['image']) ?>">
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="swiper-pagination"></div>
        <button class="swiper-button-next"></button>
        <button class="swiper-button-prev"></button>
    </div>
    <!-- End of .swiper-container -->
</section>
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

function readURL1(input) {
    if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		 reader.onload = function (e) {
		 document.getElementById("img_div").style.display = "block";
		 $('#fertilizer_license_photo_prev').attr('src', e.target.result);
		 $("#fertilizer_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
		 };
		 reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#fertilizer_license_photo_prev').attr('src', "#");
	 }
}

function readURL2(input) {
 if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		 reader.onload = function (e) {
		 document.getElementById("img_div").style.display = "block";
		 $('#pesticide_license_photo_prev').attr('src', e.target.result);
		 $("#pesticide_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
		 };
		 reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#pesticide_license_photo_prev').attr('src', "#");
	 }
}

function readURL3(input) {
 if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		 reader.onload = function (e) {
		 document.getElementById("img_div").style.display = "block";
		 $('#seeds_license_photo_prev').attr('src', e.target.result);
		 $("#seeds_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
		 };
		 reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#seeds_license_photo_prev').attr('src', "#");
	 }
}

function readURL4(input) {
 if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		 reader.onload = function (e) {
		 document.getElementById("img_div").style.display = "block";
		 $('#o_form_photo_prev').attr('src', e.target.result);
		 $("#o_form_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
		 };
		 reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#o_form_photo_prev').attr('src', "#");
	 }
}
</script>