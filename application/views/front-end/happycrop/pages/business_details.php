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
                            
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step4') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Firm Name *</label>
                                                        <input type="text" class='form-control' placeholder="Firm Name" id="company_name" name="company_name" value="<?php echo $seller_data->company_name;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Year of Establishment *</label>
                                                        <input type="text" class='form-control' placeholder="Year of Establishment" id="year_establish" name="year_establish" value="<?php echo $seller_data->year_establish;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Proprietor Name</label>
                                                        <input type="text" class='form-control' placeholder="Proprietor Name" id="owner_name" name="owner_name" value="<?php echo $seller_data->owner_name;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Website URL</label>
                                                        <input type="text" class='form-control' placeholder="Website URL" id="website_url" name="website_url" value="<?php echo $seller_data->website_url;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Google Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Google Business URL" id="google_business_url" name="google_business_url" value="<?php echo $seller_data->google_business_url;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Facebook Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Facebook Business URL" id="facebook_business_url" name="facebook_business_url" value="<?php echo $seller_data->facebook_business_url;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Instagram Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Instagram Business URL" id="instagram_business_url" name="instagram_business_url" value="<?php echo $seller_data->instagram_business_url;?>"/>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <hr />
                                            <h3>Sales office address</h3>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Shop Name</label>
                                                        <input type="text" class="form-control" name="shop_name" id="shop_name" placeholder="Shop Name" value="<?php echo $seller_data->shop_name;?>"/>
                                                    </div>    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Plot No. / Floor / Building  </label>
                                                        <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $seller_data->plot_no;?>"/>
                                                    </div>    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Street / Locality / Village</label>
                                                        <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality / Village" value="<?php echo $seller_data->street_locality;?>"/>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Land Mark</label>
                                                        <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $seller_data->landmark;?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <select class="form-control" name="state_id" id="state_id" placeholder="State" onchange="getCities(this.value, 'city_id');">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $states = $this->db->query("SELECT * FROM `states` ORDER BY name ASC")->result_array();
                                                            if($states)
                                                            {
                                                                foreach($states as $state)
                                                                {
                                                                    $selected = "";
                                                                    if($seller_data->state_id == $state['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($state['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>District</label>
                                                        <select class="form-control" name="city_id" id="city_id" placeholder="District">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if($seller_data->state_id)
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` WHERE `state_id` = '".$seller_data->state_id."' ORDER BY name ASC")->result_array();
                                                            }
                                                            else
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` ORDER BY name ASC")->result_array();   
                                                            }
                                                            if($cities)
                                                            {
                                                                foreach($cities as $city)
                                                                {
                                                                    $selected = "";
                                                                    if($seller_data->city_id == $city['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($city['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Pin  </label>
                                                        <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" value="<?php echo $seller_data->pin;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <h3>Storage address</h3>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Shop Name</label>
                                                        <input type="text" class="form-control" name="storage_shop_name" id="storage_shop_name" placeholder="Shop Name" value="<?php echo $seller_data->storage_shop_name;?>"/>
                                                    </div>    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Plot No. / Floor / Building  </label>
                                                        <input type="text" class="form-control" name="storage_plot_no" id="storage_plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $seller_data->storage_plot_no;?>"/>
                                                    </div>    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Street / Locality / Village</label>
                                                        <input type="text" class="form-control" name="storage_street_locality" id="storage_street_locality" placeholder="Street / Locality" value="<?php echo $seller_data->storage_street_locality;?>"/>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Land Mark</label>
                                                        <input type="text" class="form-control" name="storage_landmark" id="storage_landmark" placeholder="Land Mark" value="<?php echo $seller_data->storage_landmark;?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <select class="form-control" name="storage_state_id" id="storage_state_id" placeholder="State" onchange="getCities(this.value, 'storage_city_id');">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $states = $this->db->query("SELECT * FROM `states` ORDER BY name ASC")->result_array();
                                                            if($states)
                                                            {
                                                                foreach($states as $state)
                                                                {
                                                                    $selected = "";
                                                                    if($seller_data->storage_state_id == $state['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($state['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>District</label>
                                                        <select class="form-control" name="storage_city_id" id="storage_city_id" placeholder="District">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if($seller_data->storage_state_id)
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` WHERE `state_id` = '".$seller_data->storage_state_id."' ORDER BY name ASC")->result_array();
                                                            }
                                                            else
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` ORDER BY name ASC")->result_array();   
                                                            }
                                                            if($cities)
                                                            {
                                                                foreach($cities as $city)
                                                                {
                                                                    $selected = "";
                                                                    if($seller_data->storage_city_id == $city['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($city['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Pin  </label>
                                                        <input type="text" class="form-control" name="storage_pin" id="storage_pin" placeholder="Pin" value="<?php echo $seller_data->storage_pin;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a class="float-right btn btn-primary btn-rounded mb-2" href="<?php echo base_url('my-account/manage-storage-address');?>">Add Address</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->logo) && $seller_data->logo!='')
                                                    {
                                                        ?>
                                                        <img id="logo_prev" src="<?php echo base_url().$seller_data->logo;?>" alt="" style="width: 150px;" /><?php /*   onclick="$('#logo').click();" */ ?>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="logo_prev" src="<?php echo base_url();?>uploads/retailer_logo/default.jpg" alt="" style="width: 150px;" /> <?php /*   onclick="$('#logo').click();" */ ?> 
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input class="p-0" type="hidden" name="old_logo" value="<?php echo $seller_data->logo; ?>" />
                                                    <input class="custom-file-input p-0" id="logo" name="logo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-5" href="javascript:void(0);" onclick="$('#logo').click();">Select File</a>
                                                    <button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-5">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
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
	function readURL(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("img_div").style.display = "block";
			 $('#logo_prev').attr('src', e.target.result);
			 $("#logo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#logo_prev').attr('src', "#");
		 }
	}
    function getCities(state_id, city_div)
    {
        $.ajax({
            url: "<?=base_url()?>my_account/getCities",
            data:{state_id:state_id},
            method:"Post",
            cache: false
        }).done(function( html ) {
            $( "#"+city_div ).html( html );
        });
    }
</script>