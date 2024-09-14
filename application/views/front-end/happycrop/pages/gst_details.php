<!-- breadcrumb -->
<style>
.login-popup2 .form-group label{display: block;margin-bottom: 0.5rem;}
#gst_no_photo_prev, #pan_no_photo_prev {max-height: 180px;object-fit: contain;}
</style>
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
    <div class="main-content container -fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div id="account-dashboard" class="col-md-9 col-12 ">
                <div class="login-popup2 col-md-12  bg-white p-1">
                    <div class="row">
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
                        <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle btn-icon-left d-block d-lg-none mb-3"><!--<i class="w-icon-hamburger"></i>-->Menu</a>
                        <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                            <ul class="nav prof-nav">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/basic-profile/'.$is_seller) ?>" class="nav-link2">Basic Details</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/business-details/'.$is_seller) ?>" class="nav-link2">Business Details</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/bank-details/'.$is_seller) ?>" class="nav-link2">Bank Details</a>
                                </li>
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/gst-details/'.$is_seller) ?>" class="nav-link2 active">GST</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/license-details/'.$is_seller) ?>" class="nav-link2">License</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/business-card/'.$is_seller) ?>" class="nav-link2">Business Card</a>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-8" id="sign-in">
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step6/') ?>' method="post">
                                    <div class="form-group label-hide">
                                        <label class="">
                                        <input type="radio" placeholder="I have GST Number" class="have_gst_no" name="have_gst_no" value="1" <?php echo ($seller_data->have_gst_no==1) ? 'checked="checked"' : '';?> data-target="1"/>
                                        I have GST Number
                                        </label>
                                    </div>
                                    <div class="form-group gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                        <label>GST Number</label>
                                        <input type="text" class='form-control' placeholder="GST Number" id="gst_no" name="gst_no" value="<?php echo $seller_data->gst_no; ?>"/>
                                    </div>
                                    
                                    <?php /* ?>
                                    <div class="row gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                        <div class="form-group col-md-8">
                                            <label>Upload Photo*</label>
                                            <div class="row">
                                                <div class="btn-browse col-md-6">
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
                                    
                                    <div class="row gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                        <div class="col-md-7 pb-lg-10 pt-lg-10">
                                            <label>Upload Photo</label>
                                            <div class="clearfix"></div>
                                            <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#gst_no_photo').click();">Select Photo</a>
                                            <label class="mb-2 mt-2">Or</label>
                                            <label>Upload PDF</label>
                                            <div class="clearfix"></div>
                                            <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-2" href="javascript:void(0);" onclick="$('#gst_no_pdf').click();">Select PDF</a>
                                            <?php
                                            if(file_exists($seller_data->gst_no_pdf) && $seller_data->gst_no_pdf!='')
                                            {
                                                ?>
                                                <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->gst_no_pdf;?>" target="_blank">View PDF</a>
                                                <?php
                                            }
                                            ?>
                                            
                                            <input type="hidden" name="old_gst_no_photo" value="<?php echo $seller_data->gst_no_photo; ?>" />
                                            <input class="custom-file-input label-hide" id="gst_no_photo" name="gst_no_photo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                            <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            
                                            <input type="hidden" name="old_gst_no_pdf" value="<?php echo $seller_data->gst_no_pdf; ?>" />
                                            <input class="custom-file-input label-hide" id="gst_no_pdf" name="gst_no_pdf" type="file" accept="application/pdf" />
                                        </div>
                                        <div class="col-md-5 pb-1 pt-1">
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
                                    
                                    <div class="form-group label-hide">
                                        <label>
                                        <input type="radio" placeholder="I dont have GST Number" class="have_gst_no" name="have_gst_no" value="2" <?php echo ($seller_data->have_gst_no==2) ? 'checked="checked"' : '';?> data-target="2"/>
                                        I dont have GST Number
                                        </label>
                                    </div>
                                    
                                    <div class="form-group non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                        <label>PAN Number </label>
                                        <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN Number" value="<?php echo $seller_data->pan_number; ?>"/>
                                    </div>
                                    
                                    <?php /* ?>
                                    <div class="row non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                        <div class="form-group col-md-6">
                                            <label>Upload Photo*</label>
                                            <div class="row">
                                                <div class="btn-browse col-md-6">
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
                                    
                                    <div class="row non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                        <div class="col-md-7 pb-lg-10 pt-lg-10">
                                            <label>Upload Photo</label>
                                            <div class="clearfix"></div>
                                            <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#pan_no_photo').click();">Select Photo</a>
                                            <label class="mb-2 mt-2">Or</label>
                                            <label>Upload PDF</label>
                                            <div class="clearfix"></div>
                                            <input type="hidden" name="old_pan_no_pdf" value="<?php echo $seller_data->pan_no_pdf; ?>" />
                                            <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#pan_no_pdf').click();">Select PDF</a>
                                            <?php
                                            if(file_exists($seller_data->pan_no_pdf) && $seller_data->pan_no_pdf!='')
                                            {
                                                ?>
                                                <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->pan_no_pdf;?>" target="_blank">View PDF</a>
                                                <?php
                                            }
                                            ?>
                                            
                                            <input type="hidden" name="old_pan_no_photo" value="<?php echo $seller_data->pan_no_photo; ?>" />
                                            <input class="custom-file-input label-hide" id="pan_no_photo" name="pan_no_photo" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                            <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            
                                            <input type="hidden" name="old_pan_no_pdf" value="<?php echo $seller_data->pan_no_pdf; ?>" />
                                            <input class="custom-file-input label-hide" id="pan_no_pdf" name="pan_no_pdf" type="file" accept="application/pdf" />
                                        </div>
                                        <div class="col-md-5 pb-1 pt-1">
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
                                    
                                    <div class="form-group">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                        <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                        <button type="submit" class="submit_btn btn btn-primary btn-rounded btn-block--">Update</button>
                                        <button type="button" class="ed_btn btn btn-primary btn-rounded btn-block--">Edit</button>
                                                <button type="button" class="canc_btn btn btn-primary btn-rounded btn-block--">Cancel</button>
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
<?php
$sliders = array();
//$sliders = get_sliders();
if($is_seller)
{
    $sliders = get_sliders('', '', '', 3);
}
else
{
    $sliders = get_sliders('', '', '', 2);
}
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