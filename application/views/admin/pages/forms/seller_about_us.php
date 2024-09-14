<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Manufacturer</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Manufacturer</li>
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
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/edit-seller?edit_id='.$user_id) ?>">Basic Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/bussiness_details?edit_id='.$user_id) ?>">Business Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/bank_details?edit_id='.$user_id) ?>">Bank Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/gst_details?edit_id='.$user_id) ?>">GST</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/license_details?edit_id='.$user_id) ?>">License</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('admin/sellers/about_us?edit_id='.$user_id) ?>">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/business_card?edit_id='.$user_id) ?>">Business Card</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/settings?edit_id='.$user_id) ?>">Settings</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="col-md-12">
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('admin/sellers/save_about_us/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>About Company</label>
                                            <?php /* ?>
                                            <textarea class="form-control textarea" placeholder="About Company" id="about_us" name="about_us"><?= (isset($seller_data->about_us)) ? stripslashes($seller_data->about_us) : ''; ?></textarea>
                                            <?php */ ?>
                                            <?php echo $this->ckeditor->editor("about_us",$seller_data->about_us);?>
                                        </div>
                                        <div class="form-group">
                                            <label>Infrastructure & Facilities</label>
                                            <?php /* ?>
                                            <textarea class="form-control textarea" placeholder="Infrastructure & Facilities" id="infrastructure" name="infrastructure"><?= (isset($seller_data->infrastructure)) ? stripslashes($seller_data->infrastructure) : ''; ?></textarea>
                                            <?php */ ?>
                                            <?php echo $this->ckeditor->editor("infrastructure",$seller_data->infrastructure);?>
                                        </div>
                                        <div class="form-group">
                                            <label>Quality & Compliance</label>
                                            <?php /* ?>
                                            <textarea class="form-control textarea" placeholder="Quality & Compliance" id="quality_compliance" name="quality_compliance"><?= (isset($seller_data->quality_compliance)) ? stripslashes($seller_data->quality_compliance) : ''; ?></textarea>
                                            <?php */ ?>
                                            <?php echo $this->ckeditor->editor("quality_compliance",$seller_data->quality_compliance);?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="btn btn-primary btn-rounded btn-md btn-block--" for="quality_compliance_file">Select File</label>
                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                <input type="file" class="form-control" name="quality_compliance_file" id="quality_compliance_file" style="padding:0px;min-height: 28px;" onchange="$('#quality_compliance_file_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                            </div>
                                                            <p class=""><span id="quality_compliance_file_text"></span></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php
                                                        if(file_exists($seller_data->quality_compliance_file) && $seller_data->quality_compliance_file!='')
                                                        {
                                                            ?>
                                                            <a class="btn btn-primary btn-rounded btn-md btn-block--" href="<?php echo base_url().$seller_data->quality_compliance_file; ?>" target="_blank">View File</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Awards & Recognition</label>
                                            <?php /* ?>
                                            <textarea class="form-control textarea" placeholder="Awards & Recognition" id="awards_recognition" name="awards_recognition"><?= (isset($seller_data->awards_recognition)) ? stripslashes($seller_data->awards_recognition) : ''; ?></textarea>
                                            <?php */ ?>
                                            <?php echo $this->ckeditor->editor("awards_recognition",$seller_data->awards_recognition);?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="btn btn-primary btn-rounded btn-md btn-block--" for="awards_recognition_file">Select File</label>
                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                <input type="file" class="form-control" name="awards_recognition_file" id="awards_recognition_file" style="padding:0px;min-height: 28px;" onchange="$('#awards_recognition_file_text').html(this.value.replace('C:\\fakepath\\', ''));"  />
                                                            </div>
                                                            <p class=""><span id="awards_recognition_file_text"></span></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php
                                                        if(file_exists($seller_data->awards_recognition_file) && $seller_data->awards_recognition_file!='')
                                                        {
                                                            ?>
                                                            <a class="btn btn-primary btn-rounded btn-md btn-block--" href="<?php echo base_url().$seller_data->awards_recognition_file; ?>" target="_blank">View File</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="edit_seller" value="<?= $user_id ?>">
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
</script>