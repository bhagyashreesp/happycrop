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
                                <a class="nav-link" href="<?php echo base_url('seller/profile/') ?>">Basic Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('seller/profile/bussiness_details/') ?>">Business Details</a>
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
                                    <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('seller/profile/save_bussiness_details/') ?>' method="post">
                                        <div class="form-group">
                                            <label>Company Name *</label>
                                            <input type="text" class='form-control' placeholder="Company Name" id="company_name" name="company_name" value="<?php echo $seller_data->company_name;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Name 1 </label>
                                            <input type="text" class='form-control' placeholder="Brand Name 1" id="brand_name_1" name="brand_name_1" value="<?php echo $seller_data->brand_name_1;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Name 2 </label>
                                            <input type="text" class='form-control' placeholder="Brand Name 2" id="brand_name_2" name="brand_name_2" value="<?php echo $seller_data->brand_name_2;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Name 3 </label>
                                            <input type="text" class='form-control' placeholder="Brand Name 3" id="brand_name_3" name="brand_name_3" value="<?php echo $seller_data->brand_name_3;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Plot No. / Floor / Building  </label>
                                            <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $seller_data->plot_no;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Street / Locality</label>
                                            <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality" value="<?php echo $seller_data->street_locality;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Land Mark</label>
                                            <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $seller_data->landmark;?>" />
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Pin  </label>
                                                <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" value="<?php echo $seller_data->pin;?>"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City" value="<?php echo $seller_data->city;?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="state" id="state" placeholder="State" value="<?php echo $seller_data->state;?>" />
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
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