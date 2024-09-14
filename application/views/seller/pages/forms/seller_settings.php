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
                        
                        <div class="col-md-12">
                            <div class="p-3">
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('seller/profile/save_profile_settings') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-8"> 
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Old Password</label>
                                                        <input type="password" class="form-control" name="old" id="old" placeholder="Password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <input type="password" class="form-control" name="new" id="new" placeholder="Password" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group label-hide pt-2">
                                                        <br />
                                                        <label>
                                                        <input class="custom-checkbox" type="checkbox" id="showPass"/>&nbsp;&nbsp; 
                                                         Show Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Confirm New Password</label>
                                                        <input type="password" class="form-control" name="new_confirm" id="new_confirm" placeholder="Confirm Password" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                            <button type="submit" class="submit_btn btn btn-primary btn-block--">Update</button>
                                            <button type="button" class="ed_btn btn btn-primary btn-rounded btn-block--">Edit</button>
                                            <button type="button" class="canc_btn btn btn-primary btn-rounded btn-block--">Cancel</button>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group" id="error_box2"></div>
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
<script type='text/javascript'>
    $(document).ready(function(){
        $('#showPass').on('click', function(){
            var passInput=$("#new");
            if(passInput.attr('type')==='password')
            {
                passInput.attr('type','text');
            }
            else
            {
                passInput.attr('type','password');
            }
        });
    });
</script>