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
                        <form class="form-horizontal" action="<?= base_url('admin/product/add_new_product'); ?>" method="POST" enctype="multipart/form-data" id="save-product2">
                            <input type="hidden" name="cod_allowed" value="1">
                            <input type="hidden" name="product_type" value="<?= isset($product_details[0]['type']) ? $product_details[0]['type'] : '' ?>">
                            <input type="hidden" name="variant_stock_status" <?= isset($product_details[0]['stock_type']) && !empty($product_details[0]['stock_type']) && $product_details[0]['type'] == 'variable_product' ? 'value="0"'  : '' ?>>
                            <input type="hidden" name="indicator" value="0"/>
                            <input type="hidden" id="deliverable_type" name="deliverable_type" value="1" />
                            <?php if (isset($product_details[0]['id'])) {
                            ?>
                                <input type="hidden" name="edit_product_id" value="<?= (isset($product_details[0]['id'])) ? $product_details[0]['id'] : "" ?>">
                                <input type="hidden" id="subcategory_id_js" value="<?= (isset($product_details[0]['subcategory_id'])) ? $product_details[0]['subcategory_id'] : "" ?>">
                            <?php } ?>
                            
                            <div class="card-body">
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="seller" class="col-form-label">Seller <span class='text-danger text-sm'>*</span></label>
                                            <select class='form-control' name='seller_id' id="seller_id" onchange="getBrands(this.value);">
                                                <option value="">Select Seller </option>
                                                <?php foreach ($sellers as $seller) { ?>
                                                    <option value="<?= $seller['seller_id'] ?>" <?= (isset($product_details[0]['seller_id']) && $product_details[0]['seller_id'] == $seller['seller_id']) ? 'selected' : "" ?>><?= $seller['seller_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <label for="parent_id" class="col-form-label">Main Category <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control' name='parent_id' id="parent_id" onchange="getSubcategories(this.value,'category_id');">
                                            <option value="">Select </option>
                                            <?php foreach ($categories as $category) { ?>
                                                <option value="<?= $category['id'] ?>" <?= (isset($product_details[0]['parent_id']) && $product_details[0]['parent_id'] == $category['id']) ? 'selected' : "" ?>><?= $category['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="category_id" class="col-form-label">Sub Category <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control' name='category_id' id="category_id" onchange="getSubcategories(this.value,'formulation_id');">
                                            <option value="">Select</option>
                                            <?php foreach ($sub_categories as $sub_category) { ?>
                                                <option value="<?= $sub_category['id'] ?>" <?= (isset($product_details[0]['category_id']) && $product_details[0]['category_id'] == $sub_category['id']) ? 'selected' : "" ?>><?= $sub_category['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="formulation_id" class="col-form-label">Formulation <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control' name='formulation_id' id="formulation_id" onchange="getSpecifications(this.value);">
                                            <option value="">Select</option>
                                            <?php foreach ($formulations as $formulation) { ?>
                                                <option value="<?= $formulation['id'] ?>" <?= (isset($product_details[0]['formulation_id']) && $product_details[0]['formulation_id'] == $formulation['id']) ? 'selected' : "" ?>><?= $formulation['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <label for="brands" class="col-form-label">Brands</label>
                                        <select class='form-control' name='brand_id' id="brand_id">
                                            <option value="">Select</option>
                                            <?php
                                            if($product_details[0]['seller_id'])
                                            {
                                                $seller_data = $this->db->get_where('seller_data', array('user_id'=>$product_details[0]['seller_id']))->row();
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
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pro_input_text" class="col-form-label">Product Name</label>
                                        <input type="text" class="col-md-12 form-control" id="pro_input_text" name="pro_input_name" value="<?= (isset($product_details[0]['name'])) ? $product_details[0]['name'] : "" ?>" placeholder='Product Name'/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="form_id" class="col-form-label">Form <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control' name='form_id' id="form_id" onchange="getUnitSize(this.value);">
                                            <option value="">Select</option>
                                            <?php foreach ($forms as $form) { ?>
                                                <option value="<?= $form['id'] ?>" <?= (isset($product_details[0]['form_id']) && $product_details[0]['form_id'] == $form['id']) ? 'selected' : "" ?>><?= $form['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <input type="hidden" id="type" name="type" class="form-control" value="simple_product"/>
                                        <label for="simple_mrp" class="">MRP:</label>
                                        <input type="number" name="simple_mrp" class="form-control col-md-12" value="<?php echo (isset($product_variants[0]['mrp'])) ? $product_variants[0]['mrp'] : "" ?>" min='0' step="0.01"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="simple_price" class="">Regular Price:</label>
                                        <input type="number" name="simple_price" class="form-control col-md-12" value="<?php echo (isset($product_variants[0]['price'])) ? $product_variants[0]['price'] : "" ?>" min='0' step="0.01"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type" class="">Discounted Price:</label>
                                        <input type="number" name="simple_special_price" class="form-control col-md-12 discounted_price" value="<?php echo (isset($product_variants[0]['special_price'])) ? $product_variants[0]['special_price'] : "" ?>" min='0' step="0.01">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
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
                                        <label for="licence_no" class="col-form-label">Licence Number</label>
                                        <input type="text" class="form-control" id="licence_no" name="licence_no" value="<?= (isset($product_details[0]['licence_no'])) ? $product_details[0]['licence_no'] : "" ?>" placeholder='Licence Number'/>
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">Main Image <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-sm-10">
                                                <div class='col-md-12'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='pro_input_image' data-isremovable='0' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                <?php
                                                if (isset($product_details[0]['id']) && !empty($product_details[0]['id'])) {
                                                ?>
                                                    <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                    <div class="container-fluid row image-upload-section ">
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-2 bg-white rounded m--4 text-center grow image">
                                                            <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= BASE_URL() . $product_details[0]['image'] ?>" alt="Image Not Found"></div>
                                                            <input type="hidden" name="pro_input_image" value='<?= $product_details[0]['image'] ?>'>
                                                        </div>
                                                    </div>
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
                                                <div class='col-md-12'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='other_images[]' data-isremovable='1' data-is-multiple-uploads-allowed='1' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                <?php
                                                if (isset($product_details[0]['id']) && !empty($product_details[0]['id'])) {
                                                ?>
                                                    <div class="container-fluid row image-upload-section">
                                                        <?php
                                                        $other_images = json_decode($product_details[0]['other_images']);
                                                        if (!empty($other_images)) {
                                                            foreach ($other_images as $row) {
                                                        ?>
                                                                <div class="col-md-3 col-sm-12 shadow bg-white rounded m-3 p-3 text-center grow">
                                                                    <div class='image-upload-div'><img src="<?= BASE_URL()  . $row ?>" alt="Image Not Found"></div>
                                                                    <a href="javascript:void(0)" class="delete-img m-3" data-id="<?= $product_details[0]['id'] ?>" data-field="other_images" data-img="<?= $row ?>" data-table="products" data-path="<?= $row ?>" data-isjson="true">
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
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-2 bg-white rounded m--4 text-center grow image">
                                                            <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= base_url('assets/admin/images/video-file.png') ?>" alt="Product Video" title="Product Video"></div>
                                                            <input type="hidden" name="pro_input_video" value='<?= $product_details[0]['video'] ?>'>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="container-fluid row image-upload-section">
                                                        <div class="col-md-3 col-sm-12 shadow p-3 mb-2 bg-white rounded m--4 text-center grow image d-none">
                                                        </div>
                                                    </div>
                                                <?php } ?>
    
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                        <label for="pro_input_specifications">Specifications Of Fertilisers </label>
                                        <div class="mb-3" id="pro_specifications">
                                            <textarea id="pro_input_specifications" name="pro_input_specifications" class="textarea" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? stripslashes($product_details[0]['specifications']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-center">
                                            <div class="form-group" id="error_box">
                                            </div>
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