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
    <div class="main-content container -fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div id="account-dashboard" class="col-md-9 col-12">
                <div class="login-popup2 col-md-12 bg-white p-1">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2 && $seller_data->is_finish == 1) { ?>
                        <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                            Your account is under approval process. After approval, you can add products.
                        </div>
                        <?php } else if($this->ion_auth->member_status() == 2 && $seller_data->is_finish == 1) { ?>
                        <div class="alert alert-icon alert-success text-white alert-bg alert-inline show-code-action">
                            Your account is under approval process. After approval, you can browse products.
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
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/gst-details/'.$is_seller) ?>" class="nav-link2">GST</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/license-details/'.$is_seller) ?>" class="nav-link2">License</a>
                                </li>
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/business-card/'.$is_seller) ?>" class="nav-link2 active">Business Card</a>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-12" id="sign-in">
                            
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step8') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <label>Front View</label>
                                                <div id="front_img_div">
                                                    <?php
                                                    if(file_exists($seller_data->business_card_front) && $seller_data->business_card_front!='')
                                                    {
                                                        ?>
                                                        <img id="business_card_front_prev" src="<?php echo base_url().$seller_data->business_card_front;?>" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_front').click();" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="business_card_front_prev" src="<?php echo base_url();?>uploads/retailer_business_card_front/default.jpg" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_front').click();" />  
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="old_business_card_front" value="<?php echo $seller_data->business_card_front; ?>" />
                                                    <input class="custom-file-input" id="business_card_front" name="business_card_front" type="file" onchange="readURL1(this);" accept="image/jpg, image/jpeg, image/png" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <label>Back View</label>
                                                <div id="back_img_div">
                                                    <?php
                                                    if(file_exists($seller_data->business_card_back) && $seller_data->business_card_back!='')
                                                    {
                                                        ?>
                                                        <img id="business_card_back_prev" src="<?php echo base_url().$seller_data->business_card_back;?>" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_back').click();" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="business_card_back_prev" src="<?php echo base_url();?>uploads/retailer_business_card_back/default.jpg" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_back').click();" />  
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="old_business_card_back" value="<?php echo $seller_data->business_card_back; ?>" />
                                                    <input class="custom-file-input" id="business_card_back" name="business_card_back" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                                <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                                <button type="submit" class="submit_btn btn btn-primary btn-rounded btn-block--">Update</button>
                                                <button type="button" class="ed_btn btn btn-primary btn-rounded btn-block--">Edit</button>
                                                <button type="button" class="canc_btn btn btn-primary btn-rounded btn-block--">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-center">
                                                <div class="form-group" id="error_box2"></div>
                                            </div>
                                        </div>
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
<script>
    function readURL1(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("front_img_div").style.display = "block";
			 $('#business_card_front_prev').attr('src', e.target.result);
			 $("#business_card_front_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#business_card_front_prev').attr('src', "#");
		 }
	}
	function readURL2(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("back_img_div").style.display = "block";
			 $('#business_card_back_prev').attr('src', e.target.result);
			 $("#business_card_back_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#business_card_back_prev').attr('src', "#");
		 }
	}
</script>