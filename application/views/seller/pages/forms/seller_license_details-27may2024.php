<style>#fertilizer_license_photo_prev, #pesticide_license_photo_prev, #seeds_license_photo_prev {max-height: 180px;object-fit: contain;}</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Profile</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <ul class="nav nav-tabs" id="myTab" >
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/') ?>">Basic Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/bussiness_details/') ?>">Business Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/bank_details/') ?>">Bank Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/gst_details/') ?>">GST</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('seller/profile/license_details/') ?>">License</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/about_us/') ?>">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/business_card/') ?>">Business Card</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="col-md-6">
                                    <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('seller/profile/save_license_details/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="d-block">
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
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
                                                <div class="row fertilizer_license_div"  style="<?php echo ($seller_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_fertilizer_license_photo" value="<?php echo $seller_data->fertilizer_license_photo; ?>" />
                                                        <input class="custom-file-input label-hide" id="fertilizer_license_photo" name="fertilizer_license_photo" type="file" onchange="readURL1(this);" accept="image/jpg, image/jpeg, image/png" />
                                                        <a style="margin-top: -25px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#fertilizer_license_photo').click();">Select Photo</a>
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    </div>
                                                    <div class="col-md-6 pb-1 pt-1">
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
                                                    <label class="d-block">
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
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
                                                <div class="row pesticide_license_div" style="<?php echo ($seller_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_pesticide_license_photo" value="<?php echo $seller_data->pesticide_license_photo; ?>" />
                                                        <input class="custom-file-input label-hide" id="pesticide_license_photo" name="pesticide_license_photo" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                        <a style="margin-top: -25px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#pesticide_license_photo').click();">Select Photo</a>
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    </div>
                                                    <div class="col-md-6 pb-1 pt-1">
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
                                                    <label class="d-block">
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
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
                                                <div class="row seeds_license_div" style="<?php echo ($seller_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_seeds_license_photo" value="<?php echo $seller_data->seeds_license_photo; ?>" />
                                                        <input class="custom-file-input label-hide" id="seeds_license_photo" name="seeds_license_photo" type="file" onchange="readURL3(this);" accept="image/jpg, image/jpeg, image/png" />
                                                        <a style="margin-top: -25px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#seeds_license_photo').click();">Select Photo</a>
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                    </div>
                                                    <div class="col-md-6 pb-1 pt-1">
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
                                        
                                        <?php /* ?>
                                        <div class="form-group">
                                            <label>I have Fertilizer License</label>
                                            <select class='have_license_no float-right' placeholder="Fertilizer License" id="have_fertilizer_license" name="have_fertilizer_license">
                                                <option value="0" <?php echo ($seller_data->have_fertilizer_license==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($seller_data->have_fertilizer_license==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
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
                                            <?php *//* ?>
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
                                                        <div class="col-md-4">
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
                                        <div class="form-group">
                                            <label>I have Pesticide License</label>
                                            <select class='have_pesticide_license_no float-right' placeholder="Pesticide License" id="have_pesticide_license_no" name="have_pesticide_license_no">
                                                <option value="0" <?php echo ($seller_data->have_pesticide_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($seller_data->have_pesticide_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
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
                                            <?php *//* ?>
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
                                                        <div class="col-md-4">
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
                                        <div class="form-group">
                                            <label>I have Seeds License</label>
                                            <select class='have_seeds_license_no float-right' placeholder="Seeds License" id="have_seeds_license_no" name="have_seeds_license_no">
                                                <option value="0" <?php echo ($seller_data->have_seeds_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($seller_data->have_seeds_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
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
                                            <?php *//* ?>
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
                                                        <div class="col-md-4">
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
                                        <?php */ ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                <label>O form / Principle certificate *</label>
                                                <label>Upload Photo *</label>
                                                <div class="clearfix"></div>
                                                <input type="hidden" name="old_o_form_photo" value="<?php echo $seller_data->o_form_photo; ?>" />
                                                <input class="custom-file-input label-hide" id="o_form_photo" name="o_form_photo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <a class="btn btn-primary btn-browse btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#o_form_photo').click();">Select File</a>
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            </div>
                                            <div class="col-md-4 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->o_form_photo) && $seller_data->o_form_photo!='')
                                                    {
                                                        ?>
                                                        <img id="o_form_photo_prev" src="<?php echo base_url().$seller_data->o_form_photo;?>" alt="" style="width: 150px;" /> <!--  onclick="$('#o_form_photo').click();" -->
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="o_form_photo_prev" src="<?php echo base_url();?>assets/o-form.png" alt="" style="width: 150px;" /><!--  onclick="$('#o_form_photo').click();" -->  
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="submit_btn btn btn-primary btn-block--">Update</button>
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
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
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
</script>