<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Retailer</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Retailer</li>
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
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/edit-retailer?edit_id='.$user_id) ?>">Basic Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/bussiness_details?edit_id='.$user_id) ?>">Business Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/bank_details?edit_id='.$user_id) ?>">Bank Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/gst_details?edit_id='.$user_id) ?>">GST</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('admin/retailers/license_details?edit_id='.$user_id) ?>">License</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/business_card?edit_id='.$user_id) ?>">Business Card</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/settings?edit_id='.$user_id) ?>">Settings</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="col-md-8">
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('admin/retailers/save_license_details/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>I have Fertilizer License</label>
                                            <select class='have_license_no float-right' placeholder="Fertilizer License" id="have_fertilizer_license" name="have_fertilizer_license">
                                                <option value="0" <?php echo ($retailer_data->have_fertilizer_license==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($retailer_data->have_fertilizer_license==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
                                        </div>
                                                                                
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group fertilizer_license_div" style="<?php echo ($retailer_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                            <label>Fertilizer License Number</label>
                                                            <input type="text" class='form-control' placeholder="Fertilizer License Number" id="fertilizer_license_no" name="fertilizer_license_no" value="<?php echo $retailer_data->fertilizer_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group fertilizer_license_div" style="<?php echo ($retailer_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="fert_lic_expiry_date" name="fert_lic_expiry_date" value="<?php echo $retailer_data->fert_lic_expiry_date;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                                                        
                                            <div class="col-md-7">
                                                <div class="row fertilizer_license_div"  style="<?php echo ($retailer_data->have_fertilizer_license==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-7 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#fertilizer_license_photo').click();">Select Photo</a>
                                                        <?php
                                                        if(file_exists($retailer_data->fertilizer_license_photo) && $retailer_data->fertilizer_license_photo!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->fertilizer_license_photo;?>" target="_blank">View Photo</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="fertilizer_license_photo_text"></span></p>
                                                        <label class="mb-1 mt-1">Or</label><br />
                                                        <label>Upload PDF</label><br />
                                                        <div class="clearfix"></div>
                                                        <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#fertilizer_license_pdf').click();">Select PDF</a>
                                                        <?php
                                                        if(file_exists($retailer_data->fertilizer_license_pdf) && $retailer_data->fertilizer_license_pdf!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->fertilizer_license_pdf;?>" target="_blank">View PDF</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="fertilizer_license_pdf_text"></span></p>
                                                        <input type="hidden" name="old_fertilizer_license_photo" value="<?php echo $retailer_data->fertilizer_license_photo; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="fertilizer_license_photo" name="fertilizer_license_photo" type="file" onchange="readURL1(this);$('#fertilizer_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="image/jpg, image/jpeg, image/png" />
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                        
                                                        <input type="hidden" name="old_fertilizer_license_pdf" value="<?php echo $retailer_data->fertilizer_license_pdf; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="fertilizer_license_pdf" name="fertilizer_license_pdf" type="file" accept="application/pdf" onchange="$('#fertilizer_license_pdf_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                    </div>
                                                    <div class="col-md-5 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($retailer_data->fertilizer_license_photo) && $retailer_data->fertilizer_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="fertilizer_license_photo_prev" src="<?php echo base_url().$retailer_data->fertilizer_license_photo;?>" alt="" style="width: 200px;" />
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
                                        
                                        <div class="form-group">
                                            <label>I have Pesticide License</label>
                                            <select class='have_pesticide_license_no float-right' placeholder="Pesticide License" id="have_pesticide_license_no" name="have_pesticide_license_no">
                                                <option value="0" <?php echo ($retailer_data->have_pesticide_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($retailer_data->have_pesticide_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group pesticide_license_div" style="<?php echo ($retailer_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Pesticide License Number</label>
                                                            <input type="text" class='form-control' placeholder="Pesticide License Number" id="pesticide_license_no" name="pesticide_license_no" value="<?php echo $retailer_data->pesticide_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group pesticide_license_div" style="<?php echo ($retailer_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="pest_lic_expiry_date" name="pest_lic_expiry_date" value="<?php echo $retailer_data->pest_lic_expiry_date;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row pesticide_license_div" style="<?php echo ($retailer_data->have_pesticide_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-7 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#pesticide_license_photo').click();">Select Photo</a>
                                                        <?php
                                                        if(file_exists($retailer_data->pesticide_license_photo) && $retailer_data->pesticide_license_photo!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->pesticide_license_photo;?>" target="_blank">View Photo</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="pesticide_license_photo_text"></span></p>
                                                        <label class="mb-1 mt-1">Or</label><br />
                                                        <label>Upload PDF</label><br />
                                                        <div class="clearfix"></div>
                                                        <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#pesticide_license_pdf').click();">Select PDF</a>
                                                        <?php
                                                        if(file_exists($retailer_data->pesticide_license_pdf) && $retailer_data->pesticide_license_pdf!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->pesticide_license_pdf;?>" target="_blank">View PDF</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="pesticide_license_pdf_text"></span></p>
                                                        <input type="hidden" name="old_pesticide_license_photo" value="<?php echo $retailer_data->pesticide_license_photo; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="pesticide_license_photo" name="pesticide_license_photo" type="file" onchange="readURL2(this);$('#pesticide_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="image/jpg, image/jpeg, image/png" />
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                        
                                                        <input type="hidden" name="old_pesticide_license_pdf" value="<?php echo $retailer_data->pesticide_license_pdf; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="pesticide_license_pdf" name="pesticide_license_pdf" type="file" accept="application/pdf" onchange="$('#pesticide_license_pdf_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                    </div>
                                                    <div class="col-md-5 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($retailer_data->pesticide_license_photo) && $retailer_data->pesticide_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="pesticide_license_photo_prev" src="<?php echo base_url().$retailer_data->pesticide_license_photo;?>" alt="" style="width: 200px;" />
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
                                        
                                        <div class="form-group">
                                            <label>I have Seeds License</label>
                                            <select class='have_seeds_license_no float-right' placeholder="Seeds License" id="have_seeds_license_no" name="have_seeds_license_no">
                                                <option value="0" <?php echo ($retailer_data->have_seeds_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                <option value="1" <?php echo ($retailer_data->have_seeds_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                            </select>
                                        </div>
                                                                                
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group seeds_license_div" style="<?php echo ($retailer_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Seeds License Number</label>
                                                            <input type="text" class='form-control' placeholder="Seeds License Number" id="seeds_license_no" name="seeds_license_no" value="<?php echo $retailer_data->seeds_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group seeds_license_div" style="<?php echo ($retailer_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="seeds_lic_expiry_date" name="seeds_lic_expiry_date" value="<?php echo $retailer_data->seeds_lic_expiry_date;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row seeds_license_div" style="<?php echo ($retailer_data->have_seeds_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-7 pb-lg-10 pt-lg-10">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#seeds_license_photo').click();">Select Photo</a>
                                                        <?php
                                                        if(file_exists($retailer_data->seeds_license_photo) && $retailer_data->seeds_license_photo!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->seeds_license_photo;?>" target="_blank">View Photo</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="seeds_license_photo_text"></span></p>
                                                        <label class="mb-1 mt-1">Or</label><br />
                                                        <label>Upload PDF</label><br />
                                                        <div class="clearfix"></div>
                                                        <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#seeds_license_pdf').click();">Select PDF</a>
                                                        <?php
                                                        if(file_exists($retailer_data->seeds_license_pdf) && $retailer_data->seeds_license_pdf!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->seeds_license_pdf;?>" target="_blank">View PDF</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="seeds_license_pdf_text"></span></p>
                                                        <input type="hidden" name="old_seeds_license_photo" value="<?php echo $retailer_data->seeds_license_photo; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="seeds_license_photo" name="seeds_license_photo" type="file" onchange="readURL3(this);$('#seeds_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="image/jpg, image/jpeg, image/png" />
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                        
                                                        <input type="hidden" name="old_seeds_license_pdf" value="<?php echo $retailer_data->seeds_license_pdf; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="seeds_license_pdf" name="seeds_license_pdf" type="file" accept="application/pdf" onchange="$('#seeds_license_pdf_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                    </div>
                                                    <div class="col-md-5 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($retailer_data->seeds_license_photo) && $retailer_data->seeds_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="seeds_license_photo_prev" src="<?php echo base_url().$retailer_data->seeds_license_photo;?>" alt="" style="width: 200px;" />
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
                                                    <label class="d-block">
                                                    I have Agri Services License
                                                    <select class='have_agri_serv_license_no float-lg-right' placeholder="Agri Services License" id="have_agri_serv_license_no" name="have_agri_serv_license_no">
                                                        <option value="0" <?php echo ($retailer_data->have_agri_serv_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                        <option value="1" <?php echo ($retailer_data->have_agri_serv_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                    </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group agri_serv_license_div" style="<?php echo ($retailer_data->have_agri_serv_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Agri Services License Number</label>
                                                            <input type="text" class='form-control' placeholder="Agri Services License Number" id="agri_serv_license_no" name="agri_serv_license_no" value="<?php echo $retailer_data->agri_serv_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group agri_serv_license_div" style="<?php echo ($retailer_data->have_agri_serv_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="agri_serv_lic_expiry_date" name="agri_serv_lic_expiry_date" value="<?php echo $retailer_data->agri_serv_lic_expiry_date;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row agri_serv_license_div" style="<?php echo ($retailer_data->have_agri_serv_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#agri_serv_license_photo').click();">Select Photo</a>
                                                        <?php
                                                        if(file_exists($retailer_data->agri_serv_license_photo) && $retailer_data->agri_serv_license_photo!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->agri_serv_license_photo;?>" target="_blank">View Photo</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="agri_serv_license_photo_text"></span></p>
                                                        <label class="mb-1 mt-1">Or</label><br />
                                                        <label>Upload PDF</label><br />
                                                        <div class="clearfix"></div>
                                                        <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#agri_serv_license_pdf').click();">Select PDF</a>
                                                        <?php
                                                        if(file_exists($retailer_data->agri_serv_license_pdf) && $retailer_data->agri_serv_license_pdf!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->agri_serv_license_pdf;?>" target="_blank">View PDF</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="agri_serv_license_pdf_text"></span></p>
                                                        <input type="hidden" name="old_agri_serv_license_photo" value="<?php echo $retailer_data->agri_serv_license_photo; ?>" />
                                                        <input class="custom-file-input d-none" id="agri_serv_license_photo" name="agri_serv_license_photo" type="file" onchange="readURL4(this);$('#agri_serv_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="image/jpg, image/jpeg, image/png" />
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                        
                                                        <input type="hidden" name="old_agri_serv_license_pdf" value="<?php echo $retailer_data->agri_serv_license_pdf; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="agri_serv_license_pdf" name="agri_serv_license_pdf" type="file" accept="application/pdf" onchange="$('#agri_serv_license_pdf_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                    </div>
                                                    <div class="col-md-5 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($retailer_data->agri_serv_license_photo) && $retailer_data->agri_serv_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="agri_serv_license_photo_prev" src="<?php echo base_url().$retailer_data->agri_serv_license_photo;?>" alt="" style="width: 200px;" />
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
                                                    <label class="d-block">
                                                    I have Agri Equipments License
                                                    <select class='have_agri_equip_license_no float-lg-right' placeholder="Agri Equipments License" id="have_agri_equip_license_no" name="have_agri_equip_license_no">
                                                        <option value="0" <?php echo ($retailer_data->have_agri_equip_license_no==0) ? 'selected="selected"' : '';?>>No</option>
                                                        <option value="1" <?php echo ($retailer_data->have_agri_equip_license_no==1) ? 'selected="selected"' : '';?>>Yes</option>
                                                    </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group agri_equip_license_div" style="<?php echo ($retailer_data->have_agri_equip_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Agri Equipments License Number</label>
                                                            <input type="text" class='form-control' placeholder="Agri Equipments License Number" id="agri_equip_license_no" name="agri_equip_license_no" value="<?php echo $retailer_data->agri_equip_license_no;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group agri_equip_license_div" style="<?php echo ($retailer_data->have_agri_equip_license_no==0) ? 'display: none;' : '';?>">
                                                            <label>Expiry Date</label>
                                                            <input type="date" class='form-control' placeholder="Expiry Date" id="agri_equip_lic_expiry_date" name="agri_equip_lic_expiry_date" value="<?php echo $retailer_data->agri_equip_lic_expiry_date;?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row agri_equip_license_div" style="<?php echo ($retailer_data->have_agri_equip_license_no==0) ? 'display: none;' : '';?>">
                                                    <div class="col-md-7 pb-lg-3 pt-lg-3">
                                                        <label>Upload Photo</label>
                                                        <div class="clearfix"></div>
                                                        <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#agri_equip_license_photo').click();">Select Photo</a>
                                                        <?php
                                                        if(file_exists($retailer_data->agri_equip_license_photo) && $retailer_data->agri_equip_license_photo!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->agri_equip_license_photo;?>" target="_blank">View Photo</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="agri_equip_license_photo_text"></span></p>
                                                        <label class="mb-1 mt-1">Or</label><br />
                                                        <label>Upload PDF</label><br />
                                                        <div class="clearfix"></div>
                                                        <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#agri_equip_license_pdf').click();">Select PDF</a>
                                                        <?php
                                                        if(file_exists($retailer_data->agri_equip_license_pdf) && $retailer_data->agri_equip_license_pdf!='')
                                                        {
                                                            ?>
                                                            <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$retailer_data->agri_equip_license_pdf;?>" target="_blank">View PDF</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <p class=""><span id="agri_equip_license_pdf_text"></span></p>
                                                        <input type="hidden" name="old_agri_equip_license_photo" value="<?php echo $retailer_data->agri_equip_license_photo; ?>" />
                                                        <input class="custom-file-input d-none" id="agri_equip_license_photo" name="agri_equip_license_photo" type="file" onchange="readURL5(this);$('#agri_equip_license_photo_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="image/jpg, image/jpeg, image/png" />
                                                        <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                        
                                                        <input type="hidden" name="old_agri_equip_license_pdf" value="<?php echo $retailer_data->agri_equip_license_pdf; ?>" />
                                                        <input class="custom-file-input d-none label-hide" id="agri_equip_license_pdf" name="agri_equip_license_pdf" type="file" accept="application/pdf" onchange="$('#agri_equip_license_pdf_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                    </div>
                                                    <div class="col-md-5 pb-1 pt-1">
                                                        <div id="img_div">
                                                            <?php
                                                            if(file_exists($retailer_data->agri_equip_license_photo) && $retailer_data->agri_equip_license_photo!='')
                                                            {
                                                                ?>
                                                                <img id="agri_equip_license_photo_prev" src="<?php echo base_url().$retailer_data->agri_equip_license_photo;?>" alt="" style="width: 200px;" />
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
                                        
                                        <hr />
                                        
                                        <div class="row">
                                            <div class="col-md-6 pb-lg-10 pt-lg-10">
                                                <label>O form / Principle certificate</label><br />
                                                <label>Upload Photo</label><br />
                                                <div class="clearfix"></div>
                                                <a class="btn btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#o_form_photo').click();">Select File</a>
                                                <input type="hidden" name="old_o_form_photo" value="<?php echo $retailer_data->o_form_photo; ?>" />
                                                <input class="custom-file-input" id="o_form_photo" name="o_form_photo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                            </div>
                                            <div class="col-md-4 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($retailer_data->o_form_photo) && $retailer_data->o_form_photo!='')
                                                    {
                                                        ?>
                                                        <img id="o_form_photo_prev" src="<?php echo base_url().$retailer_data->o_form_photo;?>" alt="" style="width: 150px;" onclick="$('#o_form_photo').click();" />
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
                                        <div class="form-group">
                                            <input type="hidden" name="edit_retailer" value="<?= $user_id ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                            <button type="submit" class="submit_btn btn btn-primary btn-block--">Update</button>
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