<style>.logo img {width: 150px;}.login-popup .nav-item .nav-link{font-size: 1.4rem;}label.error {display: inline;color: #ce0404;font-size: 12px;padding: 2px;margin-top: 2px;}</style>
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
                                    <li class="nav-item active">
                                        <a href="<?php echo base_url('register/step4/'.$is_seller) ?>" class="nav-link2 active">Business Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('register/step5/'.$is_seller) ?>" class="nav-link2">Bank Details</a>
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
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('register/save_step4/'.$is_seller) ?>' method="post">
                                        <div class="form-group">
                                            <label>Firm Name *</label>
                                            <input type="text" class='form-control' placeholder="Firm Name" id="company_name" name="company_name" value="<?php echo $seller_data->company_name;?>"/>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Plot No. / Floor / Building  </label>
                                            <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $seller_data->plot_no;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Street / Locality / Village</label>
                                            <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality / Village" value="<?php echo $seller_data->street_locality;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Land Mark</label>
                                            <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $seller_data->landmark;?>" />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
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
                                            <div class="form-group col-md-6">
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
                                            <!--
                                            <div class="form-group col-md-6">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City" value="<?php echo $seller_data->city;?>"/>
                                            </div>-->
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <!--<label>State</label>
                                                <input type="text" class="form-control" name="state" id="state" placeholder="State" value="<?php echo $seller_data->state;?>" />-->
                                                
                                                <label>Pin  </label>
                                                <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" pattern="^[1-9][0-9]{5}$" value="<?php echo $seller_data->pin;?>"/>
                                                
                                            </div>
                                        </div>
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
                                                company_name: {
                                                    required: true
                                                }
                                            },
                                            messages: {
                                                company_name: {
                                                    required: 'Please Enter Firm Name.'
                                                },
                                                pin: {
                                                    pattern: 'Please Enter Valid Pin'
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