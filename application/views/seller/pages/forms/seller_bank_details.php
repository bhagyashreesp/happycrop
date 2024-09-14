<style>#cancelled_cheque_prev{max-height: 180px;object-fit: contain;}</style>
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
                                <a class="nav-link active" href="<?php echo base_url('seller/profile/bank_details/') ?>">Bank Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/gst_details/') ?>">GST</a>
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
                                <div class="col-md-4">
                                    <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('seller/profile/save_bank_details/') ?>' method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Account Holder Name *</label>
                                            <input type="text" class='form-control' placeholder="Account Holder Name" id="account_name" name="account_name" value="<?php echo $seller_data->account_name;?>" autocomplete="off"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Account Number *</label>
                                            <input type="text" class='form-control' placeholder="Account Number" id="account_number" name="account_number" value="<?php echo $seller_data->account_number;?>" onpaste="return false;" ondrop="return false;" autocomplete="off"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Account Number * </label>
                                            <input type="text" class="form-control" name="confirm_account_number" id="confirm_account_number" placeholder="Confirm Account Number" value="<?php echo $seller_data->account_number;?>" onpaste="return false;" ondrop="return false;" autocomplete="off"/>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>IFSC Code *</label>
                                                <input type="text" class="form-control" name="bank_code" id="bank_code" placeholder="IFSC Code" value="<?php echo $seller_data->bank_code;?>"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Account Type *</label>
                                                <!--<input type="text" class="form-control" name="account_type" id="account_type" placeholder="Account Type" value="<?php echo $seller_data->account_type;?>"/>-->
                                                <select class="form-control" name="account_type" id="account_type" placeholder="Account Type">
                                                    <option value="">Select</option>
                                                    <option value="current" <?php echo ($seller_data->account_type=='current') ? 'selected="selected"' : '';?>>Current</option>
                                                    <option value="saving" <?php echo ($seller_data->account_type=='saving') ? 'selected="selected"' : '';?>>Saving</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Bank Name *</label>
                                            <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" value="<?php echo $seller_data->bank_name;?>" />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Branch *</label>
                                                <input type="text" class="form-control" name="bank_branch" id="bank_branch" placeholder="Branch" value="<?php echo $seller_data->bank_branch;?>"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>City *</label>
                                                <input type="text" class="form-control" name="bank_city" id="bank_city" placeholder="City" value="<?php echo $seller_data->bank_city;?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>State *</label>
                                            <input type="text" class="form-control" name="bank_state" id="bank_state" placeholder="State" value="<?php echo $seller_data->bank_state;?>" />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Upload cancelled cheque*</label>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="text-center">
                                                            <div id="img_div">
                                                                <?php
                                                                if(file_exists($seller_data->cancelled_cheque) && $seller_data->cancelled_cheque!='')
                                                                {
                                                                    ?>
                                                                    <img id="cancelled_cheque_prev" src="<?php echo base_url().$seller_data->cancelled_cheque;?>" alt="" style="width: 100%;" /><!--  onclick="$('#cancelled_cheque').click();" -->
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <img id="cancelled_cheque_prev" src="<?php echo base_url();?>assets/cancelled_cheque.png" alt="" style="width: 100%;" /> <!--  onclick="$('#cancelled_cheque').click();" -->  
                                                                    <?php
                                                                }
                                                                ?>
                                                                <div class="clearfix"></div>
                                                                <input type="hidden" name="old_cancelled_cheque" value="<?php echo $seller_data->cancelled_cheque; ?>" />
                                                                <input class="custom-file-input" id="cancelled_cheque" name="cancelled_cheque" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                                <a class="btn btn-primary btn-browse btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#cancelled_cheque').click();">Select File</a>
                                                                <button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
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
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script>
	function readURL(input) {
	 if(input.files && input.files[0]) {//Check if input has files.
		 var reader = new FileReader(); //Initialize FileReader.
			 reader.onload = function (e) {
			 document.getElementById("img_div").style.display = "block";
			 $('#cancelled_cheque_prev').attr('src', e.target.result);
			 $("#cancelled_cheque_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#cancelled_cheque_prev').attr('src', "#");
		 }
	}
</script>