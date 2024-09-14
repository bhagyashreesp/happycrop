<style>#cancelled_cheque_prev{max-height: 180px;object-fit: contain;}</style>
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
                                <li class="nav-item active">
                                    <a href="<?php echo base_url('my-account/bank-details/'.$is_seller) ?>" class="nav-link2 active">Bank Details</a>
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
                            <div class="col-md-12">
                                <div class="alert- alert-icon- alert-warning-- alert-bg- alert-inline- text-primary pb-3 show-code-action text-uppercase">
                                    Bank account should be in the name of registered business name or trade name as per GSTIN
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="sign-in">
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step5/') ?>' method="post">
                                    <div class="form-group">
                                        <label>Account Holder Name *</label>
                                        <input type="text" class='form-control' placeholder="Account Holder Name" id="account_name" name="account_name" value="<?php echo $seller_data->account_name;?>" autocomplete="off" />
                                    </div>
                                    <div class="form-group">
                                        <label>Account Number *</label>
                                        <input type="password" class='form-control' placeholder="Account Number" id="account_number" name="account_number" value="<?php echo $seller_data->account_number;?>" onpaste="return false;" ondrop="return false;" autocomplete="off"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Account Number * </label>
                                        <input type="text" class="form-control" name="confirm_account_number" id="confirm_account_number" placeholder="Confirm Account Number" value="<?php echo $seller_data->account_number;?>" onpaste="return false;" ondrop="return false;" autocomplete="off"/>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>IFSC Code * </label>
                                            <input type="text" class="form-control" name="bank_code" id="bank_code" placeholder="IFSC Code" value="<?php echo $seller_data->bank_code;?>"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Account Type *</label>
                                            <!--<input type="text" class="form-control" name="account_type" id="account_type" placeholder="Account Type" value="<?php echo $seller_data->account_type;?>"/>-->
                                            <select class="form-control" name="account_type" id="account_type" placeholder="Account Type">
                                                <option value="">Select</option>
                                                <option value="current" <?php echo ($seller_data->account_type=='current') ? 'selected="selected"' : '';?>>Current</option>
                                                <option value="saving" <?php echo ($seller_data->account_type=='saving') ? 'selected="selected"' : '';?>>Saving</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Bank Name * </label>
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
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Upload cancelled cheque*</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="text-center">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($seller_data->cancelled_cheque) && $seller_data->cancelled_cheque!='')
                                                            {
                                                                ?>
                                                                <img id="cancelled_cheque_prev" src="<?php echo base_url().$seller_data->cancelled_cheque;?>" alt="" style="width: 100%;" /><?php /*   onclick="$('#cancelled_cheque').click();" */ ?>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <img id="cancelled_cheque_prev" src="<?php echo base_url();?>assets/cancelled_cheque.png" alt="" style="width: 100%;" /><?php /*   onclick="$('#cancelled_cheque').click();" */ ?>  
                                                                <?php
                                                            }
                                                            ?>
                                                            <div class="clearfix"></div>
                                                            <input type="hidden" name="old_cancelled_cheque" value="<?php echo $seller_data->cancelled_cheque; ?>" />
                                                            <input class="custom-file-input label-hide" id="cancelled_cheque" name="cancelled_cheque" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                            <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#cancelled_cheque').click();">Select File</a>
                                                            <button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php /* ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="btn btn-primary btn-rounded btn-sm btn-block--" for="cancelled_cheque">Select File</label>
                                                    <div class="custom-file-input" style="margin-top: -30px;">
                                                        <input type="file" class="form-control" name="cancelled_cheque" id="cancelled_cheque" style="padding:0px;min-height: 28px;" onchange="$('#cancelled_cheque_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                    </div>
                                                    <p class=""><span id="cancelled_cheque_text"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                <?php
                                                if(file_exists($seller_data->cancelled_cheque) && $seller_data->cancelled_cheque!='')
                                                {
                                                    ?>
                                                    <a class="btn btn-primary btn-rounded btn-sm btn-block--" href="<?php echo base_url().$seller_data->cancelled_cheque; ?>" target="_blank">View File</a>
                                                    <?php
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <?php */ ?>
                                            
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
<!-- End of .intro-section -->
<script>
	function readURL(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("img_div").style.display = "block";
			 $('#cancelled_cheque_prev').attr('src', e.target.result);
			 $("#cancelled_cheque_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#cancelled_cheque_prev').attr('src', "#");
		 }
	}
</script>