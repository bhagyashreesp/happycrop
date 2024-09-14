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
            <div id="account-dashboard" class="col-md-9 col-12 ">
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
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/basic-profile/'.$is_seller) ?>" class="nav-link2 active">Basic Details</a>
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
                                <li class="nav-item">
                                    <a href="<?php echo base_url('my-account/business-card/'.$is_seller) ?>" class="nav-link2">Business Card</a>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-12" id="sign-in">
                            
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step3') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6"> 
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Contact Person Name *</label>
                                                        <input type="text" class='form-control' placeholder="Contact Person Name" id="username" name="username" value="<?php echo $user->username; ?>"/>
                                                    </div>
                                                </div>
                                                <?php /* ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Designation</label>
                                                        <input type="text" class='form-control' placeholder="Designation" id="designation" name="designation" value="<?php echo $user->designation; ?>"/>
                                                    </div>
                                                </div>
                                                <?php */ ?>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Mobile No. *</label>
                                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile number" value="<?php echo $user->mobile; ?>" readonly=""/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Alternate Mobile No.</label>
                                                        <input type="text" class="form-control" name="alternate_mobile" id="alternate_mobile" placeholder="Alternate Mobile number" value="<?php echo $user->alternate_mobile; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Email *</label>
                                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Alternate Email</label>
                                                        <input type="text" class="form-control" name="alternate_email" id="alternate_email" placeholder="Alternate Email" value="<?php echo $user->alternate_email; ?>"/>
                                                    </div>    
                                                </div>
                                            </div>
                                            <?php /* ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Landline No.</label>
                                                        <input type="text" class="form-control" name="landline" id="landline" placeholder="Landline No." value="<?php echo $user->landline; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Alternate Landline No.</label>
                                                        <input type="text" class="form-control" name="alternate_landline" id="alternate_landline" placeholder="Alternate Landline number" value="<?php echo $user->alternate_landline; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Old Password</label>
                                                        <input type="password" class="form-control" name="old" id="old" placeholder="Password" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <input type="password" class="form-control" name="new" id="new" placeholder="Password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Confirm New Password</label>
                                                        <input type="password" class="form-control" name="new_confirm" id="new_confirm" placeholder="Confirm Password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <?php */ ?>
                                        </div>
                     
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($user->profile_img) && $user->profile_img!='')
                                                    {
                                                        ?>
                                                        <img id="profile_img_prev" src="<?php echo base_url().$user->profile_img;?>" alt="" style="width: 150px;" /><?php /*   onclick="$('#profile_img').click();" */ ?>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="profile_img_prev" src="<?php echo base_url();?>uploads/user_avatar/default.jpg" alt="" style="width: 150px;" />  <?php /*   onclick="$('#profile_img').click();" */ ?>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="old_profile_img" value="<?php echo $user->profile_img; ?>" />
                                                    <input class="custom-file-input label-hide" id="profile_img" name="profile_img" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#profile_img').click();">Select File</a>
                                                    <button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-2">
                                            <div class="form-group">
                                                <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                                <button type="submit" class="submit_btn btn btn-primary btn-rounded btn-block--">Update</button>
                                                <button type="button" class="ed_btn btn btn-primary btn-rounded btn-block--">Edit</button>
                                                <button type="button" class="canc_btn btn btn-primary btn-rounded btn-block--">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
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
	function readURL(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("img_div").style.display = "block";
			 $('#profile_img_prev').attr('src', e.target.result);
			 $("#profile_img_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#profile_img_prev').attr('src', "#");
		 }
	}
</script>