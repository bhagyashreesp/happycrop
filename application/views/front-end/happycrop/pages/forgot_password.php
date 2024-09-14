<style>.logo img {width: 170px;}.login-popup .nav.nav-tabs .nav-item{width: 60%;}label.error {display: inline;color: #ce0404;font-size: 12px;padding: 2px;margin-top: 2px;}</style>
<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="login-popup border-radius-10 bg-white">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#sign-in" class="nav-link border-none active">Forgot Password</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-in">
                                    <?php if($this->session->flashdata('message')) { ?>
                                    <div class="alert alert-info">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?=$this->session->flashdata('message')?>
                                    </div>
                                    <?php } ?>
                                    <form id="sign-in-form" name="sign-in-form" action="<?php echo base_url('register/reset-password') ?>" class='form-submit-event2' method="post">
                                        <?php /* ?>
                                        <div class="form-group mb-3">
                                            <label>Mobile No. *</label>
                                            <input type="text" class="form-control" name="identity" id="identity" placeholder="Mobile No."  pattern="^[6-9]\d{9}$"/>
                                        </div>
                                        <?php */ ?>
                                        <div class="form-group mb-3">
                                            <label>Email *</label>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" pattern="^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2}$"/>
                                        </div>
                                        <br />
                                        <div class="text-center d-flex mb-3 justify-content-center">
                                            <input type="hidden" id="is_seller" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="btn btn-primary btn-rounded d-inline-block btn-block--">Continue</button>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group" id="error_box2" style="display: none;"></div>
                                        </div>
                                    </form>
                                    <?php /* ?>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
                                    <?php */ ?>
                                    <script src="<?php echo base_url();?>assets/front_end/happycrop/js/jquery.validate.min.js"></script>
                                    <script src="<?php echo base_url();?>assets/front_end/happycrop/js/additional-methods.min.js"></script>
                                    <script>
                                    $(document).ready (function () {  
                                        $("#sign-in-form").validate({
                                            rules: {
                                                /*identity: {
                                                    required: true
                                                }*/
                                                email:{
                                                    required: true
                                                }
                                            },
                                            messages: {
                                                /*identity: {
                                                    required: 'Please Enter Mobile No.',
                                                    pattern: 'Please Enter Valid Mobile No.'
                                                }*/
                                                email: {
                                                    required: 'Please Enter Email',
                                                    pattern: 'Please Enter Valid Email'
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
            </div>
        </div>
    </div>
</div>

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
