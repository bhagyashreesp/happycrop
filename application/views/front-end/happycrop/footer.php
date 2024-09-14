<!-- Start of Footer -->
<?php $web_settings = get_settings('web_settings', true); ?>
<footer class="footer appear-animate" data-animation-options="{
    'name': 'fadeIn'
}">
    <div class="container">
        <div class="footer-top pt-4">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="widget widget-about mb-2">
                        <h3 class="widget-title mb-2">Got Question? Call us</h3>
                        <div class="widget-body mb-3">
                            <a href="tel:<?= $web_settings['support_number'] ?>" class="widget-about-call"><?= $web_settings['support_number'] ?></a>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-8">
                    <div class="widget mb-2">
                        <h3 class="widget-title mb-2">Happycrop</h3>
                        <ul class="widget-body row">
                            <li class="col-md-3 col-6"><a href="<?php echo base_url('about-us');?>">About Us</a></li>
                            <li class="col-md-2 col-6"><a href="<?php echo base_url('faq');?>">FAQ</a></li>
                            <li class="col-md-2 col-6"><a href="<?php echo base_url('help');?>">Help</a></li>
                            <li class="col-md-2 col-6"><a href="<?php echo base_url('careers');?>">Careers</a></li>
                            <li class="col-md-3 col-6"><a href="<?php echo base_url('contact-us');?>">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <?php /* ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget mb-3">
                        <h4 class="widget-title">My Account</h4>
                        <ul class="widget-body">
                            <?php if ($this->ion_auth->logged_in() && $this->ion_auth->member_status()== 1) { ?>
                            <li><a href="<?= base_url('my-account') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('cart') ?>">View Cart</a></li>
                            <li><a href="<?= base_url('my-account/orders') ?>">My Orders</a></li>
                            <li><a href="<?= base_url('my-account/favorites') ?>">My Wishlist</a></li>
                            <?php } else { ?> 
                            <li><a href="<?= base_url() ?>">Dashboard</a></li>
                            <li><a href="<?= base_url() ?>">View Cart</a></li>
                            <li><a href="<?= base_url() ?>">My Orders</a></li>
                            <li><a href="<?= base_url() ?>">My Wishlist</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <?php */ ?>
                <div class="col-lg-3 col-sm-6 col-4">
                    <div class="widget mb-2">
                        <h4 class="widget-title mb-2 d-none- d-lg-block">&nbsp;</h4>
                        <ul class="widget-body row">
                            <?php if ($this->ion_auth->logged_in()) { ?>
                            <li class="col-md-6 col-12"><a href="<?php echo base_url('privacy-policy');?>">Privacy Policy</a></li>
                            <li class="col-md-6 col-12"><a href="<?php echo base_url('terms-of-use');?>">Term of Use</a></li>
                            <?php } else { ?> 
                            <li class="col-md-6 col-12"><a href="<?php echo base_url('register');?>#reg_sec">Privacy Policy</a></li>
                            <li class="col-md-6 col-12"><a href="<?php echo base_url('register');?>#reg_sec">Term of Use</a></li>
                            <?php } ?> 
                            <?php /* ?>
                            <li><a href="<?php echo base_url('shipping-policy');?>">Shipping Policy</a></li>
                            <li><a href="<?php echo base_url('return-policy');?>">Return Policy</a></li>
                            <li><a href="<?php echo base_url('quality-policy');?>">Quality Policies</a></li>
                            <li><a href="<?php echo base_url('pricing-policy');?>">Pricing Policy</a></li>
                            <?php */ ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="widget mb-2">
                        <?php /* ?>
                        <h4 class="widget-title">Useful Links</h4>
                        <ul class="widget-body">
                            <li><a href="<?php echo base_url('delivery-policy');?>">Delivery Policy</a></li>
                            <li><a href="<?php echo base_url('payment-policy');?>">Payment Policy</a></li>
                            <li><a href="<?php echo base_url('security-policy');?>">Security Policy</a></li>
                            <li><a href="<?php echo base_url('partnering-policy');?>">Partnering Policy</a></li>
                            <li><a href="<?php echo base_url('licensing-policy');?>">Licensing Policy</a></li>
                        </ul>
                        <?php */ ?>
                        <h3 class="widget-title mb-2">Find us on Social</h3>
                        <div class="widget-body">
                            <div class="social-icons social-icons-colored">
                                <a href="#" class="social-icon social-facebook w-icon-facebook"></a>
                                <a href="#" class="social-icon social-twitter w-icon-twitter"></a>
                                <a href="#" class="social-icon social-instagram w-icon-instagram"></a>
                                <a href="#" class="social-icon social-youtube w-icon-youtube"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="footer-bottom pt-3 pb-3">
            <div class="footer-left- w-100 text-center">
                <?php $company_name = get_settings('web_settings', true);
                if (isset($company_name['copyright_details']) && !empty($company_name['copyright_details'])) {
                ?>
                    <p class="mb-0"> <?= (isset($company_name['copyright_details']) && !empty($company_name['copyright_details'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $company_name['copyright_details'])) : "" ?> </p>
                <?php } else { ?>
                    <p class="mb-0">Copyright &copy; <?= date('Y') ?>, All Right Reserved</p>
                <?php } ?>
            </div>
            <div class="footer-right">
                <?php /* ?>
                <span class="payment-label mr-lg-8">We're using safe payment for</span>
                <figure class="payment">
                    <img src="<?= THEME_ASSETS_URL . 'images/payment.png' ?>" alt="payment" width="159" height="25" />
                </figure>
                <?php */ ?>
            </div>
        </div>
    </div>
</footer>
<!-- End of Footer -->
<!-- newsletter modal -->
<section class="modal-popup">
    <div class="modal fade" id="newsletter-modal" tabindex="-1" role="dialog" aria-labelledby="newslettermodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center newsletter-modal">
                    <h2 id="newslettermodal">Sign Up And Connect With XShop</h2>
                    <p class="modal-sen">duis convallis convallis tellus id interdum velit laoreet id donec ultrices
                        tincidunt arcu non sodales</p>
                </div>
                <div class="modal-body">
                    <div class="modal-newsletter">
                        <p><input type="email" class="form-control modal-form newsletter-field" id="inputEmail4" placeholder="Your Email Address"></p>
                        <p><button class="buttons btn-5 default primary-button">Sign Up</button></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> <!-- end newsletter modal -->

 <!-- Start of Scroll Top -->
<a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
<!-- End of Scroll Top -->
    
<div id="modal-custom" class="auth-modal" data-iziModal-group="group1">
    <button data-iziModal-close class="icon-close">x</button>
    <header>
        <a href="" id="login"><?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></a>
        <a href="" id="register" class="active"><?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></a>
    </header>
    <section class="hide" id="login_div">
        <form action="<?= base_url('home/login') ?>" class='form-submit-event' method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="identity" placeholder="Mobile number" value="" required>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" name="password" placeholder="Password" value="" required>
            </div>
            <div class="mb-4 text-right">
                <a href="<?= base_url() ?>" id="forgot_password_link"><?= !empty($this->lang->line('forgot_password')) ? $this->lang->line('forgot_password') : 'Forgot Password' ?> ?</a>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" class="submit_btn  btn btn-primary btn-block"><?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></button>
            </footer>
            <br>
            <div class="d-flex justify-content-center">
                <div class="form-group" id="error_box"></div>
            </div>
        </form>
    </section>
    <section id="register_div">
        <form id='send-otp-form' class='send-otp-form' action='#'>
            <div class="row sign-up-verify-number">
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="text" class='form-input' placeholder="Enter Mobile Number" id="phone-number" required>
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id="error-msg" class="hide text-danger">Enter a valid number</div>
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id="recaptcha-container"></div>
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id='is-user-exist-error' class='text-center p-3 text-danger'></div>
                </div>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button id='send-otp-button'><?= !empty($this->lang->line('send_otp')) ? $this->lang->line('send_otp') : 'Send OTP' ?></button>
            </footer>
        </form>
        <form id='verify-otp-form' class='verify-otp-form d-none' action='<?= base_url('auth/register-user') ?>' method="POST">
            <div class="row sign-up-verify-number">
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="text" class='form-input' placeholder="OTP" id="otp" name="otp" autocomplete="off">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="text" class='form-input' placeholder="Username" id="name" name="name">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="email" class='form-input' placeholder="Email" id="email" name="email">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="password" class='form-input' placeholder="Password" id="password" name="password">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id='registration-error' class='text-center p-3 text-danger'></div>
                </div>
            </div>
            <footer>
                <button data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" id='register_submit_btn'><?= !empty($this->lang->line('submit')) ? $this->lang->line('submit') : 'Submit' ?></button>
            </footer>
        </form>
        <form id='sign-up-form' class='sign-up-form collapse' action='#'>
            <input type="text" placeholder="Username" name='username' class='form-input' required>
            <input type="text" placeholder="email" name='email' class='form-input' required>
            <input type="password" placeholder="Password" name='password' class='form-input' required>
            <div id='sign-up-error' class='text-center p-3'></div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type='submit'><?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></button>
            </footer>
        </form>
    </section>
    <section class="hide pt-0" id="forgot_password_div">
        <div class="text-center h5"><?= !empty($this->lang->line('forgot_password')) ? $this->lang->line('forgot_password') : 'Forgot Password' ?></div>
        <hr class="mt-0">
        <form id="send_forgot_password_otp_form" method="POST" action="#">
            <div class="input-group">
                <input type="text" class="form-control" name="mobile_number" id="forgot_password_number" placeholder="Mobile number" value="">
            </div>
            <div class="col-12 d-flex justify-content-center pb-4 mt-3">
                <div id="recaptcha-container-2"></div>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" id="forgot_password_send_otp_btn" class="submit_btn  btn btn-primary btn-block"><?= !empty($this->lang->line('send_otp')) ? $this->lang->line('send_otp') : 'Send OTP' ?></button>
            </footer>
            <br>
            <div class="d-flex justify-content-center">
                <div class="form-group" id="forgot_pass_error_box"></div>
            </div>
        </form>
        <form id="verify_forgot_password_otp_form" class="d-none" method="post" action="#">
            <div class="input-group">
                <input type="text" id="forgot_password_otp" class="form-control" name="otp" placeholder="OTP" value="" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" name="new_password" placeholder="New Password" value="" required>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" class="submit_btn  btn btn-primary btn-block" id="reset_password_submit_btn"><?= !empty($this->lang->line('submit')) ? $this->lang->line('submit') : 'Submit' ?></button>
            </footer>
            <br>
            <div class="d-flex justify-content-center">
                <div class="form-group" id="set_password_error_box"></div>
            </div>
        </form>
    </section>
</div>
<!-- quick view -->
<div id="quick-view" data-iziModal-group="grupo3" class='product-page-content'>
    <button data-iziModal-close class="icon-close">x</button>
    <div class="row p-4">
        <div class="col-12 col-sm-6 product-preview-image-section-md">
            <div class="swiper-container gallery-top">
                <div class="swiper-wrapper"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next swiper-button-black"></div>
                <div class="swiper-button-prev swiper-button-black"></div>
            </div>
            <div class="swiper-container gallery-thumbs">
                <div class="swiper-wrapper" id="gal1"></div>
            </div>
        </div>
        <!-- Mobile Product Image Slider -->
        <div class="col-12 col-sm-6 product-preview-image-section-sm">
            <div class=" swiper-container mobile-image-swiper">
                <div class="swiper-wrapper"></div>
                <div class="swiper-pagination mobile-image-swiper-pagination text-center"></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 product-page-details">
            <h3 class="my-3 product-title" id="modal-product-title"></h3>
            <p id="modal-product-short-description"></p>
            <hr class="mb-0">
            <div class="mb-3 product-rating-small">
                <input type="text" class="kv-fa rating rating-loading" id="modal-product-rating" value="0" data-show-caption="false" data-size="sm" data-show-clear="false" title="" readonly>
                (<span class="rating-status" id="modal-product-no-of-ratings">1203</span> reviews )
            </div>
            <p class="mb-0 price">
                <span id="modal-product-price"></span>
                <sup>
                    <span class="striped-price text-danger" id="modal-product-special-price-div">
                        <s id="modal-product-special-price"></s>
                    </span>
                </sup>
            </p>
            <div id="modal-product-variant-attributes"></div>
            <div id="modal-product-variants-div"></div>
            <div class="num-block skin-2 py-2 pt-4 pb-4">
                <div class="num-in">
                    <span class="minus dis"></span>
                    <input type="text" class="in-num" id="modal-product-quantity">
                    <span class="plus"></span>
                </div>
            </div>
            <div class="pt-4 pb-4 text-center text-md-left">
                <div class="button button-sm m-0 p-0">
                    <button class="buttons primary-button m-0 add_to_cart" id="modal-add-to-cart-button"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></button>
                </div>
                <div class="button button-sm m-0 p-0">
                    <button class="buttons red-button m-0" id="add_to_favorite_btn"><i class="far fa-heart"></i> <span><?= !empty($this->lang->line('add_to_favorite')) ? $this->lang->line('add_to_favorite') : 'Add to Favorite' ?></span></button>
                </div>
            </div>

            <div class="mt-2">

                <div id="modal-product-tags"></div>

                </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_ajax">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title text-white"></h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<?php /* ?> 
<?php $web_settings = get_settings('web_settings', true); ?>
<!-- footer starts -->
<div class="footer-section-footer" id="dark-footer">
    <div class="main-content footer-con">
        <div class="footer-cta pt-5 pb-5">
            <div class="row">
                <?php if (isset($web_settings['address']) && !empty($web_settings['address'])) { ?>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="cta-text">
                                <h4><?= !empty($this->lang->line('find_us')) ? $this->lang->line('find_us') : 'Find us' ?></h4>
                                <span><?= output_escaping(str_replace('\r\n', '</br>', $web_settings['address'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (isset($web_settings['support_number']) && !empty($web_settings['support_number'])) { ?>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <a href="tel:<?= $web_settings['support_number'] ?>">
                            <div class="single-cta">
                                <i class="fas fa-phone"></i>
                                <div class="cta-text">
                                    <h4><?= !empty($this->lang->line('call_us')) ? $this->lang->line('call_us') : 'Call us' ?></h4>
                                    <span><?= $web_settings['support_number'] ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>

                <?php if (isset($web_settings['support_email']) && !empty($web_settings['support_email'])) { ?>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <a href="mailto:<?= $web_settings['support_email'] ?>">
                            <div class="single-cta">
                                <i class="far fa-envelope-open"></i>
                                <div class="cta-text">
                                    <h4><?= !empty($this->lang->line('mail_us')) ? $this->lang->line('mail_us') : 'Mail us' ?></h4>
                                    <span><?= $web_settings['support_email'] ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="footer-content-footer pt-5 pb-5">
            <div class="row">
                <div class="col-lg-3 mb-50">
                    <div class="footer-widget">
                        <div class="footer-logo-footer">
                            <?php $logo = get_settings('web_logo'); ?>
                            <a href="<?= base_url() ?>"><img src="<?= base_url($logo) ?>" alt="logo"></a>
                        </div>
                        <div class="footer-social-icon">
                            <span><?= !empty($this->lang->line('follow_us')) ? $this->lang->line('follow_us') : 'Follow us' ?></span>
                            <?php if (isset($web_settings['facebook_link']) &&  !empty($web_settings['facebook_link'])) { ?>
                                <a href="<?= $web_settings['facebook_link'] ?>" target="_blank"><i class="fab fa-facebook-f rounded-icon clr-facebbok"></i></a>
                            <?php } ?>
                            <?php if (isset($web_settings['twitter_link']) && !empty($web_settings['twitter_link'])) { ?>
                                <a href="<?= $web_settings['twitter_link'] ?>" target="_blank"><i class="fab fa-twitter rounded-icon clr-twitter"></i></a>
                            <?php } ?>
                            <?php if (isset($web_settings['instagram_link']) &&  !empty($web_settings['instagram_link'])) { ?>
                                <a href="<?= $web_settings['instagram_link'] ?>" target="_blank"><i class="fab fa-instagram rounded-icon clr-insta"></i></a>
                            <?php } ?>
                            <?php if (isset($web_settings['youtube_link']) &&  !empty($web_settings['youtube_link'])) { ?>
                                <a href="<?= $web_settings['youtube_link'] ?>" target="_blank"><i class="fab fa-youtube rounded-icon clr-youtube"></i></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 mb-50">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></h3>
                        </div>
                        <div class="footer-text">
                            <?php if (isset($web_settings['app_short_description'])) { ?>
                                <p><?= $web_settings['app_short_description'] ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3><?= !empty($this->lang->line('useful_links')) ? $this->lang->line('useful_links') : 'Useful Links' ?></h3>
                        </div>
                        <ul>
                            <li><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                            <li><a href="<?= base_url('home/categories') ?>"><?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Shop by Categories' ?></a></li>
                            <li><a href="<?= base_url('products') ?>"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></a></li>
                            <?php if ($this->ion_auth->logged_in()) { ?>
                                <li><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
                                <li><a href="<?= base_url('my-account/orders') ?>"><?= !empty($this->lang->line('my_orders')) ? $this->lang->line('my_orders') : 'My Orders' ?></a></li>
                                <li><a href="<?= base_url('my-account/favorites') ?>"><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?></a></li>
                            <?php } else { ?>
                                <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="login"><?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></a></li>
                                <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="register"><?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></a></li>
                            <?php } ?>
                            <li><a href="<?= base_url('home/about-us') ?>"><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></a></li>
                            <li><a href="<?= base_url('home/contact-us') ?>"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="main-content footer-con">
            <div class="row">
                <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                    <div class="copyright-text">
                        <?php $company_name = get_settings('web_settings', true);
                        if (isset($company_name['copyright_details']) && !empty($company_name['copyright_details'])) {
                        ?>
                            <p> <?= (isset($company_name['copyright_details']) && !empty($company_name['copyright_details'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $company_name['copyright_details'])) : " " ?> </p>
                        <?php } else { ?>
                            <p>Copyright &copy; <?= date('Y') ?>, All Right Reserved</p>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 d-none d-lg-block text-right">
                    <div class="footer-menu">
                        <ul>
                            <li><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                            <li><a href="<?= base_url('home/terms-and-conditions') ?>"><?= !empty($this->lang->line('terms_and_condition')) ? $this->lang->line('terms_and_condition') : 'Terms & Conditions' ?></a></li>
                            <li><a href="<?= base_url('home/privacy-policy') ?>"><?= !empty($this->lang->line('privacy_policy')) ? $this->lang->line('privacy_policy') : 'Privacy Policy' ?></a></li>
                            <li><a href="<?= base_url('home/about-us') ?>"><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></a></li>
                            <li><a href="<?= base_url('home/contact-us') ?>"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer ends -->
<!-- newsletter modal -->
<section class="modal-popup">
    <div class="modal fade" id="newsletter-modal" tabindex="-1" role="dialog" aria-labelledby="newslettermodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center newsletter-modal">
                    <h2 id="newslettermodal">Sign Up And Connect With XShop</h2>
                    <p class="modal-sen">duis convallis convallis tellus id interdum velit laoreet id donec ultrices
                        tincidunt arcu non sodales</p>
                </div>
                <div class="modal-body">
                    <div class="modal-newsletter">
                        <p><input type="email" class="form-control modal-form newsletter-field" id="inputEmail4" placeholder="Your Email Address"></p>
                        <p><button class="buttons btn-5 default primary-button">Sign Up</button></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> <!-- end newsletter modal -->

<?php if (ALLOW_MODIFICATION == 0) { ?>
    <!-- color switcher -->
    <div id="colors-switcher">
        <h6>Pick Your Favorite Color</h6>
        <ul class="color-style text-center mb-2">
            <li class="list-item-inline">
                <a href="#" class="peach"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="default"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="yellow"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="green"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="purple"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="red"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="dark-blue"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="orange"></a>
            </li>
            <li class="list-item-inline">
                <a href="#" class="cyan-dark"></a>
            </li>
        </ul>
        <div class="color-bottom">
            <a href="#" class="settings bg-white d-block"><i class="fa fa-cog fa-lg fa-spin setting-icon"></i></a>
        </div>
    </div> <!-- end color switcher -->
<?php } ?>

<div id="modal-custom" class="auth-modal" data-iziModal-group="group1">
    <button data-iziModal-close class="icon-close">x</button>
    <header>
        <a href="" id="login"><?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></a>
        <a href="" id="register" class="active"><?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></a>
    </header>
    <section class="hide" id="login_div">
        <form action="<?= base_url('home/login') ?>" class='form-submit-event' method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="identity" placeholder="Mobile number" value="" required>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" name="password" placeholder="Password" value="" required>
            </div>
            <div class="mb-4 text-right">
                <a href="<?= base_url() ?>" id="forgot_password_link"><?= !empty($this->lang->line('forgot_password')) ? $this->lang->line('forgot_password') : 'Forgot Password' ?> ?</a>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" class="submit_btn  btn btn-primary btn-block"><?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></button>
            </footer>
            <br>
            <div class="d-flex justify-content-center">
                <div class="form-group" id="error_box"></div>
            </div>
        </form>
    </section>
    <section id="register_div">
        <form id='send-otp-form' class='send-otp-form' action='#'>
            <div class="row sign-up-verify-number">
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="text" class='form-input' placeholder="Enter Mobile Number" id="phone-number" required>
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id="error-msg" class="hide text-danger">Enter a valid number</div>
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id="recaptcha-container"></div>
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id='is-user-exist-error' class='text-center p-3 text-danger'></div>
                </div>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button id='send-otp-button'><?= !empty($this->lang->line('send_otp')) ? $this->lang->line('send_otp') : 'Send OTP' ?></button>
            </footer>
        </form>
        <form id='verify-otp-form' class='verify-otp-form d-none' action='<?= base_url('auth/register-user') ?>' method="POST">
            <div class="row sign-up-verify-number">
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="text" class='form-input' placeholder="OTP" id="otp" name="otp" autocomplete="off">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="text" class='form-input' placeholder="Username" id="name" name="name">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="email" class='form-input' placeholder="Email" id="email" name="email">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <input type="password" class='form-input' placeholder="Password" id="password" name="password">
                </div>
                <div class="col-12 d-flex justify-content-center pb-4">
                    <div id='registration-error' class='text-center p-3 text-danger'></div>
                </div>
            </div>
            <footer>
                <button data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" id='register_submit_btn'><?= !empty($this->lang->line('submit')) ? $this->lang->line('submit') : 'Submit' ?></button>
            </footer>
        </form>
        <form id='sign-up-form' class='sign-up-form collapse' action='#'>
            <input type="text" placeholder="Username" name='username' class='form-input' required>
            <input type="text" placeholder="email" name='email' class='form-input' required>
            <input type="password" placeholder="Password" name='password' class='form-input' required>
            <div id='sign-up-error' class='text-center p-3'></div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type='submit'><?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></button>
            </footer>
        </form>
    </section>
    <section class="hide pt-0" id="forgot_password_div">
        <div class="text-center h5"><?= !empty($this->lang->line('forgot_password')) ? $this->lang->line('forgot_password') : 'Forgot Password' ?></div>
        <hr class="mt-0">
        <form id="send_forgot_password_otp_form" method="POST" action="#">
            <div class="input-group">
                <input type="text" class="form-control" name="mobile_number" id="forgot_password_number" placeholder="Mobile number" value="">
            </div>
            <div class="col-12 d-flex justify-content-center pb-4 mt-3">
                <div id="recaptcha-container-2"></div>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" id="forgot_password_send_otp_btn" class="submit_btn  btn btn-primary btn-block"><?= !empty($this->lang->line('send_otp')) ? $this->lang->line('send_otp') : 'Send OTP' ?></button>
            </footer>
            <br>
            <div class="d-flex justify-content-center">
                <div class="form-group" id="forgot_pass_error_box"></div>
            </div>
        </form>
        <form id="verify_forgot_password_otp_form" class="d-none" method="post" action="#">
            <div class="input-group">
                <input type="text" id="forgot_password_otp" class="form-control" name="otp" placeholder="OTP" value="" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" name="new_password" placeholder="New Password" value="" required>
            </div>
            <footer>
                <button type="button" data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button type="submit" class="submit_btn  btn btn-primary btn-block" id="reset_password_submit_btn"><?= !empty($this->lang->line('submit')) ? $this->lang->line('submit') : 'Submit' ?></button>
            </footer>
            <br>
            <div class="d-flex justify-content-center">
                <div class="form-group" id="set_password_error_box"></div>
            </div>
        </form>
    </section>
</div>
<!-- quick view -->
<div id="quick-view" data-iziModal-group="grupo3" class='product-page-content'>
    <button data-iziModal-close class="icon-close">x</button>
    <div class="row p-4">
        <div class="col-12 col-sm-6 product-preview-image-section-md">
            <div class="swiper-container gallery-top">
                <div class="swiper-wrapper"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next swiper-button-black"></div>
                <div class="swiper-button-prev swiper-button-black"></div>
            </div>
            <div class="swiper-container gallery-thumbs">
                <div class="swiper-wrapper" id="gal1"></div>
            </div>
        </div>
        <!-- Mobile Product Image Slider -->
        <div class="col-12 col-sm-6 product-preview-image-section-sm">
            <div class=" swiper-container mobile-image-swiper">
                <div class="swiper-wrapper"></div>
                <div class="swiper-pagination mobile-image-swiper-pagination text-center"></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 product-page-details">
            <h3 class="my-3 product-title" id="modal-product-title"></h3>
            <p id="modal-product-short-description"></p>
            <hr class="mb-0">
            <div class="mb-3 product-rating-small">
                <input type="text" class="kv-fa rating rating-loading" id="modal-product-rating" value="0" data-show-caption="false" data-size="sm" data-show-clear="false" title="" readonly>
                (<span class="rating-status" id="modal-product-no-of-ratings">1203</span> reviews )
            </div>
            <p class="mb-0 price">
                <span id="modal-product-price"></span>
                <sup>
                    <span class="striped-price text-danger" id="modal-product-special-price-div">
                        <s id="modal-product-special-price"></s>
                    </span>
                </sup>
            </p>
            <div id="modal-product-variant-attributes"></div>
            <div id="modal-product-variants-div"></div>
            <div class="num-block skin-2 py-2 pt-4 pb-4">
                <div class="num-in">
                    <span class="minus dis"></span>
                    <input type="text" class="in-num" id="modal-product-quantity">
                    <span class="plus"></span>
                </div>
            </div>
            <div class="pt-4 pb-4 text-center text-md-left">
                <div class="button button-sm m-0 p-0">
                    <button class="buttons primary-button m-0 add_to_cart" id="modal-add-to-cart-button"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></button>
                </div>
                <div class="button button-sm m-0 p-0">
                    <button class="buttons red-button m-0" id="add_to_favorite_btn"><i class="far fa-heart"></i> <span><?= !empty($this->lang->line('add_to_favorite')) ? $this->lang->line('add_to_favorite') : 'Add to Favorite' ?></span></button>
                </div>
            </div>

            <div class="mt-2">

                <div id="modal-product-tags"></div>

                </span>
            </div>
        </div>
    </div>
</div>

<?php if (ALLOW_MODIFICATION == 0) { ?>
    <?php /* ?>
    <div class="buy-now-btn">
        <a href="https://codecanyon.net/item/eshop-multipurpose-ecommercestore-website/31071809" target="_blank" class="btn btn-danger"> <i class="fa fa-shopping-cart"></i> Buy Now</a>
    </div>
    <?php *//* ?>
<?php } ?>

<!-- end -->
<!-- main content ends -->
<?php */ ?>