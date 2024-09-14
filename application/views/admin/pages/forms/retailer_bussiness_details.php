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
                                <a class="nav-link active" href="<?php echo base_url('admin/retailers/bussiness_details?edit_id='.$user_id) ?>">Business Details</a>
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
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/business_card?edit_id='.$user_id) ?>">Business Card</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('admin/retailers/settings?edit_id='.$user_id) ?>">Settings</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('admin/retailers/save_bussiness_details/') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>Firm Name *</label>
                                                    <input type="text" class='form-control' placeholder="Firm Name" id="company_name" name="company_name" value="<?php echo $retailer_data->company_name;?>"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Year of Establishment</label>
                                                        <input type="text" class='form-control' placeholder="Year of Establishment" id="year_establish" name="year_establish" value="<?php echo $retailer_data->year_establish;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Proprietor Name</label>
                                                        <input type="text" class='form-control' placeholder="Proprietor Name" id="owner_name" name="owner_name" value="<?php echo $retailer_data->owner_name;?>"/>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <hr />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Website URL</label>
                                                        <input type="text" class='form-control' placeholder="Website URL" id="website_url" name="website_url" value="<?php echo $retailer_data->website_url;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Google Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Google Business URL" id="google_business_url" name="google_business_url" value="<?php echo $retailer_data->google_business_url;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Facebook Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Facebook Business URL" id="facebook_business_url" name="facebook_business_url" value="<?php echo $retailer_data->facebook_business_url;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Instagram Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Instagram Business URL" id="instagram_business_url" name="instagram_business_url" value="<?php echo $retailer_data->instagram_business_url;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="form-group">
                                                <label>Sales office address</label><br />
                                                <label>Shop Name</label>
                                                <input type="text" class="form-control" name="shop_name" id="shop_name" placeholder="Shop Name" value="<?php echo $retailer_data->shop_name;?>"/>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Plot No. / Floor / Building  </label>
                                                        <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $retailer_data->plot_no;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Street / Locality / Village</label>
                                                        <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality" value="<?php echo $retailer_data->street_locality;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Land Mark</label>
                                                        <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $retailer_data->landmark;?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <select class="form-control" name="state_id" id="state_id" placeholder="State" onchange="getCities(this.value, 'city_id');">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $states = $this->db->query("SELECT * FROM `states` ORDER BY name ASC")->result_array();
                                                            if($states)
                                                            {
                                                                foreach($states as $state)
                                                                {
                                                                    $selected = "";
                                                                    if($retailer_data->state_id == $state['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($state['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>District</label>
                                                        <select class="form-control" name="city_id" id="city_id" placeholder="District">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if($retailer_data->state_id)
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` WHERE `state_id` = '".$retailer_data->state_id."' ORDER BY name ASC")->result_array();
                                                            }
                                                            else
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` ORDER BY name ASC")->result_array();   
                                                            }
                                                            if($cities)
                                                            {
                                                                foreach($cities as $city)
                                                                {
                                                                    $selected = "";
                                                                    if($retailer_data->city_id == $city['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($city['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Pin  </label>
                                                        <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" value="<?php echo $retailer_data->pin;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="form-group">
                                                <label>Storage address</label><br />
                                                <label>Shop Name</label>
                                                <input type="text" class="form-control" name="storage_shop_name" id="storage_shop_name" placeholder="Shop Name" value="<?php echo $retailer_data->storage_shop_name;?>"/>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Plot No. / Floor / Building  </label>
                                                        <input type="text" class="form-control" name="storage_plot_no" id="storage_plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $retailer_data->storage_plot_no;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Street / Locality / Village</label>
                                                        <input type="text" class="form-control" name="storage_street_locality" id="storage_street_locality" placeholder="Street / Locality" value="<?php echo $retailer_data->storage_street_locality;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Land Mark</label>
                                                        <input type="text" class="form-control" name="storage_landmark" id="storage_landmark" placeholder="Land Mark" value="<?php echo $retailer_data->storage_landmark;?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <select class="form-control" name="storage_state_id" id="storage_state_id" placeholder="State" onchange="getCities(this.value, 'storage_city_id');">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $states = $this->db->query("SELECT * FROM `states` ORDER BY name ASC")->result_array();
                                                            if($states)
                                                            {
                                                                foreach($states as $state)
                                                                {
                                                                    $selected = "";
                                                                    if($retailer_data->storage_state_id == $state['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($state['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>District</label>
                                                        <select class="form-control" name="storage_city_id" id="storage_city_id" placeholder="District">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if($retailer_data->storage_state_id)
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` WHERE `state_id` = '".$retailer_data->storage_state_id."' ORDER BY name ASC")->result_array();
                                                            }
                                                            else
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` ORDER BY name ASC")->result_array();   
                                                            }
                                                            if($cities)
                                                            {
                                                                foreach($cities as $city)
                                                                {
                                                                    $selected = "";
                                                                    if($retailer_data->storage_city_id == $city['id'])
                                                                    {
                                                                        $selected = "selected='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($city['name'])); ?></option>
                                                                    <?php       
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Pin  </label>
                                                        <input type="text" class="form-control" name="storage_pin" id="storage_pin" placeholder="Pin" value="<?php echo $retailer_data->storage_pin;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <input type="hidden" name="edit_retailer" value="<?= $user_id ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                                <input type="hidden" name="is_seller" value="<?php echo $is_seller; ?>" />
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"/>
                                                <button type="submit" class="submit_btn btn btn-primary btn-rounded btn-block--">Update</button>  
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-group" id="error_box2"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div id="img_div">
                                                    <?php
                                                    if(file_exists($retailer_data->logo) && $retailer_data->logo!='')
                                                    {
                                                        ?>
                                                        <img id="logo_prev" src="<?php echo base_url().$retailer_data->logo;?>" alt="" style="width: 150px;" onclick="$('#logo').click();" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="logo_prev" src="<?php echo base_url();?>uploads/retailer_logo/default.jpg" alt="" style="width: 150px;" onclick="$('#logo').click();" />  
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="old_logo" value="<?php echo $retailer_data->logo; ?>" />
                                                    <input class="custom-file-input" id="logo" name="logo" type="file" onchange="readURL(this);" accept="image/jpg, image/jpeg, image/png" />
                                                    <a class="btn btn-primary btn-rounded btn-sm mb-3" href="javascript:void(0);" onclick="$('#logo').click();">Select File</a>
                                                    <button type="submit" class="submit_btn btn btn-primary btn-sm btn-rounded mb-3">Upload</button>
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
			 $('#logo_prev').attr('src', e.target.result);
			 $("#logo_prev").resizable({ aspectRatio: true, maxHeight: 300 });
			 };
			 reader.readAsDataURL(input.files[0]);
		 } 
		 else {
			$('#logo_prev').attr('src', "#");
		 }
	}
    function getCities(state_id, city_div)
    {
        $.ajax({
            url: "<?=base_url()?>my_account/getCities",
            data:{state_id:state_id},
            method:"Post",
            cache: false
        }).done(function( html ) {
            $( "#"+city_div ).html( html );
        });
    }
</script>