<!-- breadcrumb -->
<style>.login-popup2 .form-group label {display: block;margin-bottom: 0.5rem;}</style>
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
            <div id="account-dashboard" class="col-md-9 col-12 row">
                <div class="login-popup2 col-md-12 bg-white p-1">
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
                        <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle btn-icon-left d-block d-lg-none mb-3"><i class="w-icon-hamburger"></i></a>
                        <div class="tab tab-with-title tab-nav-left tab-line-grow reg_tab">
                            <ul class="nav">
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
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/license-details/'.$is_seller) ?>" class="nav-link2 active">License</a>
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
                            <div class="col-md-10" id="sign-in">
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step7/') ?>' method="post">
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
                                        <label>Fertilizer License Number</label>
                                        <input type="text" class='form-control' placeholder="Fertilizer License Number" id="fertilizer_license_no" name="fertilizer_license_no" value="<?php echo $seller_data->fertilizer_license_no;?>"/>
                                    </div>
                                    <div class="row">
                                        <?php /* ?>
                                        <div class="col-md-6">
                                            <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                <label>Issue Date</label>
                                                <input type="date" class='form-control' placeholder="Issue Date" id="fert_lic_issue_date" name="fert_lic_issue_date" value="<?php echo $seller_data->fert_lic_issue_date;?>"/>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        <div class="col-md-4">
                                            <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                <label>Expiry Date</label>
                                                <input type="date" class='form-control' placeholder="Expiry Date" id="fert_lic_expiry_date" name="fert_lic_expiry_date" value="<?php echo $seller_data->fert_lic_expiry_date;?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                <label>Upload Photo*</label>
                                                <div class="row">
                                                    <div class="col-md-4 btn-browse">
                                                        <label class="btn btn-primary btn-rounded btn-md btn-block--" for="fertilizer_license_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="fertilizer_license_photo" id="fertilizer_license_photo" style="padding:0px;min-height: 28px;" onchange="$('#fertilizer_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="fertilizer_license_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-4">
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
                                    <hr />
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
                                        <label>Pesticide License Number</label>
                                        <input type="text" class='form-control' placeholder="Pesticide License Number" id="pesticide_license_no" name="pesticide_license_no" value="<?php echo $seller_data->pesticide_license_no;?>"/>
                                    </div>
                                    <div class="row">
                                        <?php /* ?>
                                        <div class="col-md-6">
                                            <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                <label>Issue Date</label>
                                                <input type="date" class='form-control' placeholder="Issue Date" id="pest_lic_issue_date" name="pest_lic_issue_date" value="<?php echo $seller_data->pest_lic_issue_date;?>"/>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        <div class="col-md-4">
                                            <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                <label>Expiry Date</label>
                                                <input type="date" class='form-control' placeholder="Expiry Date" id="pest_lic_expiry_date" name="pest_lic_expiry_date" value="<?php echo $seller_data->pest_lic_expiry_date;?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                <label>Upload Photo*</label>
                                                <div class="row">
                                                    <div class="col-md-4 btn-browse">
                                                        <label class="btn btn-primary btn-rounded btn-md btn-block--" for="pesticide_license_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="pesticide_license_photo" id="pesticide_license_photo" style="padding:0px;min-height: 28px;" onchange="$('#pesticide_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="pesticide_license_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-4">
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
                                    <hr />
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
                                        <label>Seeds License Number</label>
                                        <input type="text" class='form-control' placeholder="Seeds License Number" id="seeds_license_no" name="seeds_license_no" value="<?php echo $seller_data->seeds_license_no;?>"/>
                                    </div>
                                    <div class="row">
                                        <?php /* ?>
                                        <div class="col-md-6">
                                            <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                <label>Issue Date</label>
                                                <input type="date" class='form-control' placeholder="Issue Date" id="seeds_lic_issue_date" name="seeds_lic_issue_date" value="<?php echo $seller_data->seeds_lic_issue_date;?>"/>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        <div class="col-md-4">
                                            <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                <label>Expiry Date</label>
                                                <input type="date" class='form-control' placeholder="Expiry Date" id="seeds_lic_expiry_date" name="seeds_lic_expiry_date" value="<?php echo $seller_data->seeds_lic_expiry_date;?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                <label>Upload Photo*</label>
                                                <div class="row">
                                                    <div class="col-md-4 btn-browse">
                                                        <label class="btn btn-primary btn-rounded btn-md btn-block--" for="seeds_license_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="seeds_license_photo" id="seeds_license_photo" style="padding:0px;min-height: 28px;" onchange="$('#seeds_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="seeds_license_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-4">
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
                                    <div class="row">
                                        <div class="col-md-6 pb-lg-10 pt-lg-10">
                                            <label>O form / Principle certificate *</label>
                                            <label>Upload Photo *</label>
                                            <div class="clearfix"></div>
                                            <input type="hidden" name="old_o_form_photo" value="<?php echo $seller_data->o_form_photo; ?>" />
                                            <input class="custom-file-input" id="o_form_photo" name="o_form_photo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                            <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#o_form_photo').click();">Select File</a>
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
                                    <div class="form-group">
                                        <input type="hidden" name="is_finish" value="1" />
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
$sliders = get_sliders();
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
	function readURL(input) {
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