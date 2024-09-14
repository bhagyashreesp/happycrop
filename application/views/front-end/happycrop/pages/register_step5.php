<style>#cancelled_cheque_prev{max-height: 180px;object-fit: contain;}.logo img {width: 150px;}.login-popup .nav-item .nav-link{font-size: 1.4rem;}label.error {display: inline;color: #ce0404;font-size: 12px;padding: 2px;margin-top: 2px;}</style>
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
                        <div class="p-4 pt-1">
                            <div class="row">
                                <div class="col-md-12" id="sign-in">
                                    <div class="alert- alert-icon- alert-warning- alert-bg- alert-inline- show-code-action- text-warning font-weight-bold">
                                        Bank account should be in the name of registered business name or trade name as per GSTIN
                                    </div>
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step5/') ?>' method="post">
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
                                                <label>IFSC Code *</label>
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
                                            <label>Bank Name *</label>
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
                                                                <input class="custom-file-input" id="cancelled_cheque" name="cancelled_cheque" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" value="<?php echo $seller_data->cancelled_cheque; ?>" />
                                                                <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#cancelled_cheque').click();" style="display: inline-block;">Select File</a>
                                                                <button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3" style="display: inline-block;">Upload</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /* ?>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Upload cancelled cheque*</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary btn-rounded btn-md btn-block--" for="cancelled_cheque">Select File</label>
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
                                                        <a class="btn btn-primary btn-rounded btn-md btn-block--" href="<?php echo base_url().$seller_data->cancelled_cheque; ?>" target="_blank">View File</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
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
                                                account_name: {
                                                    required: true
                                                },
                                                account_number: {
                                                    required: true
                                                },
                                                confirm_account_number: {
                                                    required: true,
                                                    equalTo: "#account_number"
                                                },
                                                bank_code: {
                                                    required: true
                                                },
                                                account_type: {
                                                    required: true
                                                },
                                                bank_name: {
                                                    required: true
                                                }/*,
                                                cancelled_cheque: {
                                                    required: true
                                                }*/
                                            },
                                            messages: {
                                                account_name: {
                                                    required: 'Please Enter Account Holder Name.'
                                                },
                                                account_number: {
                                                    required: 'Please Enter Account Number'
                                                },
                                                confirm_account_number: {
                                                    required: 'Please Enter Confirm Account Number.',
                                                    equalTo: 'Account Number & Confirm Account Number must be same.'
                                                },
                                                bank_code: {
                                                    required: 'Please Enter IFSC Code'
                                                },
                                                account_type: {
                                                    required: 'Please Enter Account Type'
                                                },
                                                bank_name: {
                                                    required: 'Please Enter Bank Name'
                                                }/*,
                                                cancelled_cheque: {
                                                    required: 'Please Upload Cancelled Cheque'
                                                }*/
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
		else 
        {
			$('#cancelled_cheque_prev').attr('src', "#");
		}
	}
</script>