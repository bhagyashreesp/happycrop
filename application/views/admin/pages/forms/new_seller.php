<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Manufacturer</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Manufacturer</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id='set_commission_model'>
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Categories & Commission(%)</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                                <form class="form-horizontal" id="add-seller-commission-form" action="<?= base_url('admin/sellers/add-seller-commission'); ?>" method="POST" enctype="multipart/form-data">

                                    <div class="card-body row">
                                        <!-- dynamic section here -->
                                        <label for="Categories" class="col-sm-2 col-form-label">Categories</label>

                                        <div id="category_section"> </div>

                                        <div class="form-group col-md-12  text-center">
                                            <button type="button" id="add_category" class="btn btn-primary"> <i class="far fa-plus"></i> Add More Category </button>
                                        </div>
                                        <br>
                                        <div class="form-group ">
                                            <button type="reset" class="btn btn-warning">Reset</button>
                                            <button type="submit" class="btn btn-success" id="save_btn">Save</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-group" id="error_box">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/sellers/add_new_seller'); ?>" method="POST" id="add_product_form">
                            <?php if (isset($fetched_data[0]['id'])) { ?>
                                <input type="hidden" name="edit_seller" value="<?= $fetched_data[0]['user_id'] ?>">
                                <input type="hidden" name="edit_seller_data_id" value="<?= $fetched_data[0]['id'] ?>">
                                <input type="hidden" name="old_address_proof" value="<?= $fetched_data[0]['address_proof'] ?>">
                                <input type="hidden" name="old_store_logo" value="<?= $fetched_data[0]['logo'] ?>">
                                <input type="hidden" name="old_national_identity_card" value="<?= $fetched_data[0]['national_identity_card'] ?>">
                            <?php
                            } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <textarea cols="20" rows="20" id="cat_data" name="commission_data" style="display:none;"></textarea>
                                    <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" placeholder="Seller Name" name="name" value="<?= @$fetched_data[0]['username'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" value="<?= @$fetched_data[0]['mobile'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?= @$fetched_data[0]['email'] ?>">
                                    </div>
                                </div>
                                <?php
                                if (!isset($fetched_data[0]['id'])) {
                                ?>
                                    <div class="form-group row ">
                                        <label for="password" class="col-sm-2 col-form-label">Password <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password" placeholder="Enter Passsword" name="password" value="<?= @$fetched_data[0]['password'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password" name="confirm_password">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php /* ?>
                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">Address <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="address" placeholder="Enter Address" name="address"><?= isset($fetched_data[0]['address']) ? @$fetched_data[0]['address'] : ""; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address_proof" class="col-sm-2 col-form-label">Address Proof <span class='text-danger text-sm'>*</span> </label>
                                    <div class="col-sm-10">
                                        <?php if (isset($fetched_data[0]['address_proof']) && !empty($fetched_data[0]['address_proof'])) { ?>
                                            <span class="text-danger">*Leave blank if there is no change</span>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="address_proof" id="address_proof" accept="image/*" />
                                    </div>
                                </div>
                                <?php if (isset($fetched_data[0]['address_proof']) && !empty($fetched_data[0]['address_proof'])) { ?>
                                    <div class="form-group row">
                                        <div class="mx-auto product-image"><a href="<?= base_url($fetched_data[0]['address_proof']); ?>" data-toggle="lightbox" data-gallery="gallery_seller"><img src="<?= base_url($fetched_data[0]['address_proof']); ?>" class="img-fluid rounded"></a></div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="commission" class="col-sm-2 col-form-label">Commission(%) <small>(Commission(%) to be given to the Super Admin on order item globally.)</small> </label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="global_commission" placeholder="Enter Commission(%) to be given to the Super Admin on order item." name="global_commission" value="<?= @$fetched_data[0]['commission'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <?php
                                    $category_html =  get_categories_option_html($categories);
                                    ?>
                                    <label for="commission" class="col-sm-8 col-form-label">Choose Categories & Commission(%) <small>(Commission(%) to be given to the Super Admin on order item by Category you select.If you do not set the commission beside category then it will get global commission other wise perticuler category commission will be consider.)</small> </label>
                                    <div style="display:none" id="cat_html">
                                        <?= $category_html ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 offset-2">
                                        <a href="javascript:void(0)" id="seller_model" data-seller_id="<?= (isset($fetched_data[0]['user_id']) && !empty($fetched_data[0]['user_id'])) ? $fetched_data[0]['user_id'] : ""; ?>" data-cat_ids="<?= (isset($fetched_data[0]['id']) &&  !empty($fetched_data[0]['id'])) ? $fetched_data[0]['category_ids'] : ""; ?>" class=" btn btn-block  btn-outline-primary btn-sm" title="Manage Categories & Commission" data-target="#set_commission_model" data-toggle="modal">Manage</a>
                                    </div>
                                </div>
                                <?php */ ?>
                                <h4>Business Details</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="company_name" class="col-sm-2 col-form-label">Company Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="company_name" placeholder="Company Name" name="company_name" value="<?= @$fetched_data[0]['company_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="brand_name_1" class="col-sm-2 col-form-label">Brand Name 1 <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="brand_name_1" placeholder="Brand Name 1" name="brand_name_1" value="<?= @$fetched_data[0]['brand_name_1'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="brand_name_2" class="col-sm-2 col-form-label">Brand Name 2 <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="brand_name_2" placeholder="Brand Name 2" name="brand_name_2" value="<?= @$fetched_data[0]['brand_name_2'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="brand_name_3" class="col-sm-2 col-form-label">Brand Name 3 <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="brand_name_3" placeholder="Brand Name 3" name="brand_name_3" value="<?= @$fetched_data[0]['brand_name_3'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="plot_no" class="col-sm-2 col-form-label">Plot No. / Floor / Building <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="plot_no" placeholder="Plot No. / Floor / Building" name="plot_no" value="<?= @$fetched_data[0]['plot_no'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="street_locality" class="col-sm-2 col-form-label">Street / Locality <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="street_locality" placeholder="Street / Locality" name="street_locality" value="<?= @$fetched_data[0]['street_locality'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="landmark" class="col-sm-2 col-form-label">Land Mark <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="landmark" placeholder="Land Mark" name="landmark" value="<?= @$fetched_data[0]['landmark'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pin" class="col-sm-2 col-form-label">Pin <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pin" placeholder="Pin" name="pin" value="<?= @$fetched_data[0]['pin'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company_name" class="col-sm-2 col-form-label">City <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="city" placeholder="City" name="city" value="<?= @$fetched_data[0]['city'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="state" class="col-sm-2 col-form-label">State <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="state" placeholder="State" name="state" value="<?= @$fetched_data[0]['state'] ?>">
                                    </div>
                                </div>
                                <?php /* ?>
                                <div class="form-group row">
                                    <label for="store_name" class="col-sm-2 col-form-label">State <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="store_name" placeholder="Store Name" name="store_name" value="<?= @$fetched_data[0]['store_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="store_url" class="col-sm-2 col-form-label">URL </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="store_url" placeholder="Store URL" name="store_url" value="<?= @$fetched_data[0]['store_url'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="store_description" class="col-sm-2 col-form-label">Description </label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="store_description" placeholder="Store Description" name="store_description"><?= isset($fetched_data[0]['store_description']) ? @$fetched_data[0]['store_description'] : ""; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="logo" class="col-sm-2 col-form-label">Logo <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <?php if (isset($fetched_data[0]['logo']) && !empty($fetched_data[0]['logo'])) { ?>
                                            <span class="text-danger">*Leave blank if there is no change</span>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="store_logo" id="store_logo" accept="image/*" />
                                    </div>
                                </div>
                                <?php if (isset($fetched_data[0]['logo']) && !empty($fetched_data[0]['logo'])) { ?>
                                    <div class="form-group row">
                                        <div class="mx-auto product-image"><a href="<?= base_url($fetched_data[0]['logo']); ?>" data-toggle="lightbox" data-gallery="gallery_seller"><img src="<?= base_url($fetched_data[0]['logo']); ?>" class="img-fluid rounded"></a></div>
                                    </div>
                                <?php } ?>
                                <?php */ ?>
                                
                                <h4>Bank Details</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="account_number" class="col-sm-2 col-form-label">Account Number </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_number" placeholder="Account Number" name="account_number" value="<?= @$fetched_data[0]['account_number'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="account_name" class="col-sm-2 col-form-label">Account Name </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_name" placeholder="Account Name" name="account_name" value="<?= @$fetched_data[0]['account_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_code" class="col-sm-2 col-form-label">IFSC Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_code" placeholder="IFSC Code" name="bank_code" value="<?= @$fetched_data[0]['bank_code'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_name" class="col-sm-2 col-form-label">Bank Name </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_name" placeholder="Bank Name" name="bank_name" value="<?= @$fetched_data[0]['bank_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_branch" class="col-sm-2 col-form-label">Bank Branch </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_branch" placeholder="Bank Branch" name="bank_branch" value="<?= @$fetched_data[0]['bank_branch'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_city" class="col-sm-2 col-form-label">Bank City </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_city" placeholder="Bank City" name="bank_city" value="<?= @$fetched_data[0]['bank_city'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_state" class="col-sm-2 col-form-label">Bank State </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_state" placeholder="Bank State" name="bank_state" value="<?= @$fetched_data[0]['bank_state'] ?>">
                                    </div>
                                </div>
                                <h4>GST</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="have_gst_no" class="col-sm-2 col-form-label">Have GST Number </label>
                                    <div class="col-sm-10">
                                        <div id="have_gst_no" class="btn-group col-sm-4">
                                            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_gst_no" value="1" <?= (isset($fetched_data[0]['have_gst_no']) && $fetched_data[0]['have_gst_no'] == '1') ? 'Checked' : '' ?>> Yes
                                            </label>
                                            <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_gst_no" value="2" <?= (isset($fetched_data[0]['have_gst_no']) && $fetched_data[0]['have_gst_no'] == '2') ? 'Checked' : '' ?>> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="gst_no" class="col-sm-2 col-form-label">GST Number </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="gst_no" placeholder="GST Number" name="gst_no" value="<?= @$fetched_data[0]['gst_no'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pan_number" class="col-sm-2 col-form-label">Pan Number </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pan_number" placeholder="Pan Number" name="pan_number" value="<?= @$fetched_data[0]['pan_number'] ?>">
                                    </div>
                                </div>
                                <h4>License</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="have_fertilizer_license" class="col-sm-2 col-form-label">Have Fertilizer License </label>
                                    <div class="col-sm-10">
                                        <div id="have_fertilizer_license" class="btn-group col-sm-4">
                                            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_fertilizer_license" value="1" <?= (isset($fetched_data[0]['have_fertilizer_license']) && $fetched_data[0]['have_fertilizer_license'] == '1') ? 'Checked' : '' ?>> Yes
                                            </label>
                                            <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_fertilizer_license" value="0" <?= (isset($fetched_data[0]['have_fertilizer_license']) && $fetched_data[0]['have_fertilizer_license'] == '0') ? 'Checked' : '' ?>> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fertilizer_license_no" class="col-sm-2 col-form-label">Fertilizer License No. </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fertilizer_license_no" placeholder="Fertilizer License Number" name="fertilizer_license_no" value="<?= @$fetched_data[0]['fertilizer_license_no'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="have_pesticide_license_no" class="col-sm-2 col-form-label">Have Pesticide License </label>
                                    <div class="col-sm-10">
                                        <div id="have_pesticide_license_no" class="btn-group col-sm-4">
                                            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_pesticide_license_no" value="1" <?= (isset($fetched_data[0]['have_pesticide_license_no']) && $fetched_data[0]['have_pesticide_license_no'] == '1') ? 'Checked' : '' ?>> Yes
                                            </label>
                                            <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_pesticide_license_no" value="0" <?= (isset($fetched_data[0]['have_pesticide_license_no']) && $fetched_data[0]['have_pesticide_license_no'] == '0') ? 'Checked' : '' ?>> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pesticide_license_no" class="col-sm-2 col-form-label">Pesticide License No. </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pesticide_license_no" placeholder="Pesticide License Number" name="pesticide_license_no" value="<?= @$fetched_data[0]['pesticide_license_no'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="have_fertilizer_license" class="col-sm-2 col-form-label">Have Seeds License </label>
                                    <div class="col-sm-10">
                                        <div id="have_seeds_license_no" class="btn-group col-sm-4">
                                            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_seeds_license_no" value="1" <?= (isset($fetched_data[0]['have_seeds_license_no']) && $fetched_data[0]['have_seeds_license_no'] == '1') ? 'Checked' : '' ?>> Yes
                                            </label>
                                            <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="have_seeds_license_no" value="0" <?= (isset($fetched_data[0]['have_seeds_license_no']) && $fetched_data[0]['have_seeds_license_no'] == '0') ? 'Checked' : '' ?>> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="seeds_license_no" class="col-sm-2 col-form-label">Seeds License No. </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="seeds_license_no" placeholder="Seeds License Number" name="seeds_license_no" value="<?= @$fetched_data[0]['seeds_license_no'] ?>">
                                    </div>
                                </div>
                                
                                <h4>Status Details</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Status <span class='text-danger text-sm'>*</span></label>
                                    <div id="status" class="btn-group col-sm-4">
                                        <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '0') ? 'Checked' : '' ?>> Deactive
                                        </label>
                                        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '1') ? 'Checked' : '' ?>> Approved
                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-danger" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="2" <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '0') ? 'Checked' : '' ?>> Not-Approved
                                        </label>
                                    </div>
                                </div>
                                <?php /* ?>
                                <div class="form-group row">
                                    <label for="national_identity_card" class="col-sm-2 col-form-label">National Identity Card <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <?php if (isset($fetched_data[0]['national_identity_card']) && !empty($fetched_data[0]['national_identity_card'])) { ?>
                                            <span class="text-danger">*Leave blank if there is no change</span>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="national_identity_card" id="national_identity_card" accept="image/*" />
                                    </div>
                                </div>
                                <?php if (isset($fetched_data[0]['national_identity_card']) && !empty($fetched_data[0]['national_identity_card'])) { ?>
                                    <div class="form-group row">
                                        <div class="mx-auto product-image"><a href="<?= base_url($fetched_data[0]['national_identity_card']); ?>" data-toggle="lightbox" data-gallery="gallery_seller"><img src="<?= base_url($fetched_data[0]['national_identity_card']); ?>" class="img-fluid rounded"></a></div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="tax_name" class="col-sm-2 col-form-label">Tax Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tax_name" placeholder="Tax Name" name="tax_name" value="<?= @$fetched_data[0]['tax_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_number" class="col-sm-2 col-form-label">Tax Number <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tax_number" placeholder="Tax Number" name="tax_number" value="<?= @$fetched_data[0]['tax_number'] ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="latitude" class="col-sm-2 col-form-label">Latitude </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="latitude" placeholder="Latitude" name="latitude" value="<?= @$fetched_data[0]['latitude'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="longitude" class="col-sm-2 col-form-label">Longitude </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="longitude" placeholder="Longitude" name="longitude" value="<?= @$fetched_data[0]['longitude'] ?>">
                                    </div>
                                </div>
                                <?php */ ?>
                                
                                <h4>Permissions </h4>
                                <hr>
                                <?php if (isset($fetched_data[0]['permissions']) && !empty($fetched_data[0]['permissions'])) {
                                    $permit = json_decode($fetched_data[0]['permissions'], true);
                                } ?>
                                <div class="form-group row">
                                    <label for="require_products_approval" class="col-sm-2 form-label">Require Product's Approval? <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="require_products_approval" <?= (isset($permit['require_products_approval']) && $permit['require_products_approval'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                                    <?php /* ?>
                                    <label for="customer_privacy" class="form-label">View Customer's Details? <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="customer_privacy" <?= (isset($permit['customer_privacy']) && $permit['customer_privacy'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                                    <label for="view_order_otp" class="form-label">View Order's OTP? & Can chnage deliver status? <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="view_order_otp" <?= (isset($permit['view_order_otp']) && $permit['view_order_otp'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                                    <label for="assign_delivery_boy" class="form-label">Can assign delivery boy? <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="assign_delivery_boy" <?= (isset($permit['assign_delivery_boy']) && $permit['assign_delivery_boy'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                                    <?php */ ?> 
                                </div>
                                
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="tax_name" placeholder="Tax Name" name="tax_name" value="<?= @$fetched_data[0]['tax_name'] ?>">
                                    <input type="hidden" class="form-control" id="tax_number" placeholder="Tax Number" name="tax_number" value="<?= @$fetched_data[0]['tax_number'] ?>">
                                    <input type="hidden" class="form-control" id="latitude" placeholder="Latitude" name="latitude" value="<?= @$fetched_data[0]['latitude'] ?>">
                                    <input type="hidden" class="form-control" id="longitude" placeholder="Longitude" name="longitude" value="<?= @$fetched_data[0]['longitude'] ?>">
                                    <!--<input type="hidden" name="require_products_approval" value="0"/>-->
                                    <input type="hidden" name="customer_privacy" value="0"/>
                                    <input type="hidden" name="view_order_otp" value="0"/>
                                    <input type="hidden" name="assign_delivery_boy" value="0"/>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Manufacturer' : 'Add Manufacturer' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                    <div class="card text-white d-none mb-3">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-footer -->
                        </form>


                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>