<!-- breadcrumb -->
<style>.product-wrap{min-width: /*156px*/136px;}.product-wrap .product {min-height: 355px;}</style>
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorites' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'favorites' ?></a></li>
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

            <div class="col-md-9 col-12">
                <div class='card border-0'>
                    <div class="card-header bg-white">
                        <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle btn-icon-left d-block d-lg-none mb-3"><!--<i class="w-icon-hamburger"></i>-->Menu</a>
                        <h3 class="h4 text-left text-uppercase mb-2 mt-1"><?= !empty($this->lang->line('Shortlisted')) ? $this->lang->line('Shortlisted') : 'Shortlisted' ?></h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <?php
                            if (isset($products) && !empty($products)) {
                                foreach ($products as $row) 
                                { 
                                    ?>
                                    <div class="col-md-3 col-sm-6 col-6">
                                        <div class="product-wrap">
                                            <div class="product text-center2">
                                                <figure class="product-media justify-content-md-center2">
                                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>" alt="" width="300" height="338"/>
                                                    </a>
                                                    <div class="product-action-vertical">
                                                        <span class="add-favorite position-relative">
                                                            <a href="#" class="btn-product-icon btn-wishlist far add-to-fav-btn <?php echo ($row['is_favorite'] == 1 || 1) ? 'fa text-danger w-icon-heart-full' : 'w-icon-heart' ?>" data-product-id="<?= $row['id'] ?>"></a>
                                                        </span>
                                                        <?php
                                                        if (count($row['variants']) <= 1) {
                                                            $variant_id = $row['variants'][0]['id'];
                                                            $modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $modal = "#quick-view";
                                                        }
                                                        ?>
                                                        <?php /* ?>
                                                        <a href="" class="quick-view-btn btn-product-icon btn-quickview w-icon-search" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"></a>
                                                        <a href="" data-tip="Add to Cart" class="add_to_cart btn-product-icon btn-cart w-icon-cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"></a>
                                                        <?php */ ?>
                                                    </div>
                                                    <div class="product-label-group">
                                                        <?php /*if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                            <span class="product-new-label product-label label-discount"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                            <label class="product-label label-discount"><?= $row['min_max_price']['discount_in_percentage'] ?>%</label>
                                                        <?php }*/ ?>
                                                    </div>
                                                </figure>
                                                <div class="product-details">
                                                    <h3 class="product-name">
                                                        <a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a>
                                                    </h3>
                                                    <h3 class="product-name">
                                                        <a class="text-primary" href="<?= base_url('products?seller=' . $row['seller_slug']) ?>">
                                                            <?php echo $row['seller_name']; ?>
                                                        </a>
                                                    </h3>
                                                    <?php /* ?>
                                                    <div class="ratings-container">
                                                        <div class="rating">
                                                            <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                                        </div>
                                                    </div>
                                                    <?php */ ?>
                                                    <?php if($row['product_stock_status']) { ?>
                                                    <div class="product-pa-wrapper2 row mb-1">
                                                        <?php 
                                                        if($row['type']== "simple_product")
                                                        {
                                                            ?>
                                                            <div class="product-price2 col col-sm-12 col-12">
                                                                <p class="mt-1 mb-1">
                                                                    <ins class="new-price"><?= $settings['currency'] ?> <?php echo $row['_special_price']; ?></ins>
                                                                </p>
                                                                <p class="mt-0 mb-0">
                                                                    <del class="old-price"><?= $settings['currency'] ?> <?php echo $row['_price']; ?></del>
                                                                </p>
                                                                <p class="mt-0 mb-0">
                                                                    <del class="old-price"><?= $settings['currency'] ?> <?php echo $row['_mrp']; ?></del>
                                                                </p>
                                                            </div>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            //$price = get_price_range_of_product($row['id']);
                                                            //echo $price['range'];
                                                            ?>
                                                            <div class="product-price2 price-sec-<?php echo $row['id'];?> col col-sm-12 col-lg-6 col-12">
                                                                <?php
                                                                //$price = get_default_price($row['id']);
                                                                //echo $price;
                                                                echo $settings['currency'].' '.$row['variants'][0]['special_price'];
                                                                ?>
                                                            </div>
                                                            <?php
                                                            if(count($row['variants']) > 0)
                                                            {
                                                                ?>
                                                                <div class="col col-sm-12 col-lg-6 col-12">
                                                                    <div class="pro_select_var">
                                                                        <select id="variant_dropdown_<?php echo $row['id'];?>" name="variant_dropdown_<?php echo $row['id'];?>" class="form-control variant_dropdown">
                                                                            <?php
                                                                            foreach($row['variants'] as $v)
                                                                            {
                                                                                ?>
                                                                                <option value="<?php echo $v['id'];?>" data-id="<?php echo $row['id'];?>" data-variant-id="<?php echo $v['id'];?>" data-mrp="<?php echo $v['mrp'];?>" data-price="<?php echo $v['price'];?>" data-special-price="<?php echo $v['special_price'];?>"><?php echo $v['packing_size'].' '.$v['unit'];?> <?php echo ($v['carton_qty'] > 1) ? ' &#x2715; '.$v['carton_qty'] : ' &#x2715; 1';?></option>  
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            /*
                                                            $attributes = array_values($row['variant_attributes']);//get_product_variants($row['id']);
                                                            var_dump($row, $attributes);die;
                                                            if($attributes)
                                                            {
                                                                ?>
                                                                <div class="col col-sm-12 col-lg-6">
                                                                    <div class="pro_select_var">
                                                                        <select id="" name="" class="form-control">
                                                                            <?php
                                                                            foreach($attributes as $attribute)
                                                                            {
                                                                                ?>
                                                                                <option><?php echo $attribute ?></option>  
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }*/
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <a class="add-to-cart add-to-cart-<?php echo $row['id']; ?> add_to_cart btn-block btn-sm text-center" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a><!--  data-izimodal-open="<?= $modal ?>" -->
                                                    <?php } else { ?> 
                                                    <div class="product-pa-wrapper2 row">
                                                        <h6 class="text-danger">Out of stock</h6>
                                                    </div>
                                                    <?php } ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php /* ?>
                                        <div class="product-grid">
                                            <aside class="add-favorite">
                                                <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>">
                                                    </a>
                                                </div>
                                                <ul class="social">
                                                    <?php
                                                    if (count($row['variants']) <= 1) {
                                                        $variant_id = $row['variants'][0]['id'];
                                                        $modal = "";
                                                    } else {
                                                        $variant_id = "";
                                                        $modal = "#quick-view";
                                                    }
                                                    ?>
                                                    <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                    <li><a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                                <?php if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                    <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                <?php } ?>
                                            </div>
                                            <div class="rating">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h3>
                                                <div class="price">
                                                    <?php $price = get_price_range_of_product($row['id']);
                                                    echo $price['range'];
                                                    ?>
                                                </div>
                                                <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
                                    </div>
                                    <?php
                                    /*
                                    ?>
                                    <div class="col-md-3 col-sm-6 mt-5">
                                        <div class="product-grid">
                                            <aside class="add-favorite">
                                                <button type="button" class="btn fa-heart add-to-fav-btn fa text-danger" data-product-id="<?= $row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="#">
                                                        <img class="pic-1" src="<?= $row['image_sm'] ?>">
                                                    </a>
                                                </div>
                                                <ul class="social">
                                                    <?php
                                                    if (count($row['variants']) <= 1) {
                                                        $variant_id = $row['variants'][0]['id'];
                                                        $modal = "";
                                                    } else {
                                                        $variant_id = "";
                                                        $modal = "#quick-view";
                                                    }
                                                    ?>
                                                    <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                    <li><a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="rating">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h3>
                                                <div class="price"><i></i><?php $price = get_price_range_of_product($row['id']);
                                                                            echo $price['range'];
                                                                            ?></span>
                                                </div>
                                                <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ Add To Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    */
                                }
                            } else { ?>
                                <div class="col-12 m-5">
                                    <div class="text-center">
                                        <h1 class="h2">No Shortlisted Products Found.</h1>
                                        <a href="<?= base_url() ?>" class="button button-rounded button-warning">Go to Shop</a>
                                    </div>
                                </div>
                            <?php } ?>
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