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
                                <a class="nav-link active" href="<?php echo base_url('seller/profile/') ?>">Basic Details</a>
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
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('seller/profile/save_basic_details') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact Person Name *</label>
                                                <input type="text" class='form-control' placeholder="Contact Person Name" id="username" name="username" value="<?php echo $user->username; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Designation</label>
                                                <input type="text" class='form-control' placeholder="Designation" id="designation" name="designation" value="<?php echo $user->designation; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile No. *</label>
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile number" value="<?php echo $user->mobile; ?>" readonly=""/>
                                            </div>
                                            <div class="form-group">
                                                <label>Alternate Mobile No. </label>
                                                <input type="text" class="form-control" name="alternate_mobile" id="alternate_mobile" placeholder="Alternate Mobile No." value="<?php echo $user->alternate_mobile; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Land Line No.</label>
                                                <input type="text" class="form-control" name="landline" id="landline" placeholder="Land Line No." value="<?php echo $user->landline; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Email *</label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Alternate Email </label>
                                                <input type="text" class="form-control" name="alternate_email" id="alternate_email" placeholder="Alternate Email" value="<?php echo $user->alternate_email; ?>"/>
                                            </div>
                                            <?php /* ?>
                                            <div class="form-group">
                                                <label>Old Password *</label>
                                                <input type="password" class="form-control" name="old" id="old" placeholder="Password" />
                                            </div>
                                            <div class="form-group">
                                                <label>New Password *</label>
                                                <input type="password" class="form-control" name="new" id="new" placeholder="Password" />
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm New Password *</label>
                                                <input type="password" class="form-control" name="new_confirm" id="new_confirm" placeholder="Confirm Password" />
                                            </div>
                                            <?php */ ?>
                                            <div class="form-group">
                                                <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                                <button type="submit" class="submit_btn btn btn-primary btn-block--">Update</button>
                                                <button type="button" class="ed_btn btn btn-primary btn-rounded btn-block--">Edit</button>
                                                <button type="button" class="canc_btn btn btn-primary btn-rounded btn-block--">Cancel</button>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-group" id="error_box2"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($user->profile_img) && $user->profile_img!='')
                                                    {
                                                        ?>
                                                        <img id="profile_img_prev" src="<?php echo base_url().$user->profile_img;?>" alt="" style="width: 150px;" onclick="$('#profile_img').click();" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="profile_img_prev" src="<?php echo base_url();?>uploads/user_avatar/default.jpg" alt="" style="width: 150px;" onclick="$('#profile_img').click();" />  
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="old_profile_img" value="<?php echo $user->profile_img; ?>" />
                                                    <input class="custom-file-input" id="profile_img" name="profile_img" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
			 $('#profile_img_prev').attr('src', e.target.result);
			 $("#profile_img_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#profile_img_prev').attr('src', "#");
		 }
	}
</script>