<style>.logo img {width: 150px;}.login-popup .nav-item .nav-link{font-size: 1.4rem;}label.error {display: inline;color: #ce0404;font-size: 12px;padding: 2px;margin-top: 2px;}#gst_no_photo_prev, #pan_no_photo_prev {max-height: 180px;object-fit: contain;}</style>
<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
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
                                    <li class="nav-item active">
                                        <a href="<?php echo base_url('register/step6/'.$is_seller) ?>" class="nav-link2 active">GST</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step7/'.$is_seller) ?>" class="nav-link2">License</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-4 pt-1">
                            <div class="row">
                                <div class="col-md-12" id="sign-in">
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step6/') ?>' method="post">
                                        <div class="form-group mb-2">
                                            <label class="radio">
                                            
                                            <input type="radio" placeholder="I have GST Number" class="have_gst_no" name="have_gst_no" value="1" <?php echo ($seller_data->have_gst_no==1) ? 'checked="checked"' : '';?> data-target="1"/>
                                            I have GST Number
                                            
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-2 gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                                <label>GST Number*</label>
                                                <input type="text" class='form-control' placeholder="GST Number" id="gst_no" name="gst_no" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" value="<?php echo $seller_data->gst_no; ?>"/>
                                            </div>
                                        </div>
                                        
                                        <div class="row gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                            <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                <label>Upload Photo *</label>
                                                <div class="clearfix"></div>
                                                <input type="hidden" name="old_gst_no_photo" value="<?php echo $seller_data->gst_no_photo; ?>" />
                                                <input class="custom-file-input" id="gst_no_photo" name="gst_no_photo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <a style="margin-top: -25px;display: inline-block;" class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#gst_no_photo').click();">Select Photo</a>
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            </div>
                                            <div class="col-md-6 mb-2 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->gst_no_photo) && $seller_data->gst_no_photo!='')
                                                    {
                                                        ?>
                                                        <img id="gst_no_photo_prev" src="<?php echo base_url().$seller_data->gst_no_photo;?>" alt="" style="width: 200px;" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="gst_no_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <?php /* ?>
                                        <div class="row gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                            <div class="form-group col-md-6">
                                                <label>Upload Photo*</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary btn-rounded btn-sm btn-block--" for="gst_no_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="gst_no_photo" id="gst_no_photo" style="padding:0px;min-height: 28px;" onchange="$('#gst_no_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="gst_no_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <?php
                                                    if(file_exists($seller_data->gst_no_photo) && $seller_data->gst_no_photo!='')
                                                    {
                                                        ?>
                                                        <a class="btn btn-primary btn-rounded btn-sm btn-block--" href="<?php echo base_url().$seller_data->gst_no_photo; ?>" target="_blank">View File</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
                                        
                                        <div class="form-group mb-2">
                                            <label>
                                            <input type="radio" placeholder="I dont have GST Number" class="have_gst_no" name="have_gst_no" value="2" <?php echo ($seller_data->have_gst_no==2) ? 'checked="checked"' : '';?> data-target="2"/>
                                            I dont have GST Number
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-2 non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                                <label>PAN Number*</label>
                                                <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN Number" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" value="<?php echo $seller_data->pan_number; ?>"/>
                                            </div>
                                        </div>
                                        
                                        <div class="row non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                            <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                <label>Upload Photo *</label>
                                                <div class="clearfix"></div>
                                                <input type="hidden" name="old_pan_no_photo" value="<?php echo $seller_data->pan_no_photo; ?>" />
                                                <input class="custom-file-input" id="pan_no_photo" name="pan_no_photo" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <a style="margin-top: -25px;display: inline-block;" class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#pan_no_photo').click();">Select Photo</a>
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            </div>
                                            <div class="col-md-6 mb-3 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->pan_no_photo) && $seller_data->pan_no_photo!='')
                                                    {
                                                        ?>
                                                        <img id="pan_no_photo_prev" src="<?php echo base_url().$seller_data->pan_no_photo;?>" alt="" style="width: 200px;" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="pan_no_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php /* ?>
                                        <div class="row non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                            <div class="form-group col-md-6">
                                                <label>Upload Photo*</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary btn-rounded btn-sm btn-block--" for="pan_no_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="pan_no_photo" id="pan_no_photo" style="padding:0px;min-height: 28px;" onchange="$('#pan_no_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="pan_no_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <?php
                                                    if(file_exists($seller_data->pan_no_photo) && $seller_data->pan_no_photo!='')
                                                    {
                                                        ?>
                                                        <a class="btn btn-primary btn-rounded btn-sm btn-block--" href="<?php echo base_url().$seller_data->pan_no_photo; ?>" target="_blank">View File</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
                                        <label id="have_gst_no-error" class="error" for="have_gst_no"></label>
                                        
                                        <div class="form-group d-flex mb-3 justify-content-center">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="btn btn-primary btn-rounded btn-block--"><?php echo ($this->lang->line('next')!='') ? $this->lang->line('next') : 'Next' ?></button>
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
                                                have_gst_no: {
                                                    required: true
                                                },
                                                gst_no:{
                                                    required: function (){
                                                        return $("input[name='have_gst_no']:checked").val() == 1
                                                    }
                                                },
                                                pan_number:{
                                                    required: function (){
                                                        return $("input[name='have_gst_no']:checked").val() == 2
                                                    }
                                                }
                                            },
                                            messages: {
                                                have_gst_no: {
                                                    required: 'Please select between have GST number or dont have GST number.'
                                                },
                                                gst_no: {
                                                    required: 'Please enter GST number.',
                                                    pattern: 'Please enter valid GST number.'
                                                },
                                                pan_number: {
                                                    required: 'Please enter PAN number.',
                                                    pattern: 'Please enter valid PAN number.'
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
<!-- End of .intro-section -->
<script type="text/javascript">
jQuery(document).ready(function (){
   $(".have_gst_no").click(function (){
    
        var inputValue = $(this).attr("value");
        
        if(inputValue == 1)
        {
            jQuery(".gst_div").show();
            jQuery(".non_gst_div").hide();
        }
        else if(inputValue == 2)
        {
            jQuery(".gst_div").hide();
            jQuery(".non_gst_div").show();
        }
   }); 
});
function readURL(input) {
 if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		 reader.onload = function (e) {
		 document.getElementById("img_div").style.display = "block";
		 $('#gst_no_photo_prev').attr('src', e.target.result);
		 $("#gst_no_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
		 };
		 reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#gst_no_photo_prev').attr('src', "#");
	 }
}

function readURL2(input) {
 if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		 reader.onload = function (e) {
		 document.getElementById("img_div").style.display = "block";
		 $('#pan_no_photo_prev').attr('src', e.target.result);
		 $("#pan_no_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
		 };
		 reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#pan_no_photo_prev').attr('src', "#");
	 }
}
</script>