<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content container -fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div id="account-dashboard" class="col-md-9 col-12 row">
                <div class="login-popup2 col-md-12 bg-white p-1">
                    <div class="">
                        <div class="col-md-12">
                            <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle btn-icon-left d-block d-lg-none mb-3"><!--<i class="w-icon-hamburger"></i>-->Menu</a>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-12" id="sign-in">
                                <h4>Settings</h4>
                                <h5 class="">Change Password</h5>
                                <form id='basic-details-form' class='basic-details-form not-editable' action='<?php echo base_url('my-account/change_password') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12"> 
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
                                                        <input class="custom-checkbox" type="checkbox" id="showPass"/> 
                                                        <label>Show Password</label>
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                                <button type="submit" class="submit_btn btn btn-primary btn-rounded btn-block--">Update</button>
                                                <button type="button" class="ed_btn btn btn-primary btn-rounded btn-block--">Edit</button>
                                                <button type="button" class="canc_btn btn btn-primary btn-rounded btn-block--">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
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
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
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
