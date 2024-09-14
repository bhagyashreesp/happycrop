<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?= isset($product_details[0]['id']) ? 'Update' : 'Add' ?> Product</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
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
                        <form class="form-horizontal" action="<?= base_url('seller/product/add_new_product'); ?>" method="POST" enctype="multipart/form-data" id="save-product3"><!-- save-product2 -->
                            <input type="hidden" name="super_category_id" value="<?php echo $super_category_id; ?>" />
                            <input type="hidden" name="cod_allowed" value="1">
                            <input type="hidden" name="seller_id" value="<?= (isset($seller_id) && !empty($seller_id)) ? $seller_id : $_SESSION['user_id']; ?>">
                            <!--<input type="hidden" name="product_type" value="<?= isset($product_details[0]['type']) ? $product_details[0]['type'] : '' ?>">-->
                            <input type="hidden" name="variant_stock_status" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'variable_product' ? 'value="0"'  : '' ?>>
                            <input type="hidden" name="indicator" value="0"/>
                            <input type="hidden" id="deliverable_type" name="deliverable_type" value="1" />
                            <input type="hidden" name="is_prices_inclusive_tax" value="1" />
                            <input type="hidden" class="col-md-12 form-control" name="minimum_order_quantity" min="1" value="<?= (isset($product_details[0]['minimum_order_quantity'])) ? $product_details[0]['minimum_order_quantity'] : 1; ?>" placeholder='Minimum Order Quantity'>
                        
                            <?php if (isset($product_details[0]['id'])) {
                            ?>
                                <input type="hidden" name="edit_product_id" value="<?= (isset($product_details[0]['id'])) ? $product_details[0]['id'] : "" ?>">
                                <input type="hidden" id="subcategory_id_js" value="<?= (isset($product_details[0]['subcategory_id'])) ? $product_details[0]['subcategory_id'] : "" ?>">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <?php 
                                        $fn = '';
                                        if($super_category_id == 5 ) 
                                        { 
                                            $fn = "getInsectPests(this.value,'target_insect_id')";
                                        }
                                        ?>
                                        <label for="parent_id" class="col-form-label">Main Category <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control select_search' name='parent_id' id="parent_id" onchange="getSubcategories(this.value,'category_id');<?php echo $fn; ?>">
                                            <option value="">Select </option>
                                            <?php foreach ($categories as $category) { ?>
                                                <option value="<?= $category['id'] ?>" <?= (isset($product_details[0]['parent_id']) && $product_details[0]['parent_id'] == $category['id']) ? 'selected' : "" ?>><?= $category['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="category_id" class="col-form-label"><?php echo ($super_category_id != 5 ) ? 'Sub Category' : 'Formulation'; ?> <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control select_search' name='category_id' id="category_id" onchange="getSubcategories(this.value,'formulation_id');">
                                            <option value="">Select</option>
                                            <?php foreach ($sub_categories as $sub_category) { ?>
                                                <option value="<?= $sub_category['id'] ?>" <?= (isset($product_details[0]['category_id']) && $product_details[0]['category_id'] == $sub_category['id']) ? 'selected' : "" ?>><?= $sub_category['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php if($super_category_id != 5 ) { ?> 
                                    <div class="col-md-4">
                                        <label for="formulation_id" class="col-form-label">Formulation <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control select_search' name='formulation_id' id="formulation_id" onchange="getSpecifications(this.value);">
                                            <option value="">Select</option>
                                            <?php foreach ($formulations as $formulation) { ?>
                                                <option value="<?= $formulation['id'] ?>" <?= (isset($product_details[0]['formulation_id']) && $product_details[0]['formulation_id'] == $formulation['id']) ? 'selected' : "" ?>><?= $formulation['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="target_insect_id[]" value="" />
                                    <?php } else if($super_category_id == 5 ) { ?>
                                    <div class="col-md-4">
                                        <label for="target_insect_id" class="col-form-label">Target Insect/Pest <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control select_search' name='target_insect_id[]' id="target_insect_id" multiple="">
                                            <?php
                                            if($product_details[0]['category_id'])
                                            {
                                                $this->db->select('id, name');
                                                $this->db->from('insect_pests');
                                                $this->db->where('status', 1);
                                                $this->db->where('sub_category_id', $product_details[0]['parent_id']);
                                                $query = $this->db->get();
                                                $insect_pests = $query->result();
                                                if($insect_pests)
                                                {
                                                    $selected_insects = explode(',', $product_details[0]['target_insect_id']);
                                                    foreach($insect_pests as $insect_pest)
                                                    {
                                                        $selected = '';
                                                        if(in_array("$insect_pest->id",$selected_insects))
                                                        {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option value="<?= $insect_pest->id ?>" <?php echo $selected; ?>><?= $insect_pest->name ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <label for="brands" class="col-form-label">Brands</label>
                                        <select class='form-control' name='brand_id' id="brand_id">
                                            <option value="">Select</option>
                                            <?php
                                            /*$seller_data = $this->db->get_where('seller_data', array('user_id'=>$this->ion_auth->get_user_id()))->row();
                                            if($seller_data->brand_name_1!='')
                                            {
                                                ?>
                                                <option value="<?php echo $seller_data->brand_name_1;?>" <?= (isset($product_details[0]['brand_id']) && $product_details[0]['brand_id'] == $seller_data->brand_name_1) ? 'selected' : "" ?>><?php echo $seller_data->brand_name_1;?></option>
                                                <?php 
                                            }
                                            
                                            if($seller_data->brand_name_2!='')
                                            {
                                                ?>
                                                <option value="<?php echo $seller_data->brand_name_2;?>" <?= (isset($product_details[0]['brand_id']) && $product_details[0]['brand_id'] == $seller_data->brand_name_2) ? 'selected' : "" ?>><?php echo $seller_data->brand_name_2;?></option>
                                                <?php 
                                            }
                                            
                                            if($seller_data->brand_name_3!='')
                                            {
                                                ?>
                                                <option value="<?php echo $seller_data->brand_name_3;?>" <?= (isset($product_details[0]['brand_id']) && $product_details[0]['brand_id'] == $seller_data->brand_name_3) ? 'selected' : "" ?>><?php echo $seller_data->brand_name_3;?></option>
                                                <?php 
                                            }*/
                                            $seller_brands = $this->db->get_where('seller_brands', array('user_id'=>$this->ion_auth->get_user_id()))->result();
                                            if($seller_brands)
                                            {
                                                foreach($seller_brands as $seller_brand)
                                                {
                                                    ?>
                                                    <option value="<?php echo $seller_brand->id;?>" <?= (isset($product_details[0]['brand_id']) && $product_details[0]['brand_id'] == $seller_brand->id) ? 'selected' : "" ?>><?php echo $seller_brand->brand_name;?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pro_input_text" class="col-form-label">Product Name</label>
                                        <input type="text" class="col-md-12 form-control" id="pro_input_text" name="pro_input_name" value="<?= (isset($product_details[0]['name'])) ? $product_details[0]['name'] : "" ?>" placeholder='Product Name'/>
                                    </div>
                                    <?php 
                                    if($super_category_id != 3)
                                    {
                                        ?>
                                        <div class="col-md-4">
                                            <label for="form_id" class="col-form-label">Physical Appearance <span class='text-danger text-sm'>*</span></label>
                                            <select class='form-control' name='form_id' id="form_id"><?php //  onchange="getUnitSize(this.value);" ?>
                                                <option value="">Select</option>
                                                <?php foreach ($forms as $form) { ?>
                                                    <option value="<?= $form['id'] ?>" <?= (isset($product_details[0]['form_id']) && $product_details[0]['form_id'] == $form['id']) ? 'selected' : "" ?>><?= $form['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <div class="col-md-4">
                                            <label for="seed_type_id" class="col-form-label">Seed type <span class='text-danger text-sm'>*</span></label>
                                            <select class='form-control' name='seed_type_id' id="seed_type_id">
                                                <option value="">Select</option>
                                                <?php foreach ($seed_types as $seed_type) { ?>
                                                    <option value="<?= $seed_type['id'] ?>" <?= (isset($product_details[0]['seed_type_id']) && $product_details[0]['seed_type_id'] == $seed_type['id']) ? 'selected' : "" ?>><?= $seed_type['seed_type'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php /* ?>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <input type="hidden" id="type" name="type" class="form-control" value="simple_product"/>
                                        <label for="simple_mrp" class="">MRP(inc GST):</label>
                                        <input type="number" name="simple_mrp" class="form-control col-md-12" value="<?php echo (isset($product_variants[0]['mrp'])) ? $product_variants[0]['mrp'] : "" ?>" min='0' step="0.01"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="simple_price" class="">Regular Price(inc GST):</label>
                                        <input type="number" name="simple_price" class="form-control col-md-12" value="<?php echo (isset($product_variants[0]['price'])) ? $product_variants[0]['price'] : "" ?>" min='0' step="0.01"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type" class="">Discounted Price(inc GST):</label>
                                        <input type="number" name="simple_special_price" class="form-control col-md-12 discounted_price" value="<?php echo (isset($product_variants[0]['special_price'])) ? $product_variants[0]['special_price'] : "" ?>" min='0' step="0.01">
                                    </div>
                                </div>
                                <?php */ ?>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <label for="pro_input_tax" class="col-form-label">GST</label>
                                        <select class="col-md-12 form-control" name="pro_input_tax">
                                            <?php if (empty($taxes)) { ?>
                                                <option value="0" selected> No Taxes Are Added </option>
                                            <?php } ?>
                                            <?php foreach ($taxes as $row) {
                                                if (isset($product_details[0]['tax']) && $product_details[0]['tax'] == $row['id']) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            ?>
                                                <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['title'] ?></option>
                                            <?php
                                            } ?>
                                        </select>

                                    </div>
                                    <?php /* ?>
                                    <div class="col-md-4">
                                        <label for="unit_size_id" class="">Unit Size</label>
                                        <select class='form-control' name='unit_size_id' id="unit_size_id">
                                            <option value="">Select</option>
                                            <?php foreach ($unit_sizes as $unit_size) { ?>
                                                <option value="<?= $unit_size['id'] ?>" <?= (isset($product_details[0]['unit_size_id']) && $product_details[0]['unit_size_id'] == $unit_size['id']) ? 'selected' : "" ?>><?= $unit_size['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" name="simple_product_stock_status" value="1" />
                                        <label class="control-label">Total Stock :</label>
                                        <input type="text" name="product_total_stock" placeholder="Total Stock" class="col form-control stock-simple-mustfill-field" <?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] != NULL) ? ' value="' . $product_details[0]['stock'] . '" ' : '' ?>>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="minimum_order_quantity" class="col-form-label">Minimum Order Quantity</label>
                                        <input type="number" class="col-md-12 form-control" name="minimum_order_quantity" min="1" value="<?= (isset($product_details[0]['minimum_order_quantity'])) ? $product_details[0]['minimum_order_quantity'] : 1; ?>" placeholder='Minimum Order Quantity'>
                                    </div>
                                    <?php */ ?>
                                    <!--
                                    <div class="col-md-4">
                                        <label for="licence_no" class="col-form-label">Licence Number</label>
                                        <input type="text" class="form-control" id="licence_no" name="licence_no" value="<?= (isset($product_details[0]['licence_no'])) ? $product_details[0]['licence_no'] : "" ?>" placeholder='Licence Number'/>
                                    </div>-->
                                    <div class="col-md-4">
                                        <label for="made_in" class="col-form-label">Country Of Origin </label>
                                        <input type="text" class="col-md-12 form-control" name="made_in" value="<?= (isset($product_details[0]['made_in'])) ? $product_details[0]['made_in'] : ''; ?>" placeholder='Country Of Origin'>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="hsn_no" class="col-form-label">HSN Number </label>
                                        <input type="text" class="col-md-12 form-control" name="hsn_no" value="<?= (isset($product_details[0]['hsn_no'])) ? $product_details[0]['hsn_no'] : ''; ?>" placeholder='HSN Number'>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="licence_no" class="col-form-label">Licence Number <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="licence_no" name="licence_no" value="<?= (isset($product_details[0]['licence_no'])) ? $product_details[0]['licence_no'] : $licence_no; ?>" placeholder='Licence Number'/>
                                    </div>
                                    <?php 
                                    if($super_category_id == 1 || $super_category_id == 2)
                                    {
                                        ?>
                                        <div class="col-md-4">
                                            <label for="toxicity_id" class="col-form-label">Toxicity</label>
                                            <select class='form-control' name='toxicity_id' id="toxicity_id">
                                                <option value="">Select</option>
                                                <?php foreach ($toxicities as $toxicity) { ?>
                                                    <option value="<?= $toxicity['id'] ?>" <?= (isset($product_details[0]['toxicity_id']) && $product_details[0]['toxicity_id'] == $toxicity['id']) ? 'selected' : "" ?>><?= $toxicity['toxicity_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php
                                    }  
                                    ?>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">Main Image <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-sm-10">
                                                <div class='col-md-12 mb-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='pro_input_image' data-isremovable='0' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                <?php
                                                if (isset($product_details[0]['id']) && !empty($product_details[0]['id'])) {
                                                ?>
                                                    <div class="container-fluid row image-upload-section ">
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-2 bg-white rounded m--4 text-center grow image">
                                                            <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= BASE_URL() . $product_details[0]['image'] ?>" alt="Image Not Found"></div>
                                                            <input type="hidden" name="pro_input_image" value='<?= $product_details[0]['image'] ?>'>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger mt-3">*Only Choose When Update is necessary</small>
                                                <?php
                                                } else { ?>
                                                    <div class="container-fluid row image-upload-section">
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-2 bg-white rounded m--4 text-center grow image d-none">
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="other_images">Other Images </label>
                                            <div class="col-sm-12">
                                                <div class='col-md-12 mb-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='other_images[]' data-isremovable='1' data-is-multiple-uploads-allowed='1' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                <?php
                                                if (isset($product_details[0]['id']) && !empty($product_details[0]['id'])) {
                                                ?>
                                                    <div class="container-fluid row image-upload-section">
                                                        <?php
                                                        $other_images = json_decode($product_details[0]['other_images']);
                                                        if (!empty($other_images)) {
                                                            foreach ($other_images as $row) {
                                                        ?>
                                                                <div class="col-md-3 col-sm-12 shadow bg-white rounded mb-3 p-3 text-center grow">
                                                                    <div class='image-upload-div'><img src="<?= BASE_URL()  . $row ?>" alt="Image Not Found"></div>
                                                                    <a href="javascript:void(0)" class="delete-img mb-3" data-id="<?= $product_details[0]['id'] ?>" data-field="other_images" data-img="<?= $row ?>" data-table="products" data-path="<?= $row ?>" data-isjson="true">
                                                                        <span class="btn btn-block bg-gradient-danger btn-xs"><i class="far fa-trash-alt "></i> Delete</span></a>
                                                                    <input type="hidden" name="other_images[]" value='<?= $row ?>'>
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                <?php
                                                } else { ?>
                                                    <div class="container-fluid row image-upload-section">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group d-flex">
                                            <div class="col-md-6">
                                                <label for="video_type" class="col-form-label">Video Type</label>
                                                <select class='form-control' name='video_type' id='video_type'>
                                                    <option value='' <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == '' || $product_details[0]['video_type'] == NULL)) ? 'selected' : ''; ?>>None</option>
                                                    <!--<option value='self_hosted' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'self_hosted') ? 'selected' : ''; ?>>Self Hosted</option>-->
                                                    <option value='youtube' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'youtube') ? 'selected' : ''; ?>>Youtube</option>
                                                    <option value='vimeo' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'vimeo') ? 'selected' : ''; ?>>Vimeo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'youtube' ||  $product_details[0]['video_type'] == 'vimeo')) ? '' : 'd-none'; ?>" id="video_link_container">
                                                <label for="video" class="col-form-label">Video Link <span class='text-danger text-sm'>*</span></label>
                                                <input type="text" class='form-control' name='video' id='video' value="<?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'youtube' || $product_details[0]['video_type'] == 'vimeo')) ? $product_details[0]['video'] : ''; ?>" placeholder="Paste Youtube / Vimeo Video link or URL here">
                                            </div>
                                            <div class="col-md-6 <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'self_hosted')) ? '' : 'd-none'; ?>" id="video_media_container">
                                                <label for="image">Video <span class='text-danger text-sm'>*</span></label>
                                                <div class='col-md-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='pro_input_video' data-isremovable='1' data-media_type='video' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                <?php if (isset($product_details[0]['id']) && !empty($product_details[0]['id']) && isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'self_hosted') { ?>
                                                    <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                    <div class="container-fluid row image-upload-section ">
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m--4 text-center grow image">
                                                            <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= base_url('assets/admin/images/video-file.png') ?>" alt="Product Video" title="Product Video"></div>
                                                            <input type="hidden" name="pro_input_video" value='<?= $product_details[0]['video'] ?>'>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="container-fluid row image-upload-section">
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m--4 text-center grow image d-none">
                                                        </div>
                                                    </div>
                                                <?php } ?>
    
                                            </div>
                                        </div>
                                        <div class="form-group d-flex">
                                            <?php if ($product_details[0]['video_type'] == 'self_hosted') { ?>
                                                <video controls width="320" height="240" src="<?= $product_details[0]['video'] ?>">
                                                    Your browser does not support the video tag.
                                                </video>
                                             <?php } else if ($product_details[0]['video_type'] == 'youtube' || $product_details[0]['video_type'] == 'vimeo') {
                                                if ($product_details[0]['video_type'] == 'vimeo') {
                                                    $url =  explode("/", $product_details[0]['video']);
                                                    $id = end($url);
                                                    $url = 'https://player.vimeo.com/video/' . $id;
                                                } else if ($product_details[0]['video_type'] == 'youtube') {
                                                    if (strpos($product_details[0]['video'], 'watch?v=') !== false) {
                                                        $url = str_replace("watch?v=", "embed/", $product_details[0]['video']);
                                                    }else{
                                                        $url = $product_details[0]['video'];
                                                    }
                                                } else {
                                                    $url = $product_details[0]['video'];
                                                } ?>
                                                <iframe  width="100%" height="300" src="<?= $url ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="attributes_values_json_data" class="d-none">
                                    <select class="select_single" data-placeholder=" Type to search and select attributes">
                                        <option value=""></option>
                                        <?php
                                        foreach ($attributes_refind as $key => $value) {
                                        ?>
                                            <optgroup label="<?= $key ?>"><?= $key ?>
                                                <?php foreach ($value as $key => $value) {  ?>
                                                    <option name='<?= $key ?>' value='<?= $key ?>' data-values='<?= json_encode($value, 1) ?>'><?= $key ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <style>
                                .width-120 {width: 100px;}
                                </style>
                                <div class="col-12 mb-3">
                                    <table class="table table-bordered price-table">
                                        <tr>
                                            <th>Packing Size</th>
                                            <th>Unit</th>
                                            <th>MRP</th>
                                            <th>STD Prize</th>
                                            <th>DISC Price</th>
                                            <th>Qty Per Carton</th>
                                            <th>Total Weight</th>
                                            <th>MRP</th>
                                            <th>STD Prize</th>
                                            <th>DISC Price</th>
                                            <th></th>
                                        </tr>
                                        <?php
                                        if($product_variants)
                                        {
                                            $p = 0;
                                            foreach($product_variants as $product_variant)
                                            {
                                                $p++;
                                                ?>
                                                <tr id="row_<?php echo $p;?>">
                                                    <td>
                                                        <input type="text" id="packing_size_<?php echo $p;?>" name="packing_size[]" class="form-control varaint-must-fill-field width-120" value="<?php echo $product_variant['packing_size']; ?>" onkeyup="calculateWeightRow(<?php echo $p;?>);"/>
                                                    </td>
                                                    <td>
                                                        <select id="unit_id_<?php echo $p;?>" name="unit_id[]" class="form-control varaint-must-fill-field">
                                                            <option value="">Select</option>
                                                            <?php 
                                                            foreach ($units as $row) 
                                                            {
                                                                ?>
                                                                <option value="<?= $row['id'] ?>" <?= (isset($product_variant['unit_id']) && $product_variant['unit_id'] == $row['id']) ? 'selected' : '' ?>> <?= $row['unit'] ?></option>
                                                                <?php
                                                            } 
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="variant_mrp_per_item_<?php echo $p;?>" name="variant_mrp_per_item[]" class="form-control varaint-must-fill-field width-120" value="<?php echo $product_variant['mrp_per_item']; ?>" onkeyup="calculateMRPRow(<?php echo $p;?>);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="variant_price_per_item_<?php echo $p;?>" name="variant_price_per_item[]" class="form-control varaint-must-fill-field width-120" value="<?php echo $product_variant['price_per_item']; ?>" onkeyup="calculatePriceRow(<?php echo $p;?>);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="variant_special_price_per_item_<?php echo $p;?>" name="variant_special_price_per_item[]" class="form-control varaint-must-fill-field width-120" value="<?php echo $product_variant['special_price_per_item']; ?>" onkeyup="calculateSplPriceRow(<?php echo $p;?>);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="carton_qty_<?php echo $p;?>" name="carton_qty[]" class="form-control varaint-must-fill-field width-120" value="<?php echo $product_variant['carton_qty']; ?>" onkeyup="calculateMRPRow(<?php echo $p;?>);calculatePriceRow(<?php echo $p;?>);calculateSplPriceRow(<?php echo $p;?>);calculateWeightRow(<?php echo $p;?>);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="total_weight_<?php echo $p;?>" name="total_weight[]" class="form-control width-120" readonly="" value="<?php echo $product_variant['total_weight']; ?>" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="variant_mrp_<?php echo $p;?>" name="variant_mrp[]" class="form-control width-120" readonly="" value="<?php echo $product_variant['mrp']; ?>" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="variant_price_<?php echo $p;?>" name="variant_price[]" class="form-control width-120" readonly="" value="<?php echo $product_variant['price']; ?>" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="variant_special_price_<?php echo $p;?>" name="variant_special_price[]" class="form-control width-120" readonly="" value="<?php echo $product_variant['special_price']; ?>" />
                                                        <input type="hidden" id="edit_variant_id_<?php echo $p;?>" name="edit_variant_id[]" class="form-control width-120" value="<?php echo $product_variant['id']; ?>" />
                                                    </td>
                                                    <td>
                                                        <!--<a class="uploadFile img btn btn-primary text-white btn-sm"  data-input="variant_images[<?php echo $product_variant['id']; ?>][]" data-isremovable="1" data-is-multiple-uploads-allowed="1" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i></a>-->
                                                        <a href="javascript:void(0);" class="remove_row btn btn-xs btn-flat bg-purple plus_sign"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            $p = 1;
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="text" id="packing_size_<?php echo $p;?>" name="packing_size[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculateWeightRow(<?php echo $p;?>);"/>
                                                </td>
                                                <td>
                                                    <select id="unit_id_<?php echo $p;?>" name="unit_id[]" class="form-control varaint-must-fill-field">
                                                        <option value="">Select</option>
                                                        <?php 
                                                        foreach ($units as $row) 
                                                        {
                                                            ?>
                                                            <option value="<?= $row['id'] ?>" <?= (isset($fetched_data[0]['unit_id']) && $fetched_data[0]['unit_id'] == $row['id']) ? 'selected' : '' ?>> <?= $row['unit'] ?></option>
                                                            <?php
                                                        } 
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="variant_mrp_per_item_<?php echo $p;?>" name="variant_mrp_per_item[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculateMRPRow(<?php echo $p;?>);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="variant_price_per_item_<?php echo $p;?>" name="variant_price_per_item[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculatePriceRow(<?php echo $p;?>);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="variant_special_price_per_item_<?php echo $p;?>" name="variant_special_price_per_item[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculateSplPriceRow(<?php echo $p;?>);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="carton_qty_<?php echo $p;?>" name="carton_qty[]" class="form-control varaint-must-fill-field width-120" value="1" onkeyup="calculateMRPRow(<?php echo $p;?>);calculatePriceRow(<?php echo $p;?>);calculateSplPriceRow(<?php echo $p;?>);calculateWeightRow(<?php echo $p;?>);"  />
                                                </td>
                                                <td>
                                                    <input type="text" id="total_weight_<?php echo $p;?>" name="total_weight[]" class="form-control width-120" readonly="" />
                                                </td>
                                                <td>
                                                    <input type="text" id="variant_mrp_<?php echo $p;?>" name="variant_mrp[]" class="form-control width-120" readonly="" />
                                                </td>
                                                <td>
                                                    <input type="text" id="variant_price_<?php echo $p;?>" name="variant_price[]" class="form-control width-120" readonly="" />
                                                </td>
                                                <td>
                                                    <input type="text" id="variant_special_price_<?php echo $p;?>" name="variant_special_price[]" class="form-control width-120" readonly="" />
                                                </td>
                                                <td>
                                                    <!--<a class="uploadFile img btn btn-primary text-white btn-sm"  data-input="variant_images[][]" data-isremovable="1" data-is-multiple-uploads-allowed="1" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i></a>-->
                                                    <a href="javascript:void(0);" class="remove_row btn btn-xs btn-flat bg-purple plus_sign"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <div class="form-group simple-product-save">
                                        <div class="col">
                                            <a href="javascript:void(0);" class="btn btn-primary float-right" onclick="addRow();">Add New</a>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <input type="hidden" id="pro_type" name="product_type" value="<?= isset($product_details[0]['type']) ? $product_details[0]['type'] : 'carton_product' ?>">
                                    <input type="hidden" name="simple_product_stock_status" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'simple_product' ? 'value="' . $product_details[0]['stock_type'] . '"'  : '' ?>>
                                    <input type="hidden" name="variant_stock_level_type" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'variable_product' ? 'value="' . $variant_stock_level . '"'  : '' ?>>
                                    <input type="hidden" name="variant_stock_status" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'variable_product' ? 'value="0"'  : '' ?>>
                                    <input type="hidden" id="rowcount" name="rowcount" value="<?php echo $p;?>" />
                                </div>
                                
                                <script type="text/javascript">
                                function addRow()
                                {
                                    var rowcount = Number.parseInt($("#rowcount").val())+1;
                                    
                                    $(".price-table").append('<tr id="row_'+rowcount+'">'+
                                                                '<td><input type="text" id="packing_size_'+rowcount+'" name="packing_size[]" class="form-control width-120" onkeyup="calculateWeightRow('+rowcount+');"/></td>'+
                                                                '<td>'+
                                                                    '<select id="unit_id_'+rowcount+'" name="unit_id[]" class="form-control">'+
                                                                        '<option value="">Select</option>'+
                                                                        <?php 
                                                                        foreach ($units as $row) 
                                                                        {
                                                                            ?>
                                                                            '<option value="<?= $row['id'] ?>" <?= (isset($fetched_data[0]['unit_id']) && $fetched_data[0]['unit_id'] == $row['id']) ? 'selected' : '' ?>> <?= $row['unit'] ?></option>'+
                                                                            <?php
                                                                        } 
                                                                        ?>
                                                                    '</select>'+
                                                                '</td>'+
                                                                '<td><input type="text" id="variant_mrp_per_item_'+rowcount+'" name="variant_mrp_per_item[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculateMRPRow('+rowcount+');" /></td>'+
                                                                '<td><input type="text" id="variant_price_per_item_'+rowcount+'" name="variant_price_per_item[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculatePriceRow('+rowcount+');" /></td>'+
                                                                '<td><input type="text" id="variant_special_price_per_item_'+rowcount+'" name="variant_special_price_per_item[]" class="form-control varaint-must-fill-field width-120" onkeyup="calculateSplPriceRow('+rowcount+');" /></td>'+
                                                                '<td><input type="text" id="carton_qty_'+rowcount+'" name="carton_qty[]" class="form-control width-120" value="1" onkeyup="calculateMRPRow('+rowcount+');calculatePriceRow('+rowcount+');calculateSplPriceRow('+rowcount+');calculateWeightRow('+rowcount+');"  /></td>'+
                                                                '<td><input type="text" id="total_weight_'+rowcount+'" name="total_weight[]" class="form-control width-120" readonly="" /></td>'+
                                                                '<td><input type="text" id="variant_mrp_'+rowcount+'" name="variant_mrp[]" class="form-control width-120" readonly="" /></td>'+
                                                                '<td><input type="text" id="variant_price_'+rowcount+'" name="variant_price[]" class="form-control width-120" readonly="" /></td>'+
                                                                '<td><input type="text" id="variant_special_price_'+rowcount+'" name="variant_special_price[]" class="form-control width-120" readonly="" /></td>'+
                                                                '<td><a href="javascript:void(0);" class="remove_row btn btn-xs btn-flat bg-purple plus_sign"><i class="fa fa-trash"></i></a></td>'+
                                                             '</tr>');
                                    
                                    $("#rowcount").val(Number.parseInt(rowcount)+1);
                                }
                                
                                $(document).ready(function (){
                                    $(".price-table").on('click','.remove_row',function(){
                                        var rowCount = $('.price-table tr').length;
                                        if(rowCount < 3)
                                        {
                                            alert("You Can't Delete All Rows.");
                                        }
                                        else
                                        {
                                            $(this).parent().parent().remove();
                                        }
                                    });
                                });
                                
                                function calculateMRPRow(row)
                                {
                                    var carton_qty           = $("#carton_qty_"+row).val();
                                    var variant_mrp_per_item = $("#variant_mrp_per_item_"+row).val();
                                    
                                    var total = 0;
                                    total = carton_qty*variant_mrp_per_item;
                                    
                                    $("#variant_mrp_"+row).val(total.toFixed(2));
                                }
                                
                                function calculatePriceRow(row)
                                {
                                    var carton_qty              = $("#carton_qty_"+row).val();
                                    var variant_price_per_item  = $("#variant_price_per_item_"+row).val();
                                    
                                    var total = 0;
                                    total = carton_qty*variant_price_per_item;
                                    
                                    $("#variant_price_"+row).val(total.toFixed(2));
                                }
                                
                                function calculateSplPriceRow(row)
                                {
                                    var carton_qty                      = $("#carton_qty_"+row).val();
                                    var variant_special_price_per_item  = $("#variant_special_price_per_item_"+row).val();
                                    
                                    var total = 0;
                                    total = carton_qty*variant_special_price_per_item;
                                    
                                    $("#variant_special_price_"+row).val(total.toFixed(2));
                                }
                                
                                function calculateWeightRow(row)
                                {
                                    var carton_qty    = $("#carton_qty_"+row).val();
                                    var packing_size  = $("#packing_size_"+row).val();
                                    
                                    var total = 0;
                                    total = carton_qty*packing_size;
                                    
                                    $("#total_weight_"+row).val(total.toFixed(2));
                                }
                                </script>
                                
                                <?php /* ?>
                                <div class="col-12 mb-3">
                                    <h3 class="card-title">Additional Info</h3>

                                    <?php
                                    if (isset($product_details)) {
                                        $HideStatus = (isset($product_details[0]['id']) && $product_details[0]['stock_type'] == NULL) ? 'collapse' : '';
                                    ?>
                                        <div class="col-12 row additional-info existing-additional-settings">
                                            <div class="row mt-4 col-md-12 ">
                                                <nav class="w-100">
                                                    <div class="nav nav-tabs" id="product-tab" role="tablist">
                                                        <a class="nav-item nav-link active" id="tab-for-general-price" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-price" aria-selected="true">General</a>
                                                        <a class="nav-item nav-link edit-product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Attributes</a>
                                                        <a class="nav-item nav-link <?= ($product_details[0]['type'] == 'simple_product') ? 'disabled d-none' : 'edit-variants'; ?>""  id=" tab-for-variations" data-toggle="tab" href="#product-variants" role="tab" aria-controls="product-variants" aria-selected="false">Variations</a>
                                                    </div>
                                                </nav>
                                            </div>

                                            <div class="tab-content p-3 col-md-12" id="nav-tabContent">
                                                <div class="tab-pane fade active show" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">
                                                    <div class="form-group">
                                                        <label for="type" class="col-md-12">Type Of Product :</label>
                                                        <div class="col-md-12">
                                                            <?php @$variant_stock_level = !empty($product_details[0]['stock_type']) && $product_details[0]['stock_type'] == '1' ? 'product_level' : 'variant_level' ?>
                                                            <input type="hidden" name="product_type" value="<?= isset($product_details[0]['type']) ? $product_details[0]['type'] : '' ?>">
                                                            <input type="hidden" name="simple_product_stock_status" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'simple_product' ? 'value="' . $product_details[0]['stock_type'] . '"'  : '' ?>>
                                                            <input type="hidden" name="variant_stock_level_type" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'variable_product' ? 'value="' . $variant_stock_level . '"'  : '' ?>>
                                                            <input type="hidden" name="variant_stock_status" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'variable_product' ? 'value="0"'  : '' ?>>
                                                            <select name="type" id="product-type" class="form-control" data-placeholder=" Type to search and select type" <?= isset($product_details[0]['id']) ? 'disabled' : '' ?>>
                                                                <option value=" ">Select Type</option>
                                                                <option value="simple_product" <?= ($product_details[0]['type'] == "simple_product") ? 'selected' : '' ?>>Simple Product</option>
                                                                <option value="variable_product" <?= ($product_details[0]['type'] == "variable_product") ? 'selected' : '' ?>>Variable Product</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div id='product-general-settings'>
                                                        <?php
                                                        if ($product_details[0]['type'] == "simple_product") {
                                                        ?>
                                                            <div id="general_price_section">
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-4">MRP(inc GST):</label>
                                                                    <div class="col-md-12">
                                                                        <input type="number" name="simple_mrp" class="form-control stock-simple-mustfill-field mrp" value="<?= $product_variants[0]['mrp'] ?>" min='0' step="0.01">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-4">Standard Price(inc GST):</label>
                                                                    <div class="col-md-12">
                                                                        <input type="number" name="simple_price" class="form-control stock-simple-mustfill-field price" value="<?= $product_variants[0]['price'] ?>" min='0' step="0.01">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-4">Discounted Price(inc GST):</label>
                                                                    <div class="col-md-12">
                                                                        <input type="number" name="simple_special_price" class="form-control  discounted_price" value="<?= $product_variants[0]['special_price'] ?>" min='0' step="0.01">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-4">MRP(inc GST) Per Piece:</label>
                                                                    <div class="col-md-12">
                                                                        <input type="number" name="simple_mrp_per_item" class="form-control stock-simple-mustfill-field mrp_per_item" value="<?= $product_variants[0]['mrp_per_item'] ?>" min='0' step="0.01">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-4">Standard Price(inc GST) Per Piece:</label>
                                                                    <div class="col-md-12">
                                                                        <input type="number" name="simple_price_per_item" class="form-control stock-simple-mustfill-field price_per_item" value="<?= $product_variants[0]['price_per_item'] ?>" min='0' step="0.01">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-4">Discounted Price(inc GST) Per Piece:</label>
                                                                    <div class="col-md-12">
                                                                        <input type="number" name="simple_special_price_per_item" class="form-control  discounted_price_per_item" value="<?= $product_variants[0]['special_price_per_item'] ?>" min='0' step="0.01">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col">
                                                                        <input type="checkbox" name="simple_stock_management_status" class="align-middle simple_stock_management_status" <?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] != NULL) ? 'checked' : '' ?>> <span class="align-middle">Enable Stock Management</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group simple-product-level-stock-management <?= $HideStatus ?>">
                                                                <div class="col col-xs-12">
                                                                    <label class="control-label">SKU :</label>
                                                                    <input type="text" name="product_sku" class="col form-control simple-pro-sku" value="<?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] != NULL) ? $product_details[0]['sku'] : '' ?>">
                                                                </div>
                                                                <div class="col col-xs-12">
                                                                    <label class="control-label">Total Stock :</label>
                                                                    <input type="text" name="product_total_stock" class="col form-control stock-simple-mustfill-field" <?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] != NULL) ? ' value="' . $product_details[0]['stock'] . '" ' : '' ?>>
                                                                </div>
                                                                <div class="col col-xs-12">
                                                                    <label class="control-label">Stock Status :</label>
                                                                    <select type="text" class="col form-control stock-simple-mustfill-field" id="simple_product_stock_status">
                                                                        <option value="1" <?= (isset($product_details[0]['stock_type']) &&
                                                                                                $product_details[0]['stock_type'] != NULL && $product_details[0]['availability'] == "1") ? 'selected' : '' ?>>In Stock</option>
                                                                        <option value="0" <?= (isset($product_details[0]['stock_type']) &&
                                                                                                $product_details[0]['stock_type'] != NULL && $product_details[0]['availability'] == "0") ? 'selected' : '' ?>>Out Of Stock</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group simple-product-save">
                                                                <div class="col">
                                                                    <a href="javascript:void(0);" class="btn btn-primary save-settings">Save Settings</a>
                                                                    <a href="javascript:void(0);" class="btn btn-warning reset-settings">Reset Settings</a>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div id="variant_stock_level">
                                                                <div class="form-group">
                                                                    <div class="col">
                                                                        <input type="checkbox" name="variant_stock_management_status" class="align-middle variant_stock_status" <?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] != NULL) ? 'checked' : '' ?>> <span class="align-middle"> Enable Stock Management</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group <?= (intval($product_details[0]['stock_type']) > 0) ? '' : 'collapse' ?>" id='stock_level'>
                                                                    <label for="type" class="col-md-2">Choose Stock Management Type:</label>
                                                                    <div class="col-md-12">
                                                                        <select id="stock_level_type" class="form-control variant-stock-level-type" data-placeholder=" Type to search and select type">
                                                                            <option value=" ">Select Stock Type</option>
                                                                            <option value="product_level" <?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] == '1') ? 'Selected' : '' ?>> Product Level ( Stock Will Be Managed Generally )</option>
                                                                            <option value="variable_level" <?= (isset($product_details[0]['id']) && $product_details[0]['stock_type'] == '2') ? 'Selected' : '' ?>>Variable Level ( Stock Will Be Managed Variant Wise )</option>
                                                                        </select>
                                                                        <div class="form-group variant-product-level-stock-management <?= (intval($product_details[0]['stock_type']) == 1) ? '' : 'collapse' ?>">
                                                                            <div class="col col-xs-12">
                                                                                <label class="control-label">SKU :</label>
                                                                                <input type="text" name="sku_variant_type" class="col form-control" value="<?= (intval($product_details[0]['stock_type']) == 1 && isset($product_variants[0]['id']) && !empty($product_variants[0]['sku'])) ? $product_variants[0]['sku'] : '' ?>">
                                                                            </div>
                                                                            <div class="col col-xs-12">
                                                                                <label class="control-label">Total Stock :</label>
                                                                                <input type="text" name="total_stock_variant_type" class="col form-control variant-stock-mustfill-field" value="<?= (intval($product_details[0]['stock_type']) == 1 && isset($product_variants[0]['id']) && !empty($product_variants[0]['stock'])) ? $product_variants[0]['stock'] : '' ?>">
                                                                            </div>
                                                                            <div class="col col-xs-12">
                                                                                <label class="control-label">Stock Status :</label>
                                                                                <select type="text" id="stock_status_variant_type" name="variant_status" class="col form-control variant-stock-mustfill-field">
                                                                                    <option value="1" <?= (intval($product_details[0]['stock_type']) == 1 && isset($product_variants[0]['id']) && $product_variants[0]['availability'] == '1') ? 'Selected' : '' ?>>In Stock</option>
                                                                                    <option value="0" <?= (intval($product_details[0]['stock_type']) == 1 && isset($product_variants[0]['id']) && $product_variants[0]['availability'] == '0') ? 'Selected' : '' ?>>Out Of Stock</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col">
                                                                        <a href="javascript:void(0);" class="btn btn-primary save-variant-general-settings">Save Settings</a>
                                                                        <a href="javascript:void(0);" class="btn btn-warning reset-settings">Reset Settings</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="product-attributes" role="tabpanel" aria-labelledby="product-attributes-tab">
                                                    <div class="info col-12 p-3 d-none" id="note">
                                                        <div class=" col-12 d-flex align-center">
                                                            <strong>Note : </strong>
                                                            <input type="checkbox" checked="" class="ml-3 my-auto custom-checkbox" disabled>
                                                            <span class="ml-3">check if the attribute is to be used for variation </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <a href="javascript:void(0);" id="add_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm">Add Attributes</a>
                                                        <a href="javascript:void(0);" id="save_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm d-none">Save Attributes</a>
                                                    </div>
                                                    <div class="clearfix"></div>

                                                    <div id="attributes_process">
                                                        <div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-attributes-added">
                                                            <div class="col-md-12 text-center">No Product Attribures Are Added ! </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="product-variants-tab">
                                                    <div class="col-md-12">
                                                        <a href="javascript:void(0);" id="reset_variants" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm collapse">Reset Variants</a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-variants-added">
                                                        <div class="col-md-12 text-center"> No Product Variations Are Added ! </div>
                                                    </div>
                                                    <div id="variants_process" class="ui-sortable">

                                                        <div class="form-group move row my-auto p-2 border rounded bg-gray-light product-variant-selectbox">
                                                            <div class="col-1 text-center my-auto">
                                                                <i class="fas fa-sort"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        } else {

                                            ?>
                                                <div class="col-12 row additional-info existing-additional-settings">
                                                    <div class="row mt-4 col-md-12 ">
                                                        <nav class="w-100">
                                                            <div class="nav nav-tabs" id="product-tab" role="tablist"> <a class="nav-item nav-link active" id="tab-for-general-price" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-price" aria-selected="true">General</a> <a class="nav-item nav-link disabled product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Attributes</a> <a class="nav-item nav-link disabled product-variants d-none" id="tab-for-variations" data-toggle="tab" href="#product-variants" role="tab" aria-controls="product-variants" aria-selected="false">Variations</a>
                                                            </div>
                                                        </nav>
                                                        <div class="tab-content p-3 col-md-12" id="nav-tabContent">
                                                            <div class="tab-pane fade active show" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">
                                                                <div class="form-group">
                                                                    <label for="type" class="col-md-12">Type Of Product :</label>
                                                                    <div class="col-md-12">
                                                                        <input type="hidden" name="product_type">
                                                                        <input type="hidden" name="simple_product_stock_status">
                                                                        <input type="hidden" name="variant_stock_level_type">
                                                                        <input type="hidden" name="variant_stock_status">
                                                                        <select name="type" id="product-type" class="form-control product-type" data-placeholder=" Type to search and select type">
                                                                            <option value=" ">Select Type</option>
                                                                            <option value="simple_product">Simple Product</option>
                                                                            <option value="variable_product">Variable Product</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div id="product-general-settings">
                                                                    <div id="general_price_section" class="collapse">
                                                                        <div class="form-group">
                                                                            <label for="type" class="col-md-4">MRP(inc GST):</label>
                                                                            <div class="col-md-12">
                                                                                <input type="number" name="simple_mrp" class="form-control stock-simple-mustfill-field mrp" min='0' step="0.01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="type" class="col-md-4">Standard Price(inc GST):</label>
                                                                            <div class="col-md-12">
                                                                                <input type="number" name="simple_price" class="form-control stock-simple-mustfill-field price" min='0' step="0.01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="type" class="col-md-4">Discounted Price(inc GST):</label>
                                                                            <div class="col-md-12">
                                                                                <input type="number" name="simple_special_price" class="form-control discounted_price" min='0' step="0.01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="type" class="col-md-4">MRP(inc GST) Per Piece:</label>
                                                                            <div class="col-md-12">
                                                                                <input type="number" name="simple_mrp_per_item" class="form-control stock-simple-mustfill-field mrp_per_item" min='0' step="0.01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="type" class="col-md-4">Standard Price(inc GST) Per Piece:</label>
                                                                            <div class="col-md-12">
                                                                                <input type="number" name="simple_price_per_item" class="form-control stock-simple-mustfill-field price_per_item" min='0' step="0.01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="type" class="col-md-4">Discounted Price(inc GST) Per Piece:</label>
                                                                            <div class="col-md-12">
                                                                                <input type="number" name="simple_special_price_per_item" class="form-control  discounted_price_per_item" min='0' step="0.01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col">
                                                                                <input type="checkbox" name="simple_stock_management_status" class="align-middle simple_stock_management_status"> <span class="align-middle">Enable Stock Management</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group simple-product-level-stock-management collapse">
                                                                        <div class="col col-xs-12">
                                                                            <label class="control-label">SKU :</label>
                                                                            <input type="text" name="product_sku" class="col form-control simple-pro-sku">
                                                                        </div>
                                                                        <div class="col col-xs-12">
                                                                            <label class="control-label">Total Stock :</label>
                                                                            <input type="text" name="product_total_stock" class="col form-control stock-simple-mustfill-field">
                                                                        </div>
                                                                        <div class="col col-xs-12">
                                                                            <label class="control-label">Stock Status :</label>
                                                                            <select type="text" class="col form-control stock-simple-mustfill-field" id="simple_product_stock_status">
                                                                                <option value="1">In Stock</option>
                                                                                <option value="0">Out Of Stock</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group collapse simple-product-save">
                                                                        <div class="col"> <a href="javascript:void(0);" class="btn btn-primary save-settings">Save Settings</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="variant_stock_level" class="collapse">
                                                                    <div class="form-group">
                                                                        <div class="col">
                                                                            <input type="checkbox" name="variant_stock_management_status" class="align-middle variant_stock_status"> <span class="align-middle"> Enable Stock Management</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group collapse" id="stock_level">
                                                                        <label for="type" class="col-md-2">Choose Stock Management Type:</label>
                                                                        <div class="col-md-12">
                                                                            <select id="stock_level_type" class="form-control variant-stock-level-type" data-placeholder=" Type to search and select type">
                                                                                <option value=" ">Select Stock Type</option>
                                                                                <option value="product_level">Product Level ( Stock Will Be Managed Generally )</option>
                                                                                <option value="variable_level">Variable Level ( Stock Will Be Managed Variant Wise )</option>
                                                                            </select>
                                                                            <div class="form-group row variant-product-level-stock-management collapse">
                                                                                <div class="col col-xs-12">
                                                                                    <label class="control-label">SKU :</label>
                                                                                    <input type="text" name="sku_variant_type" class="col form-control">
                                                                                </div>
                                                                                <div class="col col-xs-12">
                                                                                    <label class="control-label">Total Stock :</label>
                                                                                    <input type="text" name="total_stock_variant_type" class="col form-control variant-stock-mustfill-field">
                                                                                </div>
                                                                                <div class="col col-xs-12">
                                                                                    <label class="control-label">Stock Status :</label>
                                                                                    <select type="text" id="stock_status_variant_type" name="variant_status" class="col form-control variant-stock-mustfill-field">
                                                                                        <option value="1">In Stock</option>
                                                                                        <option value="0">Out Of Stock</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col"> <a href="javascript:void(0);" class="btn btn-primary save-variant-general-settings">Save Settings</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="product-attributes" role="tabpanel" aria-labelledby="product-attributes-tab">
                                                                <div class="info col-12 p-3 d-none" id="note">
                                                                    <div class=" col-12 d-flex align-center"> <strong>Note : </strong>
                                                                        <input type="checkbox" checked="checked" class="ml-3 my-auto custom-checkbox" disabled> <span class="ml-3">check if the attribute is to be used for variation </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12"> <a href="javascript:void(0);" id="add_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm">Add Attributes</a> <a href="javascript:void(0);" id="save_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm d-none">Save Attributes</a>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div id="attributes_process">
                                                                    <div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-attributes-added">
                                                                        <div class="col-md-12 text-center">No Product Attribures Are Added !</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="product-variants-tab">
                                                                <div class="clearfix"></div>
                                                                <div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-variants-added">
                                                                    <div class="col-md-12 text-center">No Product Variations Are Added !</div>
                                                                </div>
                                                                <div id="variants_process" class="ui-sortable"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php

                                        }
                                            ?>
                                            </div>
                                        </div>
                                </div>
                                <?php */ ?>
                            
                                
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <label for="pro_short_description" class="col-form-label">Short Description <span class='text-danger text-sm'>*</span></label>
                                        <div class="mb-3">
                                            <textarea type="text" class="form-control" id="short_description" placeholder="Product Short Description" name="short_description"><?= isset($product_details[0]['short_description']) ? $product_details[0]['short_description'] : ""; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_description">Description </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_description" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['description']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_rec_crops">Recommended Crops </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_rec_crops" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['rec_crops']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_about_company">About The Company </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_about_company" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['about_company']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_about_formulation">Formulation </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_about_formulation" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['about_formulation']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_about_usage">Usage </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_about_usage" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['about_usage']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_method_of_app">Method Of Application </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_method_of_app" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['method_of_app']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_dosage">Dosage </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_dosage" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['dosage']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_benefits">Benefits </label>
                                        <div class="mb-3">
                                            <textarea name="pro_input_benefits" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['benefits']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pro_input_specifications">Specifications </label>
                                        <div class="mb-3" id="pro_specifications">
                                            <textarea id="pro_input_specifications" name="pro_input_specifications" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['specifications']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="specifications_file">Specifications of <?php echo $super_category['name']; ?> <span class='text-danger text-sm'>*</span></label>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="btn btn-warning btn-sm btn-block" for="specifications_file">Browse PDF</label>
                                                <div class="custom-file-input" style="margin-top: -30px;">
                                                    <input type="file" class="form-control" name="specifications_file" id="specifications_file" style="padding:0px;min-height: 28px;" onchange="$('#f1_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="application/pdf" />
                                                </div>
                                                <p class=""><span id="f1_text"></span></p>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                if(file_exists('uploads/specifications_file/' .$product_details[0]['id'].'.pdf'))
                                                {
                                                    ?>
                                                    <a href="<?php echo base_url().'uploads/specifications_file/' .$product_details[0]['id'].'.pdf';?>" target="_blank" class="btn btn-warning btn-sm btn-block" title="Specifications of <?php echo $super_category['name']; ?>">View PDF</a>
                                                    <?php
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="quality_inspection_file">Quality inspection report</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="btn btn-warning btn-sm btn-block" for="quality_inspection_file">Browse PDF</label>
                                                <div class="custom-file-input" style="margin-top: -30px;">
                                                    <input type="file" class="form-control" name="quality_inspection_file" id="quality_inspection_file" style="padding:0px;min-height: 28px;" onchange="$('#f2_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="application/pdf" />
                                                </div>
                                                <p class=""><span id="f2_text"></span></p>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                if(file_exists('uploads/quality_inspection_file/' .$product_details[0]['id'].'.pdf'))
                                                {
                                                    ?>
                                                    <a href="<?php echo base_url().'uploads/quality_inspection_file/' .$product_details[0]['id'].'.pdf';?>" target="_blank" class="btn btn-warning btn-sm btn-block" title="Quality inspection report">View PDF</a>
                                                    <?php
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="patent_file">Patent</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="btn btn-warning btn-sm btn-block" for="patent_file">Browse PDF</label>
                                                <div class="custom-file-input" style="margin-top: -30px;">
                                                    <input type="file" class="form-control" name="patent_file" id="patent_file" style="padding:0px;min-height: 28px;" onchange="$('#f3_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="application/pdf" />
                                                </div>
                                                <p class=""><span id="f3_text"></span></p>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                if(file_exists('uploads/patent_file/' .$product_details[0]['id'].'.pdf'))
                                                {
                                                    ?>
                                                    <a href="<?php echo base_url().'uploads/patent_file/' .$product_details[0]['id'].'.pdf';?>" target="_blank" class="btn btn-warning btn-sm btn-block" title="Patent">View PDF</a>
                                                    <?php
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="certification_file">Certification</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="btn btn-warning btn-sm btn-block" for="certification_file">Browse PDF</label>
                                                <div class="custom-file-input" style="margin-top: -30px;">
                                                    <input type="file" class="form-control" name="certification_file" id="certification_file" style="padding:0px;min-height: 28px;" onchange="$('#f4_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="application/pdf" />
                                                </div>
                                                <p class=""><span id="f4_text"></span></p>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                if(file_exists('uploads/certification_file/' .$product_details[0]['id'].'.pdf'))
                                                {
                                                    ?>
                                                    <a href="<?php echo base_url().'uploads/certification_file/' .$product_details[0]['id'].'.pdf';?>" target="_blank" class="btn btn-warning btn-sm btn-block" title="Certification">View PDF</a>
                                                    <?php
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="additional_information_file">Additional information</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="btn btn-warning btn-sm btn-block" for="additional_information_file">Browse PDF</label>
                                                <div class="custom-file-input" style="margin-top: -30px;">
                                                    <input type="file" class="form-control" name="additional_information_file" id="additional_information_file" style="padding:0px;min-height: 28px;" onchange="$('#f5_text').html(this.value.replace('C:\\fakepath\\', ''));" accept="application/pdf" />
                                                </div>
                                                <p class=""><span id="f5_text"></span></p>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                if(file_exists('uploads/additional_information_file/' .$product_details[0]['id'].'.pdf'))
                                                {
                                                    ?>
                                                    <a href="<?php echo base_url().'uploads/additional_information_file/' .$product_details[0]['id'].'.pdf';?>" target="_blank" class="btn btn-warning btn-sm btn-block" title="Additional Information">View PDF</a>
                                                    <?php
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group" id="error_box"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="reset" class="btn btn-warning">Reset</button>
                                            <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($product_details[0]['id'])) ? 'Update Product' : 'Add Product' ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>