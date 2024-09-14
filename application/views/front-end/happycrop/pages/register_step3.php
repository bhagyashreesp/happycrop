<style>.logo img {width: 170px;}.login-popup .nav.nav-tabs .nav-item{width: 60%;}label.error {display: inline;color: #ce0404;font-size: 12px;padding: 2px;margin-top: 2px;}</style>
<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-popup border-radius-10 bg-white mb-3">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#sign-in" class="nav-link border-none active"><?php echo ($is_seller) ? 'Businesses' : 'Shop Owner' ; ?> Sign Up</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active pb-0" id="sign-in">
                                    <form id="sign-up2-form" class="sign-up2-form form-submit-event2" action='<?php echo ($is_seller) ? base_url('seller/auth/create_custom_seller')  : base_url('auth/create_custom_user') ?>' method="post">
                                        <div class="form-group">
                                            <label>Your Name *</label>
                                            <input type="text" class='form-control' placeholder="Your Name" id="name" name="name" pattern="^[a-zA-Z ]*$"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile No. *</label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile No."  pattern="^[6-9]\d{9}$"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" pattern="^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2}$"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password *</label>
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" />
                                        </div>
                                        <style>.w-7{width: 7%;}.w-93{width: 93%;}</style>
                                        <div class="form-group mb-0 clearfix">
                                            <label>
                                                <span class="float-right d-block w-93">I have read and agreed to <a href="<?php echo base_url('terms-of-use'); ?>" target="_blank" title="Terms and Conditions">Terms and Conditions</a> and <a href="<?php echo base_url('privacy-policy'); ?>" target="_blank" title="Privacy Policies">Privacy Policies</a></span>
                                                <input class="float-left mt-1 d-block w-7" type="checkbox" id="agree_terms_privacy" name="agree_terms_privacy" value="1" /> 
                                            </label>
                                        </div>
                                        <br />
                                        <div class="text-center d-flex  justify-content-center">
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <button type="submit" class="submit_btn text-center btn btn-primary btn-rounded btn-block--">Next<?php //echo ($this->lang->line('register')!='') ? $this->lang->line('register') : 'Register' ?></button>
                                        </div>
                                        
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group mt-3 mb-2" id="error_box2" style="display: none;"></div>
                                        </div>
                                    </form>
                                    <script src="<?php echo base_url();?>assets/front_end/happycrop/js/jquery.validate.min.js"></script>
                                    <script src="<?php echo base_url();?>assets/front_end/happycrop/js/additional-methods.min.js"></script>
                                    <script>
                                    $(document).ready (function () {  
                                        $("#sign-up2-form").validate({
                                            rules: {
                                                name: {
                                                    required: true
                                                },
                                                mobile: {
                                                    required: true
                                                },
                                                email: {
                                                    required: true,
                                                    email: true
                                                },
                                                password: {
                                                    required: true,
                                                    minlength: 6
                                                },
                                                confirm_password: {
                                                    required: true,
                                                    minlength: 6,
                                                    equalTo: "#password"
                                                },
                                                agree_terms_privacy:{
                                                    required: true
                                                }
                                            },
                                            messages: {
                                                name: {
                                                    required: 'Please Enter Your Name.',
                                                    pattern: 'Please Enter Valid Name'
                                                },
                                                mobile: {
                                                    required: 'Please Enter Mobile No.',
                                                    pattern: 'Please Enter Valid Mobile No.'
                                                },
                                                email: {
                                                    required: 'Please Enter Email.',
                                                    email: 'Please Enter Valid Email.',
                                                    pattern: 'Please Enter Valid Email.'
                                                },
                                                password: {
                                                    required: 'Please Enter Password.',
                                                    minlength: 'Password at least 6 characters.'
                                                },
                                                confirm_password: {
                                                    required: 'Please Confirm Password.',
                                                    minlength: 'Confirm Password at least 6 characters.',
                                                    equalTo: 'Password & Confirm Password must be same.'
                                                },
                                                agree_terms_privacy: {
                                                    required: 'Please agree Terms and Conditions and Privacy Policies.'
                                                }
                                            }
                                        });
                                    });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-8 text-center">
                        <a href="<?php echo base_url();?>register/step2/<?php echo $is_seller; ?>" class=" mt-3 mb-8">Have a Account ? Sign In Now</a>
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
$sliders = get_sliders('', '', '', 4);
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