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
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/license_details?edit_id='.$user_id) ?>">License</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('admin/retailers/business_card?edit_id='.$user_id) ?>">Business Card</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/settings?edit_id='.$user_id) ?>">Settings</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="col-md-8">
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('admin/retailers/save_business_card/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="text-left">
                                                    <label>Front View</label>
                                                    <div id="front_img_div">
                                                        <?php
                                                        if(file_exists($retailer_data->business_card_front) && $retailer_data->business_card_front!='')
                                                        {
                                                            ?>
                                                            <img id="business_card_front_prev" src="<?php echo base_url().$retailer_data->business_card_front;?>" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_front').click();" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="business_card_front_prev" src="<?php echo base_url();?>uploads/retailer_business_card_front/default.jpg" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_front').click();" />  
                                                            <?php
                                                        }
                                                        ?>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_business_card_front" value="<?php echo $retailer_data->business_card_front; ?>" />
                                                        <input class="custom-file-input" id="business_card_front" name="business_card_front" type="file" onchange="readURL1(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-left">
                                                    <label>Back View</label>
                                                    <div id="back_img_div">
                                                        <?php
                                                        if(file_exists($retailer_data->business_card_back) && $retailer_data->business_card_back!='')
                                                        {
                                                            ?>
                                                            <img id="business_card_back_prev" src="<?php echo base_url().$retailer_data->business_card_back;?>" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_back').click();" />
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img id="business_card_back_prev" src="<?php echo base_url();?>uploads/retailer_business_card_back/default.jpg" alt="" style="max-width: 310px;height: 170px;" onclick="$('#business_card_back').click();" />  
                                                            <?php
                                                        }
                                                        ?>
                                                        <div class="clearfix"></div>
                                                        <input type="hidden" name="old_business_card_back" value="<?php echo $retailer_data->business_card_back; ?>" />
                                                        <input class="custom-file-input" id="business_card_back" name="business_card_back" type="file" onchange="readURL2(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="edit_retailer" value="<?= $user_id ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                                    <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-group" id="error_box2"></div>
                                                </div>
                                            </div>
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
<script>
    function readURL1(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("front_img_div").style.display = "block";
			 $('#business_card_front_prev').attr('src', e.target.result);
			 $("#business_card_front_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#business_card_front_prev').attr('src', "#");
		 }
	}
	function readURL2(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("back_img_div").style.display = "block";
			 $('#business_card_back_prev').attr('src', e.target.result);
			 $("#business_card_back_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#business_card_back_prev').attr('src', "#");
		 }
	}
</script>