<!-- breadcrumb -->
<style>
.login-popup2 .form-group label {display: block;margin-bottom: 0.5rem;}
#fertilizer_license_photo_prev, #pesticide_license_photo_prev, #seeds_license_photo_prev {max-height: 180px;object-fit: contain;}
</style>
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
                            <div class="col-md-12" id="sign-in">
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/save_step7/') ?>' method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                I have Fertilizer License
                                                <select class='have_license_no float-lg-right' placeholder="Fertilizer License" id="have_fertilizer_license" name="have_fertilizer_license">
                                                    <option value="0" <?php echo ($seller_data->have_fertilizer_license==0) ? 'selected="selected"' : '';?>>No</option>
                                                    <option value="1" <?php echo ($seller_data->have_fertilizer_license==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                </select>
                                                </label>
                                            </div> 
                                        </div>
                                    </div>   
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                        <label>Fertilizer License Number</label>
                                                        <input type="text" class='form-control' placeholder="Fertilizer License Number" id="fertilizer_license_no" name="fertilizer_license_no" value="<?php echo $seller_data->fertilizer_license_no;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                        <label>Expiry Date</label>
                                                        <input type="date" class='form-control' placeholder="Expiry Date" id="fert_lic_expiry_date" name="fert_lic_expiry_date" value="<?php echo $seller_data->fert_lic_expiry_date;?>"/>
                                                    </div>
                                                    <?php /* ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group fertilizer_license_div" style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                            <label>Issue Date</label>
                                                            <input type="date" class='form-control' placeholder="Issue Date" id="fert_lic_issue_date" name="fert_lic_issue_date" value="<?php echo $seller_data->fert_lic_issue_date;?>"/>
                                                        </div>
                                                    </div>
                                                    <?php */ ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row fertilizer_license_div"  style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                    <label>Upload Photo</label>
                                                    <div class="clearfix"></div>
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#fertilizer_license_photo').click();">Select Photo</a>
                                                    
                                                    <label class="mb-1 mt-1">Or</label>
                                                    <label>Upload PDF</label>
                                                    <div class="clearfix"></div>
                                                    <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-2" href="javascript:void(0);" onclick="$('#fertilizer_license_pdf').click();">Select PDF</a>
                                                    <?php
                                                    if(file_exists($seller_data->fertilizer_license_pdf) && $seller_data->fertilizer_license_pdf!='')
                                                    {
                                                        ?>
                                                        <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->fertilizer_license_pdf;?>" target="_blank">View PDF</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <input type="hidden" name="old_fertilizer_license_photo" value="<?php echo $seller_data->fertilizer_license_photo; ?>" />
                                                    <input class="custom-file-input" id="fertilizer_license_photo" name="fertilizer_license_photo" type="file" onchange="readURL1(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    
                                                    <input type="hidden" name="old_fertilizer_license_pdf" value="<?php echo $seller_data->fertilizer_license_pdf; ?>" />
                                                    <input class="custom-file-input label-hide" id="fertilizer_license_pdf" name="fertilizer_license_pdf" type="file" accept="application/pdf" />
                                                </div>
                                                <div class="col-md-5 pb-1 pt-1">
                                                    <div id="img_div">
                                                        <?php
                                                        if(file_exists($seller_data->fertilizer_license_photo) && $seller_data->fertilizer_license_photo!='')
                                                        {
                                                            ?>
                                                            <img id="fertilizer_license_photo_prev" src="<?php echo base_url().$seller_data->fertilizer_license_photo;?>" alt="" style="width: 200px;" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="fertilizer_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                I have Pesticide License
                                                <select class='have_pesticide_license_no float-lg-right' placeholder="Pesticide License" id="have_pesticide_license_no" name="have_pesticide_license_no">
                                                    <option value="0" <?php echo ($seller_data->have_pesticide_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                    <option value="1" <?php echo ($seller_data->have_pesticide_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Pesticide License Number</label>
                                                        <input type="text" class='form-control' placeholder="Pesticide License Number" id="pesticide_license_no" name="pesticide_license_no" value="<?php echo $seller_data->pesticide_license_no;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Expiry Date</label>
                                                        <input type="date" class='form-control' placeholder="Expiry Date" id="pest_lic_expiry_date" name="pest_lic_expiry_date" value="<?php echo $seller_data->pest_lic_expiry_date;?>"/>
                                                    </div>
                                                    <?php /* ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Issue Date</label>
                                                            <input type="date" class='form-control' placeholder="Issue Date" id="pest_lic_issue_date" name="pest_lic_issue_date" value="<?php echo $seller_data->pest_lic_issue_date;?>"/>
                                                        </div>
                                                    </div>
                                                    <?php */ ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                    <label>Upload Photo</label>
                                                    <div class="clearfix"></div>
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#pesticide_license_photo').click();">Select Photo</a>
                                                    
                                                    <label class="mb-1 mt-1">Or</label>
                                                    <label>Upload PDF</label>
                                                    <div class="clearfix"></div>
                                                    <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-2" href="javascript:void(0);" onclick="$('#pesticide_license_pdf').click();">Select PDF</a>
                                                    <?php
                                                    if(file_exists($seller_data->pesticide_license_pdf) && $seller_data->pesticide_license_pdf!='')
                                                    {
                                                        ?>
                                                        <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->pesticide_license_pdf;?>" target="_blank">View PDF</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <input type="hidden" name="old_pesticide_license_photo" value="<?php echo $seller_data->pesticide_license_photo; ?>" />
                                                    <input class="custom-file-input" id="pesticide_license_photo" name="pesticide_license_photo" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    
                                                    <input type="hidden" name="old_pesticide_license_pdf" value="<?php echo $seller_data->pesticide_license_pdf; ?>" />
                                                    <input class="custom-file-input label-hide" id="pesticide_license_pdf" name="pesticide_license_pdf" type="file" accept="application/pdf" />
                                                </div>
                                                <div class="col-md-5 pb-1 pt-1">
                                                    <div id="img_div">
                                                        <?php
                                                        if(file_exists($seller_data->pesticide_license_photo) && $seller_data->pesticide_license_photo!='')
                                                        {
                                                            ?>
                                                            <img id="pesticide_license_photo_prev" src="<?php echo base_url().$seller_data->pesticide_license_photo;?>" alt="" style="width: 200px;" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="pesticide_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                I have Seeds License
                                                <select class='have_seeds_license_no float-lg-right' placeholder="Seeds License" id="have_seeds_license_no" name="have_seeds_license_no">
                                                    <option value="0" <?php echo ($seller_data->have_seeds_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                    <option value="1" <?php echo ($seller_data->have_seeds_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Seeds License Number</label>
                                                        <input type="text" class='form-control' placeholder="Seeds License Number" id="seeds_license_no" name="seeds_license_no" value="<?php echo $seller_data->seeds_license_no;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php /* ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Issue Date</label>
                                                            <input type="date" class='form-control' placeholder="Issue Date" id="seeds_lic_issue_date" name="seeds_lic_issue_date" value="<?php echo $seller_data->seeds_lic_issue_date;?>"/>
                                                        </div>
                                                    </div>
                                                    <?php */ ?>
                                                    <div class="form-group seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Expiry Date</label>
                                                        <input type="date" class='form-control' placeholder="Expiry Date" id="seeds_lic_expiry_date" name="seeds_lic_expiry_date" value="<?php echo $seller_data->seeds_lic_expiry_date;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                    <label>Upload Photo</label>
                                                    <div class="clearfix"></div>
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#seeds_license_photo').click();">Select Photo</a>
                                                    
                                                    <label class="mb-1 mt-1">Or</label>
                                                    <label>Upload PDF</label>
                                                    <div class="clearfix"></div>
                                                    <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-2" href="javascript:void(0);" onclick="$('#seeds_license_pdf').click();">Select PDF</a>
                                                    <?php
                                                    if(file_exists($seller_data->seeds_license_pdf) && $seller_data->seeds_license_pdf!='')
                                                    {
                                                        ?>
                                                        <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->seeds_license_pdf;?>" target="_blank">View PDF</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <input type="hidden" name="old_seeds_license_photo" value="<?php echo $seller_data->seeds_license_photo; ?>" />
                                                    <input class="custom-file-input" id="seeds_license_photo" name="seeds_license_photo" type="file" onchange="readURL3(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    
                                                    <input type="hidden" name="old_seeds_license_pdf" value="<?php echo $seller_data->seeds_license_pdf; ?>" />
                                                    <input class="custom-file-input label-hide" id="seeds_license_pdf" name="seeds_license_pdf" type="file" accept="application/pdf" />
                                                </div>
                                                <div class="col-md-5 pb-1 pt-1">
                                                    <div id="img_div">
                                                        <?php
                                                        if(file_exists($seller_data->seeds_license_photo) && $seller_data->seeds_license_photo!='')
                                                        {
                                                            ?>
                                                            <img id="seeds_license_photo_prev" src="<?php echo base_url().$seller_data->seeds_license_photo;?>" alt="" style="width: 200px;" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="seeds_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                I have Agri Services License
                                                <select class='have_agri_serv_license_no float-lg-right' placeholder="Agri Services License" id="have_agri_serv_license_no" name="have_agri_serv_license_no">
                                                    <option value="0" <?php echo ($seller_data->have_agri_serv_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                    <option value="1" <?php echo ($seller_data->have_agri_serv_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group agri_serv_license_div" style="<?php echo ($seller_data->have_agri_serv_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Agri Services License Number</label>
                                                        <input type="text" class='form-control' placeholder="Agri Services License Number" id="agri_serv_license_no" name="agri_serv_license_no" value="<?php echo $seller_data->agri_serv_license_no;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group agri_serv_license_div" style="<?php echo ($seller_data->have_agri_serv_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Expiry Date</label>
                                                        <input type="date" class='form-control' placeholder="Expiry Date" id="agri_serv_lic_expiry_date" name="agri_serv_lic_expiry_date" value="<?php echo $seller_data->agri_serv_lic_expiry_date;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row agri_serv_license_div" style="<?php echo ($seller_data->have_agri_serv_license_no==0) ? 'display: none;' : '';?>">
                                                <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                    <label>Upload Photo</label>
                                                    <div class="clearfix"></div>
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#agri_serv_license_photo').click();">Select Photo</a>
                                                    
                                                    <label class="mb-1 mt-1">Or</label>
                                                    <label>Upload PDF</label>
                                                    <div class="clearfix"></div>
                                                    <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-2" href="javascript:void(0);" onclick="$('#agri_serv_license_pdf').click();">Select PDF</a>
                                                    <?php
                                                    if(file_exists($seller_data->agri_serv_license_pdf) && $seller_data->agri_serv_license_pdf!='')
                                                    {
                                                        ?>
                                                        <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->agri_serv_license_pdf;?>" target="_blank">View PDF</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <input type="hidden" name="old_agri_serv_license_photo" value="<?php echo $seller_data->agri_serv_license_photo; ?>" />
                                                    <input class="custom-file-input" id="agri_serv_license_photo" name="agri_serv_license_photo" type="file" onchange="readURL4(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    
                                                    <input type="hidden" name="old_agri_serv_license_pdf" value="<?php echo $seller_data->agri_serv_license_pdf; ?>" />
                                                    <input class="custom-file-input label-hide" id="agri_serv_license_pdf" name="agri_serv_license_pdf" type="file" accept="application/pdf" />
                                                </div>
                                                <div class="col-md-5 pb-1 pt-1">
                                                    <div id="img_div">
                                                        <?php
                                                        if(file_exists($seller_data->agri_serv_license_photo) && $seller_data->agri_serv_license_photo!='')
                                                        {
                                                            ?>
                                                            <img id="agri_serv_license_photo_prev" src="<?php echo base_url().$seller_data->agri_serv_license_photo;?>" alt="" style="width: 200px;" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="agri_serv_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                I have Agri Equipments License
                                                <select class='have_agri_equip_license_no float-lg-right' placeholder="Agri Equipments License" id="have_agri_equip_license_no" name="have_agri_equip_license_no">
                                                    <option value="0" <?php echo ($seller_data->have_agri_equip_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                    <option value="1" <?php echo ($seller_data->have_agri_equip_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group agri_equip_license_div" style="<?php echo ($seller_data->have_agri_equip_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Agri Equipments License Number</label>
                                                        <input type="text" class='form-control' placeholder="Agri Equipments License Number" id="agri_equip_license_no" name="agri_equip_license_no" value="<?php echo $seller_data->agri_equip_license_no;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group agri_equip_license_div" style="<?php echo ($seller_data->have_agri_equip_license_no==0) ? 'display: none;' : '';?>">
                                                        <label>Expiry Date</label>
                                                        <input type="date" class='form-control' placeholder="Expiry Date" id="agri_equip_lic_expiry_date" name="agri_equip_lic_expiry_date" value="<?php echo $seller_data->agri_equip_lic_expiry_date;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row agri_equip_license_div" style="<?php echo ($seller_data->have_agri_equip_license_no==0) ? 'display: none;' : '';?>">
                                                <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                    <label>Upload Photo</label>
                                                    <div class="clearfix"></div>
                                                    <a class="btn btn-browse btn-primary btn-rounded btn-md mb-3" href="javascript:void(0);" onclick="$('#agri_equip_license_photo').click();">Select Photo</a>
                                                    
                                                    <label class="mb-1 mt-1">Or</label>
                                                    <label>Upload PDF</label>
                                                    <div class="clearfix"></div>
                                                    <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-md mb-2" href="javascript:void(0);" onclick="$('#agri_equip_license_pdf').click();">Select PDF</a>
                                                    <?php
                                                    if(file_exists($seller_data->agri_equip_license_pdf) && $seller_data->agri_equip_license_pdf!='')
                                                    {
                                                        ?>
                                                        <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->agri_equip_license_pdf;?>" target="_blank">View PDF</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <input type="hidden" name="old_agri_equip_license_photo" value="<?php echo $seller_data->agri_equip_license_photo; ?>" />
                                                    <input class="custom-file-input" id="agri_equip_license_photo" name="agri_equip_license_photo" type="file" onchange="readURL5(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    
                                                    <input type="hidden" name="old_agri_equip_license_pdf" value="<?php echo $seller_data->agri_equip_license_pdf; ?>" />
                                                    <input class="custom-file-input label-hide" id="agri_equip_license_pdf" name="agri_equip_license_pdf" type="file" accept="application/pdf" />
                                                </div>
                                                <div class="col-md-5 pb-1 pt-1">
                                                    <div id="img_div">
                                                        <?php
                                                        if(file_exists($seller_data->agri_equip_license_photo) && $seller_data->agri_equip_license_photo!='')
                                                        {
                                                            ?>
                                                            <img id="agri_equip_license_photo_prev" src="<?php echo base_url().$seller_data->agri_equip_license_photo;?>" alt="" style="width: 200px;" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="agri_equip_license_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php /* ?>
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
                                    <?php */ ?>
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
       $(".have_agri_serv_license_no").change(function (){
        
            var inputValue = $(this).val();//.attr("value");
            
            if(inputValue == 1)
            {
                jQuery(".agri_serv_license_div").show();
            }
            else if(inputValue == 0)
            {
                jQuery(".agri_serv_license_div").hide();
            }
       }); 
       $(".have_agri_equip_license_no").change(function (){
        
            var inputValue = $(this).val();//.attr("value");
            
            if(inputValue == 1)
            {
                jQuery(".agri_equip_license_div").show();
            }
            else if(inputValue == 0)
            {
                jQuery(".agri_equip_license_div").hide();
            }
       });
    });
	/*function readURL(input) {
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
	}*/
    function readURL1(input) {
     if(input.files && input.files[0]) {//Check if input has files.
    	 var reader = new FileReader(); //Initialize FileReader.
    		 reader.onload = function (e) {
    		 document.getElementById("img_div").style.display = "block";
    		 $('#fertilizer_license_photo_prev').attr('src', e.target.result);
    		 $("#fertilizer_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
    		 };
    		 reader.readAsDataURL(input.files[0]);
    	 } 
    	 else {
    		$('#fertilizer_license_photo_prev').attr('src', "#");
    	 }
    }
    
    function readURL2(input) {
     if(input.files && input.files[0]) {//Check if input has files.
    	 var reader = new FileReader(); //Initialize FileReader.
    		 reader.onload = function (e) {
    		 document.getElementById("img_div").style.display = "block";
    		 $('#pesticide_license_photo_prev').attr('src', e.target.result);
    		 $("#pesticide_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
    		 };
    		 reader.readAsDataURL(input.files[0]);
    	 } 
    	 else {
    		$('#pesticide_license_photo_prev').attr('src', "#");
    	 }
    }
    
    function readURL3(input) {
     if(input.files && input.files[0]) {//Check if input has files.
    	 var reader = new FileReader(); //Initialize FileReader.
    		 reader.onload = function (e) {
    		 document.getElementById("img_div").style.display = "block";
    		 $('#seeds_license_photo_prev').attr('src', e.target.result);
    		 $("#seeds_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
    		 };
    		 reader.readAsDataURL(input.files[0]);
    	 } 
    	 else {
    		$('#seeds_license_photo_prev').attr('src', "#");
    	 }
    }
    
    function readURL4(input) {
     if(input.files && input.files[0]) {//Check if input has files.
    	 var reader = new FileReader(); //Initialize FileReader.
    		 reader.onload = function (e) {
    		 document.getElementById("img_div").style.display = "block";
    		 $('#agri_serv_license_photo_prev').attr('src', e.target.result);
    		 $("#agri_serv_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
    		 };
    		 reader.readAsDataURL(input.files[0]);
    	 } 
    	 else {
    		$('#agri_serv_license_photo_prev').attr('src', "#");
    	 }
    }
    
    function readURL5(input) {
     if(input.files && input.files[0]) {//Check if input has files.
    	 var reader = new FileReader(); //Initialize FileReader.
    		 reader.onload = function (e) {
    		 document.getElementById("img_div").style.display = "block";
    		 $('#agri_equip_license_photo_prev').attr('src', e.target.result);
    		 $("#agri_equip_license_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
    		 };
    		 reader.readAsDataURL(input.files[0]);
    	 } 
    	 else {
    		$('#agri_equip_license_photo_prev').attr('src', "#");
    	 }
    }
</script>