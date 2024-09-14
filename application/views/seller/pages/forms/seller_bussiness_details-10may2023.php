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
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/about_us/') ?>">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('seller/profile/business_card/') ?>">Business Card</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <div class="p-3">
                                <form id='basic-details-form' class='basic-details-form' action='<?php echo base_url('seller/profile/save_bussiness_details/') ?>' method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>Company Name *</label>
                                                    <input type="text" class='form-control' placeholder="Company Name" id="company_name" name="company_name" value="<?php echo $seller_data->company_name;?>"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Year of Establishment</label>
                                                        <input type="text" class='form-control' placeholder="Year of Establishment" id="year_establish" name="year_establish" value="<?php echo $seller_data->year_establish;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>CEO Name</label>
                                                        <input type="text" class='form-control' placeholder="CEO Name" id="owner_name" name="owner_name" value="<?php echo $seller_data->owner_name;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Brand Name</label>
                                                        <input type="text" class='form-control' placeholder="Brand Name" id="brand_name" name="brand_name" value=""/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group pt-2">
                                                        <br />
                                                        <button id="btn_brand" type="button" class="btn btn-primary" onclick="addBrandName(0);">Add Brand Name</button>
                                                        <script type="text/javascript">
                                                        function addBrandName()
                                                        {
                                                            var error_box = $('#error_box2');
                                                            var brand_name = $("#brand_name").val();
                                                            
                                                            if(brand_name=='')
                                                            {
                                                                alert('Please Enter Brand Name');
                                                                return false;
                                                            }
                                                            else
                                                            {
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: '<?php echo base_url();?>seller/profile/addBrandName',
                                                                    data: {brand_name:brand_name},
                                                                    beforeSend: function () {
                                                                        $("#btn_brand").html('Please Wait..');
                                                                        $("#btn_brand").attr('disabled', true);
                                                                    },
                                                                    dataType: 'json',
                                                                    success: function (result) {
                                                                        if (result['error'] == true) 
                                                                        {
                                                                            error_box.addClass("rounded p-3 alert alert-danger").removeClass('d-none alert-success');
                                                                            error_box.show().delay(5000).fadeOut();
                                                                            error_box.html(result['message']);
                                                                            $("#brand_name").val('');
                                                                            $("#btn_brand").html('Add Brand Name');
                                                                            $("#btn_brand").attr('disabled', false);
                                                                        }
                                                                        else
                                                                        {
                                                                            error_box.addClass("rounded p-3 alert alert-success").removeClass('d-none alert-danger');
                                                                            error_box.show().delay(3000).fadeOut();
                                                                            error_box.html(result['message']);
                                                                            $("#brand_name").val('');
                                                                            $("#btn_brand").html('Add Brand Name');
                                                                            $("#btn_brand").attr('disabled', false); 
                                                                            $("#brands_div").html(result['brands_html']);  
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                            
                                                        }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="brands_div">
                                                        <?php 
                                                        if($seller_brands) 
                                                        { 
                                                            foreach($seller_brands as $seller_brand)
                                                            {
                                                                ?> 
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Brand Name</label>
                                                                            <input type="text" class='form-control' placeholder="Brand Name" id="brand_name_<?php echo $seller_brand['id']; ?>" name="brand_name_<?php echo $seller_brand['id']; ?>" value="<?php echo $seller_brand['brand_name']; ?>"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group pt-2">
                                                                            <br />
                                                                            <button id="btn_brand_<?php echo $seller_brand['id']; ?>" type="button" class="btn btn-primary" onclick="updateBrandName(<?php echo $seller_brand['id']; ?>);">Update</button>
                                                                        </div>
                                                                    </div>
                                                                </div>      
                                                                <?php
                                                            } 
                                                            ?>
                                                            <?php
                                                        } 
                                                        ?>
                                                    </div>
                                                    <script type="text/javascript">
                                                    function updateBrandName(id)
                                                    {
                                                        var error_box = $('#error_box2');
                                                        var brand_name = $("#brand_name_"+id).val();
                                                        
                                                        if(brand_name=='')
                                                        {
                                                            alert('Please Enter Brand Name');
                                                            return false;
                                                        }
                                                        else
                                                        {
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: '<?php echo base_url();?>seller/profile/updateBrandName',
                                                                data: {id:id,brand_name:brand_name},
                                                                beforeSend: function () {
                                                                    $("#btn_brand_"+id).html('Please Wait..');
                                                                    $("#btn_brand_"+id).attr('disabled', true);
                                                                },
                                                                dataType: 'json',
                                                                success: function (result) {
                                                                    if (result['error'] == true) 
                                                                    {
                                                                        error_box.addClass("rounded p-3 alert alert-danger").removeClass('d-none alert-success');
                                                                        error_box.show().delay(5000).fadeOut();
                                                                        error_box.html(result['message']);
                                                                        $("#btn_brand_"+id).html('Update');
                                                                        $("#btn_brand_"+id).attr('disabled', false);
                                                                    }
                                                                    else
                                                                    {
                                                                        error_box.addClass("rounded p-3 alert alert-success").removeClass('d-none alert-danger');
                                                                        error_box.show().delay(3000).fadeOut();
                                                                        error_box.html(result['message']);
                                                                        $("#btn_brand_"+id).html('Update');
                                                                        $("#btn_brand_"+id).attr('disabled', false);   
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        
                                                    }
                                                    </script>
                                                </div>
                                            </div>
                                            
                                            <hr />
                                            <div class="row">
                                                <?php /* ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Brand Name 1 </label>
                                                        <input type="text" class='form-control' placeholder="Brand Name 1" id="brand_name_1" name="brand_name_1" value="<?php echo $seller_data->brand_name_1;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Brand Name 2 </label>
                                                        <input type="text" class='form-control' placeholder="Brand Name 2" id="brand_name_2" name="brand_name_2" value="<?php echo $seller_data->brand_name_2;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Brand Name 3 </label>
                                                        <input type="text" class='form-control' placeholder="Brand Name 3" id="brand_name_3" name="brand_name_3" value="<?php echo $seller_data->brand_name_3;?>"/>
                                                    </div>
                                                </div>
                                                <?php */ ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Website URL</label>
                                                        <input type="text" class='form-control' placeholder="Website URL" id="website_url" name="website_url" value="<?php echo $seller_data->website_url;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Google Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Google Business URL" id="google_business_url" name="google_business_url" value="<?php echo $seller_data->google_business_url;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Facebook Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Facebook Business URL" id="facebook_business_url" name="facebook_business_url" value="<?php echo $seller_data->facebook_business_url;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Instagram Business URL</label>
                                                        <input type="text" class='form-control' placeholder="Instagram Business URL" id="instagram_business_url" name="instagram_business_url" value="<?php echo $seller_data->instagram_business_url;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Ownership Type</label>
                                                        <input type="text" class='form-control' placeholder="Ownership Type" id="ownership_type" name="ownership_type" value="<?php echo $seller_data->ownership_type;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Annual Turnover</label>
                                                        <input type="text" class='form-control' placeholder="Annual Turnover" id="annual_turnover" name="annual_turnover" value="<?php echo $seller_data->annual_turnover;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Number of Employees</label>
                                                        <input type="text" class='form-control' placeholder="Number of Employees" id="number_of_employees" name="number_of_employees" value="<?php echo $seller_data->number_of_employees;?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Minimum Order Value  </label>
                                                        <input type="text" class="form-control" name="min_order_value" id="min_order_value" placeholder="Minimum Order Value" value="<?php echo $seller_data->min_order_value;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="form-group">
                                                <label>Address</label><br />
                                                <label>Plot No. / Floor / Building  </label>
                                                <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $seller_data->plot_no;?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Street / Locality / Village</label>
                                                <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality" value="<?php echo $seller_data->street_locality;?>"/>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Land Mark</label>
                                                        <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $seller_data->landmark;?>" />
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
                                                                    if($seller_data->state_id == $state['id'])
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
                                                            if($seller_data->state_id)
                                                            {
                                                                $cities = $this->db->query("SELECT * FROM `cities` WHERE `state_id` = '".$seller_data->state_id."' ORDER BY name ASC")->result_array();
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
                                                                    if($seller_data->city_id == $city['id'])
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
                                                        <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" value="<?php echo $seller_data->pin;?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
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
                                                    if(file_exists($seller_data->logo) && $seller_data->logo!='')
                                                    {
                                                        ?>
                                                        <img id="logo_prev" src="<?php echo base_url().$seller_data->logo;?>" alt="" style="width: 150px;" onclick="$('#logo').click();" />
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="logo_prev" src="<?php echo base_url();?>uploads/seller_logo/default.jpg" alt="" style="width: 150px;" onclick="$('#logo').click();" />  
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="old_logo" value="<?php echo $seller_data->logo; ?>" />
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