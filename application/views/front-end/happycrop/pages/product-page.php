<style>.product-wrap .product{min-height: 358px;}.variation_pro_select {width: /*75px*/65px;}.num-block {float: left;margin-right: 10px;}.current_active {background: /*#ECFFA8*/#efefef;}.hc-table td {text-align: center;}.h-title{display: inline-block;border-bottom: 1px solid #000;padding-bottom: 0px;}.product-price2 span{}.product-price2 .h3{font-size:1.5rem !important;font-weight: 600;}.height-22{line-height: 22px;}</style>
<?php $total_images = 0; ?>
<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= $product['product'][0]['name'] ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <?php
                $cat_names = array();
                $cat_urls = array();
                $result = check_for_parent_id($product['product'][0]['category_id']);
                array_push($cat_names, array('name'=>$result[0]['name'],'url'=>base_url('products/category/' . html_escape($result[0]['slug']))));
                while (!empty($result[0]['parent_id'])) {
                    $result = check_for_parent_id($result[0]['parent_id']);
                    array_push($cat_names, array('name'=>$result[0]['name'],'url'=>base_url('products/category/' . html_escape($result[0]['slug']))));
                }
                $cat_names = array_reverse($cat_names, true);
                foreach ($cat_names as $row) { ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="<?= $row['url'] ?>" title="<?= $row['name'] ?>"><?= $row['name'] ?></a>
                    </li>
                <?php } ?>
            </ol>
        </nav>
    </div>

</section>
<style>
.product-page-content .product-image{padding: 40px;}
/*
.product-single-swiper .swiper-slide {
  position: relative;
  background: rgba(231,246,233,.4);
  display: -webkit-flex;
  display: flex;
}
.product-single-swiper .swiper-slide {
  width: 100%;
  padding: 50px;
  position: relative;
  display: -webkit-flex;
  display: flex;
  -webkit-align-items: center;
  align-items: center;
  -webkit-justify-content: center;
  justify-content: center;
}

.product-single-swiper .swiper-slide > figure {
  width: 100%;
  text-align: center;
  display: block;
}

.product-single-swiper .swiper-slide > figure img {
  width: 100% !important;
  height: auto !important;
  object-fit: contain;
  mix-blend-mode: multiply;
  -webkit-mix-blend-mode: multiply;
  -moz-mix-blend-mode: multiply;
  max-height: 479px;
  max-width: 310px;
}
*/
</style>
<!-- end breadcrumb -->
<section class="content main-content product-page-content my-1 py-1">
    <div class="product product-single row  justify-content-md-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-5 mb-2 mb-md-2">
                    <div class="product-gallery product-gallery-sticky">
                        <div class="swiper-container product-single-swiper swiper-theme nav-inner" data-swiper-options="{
                            'navigation': {
                                'nextEl': '.swiper-button-next',
                                'prevEl': '.swiper-button-prev'
                            }
                        }">
                            <div class="swiper-wrapper row cols-1 gutter-no">
                                <div class="swiper-slide">
                                    <figure class="product-image product-media ">
                                        <img class="pic-1-" src="<?= $product['product'][0]['image'] ?>"
                                            data-zoom-image="<?= $product['product'][0]['image'] ?>"
                                            alt="" width="800" height="900">
                                    </figure>
                                </div>
                                
                                <?php
        
                                $variant_images_md = array_column($product['product'][0]['variants'], 'images_md');
                                if (!empty($variant_images_md)) {
                                    foreach ($variant_images_md as $variant_images) {
                                        if (!empty($variant_images)) {
                                            foreach ($variant_images as $image) {
                                ?>
                                                <div class="swiper-slide">
                                                    <figure class="product-image product-media">
                                                        <img src="<?= $image ?>" data-zoom-image="<?= $image ?>" alt="" width="488" height="549"/>
                                                    </figure>
                                                </div>
                                <?php }
                                        }
                                    }
                                } ?>
                                <?php
                                if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                                    foreach ($product['product'][0]['other_images'] as $other_image) {
                                        $total_images++;
                                ?>
                                        <div class="swiper-slide">
                                            <figure class="product-image product-media">
                                                <img src="<?= $other_image ?>" id="" data-zoom-image="">
                                            </figure>
                                        </div>
                                <?php }
                                } ?>
                                <?php
                                if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                                    $total_images++;
                                ?>
                                    <div class="swiper-slide">
                                        <div class='product-view-grid'>
                                            <div class='product-view-image'>
                                                <div class='product-view-image-container'>
                                                    <?php if ($product['product'][0]['video_type'] == 'self_hosted') { ?>
                                                        <video controls width="320" height="240" src="<?= $product['product'][0]['video'] ?>">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                     <?php } else if ($product['product'][0]['video_type'] == 'youtube' || $product['product'][0]['video_type'] == 'vimeo') {
                                                        if ($product['product'][0]['video_type'] == 'vimeo') {
                                                            $url =  explode("/", $product['product'][0]['video']);
                                                            $id = end($url);
                                                            $url = 'https://player.vimeo.com/video/' . $id;
                                                        } else if ($product['product'][0]['video_type'] == 'youtube') {
                                                            if (strpos($product['product'][0]['video'], 'watch?v=') !== false) {
                                                                $url = str_replace("watch?v=", "embed/", $product['product'][0]['video']);
                                                            }else{
                                                                $url = $product['product'][0]['video'];
                                                            }
                                                        } else {
                                                            $url = $product['product'][0]['video'];
                                                        } ?>
                                                        <iframe  width="100%" height="315" src="<?= $url ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                            </div>
                            <button class="swiper-button-next"></button>
                            <button class="swiper-button-prev"></button>
                            <!--<a href="#" class="product-gallery-btn product-image-full"><i class="w-icon-zoom"></i></a>-->
                        </div>
                        <div class="product-thumbs-wrap swiper-container" data-swiper-options="{
                            'navigation': {
                                'nextEl': '.swiper-button-next',
                                'prevEl': '.swiper-button-prev'
                            }
                        }">
                            <div class="product-thumbs swiper-wrapper row cols-4 gutter-sm">
                                <div class="product-thumb swiper-slide">
                                    <img src="<?= $product['product'][0]['image'] ?>"
                                        alt="" width="800" height="900">
                                </div>
                                <?php if (!empty($variant_images_md)) {
                                foreach ($variant_images_md as $variant_images) {
                                    if (!empty($variant_images)) {
                                        foreach ($variant_images as $image) {
                                        ?>
                                            <div class="product-thumb swiper-slide">
                                                <img src="<?= $image ?>" data-zoom-image="" width="800" height="900"/>
                                            </div>
                            <?php }
                                    }
                                }
                            } ?>
                            <?php
                            if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                                foreach ($product['product'][0]['other_images'] as $other_image) { ?>
                                    <div class="product-thumb swiper-slide">
                                        <img src="<?= $other_image ?>" data-zoom-image="" width="800" height="900">
                                    </div>
                            <?php }
                            } ?>
                            <?php
                            if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                                $total_images++;
                            ?>
                                <div class="product-thumb swiper-slide">
                                    <img src="<?= base_url('assets/admin/images/video-file.png') ?>" data-zoom-image="" width="800" height="900">
                                </div>
                            <?php } ?>
                            </div>
                            <button class="swiper-button-next"></button>
                            <button class="swiper-button-prev"></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 mb-2 mb-md-2">
                    <div class="product-details pr-page" data-sticky-options="{'minWidth': 767}">
                        <div class="row">
                            <div class="col-lg-9">
                                <h1 class="product-title"><?= ucfirst($product['product'][0]['name']) ?></h1>
                                <div class="product-bm-wrapper">
                                    
                                    <div class="product-meta">
                                        <?php
                                        if($product['product'][0]['pro_no']!='' && !$product['product'][0]['is_service_category'])
                                        {
                                            ?>
                                            <div class="product-categories">
                                                HC Prodcuct No.: 
                                                <span class="product-category"><a href="#"><?php echo 'HCP'.str_pad($product['product'][0]['pro_no'],6,"0",STR_PAD_LEFT);; ?></a></span>
                                            </div>
                                            <?php
                                        }
                                        
                                        if($product['product'][0]['serv_no']!='' && $product['product'][0]['is_service_category'])
                                        {
                                            ?>
                                            <div class="product-categories">
                                                HC Service No.: 
                                                <span class="product-category"><a href="#"><?php echo 'HCS'.str_pad($product['product'][0]['serv_no'],6,"0",STR_PAD_LEFT);; ?></a></span>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                        <div class="product-categories">
                                            Category:
                                            <span class="product-category"><a href="#"><?= ucfirst($product['product'][0]['category_name']) ?></a></span>
                                        </div>
                                        <div class="product-categories">
                                            Company / Manufacture:
                                            <span class="product-category"><a class="text-primary font-weight-bold" href="<?php echo base_url().'products?seller='.$product['product'][0]['seller_slug'];?>"><?= ucfirst($product['product'][0]['company_name']) ?></a></span>
                                        </div>
                                        <?php if(isset($product['product'][0]['brand_name']) && $product['product'][0]['brand_name']!='') { ?> 
                                        <div class="product-categories">
                                            Brand:
                                            <span class="product-category"><span class="text-primary- text-dark font-weight-bold"><?= ucfirst($product['product'][0]['brand_name']) ?></span></span>
                                        </div>
                                        <?php } ?>
                                        <!--
                                        <div class="product-sku">
                                            SKU: <span><?php //echo ucfirst($product['product'][0]['product_sku']) ?></span>
                                        </div>
                                        -->
                                        <h5 class="mb-1 mt-2 font-weight-normal">Country of Origin: <?= $product['product'][0]['made_in'] ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="ratings-container">
                                    <div class="col-md-12 mb-3 product-rating-small  pl-0">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-6 col-sm-6 col-4 text-center text-xs-left">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $product['product'][0]['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="col-lg-12 col-md-6 col-sm-6 col-8 text-center text-xs-left">
                                                <span class="my-auto mx-3 mx-xs-0"> ( <?= $product['product'][0]['no_of_ratings'] ?> <?= !empty($this->lang->line('reviews')) ? $this->lang->line('reviews') : 'reviews' ?> ) </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row justify-content-center justify-content-md-start  justify-content-sm-center trust_div">
                                    <div class="col-4 col-md-4 col-sm-4 text-center">
                                        <img src="<?php echo base_url();?>uploads/seller_batches/verified.png" alt="" />
                                    </div>
                                    <div class="col-4 col-md-4 col-sm-4 text-center">
                                        <img src="<?php echo base_url();?>uploads/seller_batches/trusted.png" alt="" />
                                    </div>
                                    <?php /* ?>
                                    <div class="col-2 col-md-2 col-sm-2 text-center">
                                        <img src="<?php echo base_url();?>uploads/seller_batches/best_seller.png" alt="" />
                                    </div>
                                    <div class="col-2 col-md-2 col-sm-2 text-center">
                                        <img src="<?php echo base_url();?>uploads/seller_batches/best_quality.png" alt="" />
                                    </div>
                                    <?php */ ?>
                                </div>
                            </div>
                        </div>
                        
                        
                        <!--<h5 class="mb-1 font-weight-normal text-success">Verified, Trusted, Quality product, Happycrop fulfilment, Best selling</h5>-->
                        
                        
        
                        <hr class="product-divider">
        
                        <div class="product-price2">
                            <!--<ins class="new-price">Rs. 225</ins>-->
                            <?php if ($product['product'][0]['type'] == "simple_product") { ?>
                            <p class="mt-1 mb-1">
                                <span class="font-size-14 text-success">Best Price <?= $settings['currency'] ?> <?php echo $product['product'][0]['_special_price'];?> </span> |
                                <span class="font-size-14 product-label label-discount">Profit <?= $settings['currency'] ?> <?php echo $product['product'][0]['_mrp'] - $product['product'][0]['_special_price'];?></span>
                            </p>
                            <p class="mt-1 mb-1">
                                <span class="font-size-14 text-danger">Wholesale <?= $settings['currency'] ?> <?php echo $product['product'][0]['_price'];?></span> |
                                <span class="font-size-14 text-danger">MRP <?= $settings['currency'] ?> <?php echo $product['product'][0]['_mrp'];?></span>
                            </p>
                            <?php } ?>
                            <?php if ($product['product'][0]['type'] == "simple_product") { ?>
                                <p class="mb-0 mt-2 price" id="price">
                                    <i><?php $price = get_price_range_of_product($product['product'][0]['id']);
                                        // print_r($this->db->last_query());
                                        echo $price['range'];
                                        ?>
                                        <sup><span class="special-price striped-price"><s><?= !empty($product['product'][0]['min_max_price']['special_price']) && $product['product'][0]['min_max_price']['special_price'] != NULL  ? '<i>' . $settings['currency'] . '</i>' . number_format($product['product'][0]['min_max_price']['min_price']) : '' ?></s></span></sup>
                                </p>
                                <p class="mb-0 mt-2 price d-none" id="price">
                                    <?php $price = get_price_range_of_product($product['product'][0]['id']);
                                    // print_r($this->db->last_query());
                                    // echo $price['range'];
                                    ?>
                                </p>
                            <?php } else { ?>
                                <div class="mb-0 mt-2 price">
                                    <div class="bg-grey-efefef border-radius-10 p-3 mb-2">
                                        <h4 class="mb-1 h-title">Price per <?php if(!$product['product'][0]['is_service_category']) { ?>Product <?php } else { ?> Unit Service <?php } ?>:</h4>
                                        <div class="clearfix height-22">
                                            <span id="size" class="mr-2 mb-0" style="font-weight: 400;">
                                                Size: <?php echo $product['product'][0]['variants'][0]['packing_size'].' '.$product['product'][0]['variants'][0]['unit']; ?>
                                            </span>
                                        </div>
                                        <div class="clearfix height-22">
                                            <span id="mrp" class="mr-2 mb-0">
                                                MRP: <?php echo $product['product'][0]['variants'][0]['mrp_per_item']; ?>/-
                                            </span>
                                            <span id="price" class="mr-2 mb-0">
                                                STD Price: <?php echo $product['product'][0]['variants'][0]['price_per_item']; ?>/-
                                                <?php 
                                                //$price = get_price_range_of_product($product['product'][0]['id']);
                                                //echo $price['range'];
                                                ?>
                                            </span>
                                            <span id="selling_price" class="mr-2 mb-0">
                                                Selling Price: <?php echo (round($product['product'][0]['variants'][0]['sell_price_per_item'])) ? $product['product'][0]['variants'][0]['sell_price_per_item'] : '__'; ?>/-
                                            </span>
                                        </div>
                                        <div class="clearfix height-22">
                                            <span id="special_price" class="mr-2 mb-0">
                                                <?php
                                                $serv_chg = 0;
                                                if(round($product['product'][0]['variants'][0]['sell_price_per_item']) > 0)
                                                {
                                                    $serv_chg = (($product['product'][0]['variants'][0]['sell_price_per_item'] - $product['product'][0]['variants'][0]['special_price_per_item'])*10/100);
                                                    $serv_chg = round($serv_chg, 2);
                                                }
                                                else
                                                {
                                                    $serv_chg = (($product['product'][0]['variants'][0]['mrp_per_item'] - $product['product'][0]['variants'][0]['special_price_per_item'])*10/100);
                                                    $serv_chg = round($serv_chg, 2);
                                                }
                                                ?>
                                                Disc. Price: <?php echo $product['product'][0]['variants'][0]['special_price_per_item']+ $serv_chg; ?>/-
                                            </span>
                                            <span id="you_save" class="mr-2 mb-0">
                                                Saving: <?php echo round(($product['product'][0]['variants'][0]['price_per_item'] - ($product['product'][0]['variants'][0]['special_price_per_item']+ $serv_chg)),2); ?>/- (<?php echo round((($product['product'][0]['variants'][0]['price_per_item'] - ($product['product'][0]['variants'][0]['special_price_per_item']+ $serv_chg))/$product['product'][0]['variants'][0]['price_per_item'] *100), 2); ?>%)
                                            </span>
                                            <span id="discount" class="mr-2 mb-0"></span>
                                        </div>
                                        
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="bg-grey-dad9d9 border-radius-10 p-3 mb-2">
                                        <h4 class="mb-1 h-title"><?php if(!$product['product'][0]['is_service_category']) { ?>Discounted Price for Selected Product <?php } else { ?> Total Price for Selected <?php } ?>:</h4>
                                        <div class="clearfix height-22">
                                            <span id="discounted_price" class="mr-2 h5 mb-0">
                                            Discounted Price: <?php echo $product['product'][0]['variants'][0]['special_price']*1; ?>/-
                                            </span>
                                        </div>
                                        <div class="clearfix height-22">
                                            <span id="you_save_price" class="mr-2 h5 mb-0">
                                            Total Saving: <?php echo round(($product['product'][0]['variants'][0]['price']-$product['product'][0]['variants'][0]['special_price']),2); ?>/- (<?php echo round((($product['product'][0]['variants'][0]['price'] - $product['product'][0]['variants'][0]['special_price'])/$product['product'][0]['variants'][0]['price'] *100), 2); ?>%)
                                            </span>
                                        </div>
                                    </div>
                                    <?php /* ?>
                                    <sup>
                                        <span class="special-price striped-price text-danger" id="product-striped-price-div">
                                            <s id="striped-price"></s>
                                        </span>
                                    </sup>
                                    <?php */ ?>
                                </div>
                            <?php } ?>
                        </div>
                            
                        <div class="product-short-desc">
                            <p itemprop="description" style="display: none;"><?= (isset($product['product'][0]['short_description']) && !empty($product['product'][0]['short_description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['short_description'])) : "" ?></p>
                        </div>
                        
                        <?php
                        if($product['product'][0]['type'] == "carton_product" && !empty($product['product'][0]['variants'] && $product['product'][0]['product_stock_status']))
                        {
                            ?>
                            <style>
                            .hc-table{border-spacing: 5px;border-collapse: separate;border: none medium;}
                            .hc-table th, .hc-table td{border: none medium;padding: 2px 3px;}
                            .hc-table .bg-thead{background: #737472;border: none medium;font-weight: 600;font-size: 11px;color:#FFF;}
                            /*.tbody tr td:nth-child(1), .tbody tr td:nth-child(2){background: #a9d08e;border: none medium;padding: 5px;text-align: center;font-weight: 600;}
                            .tbody tr td:nth-child(3), .tbody tr td:nth-child(4), .tbody tr td:nth-child(5){background: #c9c9c9;border: none medium;padding: 5px;text-align: center;font-weight: 600;}
                            .tbody tr td:nth-child(6){border: none medium;padding: 5px;}*/
                            .hc-table tr td:nth-child(6), .hc-table tr.current_active td:nth-child(6){border: none medium;background: #FFF;}
                            .hc-table tr td{background: #d5cce5;border: none medium;font-weight: 600;font-size: 11px;}
                            .hc-table tr.current_active td{background: #a6a7a7;border: none medium;font-weight: 600;font-size: 11px;color: #FFF;}
                            /*.hc-table .current_active, */.hc-table .current_active .btn-primary{background: #545454 !important;border: 2px solid #545454;border-radius: 10px;padding: 12px 5px;color:#FFF;}
                            .hc-table tr .btn-primary{background: #FFF !important;border: 2px solid #545454;border-radius: 10px;padding: 12px 5px;color:#545454;}
                            </style>
                            <?php if(!$product['product'][0]['is_service_category']) { ?>
                            <h4 class="h4-">Carton Details</h4>
                            <?php } ?>
                            <table class="table table-bordered mt-2 hc-table- hc-tbl2" cellspacing="5">
                                <thead>
                                    <tr class="bg-thead">
                                        <th class="text-center"><?php echo ($product['product'][0]['is_service_category']) ? 'Unit' : 'Size';?></th>
                                        <?php if(!$product['product'][0]['is_service_category']) { ?> 
                                        <th class="text-center">Weight</th>
                                        <?php } ?>
                                        <th class="text-center" width="14%">MRP</th>
                                        <th class="text-center" width="14%">STD Price</th>
                                        <th class="text-center" width="14%">DISC Price</th>
                                        <th class="text-center" width="12%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                <?php
                                $cc = 0;
                                foreach($product['product'][0]['variants'] as $carton_variant)
                                {
                                    ?>
                                    <tr class="<?php echo (!$cc) ? 'current_active' : ''; ?>">
                                        <td><?php echo $carton_variant['packing_size'].' '.$carton_variant['unit']; echo ($carton_variant['carton_qty'] > 1) ? ' &#x2715; '.$carton_variant['carton_qty'] : ' &#x2715; 1'; //echo $attribute_values[$i]; ?></td>
                                        <?php if(!$product['product'][0]['is_service_category']) { ?>
                                        <td><?php echo $carton_variant['total_weight'].' '.$carton_variant['unit']; ?></td>
                                        <?php } ?>
                                        <td><?php echo $carton_variant['mrp']; ?></td>
                                        <td><?php echo $carton_variant['price']; ?></td>
                                        <td><?php echo $carton_variant['special_price']; ?></td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary variation_pro_select <?php echo (!$cc) ? 'selected_variation' : ''; ?>" data-product-id="<?= $product['product'][0]['id'] ?>" data-price="<?= $carton_variant['price'] ?>" data-mrp="<?= $carton_variant['mrp'] ?>" data-special-price="<?= $carton_variant['special_price'] ?>" data-price-per-item="<?= $carton_variant['price_per_item'] ?>" data-mrp-per-item="<?= $carton_variant['mrp_per_item'] ?>" data-sell-price-per-item="<?= $carton_variant['sell_price_per_item'] ?>" data-special-price-per-item="<?= $carton_variant['special_price_per_item'] ?>" data-product-variant-id="<?= $carton_variant['id'] ?>" data-packing-size="<?= $carton_variant['packing_size'] ?>" data-unit="<?= $carton_variant['unit'] ?>"> <?php echo (!$cc) ? 'Selected' : 'Select'; ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $cc++;
                                }
                                ?>
                                </tbody>
                            </table>
                            <p style="font-size: 11px;"><b>*STD price</b> – Distributor price,  <b>DISC price</b> – Discounted price,  <b>Selling price</b> – Minimum selling price for end consumer</p>
                            <?php
                        }
                        
                        ?>             
                       
                        <?php
                        $color_code = $style = "";
                        $product['product'][0]['variant_attributes'] = array_values($product['product'][0]['variant_attributes']);
                        
                        /*
                        if (isset($product['product'][0]['variant_attributes']) && !empty($product['product'][0]['variant_attributes'])) { ?>
                            <?php
                            foreach ($product['product'][0]['variant_attributes'] as $attribute) {
                                $attribute_values = explode(',', $attribute['values']);
                                $attribute_ids = explode(',', $attribute['ids']);
                                $swatche_types = explode(',', $attribute['swatche_type']);
                                $swatche_values = explode(',', $attribute['swatche_value']);
                                for ($i = 0; $i < count($swatche_types); $i++) {
                                    if (!empty($swatche_types[$i]) && $swatche_values[$i] != "") {
                                        $style = '<style> .product-page-details .btn-group>.active { background-color: #ffffff; color: #000000; border: 1px solid black;}</style>';
                                    } else if ($swatche_types[$i] == 0 && $swatche_values[$i] == null) {
                                        $style1 = '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>';
                                    }
                                }
                            ?>
                            <h4 class="mt-2"><?= $attribute['attr_name'] ?></h4>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="<?= $attribute['attr_name'] ?>">
                                <?php foreach ($attribute_values as $key => $row) {
                                    if ($swatche_types[$key] == "1") {
                                        echo '<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style>';
                                        $color_code = "style='background-color:" . $swatche_values[$key] . ";'";  ?>
                                        <label class="btn text-center fullCircle " <?= $color_code ?>>
                                            <input type="radio" name="<?= $attribute['attr_name'] ?>" value="<?= $attribute_ids[$key] ?>" autocomplete="off" class="attributes">
                                        </label>
                                    <?php } else if ($swatche_types[$key] == "2") { ?>
                                        <?= $style ?>
                                        <label class="btn text-center ">
                                            <img class="swatche-image" src="<?= $swatche_values[$key] ?>">
                                            <input type="radio" name="<?= $attribute['attr_name'] ?>" value="<?= $attribute_ids[$key] ?>" autocomplete="off" class="attributes">
                                            <br>
                                        </label>
                                    <?php } else { ?>
                                        <?= '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>'; ?>
                                        <label class="btn btn-default text-center">
                                            <?= $row ?>
                                            <input type="radio" name="<?= $attribute['attr_name'] ?>" value="<?= $attribute_ids[$key] ?>" autocomplete="off" class="attributes">
                                            <br>
                                        </label>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php
                        }
                    }
                    */
                    ?>
                    <?php
                    if(isset($product['product'][0]['variant_attributes']) && !empty($product['product'][0]['variant_attributes'])  && $product['product'][0]['product_stock_status']) 
                    { 
                        ?>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th>Carton Size</th>
                                    <th>Total Weight</th>
                                    <th width="15%">MRP</th>
                                    <th width="15%">STD Price</th>
                                    <th width="15%">DISC Price</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $cc = 0;
                            foreach($product['product'][0]['variant_attributes'] as $attribute) 
                            {
                                $attribute_values = explode(',', $attribute['values']);
                                $attribute_ids = explode(',', $attribute['ids']);
                                $swatche_types = explode(',', $attribute['swatche_type']);
                                $swatche_values = explode(',', $attribute['swatche_value']);
                                $volumes = explode(',', $attribute['volume']);
                                $units = explode(',', $attribute['unit']);
                                $variant_ids =  $attribute['variant_ids'];
                                                                   
                                for($i = 0; $i < count($swatche_types); $i++) 
                                {
                                    $variant_id = $variant_ids[$i];
                                    ?>
                                    <tr class="<?php echo (!$cc) ? 'current_active' : ''; ?>">
                                        <td><?php echo $attribute_values[$i]; ?></td>
                                        <td><?php echo $volumes[$i].' '.$units[$i]; ?></td>
                                        <td><?php echo $product['product'][0]['variants'][$i]['mrp']; ?></td>
                                        <td><?php echo $product['product'][0]['variants'][$i]['price']; ?></td>
                                        <td><?php echo $product['product'][0]['variants'][$i]['special_price']; ?></td>
                                        <td>
                                            <?php /* ?>
                                            <a href="#" class="btn btn-sm btn-primary add_to_cart" data-product-id="<?= $product['product'][0]['id'] ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?>" data-product-variant-id="<?= $variant_id ?>">Select</a>
                                            <?php */ ?>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary variation_pro_select <?php echo (!$cc) ? 'selected_variation' : ''; ?>" data-product-id="<?= $product['product'][0]['id'] ?>" data-price="<?= $product['product'][0]['variants'][$i]['price'] ?>" data-mrp="<?= $product['product'][0]['variants'][$i]['mrp'] ?>" data-special-price="<?= $product['product'][0]['variants'][$i]['special_price'] ?>" data-price-per-item="<?= $product['product'][0]['variants'][$i]['price_per_item'] ?>" data-mrp-per-item="<?= $product['product'][0]['variants'][$i]['mrp_per_item'] ?>" data-special-price-per-item="<?= $product['product'][0]['variants'][$i]['special_price_per_item'] ?>" data-product-variant-id="<?= $variant_id ?>"> <?php echo (!$cc) ? 'Selected' : 'Select'; ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $cc++;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                    <?php
                    if (!empty($product['product'][0]['variants']) && isset($product['product'][0]['variants'])) {
                        $total_images = 1;
                        foreach ($product['product'][0]['variants'] as $variant) {
                        ?>
                            <input type="hidden" class="variants" name="variants_ids" data-image-index="<?= $total_images ?>" data-name="" value="<?= $variant['variant_ids'] ?>" data-id="<?= $variant['id'] ?>" data-price="<?= $variant['price'] ?>" data-special_price="<?= $variant['special_price'] ?>" />
                    <?php
                            $total_images += count($variant['images']);
                        }
                    }
                    ?>
                    <?php /* ?>
                    <form class="mt-2" id="validate-zipcode-form" method="POST">
                        <div class="form-row">
                            <div class=" col-md-6">
                                <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                <input type="text" class="form-control" id="zipcode" placeholder="Zipcode" name="zipcode" autocomplete="off" required value="<?= $product['product'][0]['zipcode']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary button2 button-primary-outline2" id="validate_zipcode">Check Availability</button>
                        </div>
                        <div class="mt-2" id="error_box">
                            <?php if (!empty($product['product'][0]['zipcode'])) { ?>
                                <b class="text-<?= ($product['product'][0]['is_deliverable']) ? "success" : "danger" ?>">Product is <?= ($product['product'][0]['is_deliverable']) ? "" : "not" ?> delivarable on &quot; <?= $product['product'][0]['zipcode']; ?> &quot; </b>
                            <?php } ?>
                        </div>
                    </form>
                    <?php */ ?>
                    <!--end profile -->
                    <?php if($product['product'][0]['product_stock_status']) { ?> 
                        <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                        <div class="num-block skin-2 py-4">
                            <div class="num-in">
                                <span class="minus dis" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"></span>
                                <input type="text" name="qty" class="in-num" value="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?>">
                                <span class="plus" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?> " data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"></span>
                            </div>
                        </div>
                        <?php } else { ?>
                        <input type="hidden" name="qty" class="in-num" value="1"/>
                        <?php } ?>
                    <?php } else { ?>
                    <h4 class="text-danger mt-4 mb-0">Out of stock</h4>
                    <?php } ?>
                     
                    <div class="bg-gray mt-2 mb-2">
                        <?php ($product['product'][0]['tax_percentage'] != 0) ? "Tax" . $product['product'][0]['tax_percentage'] : '' ?>
                    </div>
                    <input type="hidden" class="variants_data" id="variants_data" data-name="<?= $product['product'][0]['name'] ?>" data-image="<?= $product['product'][0]['image'] ?>" data-id="<?= $variant['id'] ?>" data-price="<?= $variant['price'] ?>" data-special_price="<?= $variant['special_price'] ?>">
                    <div class="" id="result"></div>
                    <div class="pt-3 text-md-left">
                        <?php
                        if (count($product['product'][0]['variants']) <= 1) {
                            $variant_id = $product['product'][0]['variants'][0]['id'];
                        } else {
                            $variant_id = "";
                            $variant_id = $product['product'][0]['variants'][0]['id'];
                        }
                        
                        ?>
                        <?php //if ($product['product'][0]['type'] == "simple_product") { ?>
                        
                            <?php if($product['product'][0]['product_stock_status'] && !$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                            <button type="button" name="add_cart" class="btn btn-primary btn-cart buttons2 btn-6-62 extra-small m-0 add_to_cart" id="add_cart" data-product-id="<?= $product['product'][0]['id'] ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?>" data-product-variant-id="<?= $variant_id ?>">
                                <i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add to Cart' ?>
                            </button>
                            <?php } ?>
                            
                            <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                <?php if ($product['product'][0]['is_favorite'] == 0) { ?>
                                    <button class="btn btn-danger buttons2 btn-6-12 extra-small m-0 add-fav" id="add_to_favorite_btn" data-product-id="<?= $product['product'][0]['id'] ?>">
                                        <i class="fas fa-heart mr-2-"></i>
                                        <span class="d-none"><?= !empty($this->lang->line('add_to_favorite')) ? $this->lang->line('add_to_favorite') : 'Add to Favorite' ?></span>
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-danger buttons2 btn-6-12 extra-small m-0 remove-fav" id="add_to_favorite_btn" data-product-id="<?= $product['product'][0]['id'] ?>">
                                        <i class="fas fa-heart mr-2-"></i>
                                        <span class="d-none"><?= !empty($this->lang->line('remove_from_favorite')) ? $this->lang->line('remove_from_favorite') : 'Remove from Favorite' ?></span>
                                    </button>
                                <?php } ?>
                            <?php } ?>
                            
                        <?php //} ?>
                        
                    </div>
                    <?php if (isset($product['product'][0]['tags']) && !empty($product['product'][0]['tags'])) { ?>
                        <div class="mt-2">
        
                            Tags
                            <?php foreach ($product['product'][0]['tags'] as $tag) { ?>
                                <a href="<?= base_url('products/tags/' . $tag) ?>"><span class="badge badge-secondary p-1"><?= $tag ?></span></a>
                            <?php } ?>
                            </span>
                        <?php } ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mx-0">
        <?php /* ?>
        <div class="col-12 col-md-6 product-preview-image-section-md">
            <div class="swiper-container product-gallery-top gallery-top-1">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class='product-view-grid'>
                            <div class='product-view-image'>
                                <div class='product-view-image-container'>
                                    <img src="<?= $product['product'][0]['image'] ?>" id="img_01" data-zoom-image="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php

                    $variant_images_md = array_column($product['product'][0]['variants'], 'images_md');
                    if (!empty($variant_images_md)) {
                        foreach ($variant_images_md as $variant_images) {
                            if (!empty($variant_images)) {
                                foreach ($variant_images as $image) {
                    ?>
                                    <div class="swiper-slide">
                                        <div class='product-view-grid'>
                                            <div class='product-view-image'>
                                                <div class='product-view-image-container'>
                                                    <img src="<?= $image ?>" data-zoom-image="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    <?php }
                            }
                        }
                    } ?>
                    <?php
                    if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                        foreach ($product['product'][0]['other_images'] as $other_image) {
                            $total_images++;
                    ?>
                            <div class="swiper-slide">
                                <div class='product-view-grid'>
                                    <div class='product-view-image'>
                                        <div class='product-view-image-container'>
                                            <img src="<?= $other_image ?>" id="img_01" data-zoom-image="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                    <?php
                    if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                        $total_images++;
                    ?>
                        <div class="swiper-slide">
                            <div class='product-view-grid'>
                                <div class='product-view-image'>
                                    <div class='product-view-image-container'>
                                        <?php if ($product['product'][0]['video_type'] == 'self_hosted') { ?>
                                            <video controls width="320" height="240" src="<?= $product['product'][0]['video'] ?>">
                                                Your browser does not support the video tag.
                                            </video>
                                         <?php } else if ($product['product'][0]['video_type'] == 'youtube' || $product['product'][0]['video_type'] == 'vimeo') {
                                            if ($product['product'][0]['video_type'] == 'vimeo') {
                                                $url =  explode("/", $product['product'][0]['video']);
                                                $id = end($url);
                                                $url = 'https://player.vimeo.com/video/' . $id;
                                            } else if ($product['product'][0]['video_type'] == 'youtube') {
                                                if (strpos($product['product'][0]['video'], 'watch?v=') !== false) {
                                                    $url = str_replace("watch?v=", "embed/", $product['product'][0]['video']);
                                                }else{
                                                    $url = $product['product'][0]['video'];
                                                }
                                            } else {
                                                $url = $product['product'][0]['video'];
                                            } ?>
                                            <iframe  width="560" height="315" src="<?= $url ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Add Arrows -->
                <div class="swiper-button-next swiper-button-black"></div>
                <div class="swiper-button-prev swiper-button-black"></div>
            </div>
            <div class="swiper-container product-gallery-thumbs gallery-thumbs-1">
                <div class="swiper-wrapper" id="gal1">
                    <div class="swiper-slide ml-0">
                        <div class='product-view-grid'>
                            <div class='product-view-image'>
                                <div class='product-view-image-container'>
                                    <img src="<?= $product['product'][0]['image'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($variant_images_md)) {
                        foreach ($variant_images_md as $variant_images) {
                            if (!empty($variant_images)) {
                                foreach ($variant_images as $image) {
                    ?>
                                    <div class="swiper-slide">
                                        <div class='product-view-grid'>
                                            <div class='product-view-image'>
                                                <div class='product-view-image-container'>
                                                    <img src="<?= $image ?>" data-zoom-image="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    <?php }
                            }
                        }
                    } ?>
                    <?php
                    if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                        foreach ($product['product'][0]['other_images'] as $other_image) { ?>
                            <div class="swiper-slide ml-0">
                                <div class='product-view-grid'>
                                    <div class='product-view-image'>
                                        <div class='product-view-image-container'>
                                            <img src="<?= $other_image ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                    <?php
                    if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                        $total_images++;
                    ?>
                        <div class="swiper-slide">
                            <div class='product-view-grid'>
                                <div class='product-view-image'>
                                    <div class='product-view-image-container'>
                                        <img src="<?= base_url('assets/admin/images/video-file.png') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Mobile Product Image Slider -->
        <div class="col-12 col-md-6 product-preview-image-section-sm">
            <div class=" swiper-container preview-image-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide text-center"><img src="<?= $product['product'][0]['image'] ?>"></div>
                    <?php
                    if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                        foreach ($product['product'][0]['other_images'] as $other_image) { ?>
                            <div class="swiper-slide text-center"><img src="<?= $other_image ?>"></div>
                    <?php }
                    } ?>
                    <?php if (!empty($variant_images_md)) {
                        foreach ($variant_images_md as $variant_images) {
                            if (!empty($variant_images)) {
                                foreach ($variant_images as $image) {
                    ?>
                                    <div class="swiper-slide text-center"><img src="<?= $image ?>" data-zoom-image=""></div>

                    <?php }
                            }
                        }
                    } ?>
                    <?php
                    if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                        $total_images++;
                    ?>
                        <div class="swiper-slide">
                            <div class='product-view-grid'>
                                <div class='product-view-image'>
                                    <div class='product-view-image-container'>
                                        <?php if ($product['product'][0]['video_type'] == 'self_hosted') { ?>
                                            <video controls width="320" height="240" src="<?= $product['product'][0]['video'] ?>">
                                                Your browser does not support the video tag.
                                            </video>
                                         <?php } else if ($product['product'][0]['video_type'] == 'youtube' || $product['product'][0]['video_type'] == 'vimeo') { 
                                             if ($product['product'][0]['video_type'] == 'vimeo') {
                                                $url =  explode("/", $product['product'][0]['video']);
                                                $id = end($url);
                                                $url = 'https://player.vimeo.com/video/' . $id;
                                            } else if ($product['product'][0]['video_type'] == 'youtube') {
                                                if (strpos($product['product'][0]['video'], 'watch?v=') !== false) {
                                                    $url = str_replace("watch?v=", "embed/", $product['product'][0]['video']);
                                                }else{
                                                    $url = $product['product'][0]['video'];
                                                }
                                            } else {
                                                $url = $product['product'][0]['video'];
                                            }
                                            ?>
                                            <iframe width="560" height="315" src="<?= $url ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination preview-image-swiper-pagination text-center"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 product-page-details">
            <h3 class="my-3 product-title">
                <p><?= ucfirst($product['product'][0]['name']) ?></p>
            </h3>
            <p itemprop="description"><?= (isset($product['product'][0]['short_description']) && !empty($product['product'][0]['short_description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['short_description'])) : "" ?></p>
            <hr>
            <div class="col-md-12 mb-3 product-rating-small  pl-0">
                <input type="text" class="kv-fa rating-loading" value="<?= $product['product'][0]['rating'] ?>" data-size="sm" title="" readonly>
                <span class="my-auto mx-3"> ( <?= $product['product'][0]['no_of_ratings'] ?> <?= !empty($this->lang->line('reviews')) ? $this->lang->line('reviews') : 'reviews' ?> ) </span>
            </div>
            <?php if ($product['product'][0]['type'] == "simple_product") { ?>
                <p class="mb-0 mt-2 price" id="price">
                    <i><?php $price = get_price_range_of_product($product['product'][0]['id']);
                        // print_r($this->db->last_query());
                        echo $price['range'];
                        ?>
                        <sup><span class="special-price striped-price"><s><?= !empty($product['product'][0]['min_max_price']['special_price']) && $product['product'][0]['min_max_price']['special_price'] != NULL  ? '<i>' . $settings['currency'] . '</i>' . number_format($product['product'][0]['min_max_price']['min_price']) : '' ?></s></span></sup>
                </p>
                <p class="mb-0 mt-2 price d-none" id="price">
                    <?php $price = get_price_range_of_product($product['product'][0]['id']);
                    // print_r($this->db->last_query());
                    // echo $price['range'];
                    ?>
                </p>
            <?php } else { ?>
                <p class="mb-0 mt-2 price"><span id="price">
                        <?php $price = get_price_range_of_product($product['product'][0]['id']);
                        echo $price['range'];
                        ?>
                    </span>
                    <sup>
                        <span class="special-price striped-price text-danger" id="product-striped-price-div">
                            <s id="striped-price"></s>
                        </span></sup>
                </p>
            <?php }
            $color_code = $style = "";
            $product['product'][0]['variant_attributes'] = array_values($product['product'][0]['variant_attributes']);

            if (isset($product['product'][0]['variant_attributes']) && !empty($product['product'][0]['variant_attributes'])) { ?>
                <?php
                foreach ($product['product'][0]['variant_attributes'] as $attribute) {
                    $attribute_values = explode(',', $attribute['values']);
                    $attribute_ids = explode(',', $attribute['ids']);
                    $swatche_types = explode(',', $attribute['swatche_type']);
                    $swatche_values = explode(',', $attribute['swatche_value']);
                    for ($i = 0; $i < count($swatche_types); $i++) {
                        if (!empty($swatche_types[$i]) && $swatche_values[$i] != "") {
                            $style = '<style> .product-page-details .btn-group>.active { background-color: #ffffff; color: #000000; border: 1px solid black;}</style>';
                        } else if ($swatche_types[$i] == 0 && $swatche_values[$i] == null) {
                            $style1 = '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>';
                        }
                    }
                ?>
                    <h4 class="mt-2"><?= $attribute['attr_name'] ?></h4>
                    <div class="btn-group btn-group-toggle" data-toggle="buttons" id="<?= $attribute['attr_name'] ?>">
                        <?php foreach ($attribute_values as $key => $row) {
                            if ($swatche_types[$key] == "1") {
                                echo '<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style>';
                                $color_code = "style='background-color:" . $swatche_values[$key] . ";'";  ?>
                                <label class="btn text-center fullCircle " <?= $color_code ?>>
                                    <input type="radio" name="<?= $attribute['attr_name'] ?>" value="<?= $attribute_ids[$key] ?>" autocomplete="off" class="attributes">
                                </label>
                            <?php } else if ($swatche_types[$key] == "2") { ?>
                                <?= $style ?>
                                <label class="btn text-center ">
                                    <img class="swatche-image" src="<?= $swatche_values[$key] ?>">
                                    <input type="radio" name="<?= $attribute['attr_name'] ?>" value="<?= $attribute_ids[$key] ?>" autocomplete="off" class="attributes">
                                    <br>
                                </label>
                            <?php } else { ?>
                                <?= '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>'; ?>
                                <label class="btn btn-default text-center">
                                    <?= $row ?>
                                    <input type="radio" name="<?= $attribute['attr_name'] ?>" value="<?= $attribute_ids[$key] ?>" autocomplete="off" class="attributes">
                                    <br>
                                </label>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php
                }
            }
            if (!empty($product['product'][0]['variants']) && isset($product['product'][0]['variants'])) {
                $total_images = 1;
                foreach ($product['product'][0]['variants'] as $variant) {
                ?>
                    <input type="hidden" class="variants" name="variants_ids" data-image-index="<?= $total_images ?>" data-name="" value="<?= $variant['variant_ids'] ?>" data-id="<?= $variant['id'] ?>" data-price="<?= $variant['price'] ?>" data-special_price="<?= $variant['special_price'] ?>" />
            <?php
                    $total_images += count($variant['images']);
                }
            }
            ?>

            <form class="mt-2" id="validate-zipcode-form" method="POST">
                <div class="form-row">
                    <div class=" col-md-6">
                        <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                        <input type="text" class="form-control" id="zipcode" placeholder="Zipcode" name="zipcode" autocomplete="off" required value="<?= $product['product'][0]['zipcode']; ?>">
                    </div>
                    <button type="submit" class="button button-primary-outline" id="validate_zipcode">Check Availability</button>
                </div>
                <div class="mt-2" id="error_box">
                    <?php if (!empty($product['product'][0]['zipcode'])) { ?>
                        <b class="text-<?= ($product['product'][0]['is_deliverable']) ? "success" : "danger" ?>">Product is <?= ($product['product'][0]['is_deliverable']) ? "" : "not" ?> delivarable on &quot; <?= $product['product'][0]['zipcode']; ?> &quot; </b>
                    <?php } ?>
                </div>
            </form>
            <!--end profile -->
            <div class="num-block skin-2 py-4">
                <div class="num-in">
                    <span class="minus dis" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"></span>
                    <input type="text" name="qty" class="in-num" value="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?>">
                    <span class="plus" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?> " data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"></span>
                </div>
            </div>
            <div class="bg-gray mt-2 mb-2">
                <?php ($product['product'][0]['tax_percentage'] != 0) ? "Tax" . $product['product'][0]['tax_percentage'] : '' ?>
            </div>
            <input type="hidden" class="variants_data" id="variants_data" data-name="<?= $product['product'][0]['name'] ?>" data-image="<?= $product['product'][0]['image'] ?>" data-id="<?= $variant['id'] ?>" data-price="<?= $variant['price'] ?>" data-special_price="<?= $variant['special_price'] ?>">
            <div class="" id="result"></div>
            <div class="pt-3 text-md-left">
                <?php
                if (count($product['product'][0]['variants']) <= 1) {
                    $variant_id = $product['product'][0]['variants'][0]['id'];
                } else {
                    $variant_id = "";
                }
                ?>
                <button type="button" name="add_cart" class="buttons btn-6-6 extra-small m-0 add_to_cart" id="add_cart" data-product-id="<?= $product['product'][0]['id'] ?>" data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>" data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '' ?>" data-product-variant-id="<?= $variant_id ?>">
                    <i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add to Cart' ?>
                </button>
                <?php if ($product['product'][0]['is_favorite'] == 0) { ?>
                    <button class="buttons btn-6-1 extra-small m-0 add-fav" id="add_to_favorite_btn" data-product-id="<?= $product['product'][0]['id'] ?>">
                        <i class="fas fa-heart mr-2"></i>
                        <span><?= !empty($this->lang->line('add_to_favorite')) ? $this->lang->line('add_to_favorite') : 'Add to Favorite' ?></span>
                    </button>
                <?php } else { ?>
                    <button class="buttons btn-6-1 extra-small m-0 remove-fav" id="add_to_favorite_btn" data-product-id="<?= $product['product'][0]['id'] ?>">
                        <i class="fas fa-heart mr-2"></i>
                        <span><?= !empty($this->lang->line('remove_from_favorite')) ? $this->lang->line('remove_from_favorite') : 'Remove from Favorite' ?></span>
                    </button>
                <?php } ?>
            </div>
            <?php if (isset($product['product'][0]['tags']) && !empty($product['product'][0]['tags'])) { ?>
                <div class="mt-2">

                    Tags
                    <?php foreach ($product['product'][0]['tags'] as $tag) { ?>
                        <a href="<?= base_url('products/tags/' . $tag) ?>"><span class="badge badge-secondary p-1"><?= $tag ?></span></a>
                    <?php } ?>
                    </span>
                <?php } ?>
                </div>
        </div>
        <?php */ ?>
        
        <div class="col-12 row mt-0">
            <?php
            $super_category_id = $product['product'][0]['super_category_id'];
            ?>
            <div class="tab tab-nav-boxed tab-nav-underline product-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#product-tab-description" class="nav-link active">Description</a>
                    </li>
                    <?php
                    if($super_category_id != 3 && $super_category_id != 6)
                    {
                        ?>
                        <li class="nav-item">
                            <a href="#product-tab-rec-crops" class="nav-link">Recommended Crops</a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-tab-about-company" class="nav-link">About Company</a>
                        </li>
                        <?php /* ?>
                        <li class="nav-item">
                            <a href="#product-tab-about-formulation" class="nav-link">Formulation</a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-tab-about-usage" class="nav-link">Usage</a>
                        </li>
                        <?php */ ?>
                        <li class="nav-item">
                            <a href="#product-tab-dosage" class="nav-link">Dosage</a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-tab-method-of-app" class="nav-link">Method of Application</a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-tab-benefits" class="nav-link">Benefits</a>
                        </li>
                        <?php
                    }
                    ?>
                    
                    <?php
                    if($super_category_id == 6)
                    {
                        ?>
                        <li class="nav-item">
                            <a href="#product-tab-tools" class="nav-link">Tools & Equipment Required</a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-tab-technician" class="nav-link">Technician Qualification</a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-tab-certifications" class="nav-link">Certifications and Accreditations</a>
                        </li>
                        <?php
                    }
                    ?>
                    
                    <?php
                    if($super_category_id == 3)
                    {
                        ?>
                        <li class="nav-item">
                            <a href="#product-tab-specification" class="nav-link">Specification</a>
                        </li>
                        <?php
                    }
                    ?>
                    
                    <?php
                    if($super_category_id == 3)
                    {
                        ?>
                        <li class="nav-item">
                            <a href="#product-tab-about-company" class="nav-link">About Company</a>
                        </li>
                        <?php
                    }
                    ?>
                    
                    <li class="nav-item">
                        <a href="#product-documents-tab" class="nav-link">Other Documents</a>
                    </li>
                    <li class="nav-item">
                        <a href="#product-review-tab" class="nav-link">Reviews</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="product-tab-description">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['short_description']) && !empty($product['product'][0]['short_description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['short_description'])) : "" ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['description']) && !empty($product['product'][0]['description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['description'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    if($super_category_id == 6)
                    {
                        ?>
                        <div class="tab-pane" id="product-tab-tools">
                            <div class="row">
                                <div class="col-12">
                                    <?= (isset($product['product'][0]['tools']) && !empty($product['product'][0]['tools'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['tools'])) : ""  ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="product-tab-technician">
                            <div class="row">
                                <div class="col-12">
                                    <?= (isset($product['product'][0]['technician']) && !empty($product['product'][0]['technician'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['technician'])) : ""  ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="product-tab-technician">
                            <div class="row">
                                <div class="col-12">
                                    <?= (isset($product['product'][0]['certifications']) && !empty($product['product'][0]['certifications'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['certifications'])) : ""  ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    
                    <div class="tab-pane" id="product-tab-rec-crops">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['rec_crops']) && !empty($product['product'][0]['rec_crops'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['rec_crops'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-tab-about-company">
                        <div class="row">
                            <div class="col-12">
                                <?php //echo (isset($product['product'][0]['about_company']) && !empty($product['product'][0]['about_company'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['about_company'])) : ""  ?>
                                <?php echo $product['product'][0]['seller_about_us']; ?>
                            </div>
                        </div>
                    </div>
                    <?php /* ?>
                    <div class="tab-pane" id="product-tab-about-formulation">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['about_formulation']) && !empty($product['product'][0]['about_formulation'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['about_formulation'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="product-tab-about-usage">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['about_usage']) && !empty($product['product'][0]['about_usage'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['about_usage'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <?php */ ?>
                    
                    <div class="tab-pane" id="product-tab-method-of-app">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['method_of_app']) && !empty($product['product'][0]['method_of_app'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['method_of_app'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-tab-dosage">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['dosage']) && !empty($product['product'][0]['dosage'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['dosage'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-tab-specification">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['specifications']) && !empty($product['product'][0]['specifications'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['specifications'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-tab-benefits">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['benefits']) && !empty($product['product'][0]['benefits'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['benefits'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-review-tab">
                        <?php
                        if (!empty($review_images['total_images'])) {
                            if ($review_images['total_images'] > 0) { ?>
                                <h3 class="review-title"> User Review Images (<span><?= $review_images['total_images'] ?></span>)</h3>
                            <?php
                            }
                        }
                        if (isset($review_images['product_rating']) && !empty($review_images['product_rating'])) { ?>
                            <div class="row reviews">
                                <?php
                                $count = 0;
                                $total_images = $review_images['total_images'];
                                for ($i = 0; $i < count($review_images['product_rating']); $i++) {
                                    if (!empty($review_images['product_rating'][$i]['images'])) {
                                        for ($j = 0; $j < count($review_images['product_rating'][$i]['images']); $j++) {
                                            if ($count <= 8) {
                                                if ($count == 8 && !empty($review_images['product_rating'][$i]['images'][$j])) { ?>
                                                    <div class="col-sm-1">
                                                        <div class="review-box">
                                                            <a href="<?= $review_images['product_rating'][$i]['images'][$j] ?>">
                                                                <p class="limit_position"><?= "+" . ($total_images - 8) ?></p>
                                                                <img id="review-image-title" src="<?= $review_images['product_rating'][$i]['images'][$j] ?>" alt="Review Image" data-reached-end="false" data-review-limit="1" data-review-offset="0" data-product-id="<?= $review_images['product_rating'][$i]['product_id'] ?>" data-review-title="User Review Images(<span><?= $review_images['total_images'] ?></span>)" data-izimodal-open="#user-review-images" class="overlay">
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } else if (!empty($review_images['product_rating'][$i]['images'][$j])) { ?>
                                                    <div class="col-sm-1">
                                                        <div class="review-box">
                                                            <a href="<?= $review_images['product_rating'][$i]['images'][$j] ?>" data-lightbox="users-review-images" data-title="<?= "<button class='label btn-success'>" . $review_images['product_rating'][$i]['rating'] . " <i class='fa fa-star'></i></button></br>" . $review_images['product_rating'][$i]['user_name'] . "<br>" . $review_images['product_rating'][$i]['comment'] ?> ">
                                                                <img src="<?= $review_images['product_rating'][$i]['images'][$j] ?>" alt="Review Images">
                                                            </a>
                                                        </div>
                                                    </div>
                                <?php }
                                                $count += 1;
                                            }
                                        }
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-xl-7">
                                <h3 class="review-title"> <span id="no_ratings"><?= $product['product'][0]['no_of_ratings'] ?></span> Reviews For this Product</h3>
                                <ol class="review-list" id="review-list">
                                    <?php if (isset($my_rating) && !empty($my_rating)) {
                                        foreach ($my_rating['product_rating'] as $row) { ?>
                                            <li class="review-container">
                                                <div class="review-image">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/user.png' ?>" alt="" width="65" height="65">
                                                </div>
                                                <div class="review-comment">
                                                    <div class="rating-list">
                                                        <div class="product-rating">
                                                            <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="xs" title="" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="review-info">
                                                        <h4 class="reviewer-name"><?= $row['user_name'] ?></h4>
                                                        <span class="review-date text-muted"><?= $row['data_added'] ?></span>
                                                    </div>
                                                    <div class="review-text">
                                                        <p class="text-muted"><?= $row['comment'] ?></p>
                                                        <a id="delete_rating" href="<?= base_url('products/delete-rating') ?>" class="text-danger" data-rating-id="<?= $row['id'] ?>">Delete</a>
                                                    </div>
                                                    <div class="row reviews">
                                                        <?php foreach ($row['images'] as $image) { ?>
                                                            <div class="col-sm-2">
                                                                <div class="review-box">
                                                                    <a href="<?= $image ?>" data-lightbox="review-images">
                                                                        <img src="<?= $image ?>" alt="Review Image">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </li>
                                    <?php }
                                    } ?>
                                    <?php if (isset($product_ratings) && !empty($product_ratings)) {
                                        $user_id = (isset($user->id)) ? $user->id : '';
                                        foreach ($product_ratings['product_rating'] as $row) {
    
                                            if ($row['user_id'] != $user_id) { ?>
    
                                                <li class="review-container">
                                                    <div class="review-image">
                                                        <img src="<?= THEME_ASSETS_URL . 'images/user.png' ?>" alt="" width="65" height="65">
                                                    </div>
                                                    <div class="review-comment">
                                                        <div class="rating-list">
                                                            <div class="product-rating">
                                                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="xs" title="" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="review-info">
                                                            <h4 class="reviewer-name"><?= $row['user_name'] ?></h4>
                                                            <span class="review-date text-muted"><?= $row['data_added'] ?></span>
                                                        </div>
                                                        <div class="review-text">
                                                            <p class="text-muted"><?= $row['comment'] ?></p>
                                                        </div>
                                                        <div class="row reviews">
                                                            <?php foreach ($row['images'] as $image) { ?>
                                                                <div class="col-md-2">
                                                                    <div class="review-box">
                                                                        <a href="<?= $image ?>" data-lightbox="review-images">
                                                                            <img src="<?= $image ?>" alt="Review Image">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </li>
                                    <?php }
                                        }
                                    } ?>
                                </ol>
                            </div>
                            <?php if ($product['product'][0]['is_purchased'] == 1) {
                                $form_link = (!empty($my_rating)) ? base_url('products/edit-rating') : base_url('products/save-rating');
                            ?>
                                <div class="col-xl-5 <?= (!empty($my_rating)) ? 'd-none' : '' ?>" id="rating-box">
                                    <div class="add-review">
                                        <h3 class="review-title">Add Your Review</h3>
                                        <form action="<?= $form_link ?>" id="product-rating-form" method="POST">
                                            <?php if (!empty($my_rating)) { ?>
                                                <input type="hidden" name="rating_id" value="<?= $my_rating['product_rating'][0]['id'] ?>">
                                            <?php } ?>
                                            <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                            <div class="rating-form">
                                                <label for="rating">Your rating</label>
                                                <input type="text" class="kv-fa rating-loading" data-step="1" name="rating" value="<?= isset($my_rating['product_rating'][0]['rating']) ? $my_rating['product_rating'][0]['rating'] : '0' ?>" data-size="sm" title="">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Your Review</label>
                                                <textarea class="form-control" name="comment" rows="3"><?= isset($my_rating['product_rating'][0]['comment']) ? $my_rating['product_rating'][0]['comment'] : '' ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Images</label>
                                                <input type="file" name="images[]" accept="image/x-png,image/gif,image/jpeg" multiple />
                                            </div>
                                            <button class="buttons extra-small primary-button text-center m-0" id="rating-submit-btn">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($product_ratings) && !empty($product_ratings)) { ?>
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button class="buttons btn-6-6" id="load-user-ratings" data-product="<?= $product['product'][0]['id'] ?>" data-limit="<?= $user_rating_limit ?>" data-offset="<?= $user_rating_offset ?>">Load more</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-documents-tab">
                        <h4 class="h4">Other Documents</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">Sr. No.</th>
                                    <th>Title</th>
                                    <th width="10%">Click to View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr_no = 1;
                                if(file_exists('uploads/specifications_file/' .$product['product'][0]['id'].'.pdf'))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr_no; ?></td>
                                        <td>Specifications</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url().'uploads/specifications_file/' .$product['product'][0]['id'].'.pdf'; ?>" target="_blank">Click to View</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $sr_no++;
                                }
                                
                                if(file_exists('uploads/quality_inspection_file/' .$product['product'][0]['id'].'.pdf'))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr_no; ?></td>
                                        <td>Quality inspection report</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url().'uploads/quality_inspection_file/' .$product['product'][0]['id'].'.pdf'; ?>" target="_blank">Click to View</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $sr_no++;
                                }
                                
                                if(file_exists('uploads/patent_file/' .$product['product'][0]['id'].'.pdf'))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr_no; ?></td>
                                        <td>Patent</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url().'uploads/patent_file/' .$product['product'][0]['id'].'.pdf'; ?>" target="_blank">Click to View</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $sr_no++;
                                }
                                
                                if(file_exists('uploads/certification_file/' .$product['product'][0]['id'].'.pdf'))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr_no; ?></td>
                                        <td>Certification</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url().'uploads/certification_file/' .$product['product'][0]['id'].'.pdf'; ?>" target="_blank">Click to View</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $sr_no++;
                                }
                                
                                if($sr_no==1)
                                {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No Information</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php /* ?>
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link product-nav-tab active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true"><?= !empty($this->lang->line('description')) ? $this->lang->line('description') : 'Description' ?></a>
                    <a class="nav-item nav-link product-nav-tab" id="product-review-tab" data-toggle="tab" href="#product-review" role="tab" aria-controls="product-review" aria-selected="false"><?= !empty($this->lang->line('reviews')) ? $this->lang->line('reviews') : 'Reviews' ?></a>
                </div>
            </nav>
            <div class="tab-content p-3 w-100" id="nav-tabContent">
                <div class="tab-pane fade active show" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <?= (isset($product['product'][0]['description']) && !empty($product['product'][0]['description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['description'])) : ""  ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="product-review" role="tabpanel" aria-labelledby="product-review-tab">
                    <?php
                    if (!empty($review_images['total_images'])) {
                        if ($review_images['total_images'] > 0) { ?>
                            <h3 class="review-title"> User Review Images (<span><?= $review_images['total_images'] ?></span>)</h3>
                        <?php
                        }
                    }
                    if (isset($review_images['product_rating']) && !empty($review_images['product_rating'])) { ?>
                        <div class="row reviews">
                            <?php
                            $count = 0;
                            $total_images = $review_images['total_images'];
                            for ($i = 0; $i < count($review_images['product_rating']); $i++) {
                                if (!empty($review_images['product_rating'][$i]['images'])) {
                                    for ($j = 0; $j < count($review_images['product_rating'][$i]['images']); $j++) {
                                        if ($count <= 8) {
                                            if ($count == 8 && !empty($review_images['product_rating'][$i]['images'][$j])) { ?>
                                                <div class="col-sm-1">
                                                    <div class="review-box">
                                                        <a href="<?= $review_images['product_rating'][$i]['images'][$j] ?>">
                                                            <p class="limit_position"><?= "+" . ($total_images - 8) ?></p>
                                                            <img id="review-image-title" src="<?= $review_images['product_rating'][$i]['images'][$j] ?>" alt="Review Image" data-reached-end="false" data-review-limit="1" data-review-offset="0" data-product-id="<?= $review_images['product_rating'][$i]['product_id'] ?>" data-review-title="User Review Images(<span><?= $review_images['total_images'] ?></span>)" data-izimodal-open="#user-review-images" class="overlay">
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } else if (!empty($review_images['product_rating'][$i]['images'][$j])) { ?>
                                                <div class="col-sm-1">
                                                    <div class="review-box">
                                                        <a href="<?= $review_images['product_rating'][$i]['images'][$j] ?>" data-lightbox="users-review-images" data-title="<?= "<button class='label btn-success'>" . $review_images['product_rating'][$i]['rating'] . " <i class='fa fa-star'></i></button></br>" . $review_images['product_rating'][$i]['user_name'] . "<br>" . $review_images['product_rating'][$i]['comment'] ?> ">
                                                            <img src="<?= $review_images['product_rating'][$i]['images'][$j] ?>" alt="Review Images">
                                                        </a>
                                                    </div>
                                                </div>
                            <?php }
                                            $count += 1;
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-xl-7">
                            <h3 class="review-title"> <span id="no_ratings"><?= $product['product'][0]['no_of_ratings'] ?></span> Reviews For this Product</h3>
                            <ol class="review-list" id="review-list">
                                <?php if (isset($my_rating) && !empty($my_rating)) {
                                    foreach ($my_rating['product_rating'] as $row) { ?>
                                        <li class="review-container">
                                            <div class="review-image">
                                                <img src="<?= THEME_ASSETS_URL . 'images/user.png' ?>" alt="" width="65" height="65">
                                            </div>
                                            <div class="review-comment">
                                                <div class="rating-list">
                                                    <div class="product-rating">
                                                        <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="xs" title="" readonly>
                                                    </div>
                                                </div>
                                                <div class="review-info">
                                                    <h4 class="reviewer-name"><?= $row['user_name'] ?></h4>
                                                    <span class="review-date text-muted"><?= $row['data_added'] ?></span>
                                                </div>
                                                <div class="review-text">
                                                    <p class="text-muted"><?= $row['comment'] ?></p>
                                                    <a id="delete_rating" href="<?= base_url('products/delete-rating') ?>" class="text-danger" data-rating-id="<?= $row['id'] ?>">Delete</a>
                                                </div>
                                                <div class="row reviews">
                                                    <?php foreach ($row['images'] as $image) { ?>
                                                        <div class="col-sm-2">
                                                            <div class="review-box">
                                                                <a href="<?= $image ?>" data-lightbox="review-images">
                                                                    <img src="<?= $image ?>" alt="Review Image">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                <?php }
                                } ?>
                                <?php if (isset($product_ratings) && !empty($product_ratings)) {
                                    $user_id = (isset($user->id)) ? $user->id : '';
                                    foreach ($product_ratings['product_rating'] as $row) {

                                        if ($row['user_id'] != $user_id) { ?>

                                            <li class="review-container">
                                                <div class="review-image">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/user.png' ?>" alt="" width="65" height="65">
                                                </div>
                                                <div class="review-comment">
                                                    <div class="rating-list">
                                                        <div class="product-rating">
                                                            <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="xs" title="" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="review-info">
                                                        <h4 class="reviewer-name"><?= $row['user_name'] ?></h4>
                                                        <span class="review-date text-muted"><?= $row['data_added'] ?></span>
                                                    </div>
                                                    <div class="review-text">
                                                        <p class="text-muted"><?= $row['comment'] ?></p>
                                                    </div>
                                                    <div class="row reviews">
                                                        <?php foreach ($row['images'] as $image) { ?>
                                                            <div class="col-md-2">
                                                                <div class="review-box">
                                                                    <a href="<?= $image ?>" data-lightbox="review-images">
                                                                        <img src="<?= $image ?>" alt="Review Image">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </li>
                                <?php }
                                    }
                                } ?>
                            </ol>
                        </div>
                        <?php if ($product['product'][0]['is_purchased'] == 1) {
                            $form_link = (!empty($my_rating)) ? base_url('products/edit-rating') : base_url('products/save-rating');
                        ?>
                            <div class="col-xl-5 <?= (!empty($my_rating)) ? 'd-none' : '' ?>" id="rating-box">
                                <div class="add-review">
                                    <h3 class="review-title">Add Your Review</h3>
                                    <form action="<?= $form_link ?>" id="product-rating-form" method="POST">
                                        <?php if (!empty($my_rating)) { ?>
                                            <input type="hidden" name="rating_id" value="<?= $my_rating['product_rating'][0]['id'] ?>">
                                        <?php } ?>
                                        <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                        <div class="rating-form">
                                            <label for="rating">Your rating</label>
                                            <input type="text" class="kv-fa rating-loading" data-step="1" name="rating" value="<?= isset($my_rating['product_rating'][0]['rating']) ? $my_rating['product_rating'][0]['rating'] : '0' ?>" data-size="sm" title="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Your Review</label>
                                            <textarea class="form-control" name="comment" rows="3"><?= isset($my_rating['product_rating'][0]['comment']) ? $my_rating['product_rating'][0]['comment'] : '' ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Images</label>
                                            <input type="file" name="images[]" accept="image/x-png,image/gif,image/jpeg" multiple />
                                        </div>
                                        <button class="buttons extra-small primary-button text-center m-0" id="rating-submit-btn">Submit</button>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($product_ratings) && !empty($product_ratings)) { ?>
                            <div class="col-md-12">
                                <div class="text-center">
                                    <button class="buttons btn-6-6" id="load-user-ratings" data-product="<?= $product['product'][0]['id'] ?>" data-limit="<?= $user_rating_limit ?>" data-offset="<?= $user_rating_offset ?>">Load more</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php */ ?>
            
        </div>
    </div>
    <!-- /.card -->
    
    <hr />
    
    <?php if($related_products['product']) { ?> 
    <h3 class="h3"><?= !empty($this->lang->line('related_products')) ? $this->lang->line('related_products') : 'Related Products' ?> </h3>
    <!-- Default Style Design-->
    <div class="col-12 product-style-default pb-4 mt-2 mb-2">
        <div class="swiper-container product-image-swiper" data-swiper-options="{
            'spaceBetween': 0,
            'slidesPerView': 2,
            'breakpoints': {
                '576': {
                    'slidesPerView': 2
                },
                '768': {
                    'slidesPerView': 3
                },
                '992': {
                    'slidesPerView': 3
                },
                '1200': {
                    'slidesPerView': 4
                }
            }
        }">
            <div <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?> class="swiper-wrapper">
                <?php foreach ($related_products['product'] as $row) { ?>
                    <div class="swiper-slide">
                        <div class="product-wrap">
                            <div class="product text-center2">
                                <figure class="product-media justify-content-md-center2">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>" alt="" width="300" height="338"/>
                                    </a>
                                    <div class="product-action-vertical">
                                        <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                        <span class="add-favorite position-relative">
                                            <a href="#" class="btn-product-icon btn-wishlist w-icon-heart far fa-heart add-to-fav-btn <?php echo ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></a>
                                        </span>
                                        <?php } ?> 
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
                                            <?php echo $row['company_name']; ?>
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
                                    <div class="product-pa-wrapper2">
                                        <div class="product-price2 row mb-1">
                                            <?php 
                                            if($row['type']== "simple_product")
                                            {
                                                ?>
                                                <div class="product-price2 col col-sm-12">
                                                    <p class="mt-1 mb-1">
                                                        <ins class="new-price"><?= $settings['currency'] ?><?php echo $row['_special_price']; ?></ins>
                                                    </p>
                                                    <p class="mt-0 mb-0">
                                                        <del class="old-price"><?= $settings['currency'] ?><?php echo $row['_price']; ?></del>
                                                    </p>
                                                    <p class="mt-0 mb-0">
                                                        <del class="old-price"><?= $settings['currency'] ?><?php echo $row['_mrp']; ?></del>
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
                                                    echo $settings['currency'].''.$row['variants'][0]['special_price'].' <del class="old-price">'.$settings['currency'].''.$row['variants'][0]['price'].'</del>';
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
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                    <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                    <a class="add-to-cart add-to-cart-<?php echo $row['id']; ?> add_to_cart btn-block btn-sm text-center" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a><!--  data-izimodal-open="<?= $modal ?>" -->
                                    <?php } ?>
                                     
                                    <?php } else { ?> 
                                    <div class="product-pa-wrapper2 row">
                                        <h6 class="text-danger">Out of stock</h6>
                                    </div>
                                    <?php } ?>
                                    
                                    <?php /* ?>
                                    <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                    <?php */ ?>
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div class="product-grid">
                            <aside class="add-fav">
                                <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                            </aside>
                            <div class="product-image">
                                <div class="product-image-container">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                        <img class="pic-1" src="<?= $row['image_sm'] ?>">
                                    </a>
                                </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        if (count($row['variants']) <= 1) {
                                            $variant_id = $row['variants'][0]['id'];
                                            $modal = "";
                                        } else {
                                            $variant_id = "";
                                            $modal = "#quick-view";
                                        }
                                        ?>
                                        <a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">
                                            <i class="fa fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                </ul>
                                <?php if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                    <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                <?php } ?>
                            </div>
                            <div class="col-md-12 mb-3 product-rating-small">
                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                            </div>
                            <div class="product-content">
                                <h3 class="title" title="<?= $row['name'] ?>">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a>
                                </h3>
                                <div class="price">
                                    <?php $price = get_price_range_of_product($row['id']);
                                    echo $price['range'];
                                    ?>
                                </div>
                                <a href="" class="add-to-cart add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                            </div>
                        </div>
                        <?php */ ?>
                        
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-next product-image-swiper-next"></div>
        <div class="swiper-button-prev product-image-swiper-prev"></div>
    </div>
    <?php } ?>
    
    <hr />
    
    <?php if($seller_products['product']) { ?> 
    <h3 class="h3">Other products from same manufacturer</h3>
    <!-- Default Style Design-->
    <div class="col-12 product-style-default pb-4 mt-2 mb-2">
        <div class="swiper-container product-image-swiper" data-swiper-options="{
            'spaceBetween': 0,
            'slidesPerView': 2,
            'breakpoints': {
                '576': {
                    'slidesPerView': 2
                },
                '768': {
                    'slidesPerView': 3
                },
                '992': {
                    'slidesPerView': 3
                },
                '1200': {
                    'slidesPerView': 4
                }
            }
        }">
            <div <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?> class="swiper-wrapper">
                <?php foreach ($seller_products['product'] as $row) { ?>
                    <div class="swiper-slide">
                        <div class="product-wrap">
                            <div class="product text-center2">
                                <figure class="product-media justify-content-md-center2">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>" alt="" width="300" height="338"/>
                                    </a>
                                    <div class="product-action-vertical">
                                        <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                        <span class="add-favorite position-relative">
                                            <a href="#" class="btn-product-icon btn-wishlist w-icon-heart far fa-heart add-to-fav-btn <?php echo ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></a>
                                        </span>
                                        <?php } ?> 
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
                                            <?php echo $row['company_name']; ?>
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
                                    <div class="product-pa-wrapper2">
                                        <div class="product-price2 row mb-1">
                                            <?php 
                                            if($row['type']== "simple_product")
                                            {
                                                ?>
                                                <div class="product-price2 col col-sm-12">
                                                    <p class="mt-1 mb-1">
                                                        <ins class="new-price"><?= $settings['currency'] ?><?php echo $row['_special_price']; ?></ins>
                                                    </p>
                                                    <p class="mt-0 mb-0">
                                                        <del class="old-price"><?= $settings['currency'] ?><?php echo $row['_price']; ?></del>
                                                    </p>
                                                    <p class="mt-0 mb-0">
                                                        <del class="old-price"><?= $settings['currency'] ?><?php echo $row['_mrp']; ?></del>
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
                                                    echo $settings['currency'].''.$row['variants'][0]['special_price'].' <del class="old-price">'.$settings['currency'].''.$row['variants'][0]['price'].'</del>';
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
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                    <a class="add-to-cart add-to-cart-<?php echo $row['id']; ?> add_to_cart btn-block btn-sm text-center" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a><!--  data-izimodal-open="<?= $modal ?>" -->
                                    <?php } ?>
                                    <?php } else { ?> 
                                    <div class="product-pa-wrapper2 row">
                                        <h6 class="text-danger">Out of stock</h6>
                                    </div>
                                    <?php } ?>
                                    <?php /* ?>
                                    <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                    <?php */ ?>
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div class="product-grid">
                            <aside class="add-fav">
                                <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                            </aside>
                            <div class="product-image">
                                <div class="product-image-container">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                        <img class="pic-1" src="<?= $row['image_sm'] ?>">
                                    </a>
                                </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        if (count($row['variants']) <= 1) {
                                            $variant_id = $row['variants'][0]['id'];
                                            $modal = "";
                                        } else {
                                            $variant_id = "";
                                            $modal = "#quick-view";
                                        }
                                        ?>
                                        <a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">
                                            <i class="fa fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                </ul>
                                <?php if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                    <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                <?php } ?>
                            </div>
                            <div class="col-md-12 mb-3 product-rating-small">
                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                            </div>
                            <div class="product-content">
                                <h3 class="title" title="<?= $row['name'] ?>">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a>
                                </h3>
                                <div class="price">
                                    <?php $price = get_price_range_of_product($row['id']);
                                    echo $price['range'];
                                    ?>
                                </div>
                                <a href="" class="add-to-cart add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                            </div>
                        </div>
                        <?php */ ?>
                        
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-next product-image-swiper-next"></div>
        <div class="swiper-button-prev product-image-swiper-prev"></div>
    </div>
    <?php } ?>
    
</section>
<div id="user-review-images" class='product-page-content'>
    <div class="container" id="review-image-div">
        <?php
        if (isset($review_images['product_rating']) && !empty($review_images['product_rating'])) { ?>
            <div class="row reviews" id="user_image_data">

            </div>
            <div id="load_more_div">
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function (){
    $("input.in-num").change(function (){
        if($(this).val() > 0)
        {
            $(".selected_variation").click();
        }
        else
        {
            $(this).val(1);
            $(".selected_variation").click();
        }
    });
})
</script>