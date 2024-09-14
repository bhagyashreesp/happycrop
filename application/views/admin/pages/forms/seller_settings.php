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
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/about_us?edit_id='.$user_id) ?>">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/sellers/business_card?edit_id='.$user_id) ?>">Business Card</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('admin/sellers/settings?edit_id='.$user_id) ?>">Settings</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="col-md-8">
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('admin/sellers/save_settings/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Status <span class='text-danger text-sm'>*</span></label>
                                            <div id="status" class="btn-group col-sm-8">
                                                <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="0" <?= (isset($seller_data->status) && $seller_data->status == '0') ? 'Checked' : '' ?>> Deactive
                                                </label>
                                                <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="1" <?= (isset($seller_data->status) && $seller_data->status == '1') ? 'Checked' : '' ?>> Approved
                                                </label>
                                                <label class="btn btn-danger" data-toggle-class="btn-danger" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="2" <?= (isset($seller_data->status) && $seller_data->status == '2') ? 'Checked' : '' ?>> Not-Approved
                                                </label>
                                            </div>
                                        </div>
                                        <?php if (isset($seller_data->permissions) && !empty($seller_data->permissions)) {
                                            $permit = json_decode($seller_data->permissions, true);
                                        } ?>
                                        <div class="form-group row">
                                            <label for="require_products_approval" class="col-sm-3 form-label">Req. Product's Approval? <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-sm-8">
                                                <input type="checkbox" name="require_products_approval" <?= (!isset($permit['require_products_approval']) || $permit['require_products_approval'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="customer_privacy" value="0"/>
                                                    <input type="hidden" name="view_order_otp" value="0"/>
                                                    <input type="hidden" name="assign_delivery_boy" value="0"/>
                                                    <input type="hidden" name="edit_seller" value="<?= $user_id ?>">
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