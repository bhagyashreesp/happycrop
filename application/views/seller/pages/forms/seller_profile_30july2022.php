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
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
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
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="col-md-4">
                                <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('seller/profile/save_basic_details') ?>' method="post">
                                    <div class="form-group">
                                        <label>Your Name *</label>
                                        <input type="text" class='form-control' placeholder="Your Name" id="username" name="username" value="<?php echo $user->username; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile No. *</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile number" value="<?php echo $user->mobile; ?>" readonly=""/>
                                    </div>
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>"/>
                                    </div>
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
                                    <div class="form-group">
                                        <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                        <button type="submit" class="submit_btn btn btn-primary btn-block">Update</button>
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