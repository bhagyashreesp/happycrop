<style>#gst_no_photo_prev, #pan_no_photo_prev {max-height: 180px;object-fit: contain;}</style>
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
                                <a class="nav-link active" href="<?php echo base_url('seller/profile/gst_details/') ?>">GST</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/license_details/') ?>">License</a>
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
                                    <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('seller/profile/save_gst_details/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="form-group label-hide">
                                            <label>
                                            <input type="radio" placeholder="I have GST Number" class="have_gst_no" name="have_gst_no" value="1" <?php echo ($seller_data->have_gst_no==1) ? 'checked="checked"' : '';?> data-target="1"/>
                                            I have GST Number
                                            </label>
                                        </div>
                                        
                                        <div class="form-group gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                            <label>GST Number *</label>
                                            <input type="text" class='form-control' placeholder="GST Number" id="gst_no" name="gst_no" value="<?php echo $seller_data->gst_no; ?>"/>
                                        </div>
                                        
                                        <div class="row gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                            <div class="col-md-7 pb-lg-10 pt-lg-10">
                                                <label>Upload Photo</label>
                                                <div class="clearfix"></div>
                                                <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#gst_no_photo').click();">Select Photo</a>
                                                
                                                <br />
                                                <label class="mb-2 mt-2">Or</label><br />
                                                <label>Upload PDF</label><br />
                                                <div class="clearfix"></div>
                                                <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#gst_no_pdf').click();">Select PDF</a>
                                                <?php
                                                if(file_exists($seller_data->gst_no_pdf) && $seller_data->gst_no_pdf!='')
                                                {
                                                    ?>
                                                    <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-2" href="<?php echo base_url().$seller_data->gst_no_pdf;?>" target="_blank">View PDF</a>
                                                    <?php
                                                }
                                                ?>
                                                
                                                <input type="hidden" name="old_gst_no_photo" value="<?php echo $seller_data->gst_no_photo; ?>" />
                                                <input class="custom-file-input label-hide" id="gst_no_photo" name="gst_no_photo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                
                                                <input type="hidden" name="old_gst_no_pdf" value="<?php echo $seller_data->gst_no_pdf; ?>" />
                                                <input class="custom-file-input label-hide" id="gst_no_pdf" name="gst_no_pdf" type="file" accept="application/pdf" />
                                            </div>
                                            <div class="col-md-5 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->gst_no_photo) && $seller_data->gst_no_photo!='')
                                                    {
                                                        ?>
                                                        <img id="gst_no_photo_prev" src="<?php echo base_url().$seller_data->gst_no_photo;?>" alt="" style="width: 200px;" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="gst_no_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php /* ?>
                                        <div class="row gst_div" style="<?php echo ($seller_data->have_gst_no==1) ? '' : 'display: none;';?>">
                                            <div class="form-group col-md-6">
                                                <label>Upload Photo *</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary btn-rounded btn-sm btn-block--" for="gst_no_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="gst_no_photo" id="gst_no_photo" style="padding:0px;min-height: 28px;" onchange="$('#gst_no_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="gst_no_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <?php
                                                    if(file_exists($seller_data->gst_no_photo) && $seller_data->gst_no_photo!='')
                                                    {
                                                        ?>
                                                        <a class="btn btn-primary btn-rounded btn-sm btn-block--" href="<?php echo base_url().$seller_data->gst_no_photo; ?>" target="_blank">View File</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
                                        <div class="form-group label-hide">
                                            <label>
                                            <input type="radio" placeholder="I dont have GST Number" class="have_gst_no" name="have_gst_no" value="2" <?php echo ($seller_data->have_gst_no==2) ? 'checked="checked"' : '';?> data-target="2"/>
                                            I dont have GST Number
                                            </label>
                                        </div>
                                        
                                        <div class="form-group non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                            <label>PAN Number </label>
                                            <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN Number" value="<?php echo $seller_data->pan_number; ?>"/>
                                        </div>
                                        
                                        <div class="row non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                            <div class="col-md-7 pb-lg-10 pt-lg-10">
                                                <label>Upload Photo</label>
                                                <div class="clearfix"></div>
                                                <a class="btn btn-browse btn-primary btn-rounded btn-sm mb-2" href="javascript:void(0);" onclick="$('#pan_no_photo').click();">Select Photo</a>
                                                
                                                <br />
                                                <label class="mb-2 mt-2">Or</label><br />
                                                <label>Upload PDF</label>
                                                <div class="clearfix"></div>
                                                
                                                <a style="margin-top: 0px;" class="btn btn-browse btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#pan_no_pdf').click();">Select PDF</a>
                                                <?php
                                                if(file_exists($seller_data->pan_no_pdf) && $seller_data->pan_no_pdf!='')
                                                {
                                                    ?>
                                                    <a style="margin-top: 0px;" class="btn btn-primary btn-rounded btn-sm mb-3" href="<?php echo base_url().$seller_data->pan_no_pdf;?>" target="_blank">View PDF</a>
                                                    <?php
                                                }
                                                ?>
                                                
                                                <input type="hidden" name="old_pan_no_photo" value="<?php echo $seller_data->pan_no_photo; ?>" />
                                                <input class="custom-file-input label-hide" id="pan_no_photo" name="pan_no_photo" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                <!--<button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>-->
                                                
                                                <input type="hidden" name="old_pan_no_pdf" value="<?php echo $seller_data->pan_no_pdf; ?>" />
                                                <input class="custom-file-input label-hide" id="pan_no_pdf" name="pan_no_pdf" type="file" accept="application/pdf" />
                                            </div>
                                            <div class="col-md-5 pb-1 pt-1">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($seller_data->pan_no_photo) && $seller_data->pan_no_photo!='')
                                                    {
                                                        ?>
                                                        <img id="pan_no_photo_prev" src="<?php echo base_url().$seller_data->pan_no_photo;?>" alt="" style="width: 200px;" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="pan_no_photo_prev" src="<?php echo base_url();?>assets/no-image.jpg" alt="" style="width: 200px;" />  
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php /* ?>
                                        <div class="row non_gst_div" style="<?php echo ($seller_data->have_gst_no==2) ? '' : 'display: none;';?>">
                                            <div class="form-group col-md-6">
                                                <label>Upload Photo*</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary btn-rounded btn-sm btn-block--" for="pan_no_photo">Select File</label>
                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                            <input type="file" class="form-control" name="pan_no_photo" id="pan_no_photo" style="padding:0px;min-height: 28px;" onchange="$('#pan_no_photo_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                        </div>
                                                        <p class=""><span id="pan_no_photo_text"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <?php
                                                    if(file_exists($seller_data->pan_no_photo) && $seller_data->pan_no_photo!='')
                                                    {
                                                        ?>
                                                        <a class="btn btn-primary btn-rounded btn-sm btn-block--" href="<?php echo base_url().$seller_data->pan_no_photo; ?>" target="_blank">View File</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
                                        <div class="form-group">
                                            <label>TAN Number </label>
                                            <input type="text" class="form-control" name="tan_number" id="tan_number" placeholder="TAN Number" value="<?php echo $seller_data->tan_number; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>CIN / LLPIN Number </label>
                                            <input type="text" class="form-control" name="cin_number" id="cin_number" placeholder="CIN / LLPIN Number" value="<?php echo $seller_data->cin_number; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>IEC Number </label>
                                            <input type="text" class="form-control" name="iec_number" id="iec_number" placeholder="IEC Number" value="<?php echo $seller_data->iec_number; ?>"/>
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
   $(".have_gst_no").click(function (){
    
        var inputValue = $(this).attr("value");
        
        if(inputValue == 1)
        {
            jQuery(".gst_div").show();
            jQuery(".non_gst_div").hide();
        }
        else if(inputValue == 2)
        {
            jQuery(".gst_div").hide();
            jQuery(".non_gst_div").show();
        }
   }); 
});
function readURL(input) {
 if(input.files && input.files[0]) {//Check if input has files.
	 var reader = new FileReader(); //Initialize FileReader.
		reader.onload = function (e) {
            document.getElementById("img_div").style.display = "block";
            $('#gst_no_photo_prev').attr('src', e.target.result);
            $("#gst_no_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
        };
        reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#gst_no_photo_prev').attr('src', "#");
	 }
}

function readURL2(input) {
    if(input.files && input.files[0]) {//Check if input has files.
        var reader = new FileReader(); //Initialize FileReader.
        reader.onload = function (e) {
            document.getElementById("img_div").style.display = "block";
            $('#pan_no_photo_prev').attr('src', e.target.result);
            $("#pan_no_photo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
        };
		reader.readAsDataURL(input.files[0]);
	 } 
	 else {
		$('#pan_no_photo_prev').attr('src', "#");
	 }
}
</script>