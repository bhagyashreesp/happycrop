<style>
.contact-page {min-height: 320px;}.contact-page .info-icon a i {font-size: 48px;}.contact-page span.subtitle{color: #000;font-size: 16px;margin-top: 12px;}.contact-page .info-content h3 {font-size: 1.6rem;margin-top: 25px;}
.contact-page .content-box{margin:/*10px 20px*/10px 0px;background: #d9d9d9;padding: /*20px 40px*/20px 20px;border-radius: 12px;}
.contact-page .content-box p, .contact-page .content-box i {font-size: 1.8rem;color: #456B1C;}
.min-height-230{min-height: 230px;}
.min-height-200{min-height: 200px;}
.middle-div{max-width: 235px;margin: 0 auto;}
.middle-div, .content-inner {font-size: 1.7rem;}
.contact-page .content-box h4{color: #456B1C;}
.contact-box label{margin-bottom: 5px;font-size: 1.6rem;}
.contact-box h3.contact-title {margin-bottom: 30px;font-size: 2.4rem;color: #75B031;border-color: #336699;font-weight: 500;}
.contact-box h3::after {
  display: block;
  margin-top: 3px;
  margin-left: 0;
  width: 30%;
  border-top: 5px solid;
  -webkit-transition: -webkit-transform 0.3s;
  transition: -webkit-transform 0.3s;
  transition: transform 0.3s;
  transition: transform 0.3s, -webkit-transform 0.3s;
  content: "";
}
.contact-box .form-control {
  border-top: none medium !important;
  border-left: none medium !important;
  border-right: none medium !important;
  border-bottom-color: #CCC !important;
  padding-left: 5px !important;
}
.contact-box textarea.form-control {border: 2px solid #686868 !important;border-radius: 5px !important;}
h2 {font-size: 2.5rem;}
@media (max-width: 45em) {
    .contact-box h3.contact-title {font-size: 1.6rem;font-weight: 500;}
    .content-inner, .contact-page .content-box p, .contact-page .content-box i, .middle-div, .contact-box label {font-size: 1.4rem;}
    .min-height-230, .min-height-200 {min-height: 150px;}
}
</style>
<?php /* ?>
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></a></li>
            </ol>
        </nav>
    </div>
</section>
<?php */ ?>
<section id="content" class="pt-5 pb-5 contact-page">
    <div class="main-content">
        <div class="contact-box p-3 mb-5">
            <form id="contact-us-form" action="<?=base_url('home/send-contact-us-email')?>" method="POST">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <h3 class="contact-title">Connect with us.</h3>
                        <div class="form-group mb-1">
                            <label class="radio-online d-inline-block mr-3">
                                <input checked="" type="radio" class="" name="user_type" value="1"/> Mfg / Supplier
                            </label>
                            <label class="radio-online d-inline-block mr-3">
                                <input type="radio" class="" name="user_type" value="2"/> Shop Owner
                            </label>
                        </div>
                        <div class="form-group mb-1">
                            <label for="">Name*</label>
                            <input type="text" class="form-control" name="username" placeholder="Name">
                        </div>
                        <div class="form-group mb-1">
                            <label for="">Phone Number*</label>
                            <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                        </div>
                        <div class="form-group mb-1">
                            <label for="">Email ID*</label>
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group mb-1">
                            <label for="">Company / Shop Name*</label>
                            <input type="text" class="form-control" name="company_name" placeholder="Company / Shop Name">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <p class="mt-1 d-none d-sm-none d-md-block">
                            <img src="<?php echo base_url('assets/contact_bg.jpg'); ?>" alt="" />
                        </p>
                        <div class="form-group mt-2">
                            <label for="inputAddress">Briefly elaborate on what you want to discuss</label>
                            <textarea class="form-control" name="message" rows="4" cols="58" placeholder="Briefly elaborate on what you want to discuss"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center mt-3 mb-3">
                        <button id="contact-us-submit-btn" class="btn btn-primary btn-ellipse btn-5">Send</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="mt-1 d-block d-sm-none d-md-none">
                            <img src="<?php echo base_url('assets/contact_bg.jpg'); ?>" alt="" />
                        </p>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">Contact</h2>
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-lg-5">
                <div class="content-box min-height-230">
                    <h4 class="text-center">Corporate Office</h4>
                    <div class="content-inner">
                        <div class="row mb-1">
                            <div class="col-1"><i class="far fa-envelope-open"></i></div>
                            <div class="col-11">rn@happycrop.in</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-1"><i class="fas fa-phone -alt"></i></div>
                            <div class="col-11">+91 7410568343</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-1"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="col-11">B 1201, Montvert Belbrook, S. No. 10, Montvert Drive, Paud Road, Bavdhan, Pune, Maharashtra, India 412115</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="content-box min-height-230">
                    <h4 class="text-center">Registered Office</h4>
                    <div class="content-inner">
                        <div class="row mb-1">
                            <div class="col-1"><i class="far fa-envelope-open"></i></div>
                            <div class="col-11">rn@happycrop.in</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-1"><i class="fas fa-phone -alt"></i></div>
                            <div class="col-11">+91 7410568343</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-1"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="col-11">212, Main Road, Pushpanagar, Maharashtra, India 416209</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-4">
                        <div class="content-box min-height-200">
                            <h4 class="text-center">Technology</h4>
                            <div class="middle-div">
                                <div class="row mb-1">
                                    <div class="col-1"><i class="far fa-envelope-open"></i></div>
                                    <div class="col-11">bds@happycrop.in</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="content-box min-height-200">
                            <h4 class="text-center">Sales</h4>
                            <div class="middle-div">
                                <div class="row mb-1">
                                    <div class="col-1"><i class="far fa-envelope-open"></i></div>
                                    <div class="col-11">sales@happycrop.in</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-1"><i class="fas fa-phone -alt"></i></div>
                                    <div class="col-11">+91 9975548343</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="content-box min-height-200">
                            <h4 class="text-center">Customer</h4>
                            <div class="middle-div">
                                <div class="row mb-1">
                                    <div class="col-1"><i class="far fa-envelope-open"></i></div>
                                    <div class="col-11">support@happycrop.in</div>
                                </div>
                                <div class="row">
                                    <div class="col-1"><i class="fas fa-phone -alt"></i></div>
                                    <div class="col-11">+91 9975548343</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php /* ?>
        <div class="row">
            <div class="col-md-7">
                <div class="sign-up-image">
                    <?php if (isset($web_settings['map_iframe']) && !empty($web_settings['map_iframe'])) { 
                        echo html_entity_decode(stripcslashes($web_settings['map_iframe']));
                    } ?>
                </div>
            </div>
            <div class="col-md-5 login-form">
                <h2 class="form-text-style"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></h2>
                <form id="contact-us-form" action="<?=base_url('home/send-contact-us-email')?>" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"><?= !empty($this->lang->line('username')) ? $this->lang->line('username') : 'Username' ?></label>
                            <input type="text" class="form-control" id="inputEmail4" name="username" placeholder="Your Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"><?= !empty($this->lang->line('email')) ? $this->lang->line('email') : 'Email' ?></label>
                            <input type="email" class="form-control" id="inputPassword4" name="email" placeholder="Your Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress"><?= !empty($this->lang->line('subject')) ? $this->lang->line('subject') : 'Subject' ?></label>
                        <input type="text" class="form-control" id="inputAddress" name="subject" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <label for="inputAddress"><?= !empty($this->lang->line('message')) ? $this->lang->line('message') : 'Message' ?></label>
                        <textarea class="form-control" name="message" rows="4" cols="58"></textarea>
                    </div>
                    <button id="contact-us-submit-btn" class="block btn-5"><?= !empty($this->lang->line('send_message')) ? $this->lang->line('send_message') : 'Send Message' ?></button>
                </form>
            </div>
        </div>
        
        <div class="row col-mb-50 mt-5">
            <?php if (isset($web_settings['address']) && !empty($web_settings['address'])) { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-wrapper">
                        <div class="info-icon">
                            <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                        </div>
                        <div class="info-content">
                            <h3><?= !empty($this->lang->line('find_us')) ? $this->lang->line('find_us') : 'Find us' ?></h3>
                            <span class="subtitle"><?= output_escaping(str_replace('\r\n','</br>',$web_settings['address'])) ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($web_settings['support_number']) && !empty($web_settings['support_number'])) { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-wrapper">
                        <div class="info-icon">
                            <a href="#"><i class="fas fa-phone -alt"></i></a>
                        </div>
                        <div class="info-content">
                            <h3><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?>
                                <span class="subtitle"><?= $web_settings['support_number'] ?></span>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($web_settings['support_email']) && !empty($web_settings['support_email'])) { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-wrapper">
                        <div class="info-icon">
                            <a href="#"><i class="far fa-envelope-open"></i></a>
                        </div>
                        <div class="info-content">
                            <h3><?= !empty($this->lang->line('email_us')) ? $this->lang->line('email_us') : 'Email Us' ?>
                                <span class="subtitle"><?= $web_settings['support_email'] ?></span>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php */ ?>
        
    </div>
</section>