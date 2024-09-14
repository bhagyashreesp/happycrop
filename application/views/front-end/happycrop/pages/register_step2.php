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
                                    <a href="#sign-in" class="nav-link border-none active"><?php echo ($is_seller) ? 'Businesses' : 'Shop Owner' ; ?> Sign In</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-in">
                                    <form id="sign-in-form" name="sign-in-form" action="<?php echo base_url('home/login') ?>" class='form-submit-event' method="post">
                                        <div class="form-group mb-3">
                                            <label>Mobile No. *</label>
                                            <input type="text" class="form-control" name="identity" id="identity" placeholder="Mobile No."  pattern="^[6-9]\d{9}$"/>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Password *</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                        </div>
                                        <br />
                                        <div class="text-center d-flex mb-3 justify-content-center">
                                            <input type="hidden" id="is_seller" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="btn btn-primary btn-rounded d-inline-block btn-block--"><?php echo ($this->lang->line('login')!='') ? $this->lang->line('login') : 'Sign In' ?></button>
                                        </div>
                                        <div class="">
                                            <div class="form-group" id="error_box" style="display: none;"></div>
                                        </div>
                                    </form>
                                    <p class="text-center">
                                        <a href="<?php echo base_url('register/forgot-password/'.$is_seller);?>">Forgot Password ?</a>
                                    </p>
                                    <div class="mt-5 text-center">
                                        <p class="mb-2">NEW TO HAPPYCROP?  REGISTER NOW!</p>
                                        <a class="btn btn-primary btn-rounded d-inline-block" href="<?php echo base_url();?>register/step3/<?php echo $is_seller;?>" class=" mt-5">Register</a>
                                    </div>
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
                                                identity: {
                                                    required: true
                                                },
                                                password: {
                                                    required: true
                                                }
                                            },
                                            messages: {
                                                identity: {
                                                    required: 'Please Enter Mobile No.',
                                                    pattern: 'Please Enter Valid Mobile No.'
                                                },
                                                password: {
                                                    required: 'Please Enter Password.'
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

<section class="pt-3 pb-1">
    <div class="container">
        <h2 class="title line-bottom title-center mb-5">How it works</h2>
        <h4 class="h4 text-center">GROW YOUR BUSINESS IN 3 SIMPLE STEPS</h4>
        
        <div class="row">
            <div class="col-md-4">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/create_account.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h3>CREATE AN ACCOUNT</h3>
                        <p>Register using your Mobile number, Shop Name and Address.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/shop_kyc.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h3>COMPLETE SHOP KYC</h3>
                        <p>Complete your KYC using License, Bank Details and GST.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/grow_your_business.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h3>GROW YOUR BUSINESS</h3>
                        <p>Buy or Sell across the categories from Agri Inputs. Grow and transform your business to new HIGH.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container pt-3 pb-3">
    <div class="hr-divider"></div>
</div>

<section class="pt-3 pb-1">
    <div class="container">
        <h2 class="title line-bottom title-center mb-5">Why Happycrop</h2>
        
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/top_brands.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">TOP BRANDS</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/trusted_retailers.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">TRUSTED RETAILERS</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/top_products.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">TOP PRODUCTS</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/best-deals.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">BEST DEALS</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/working_capital.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">WORKING CAPITAL</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/on_time_delivery.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">ON TIME DELIVERY</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/business_analytics.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">BUSINESS ANALYTICS</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/smooth_experience.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4">SMOOTH EXPERIENCE</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container pt-3 pb-3">
    <div class="hr-divider"></div>
</div>

<section class="pt-3 pb-1">
    <div class="container">
        <h2 class="title line-bottom title-center mb-5">Categories Across The Industries</h2>
        
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="how-it-works-box text-center">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/fertilizers.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4 font-weight-700">FERTILIZERS</h4>
                        <ul class="">
                            <li>Straight Fertilizers</li>
                            <li>Complex Fertilizers</li>
                            <li>Micronutrients</li>
                            <li>Water Soluble</li>
                            <li>Organic Fertilizers</li>
                            <li>Bio Fertilizers</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box text-center">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/pesticides.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4 font-weight-700">PESTICIDES</h4>
                        <ul>
                            <li>Insecticides</li>
                            <li>Herbicides</li>
                            <li>Fungicides</li>
                            <li>Bio pesticides</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box text-center">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/seeds.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4 font-weight-700">SEEDS</h4>
                        <ul>
                            <li>Non GMO Seeds</li>
                            <li>Hybrid seeds</li>
                            <li>Seedlings</li>
                            <li>Plants</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="how-it-works-box text-center">
                    <div class="box-icon">
                        <img src="<?php echo base_url();?>assets/icons/agri_equipments.png" alt="" />
                    </div>
                    <div class="box-content">
                        <h4 class="h4 font-weight-700">AGRI EQUIPMENTS</h4>
                        <ul>
                            <li>Small Equipment's</li>
                            <li>Small machines</li>
                            <li>Heavy machines</li>
                            <li>Agri drones</li>
                            <li>Irrigation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php /* ?>
<div class="container pt-3 pb-3">
    <div class="hr-divider"></div>
</div>

<section class="pt-3 pb-1">
    <div class="container">
        <h2 class="title line-bottom title-center mb-5 d-block">
            TOP BRANDS ACROSS CATEGORIES
            <div class="float-right font-weight-normal font-size-14"><a href="#">View All</a></div>
        </h2>
        
        <div class="swiper-container swiper-theme  brands-wrapper-- mb-9 appear-animate" data-swiper-options="{
            'spaceBetween': 2,
            'slidesPerView': 3,
            'breakpoints': {
                '576': {
                    'slidesPerView': 3
                },
                '768': {
                    'slidesPerView': 4
                },
                '992': {
                    'slidesPerView': 5
                },
                '1200': {
                    'slidesPerView': 6
                }
            }
        }">
            <div class="swiper-wrapper row gutter-no cols-xl-6 cols-lg-5 cols-md-4 cols-sm-3 cols-2">
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/tapas.png" alt="Tapas" width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/syngenta.png" alt="Syngenta" width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/bayer.png" alt="Bayer" width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/seminis.png" alt="Seminis" width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/namdhari-seeds.png" alt="Namdhari Seeds" width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/rallis.png" alt="Rallis"  width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/dhanuka.png" alt="Dhanuka" width="205" height="211" />
                        </div>
                    </figure>
                </div>
                <div class="swiper-slide brand-col--">
                    <figure class="brand-wrapper--">
                        <div class="brand-thumb">
                            <img src="<?php echo base_url();?>assets/brands/fmc.png" alt="FMC" width="205" height="211" />
                        </div>
                    </figure>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <button class="swiper-button-next"></button>
            <button class="swiper-button-prev"></button>
        </div>
    </div>
</section>
<?php */ ?>