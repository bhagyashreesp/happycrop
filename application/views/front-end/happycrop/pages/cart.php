<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('cart')) ? $this->lang->line('cart') : 'Cart' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('cart')) ? $this->lang->line('cart') : 'Cart' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<style>
.text-primary{color: var(--primary-color) !important;}
.avt_img{max-height: 80px;}
.widget-image {height: 55px;line-height: 55px;width: 55px;}
.cart-product-desc-list td, .table-cart-product thead th{font-size: 12px;color: #000 !important;}
.cart-product-desc-list td i{font-size: 12px;}
.num-in input{width: 52%;}
.num-in span{width: 24%;}
.total-price td, .cart-product-summary td {font-size: 13px;}
.cart-product-summary{padding: 1.0rem 1.0rem 1.0rem;}
.shortfall-row{background: #FFD24B;color: #000;margin-bottom: 5px;}
.num-in{width: 80px;}
</style>
<!-- add to cart -->
<div class="wrapper">
    <div class="main-content" style="min-height: 350px;margin-bottom: 40px;">
    
        <?php
        if($cart['quantity'])
        {
            $product_var_ids = $cart['variant_id'];
            $this->db->distinct();
            $this->db->select('a.seller_id, d.company_name, d.slug, d.logo, d.min_order_value');
            $this->db->from('products as a');
            $this->db->join('product_variants as b','a.id = b.product_id');
            $this->db->join('users as c','c.id = a.seller_id');
            $this->db->join('seller_data as d','d.user_id = a.seller_id');
            $this->db->where_in('b.id', $product_var_ids);
            $query = $this->db->get();
            $seller_rows   = $query->result_array();
            
            $disabled = '';
            if($seller_rows)
            {
                foreach($seller_rows as $seller_row)
                {
                    ?>
                    <div id="seller_cart_container_<?php echo $seller_row['seller_id'];?>" class="seller_cart_container">
                        <div class="row">
                            <div class="col-md-7 col-7 mt-3 mb-1 bg-white">
                                <h4 class="h4 mfg-title"><a href="<?php echo base_url().'products?seller='.$seller_row['slug'];?>"><?php echo $seller_row['company_name'];?></a></h4>
                            </div>
                            <div class="col-md-2 col-5 mt-3 mb-1 bg-white">
                                <?php if(file_exists($seller_row['logo']) && $seller_row['logo']!='') { ?> 
                                <div class="logo">
                                    <a href="<?php echo base_url().'products?seller='.$seller_row['slug'];?>">
                                        <img class="avt_img float-right" src="<?php echo base_url().$seller_row['logo'];?>" alt="<?php echo $seller_row['company_name'];?>"/>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="cart-table-wrapper">
                                    <table class="table table-bordered table-responsive-sm table-cart-product">
                                        <thead>
                                            <tr>
                                                <th class="text-muted"><?= !empty($this->lang->line('image')) ? $this->lang->line('image') : 'Image' ?></th>
                                                <th width="25%" class="text-muted"><?= !empty($this->lang->line('product')) ? $this->lang->line('product') : 'Product' ?></th>
                                                <!--<th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Standard price per product (inc GST)' ?></th>
                                                <th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Discounted Price per product (inc GST)' ?></th>
                                                <th class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?>(%)</th>
                                                <th class="text-muted"><?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?></th>-->
                                                <th width="10%" class="text-muted text-center">Size</th>
                                                <!--<th width="10%" class="text-muted text-center">Min. Order Qty</th>-->
                                                <th width="12%" class="text-muted text-center">Std Price</th>
                                                <th width="12%" class="text-muted text-center">Disc. Price</th>
                                                <th width="10%" class="text-muted text-center">GST %</th>
                                                <th width="8%" class="text-muted text-center">Order Size</th>
                                                <th class="text-muted"><?= !empty($this->lang->line('volume')) ? $this->lang->line('volume') : 'Volume' ?></th>
                                                <th class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></th>
                                                <th class="text-muted">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total_mrp = $special_total_price = $total_price = 0;
                                            
                                            
                                            if($cart['quantity'])
                                            {
                                                foreach ($cart as $key => $row) 
                                                {
                                                    if (isset($row['qty']) && $row['qty'] != 0 && $seller_row['seller_id'] == $row['seller_id']) 
                                                    {
                                                        $price = $row['price'];
                                                        $special_price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price'];
                                                        $mrp   = $row['mrp'];
                                                        $standard_price   = $row['standard_price'];
                                                        
                                                        $total_price += $price * $row['qty'];
                                                        $special_total_price += $special_price * $row['qty']; 
                                                        ?>
                                                        <tr class="cart-product-desc-list">
                                                            <td>
                                                                <div class="widget-image">
                                                                    <a href="<?=base_url('products/details/'.$row['slug'])?>" target="_blank">
                                                                    <img src="<?= $row['image'] ?>" alt="<?= $row['name']; ?>"></a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="id">
                                                                    <input type="hidden" name="<?= 'id[' . $key . ']' ?>" id="id" value="<?= $row['id'] ?>">
                                                                </div>
                                                                <h2 class="cart-product-title">
                                                                    <a href="<?=base_url('products/details/'.$row['slug'])?>" target="_blank"><?= $row['name']; ?></a>
                                                                    <?php if (!empty($row['product_variants'])) { ?>
                                                                        <br><?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                                    <?php } ?>
                                                                </h2>
                                                                <?php /* ?>
                                                                <button class="btn save-for-later remove-product button button-primary button-sm" data-id="<?= $row['id']; ?>"><?= !empty($this->lang->line('save_for_later')) ? $this->lang->line('save_for_later') : 'Save For Later' ?></button>
                                                                <?php */ ?>
                                                            </td>
                                                            <td class="text-muted p-0 text-center">
                                                                <?php 
                                                                echo $row['packing_size'].' '.$row['unit']; 
                                                                echo ($row['carton_qty'] > 1) ? ' &#x2715; '.$row['carton_qty'] : ' &#x2715; 1';
                                                                ?>
                                                            </td>
                                                            <!--<td class="text-muted p-0 text-center"><?php //echo $row['minimum_order_quantity']; ?></td>-->
                                                            <td class="text-muted text-center p-0"><?= $settings['currency'] . ' ' . number_format($price, 2) ?></td>
                                                            <td class="text-muted text-center p-0"><?= $settings['currency'] . ' ' . number_format($special_price, 2) ?></td>
                                                            <td class="text-muted text-center p-0"><?= isset($row['tax_percentage']) && !empty($row['tax_percentage']) ? $row['tax_percentage'] : '-' ?></td>
                                                            <td class="item-quantity">
                                                                <div class="num-block skin-2 product-quantity">
                                                                    <div class="num-in">
                                                                        <?php $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price']; ?>
                                                                        <span class="minus dis" data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>" data-seller-id="<?php echo $row['seller_id']; ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"></span>
                                                                        <input type="text" class="in-num itemQty itemQty<?php echo $row['seller_id']; ?>" data-page="cart" data-id="<?= $row['id']; ?>" value="<?= $row['qty'] ?>" data-seller-id="<?php echo $row['seller_id']; ?>" data-std-price-<?php echo $row['seller_id']; ?>="<?php echo $row['price']; ?>" data-price-<?php echo $row['seller_id']; ?>="<?= $price ?>" data-mrp-<?php echo $row['seller_id']; ?>="<?php echo $mrp; ?>" data-std-price="<?php echo $row['price']; ?>" data-price="<?= $price ?>" data-mrp="<?php echo $mrp; ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>" data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '' ?>">
                                                                        <span class="plus" data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '0' ?> " data-seller-id="<?php echo $row['seller_id']; ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-muted p-0 text-center">
                                                                <span class="product-volume-<?php echo $row['id']; ?>">
                                                                <?php
                                                                if($row['attribute_value_ids'])
                                                                {
                                                                    $attribute_value_ids = explode(',', str_replace(' ','', $row['attribute_value_ids']));
                                                                    $this->db->select('a.volume, a.unit_id, u.unit');
                                                                    $this->db->from('attribute_values as a');
                                                                    $this->db->join('units as u','a.unit_id = u.id');
                                                                    $this->db->where_in('a.id', $attribute_value_ids);
                                                                    $query = $this->db->get();
                                                                    $at_value = $query->row_array();
                                                                    
                                                                    echo ($at_value['volume']*$row['qty']).' '.$at_value['unit'];
                                                                }
                                                                else
                                                                {
                                                                    echo ($row['total_weight']*$row['qty']).' '.$row['unit'];
                                                                }
                                                                ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-muted p-0 text-center total-price"><span class="product-line-price"> <?= $settings['currency'] . ' ' . number_format(($row['qty'] * $price), 2) ?></span></td>
                                                            <td>
                                                                <div class="product-removal">
                                                                    <button type="button" class="button button-sm button-danger text-white" name="remove_inventory" id="remove_inventory" data-id="<?= $row['id']; ?>" data-seller-id="<?php echo $row['seller_id']; ?>" title="Remove From Cart">
                                                                        <!--<i class="remove-product fas fa-trash-alt text-danger2 "></i>-->
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                }
                                            }
                                            else
                                            {
                                                ?>
                                                <tr>
                                                    <td class="text-muted text-center" colspan="11">Cart is Empty</td>
                                                </tr>
                                                <?php   
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-3 mt-0">
                                <div class="cart-product-summary">
                                    <div class="row">
                                        <div class="col-md-5 col-4">
                                            <h3 class="h4">Cart Total </h3>
                                        </div>
                                        <div class="col-md-7 col-8">
                                            <h4 class="h5 text-right text-primary">Min. order value<br /> <?php echo $settings['currency'] . '' . number_format($seller_row['min_order_value'],2);?> </h4>
                                        </div>
                                    </div>
                                    <div class="cart-total-price">
                                        <?php 
                                        $total_price = $total_price;//!empty($cart['total_price']) ? number_format($cart['total_price'], 2) : 0;
                                        $total = number_format($special_total_price, 2);
                                        
                                        $profit_gain = $total_price - $special_total_price;
                                        $profit_gain = !empty($profit_gain) ? number_format($profit_gain, 2) : 0;
                                        
                                        $profit_gain_percent = ($total_price - $special_total_price) * 100/$total_price;
                                        $profit_gain_percent = !empty($profit_gain_percent) ? number_format($profit_gain_percent, 2).'%' : 0;
                                        
                                        $shortfall = $seller_row['min_order_value'] - $special_total_price;
                                        
                                        ?>
                                        <table class="table cart-products-table mb-1">
                                            <tfoot>
                                                <tr class="total-mrp-price">
                                                    <td >Std Total Price <br />(inc GST)</td>
                                                    <td class="t_mrp_<?php echo $seller_row['seller_id'];?>"><?= $settings['currency'] . '' . $total_price ?></td>
                                                </tr>
                                                <tr class="total-mrp-profit">
                                                    <td>Profit Gain</td>
                                                    <td class="t_gain_<?php echo $seller_row['seller_id'];?>"><?= $settings['currency'] . '' . $profit_gain ?></td>
                                                </tr>
                                                <tr class="total-mrp-profit">
                                                    <td>% Profit Gain</td>
                                                    <td class="t_gain_percent_<?php echo $seller_row['seller_id'];?>"><?= $profit_gain_percent ?></td>
                                                </tr>
                                                <tr class="total-price">
                                                    <td>Total Amount <br />(inc GST) </td>
                                                    <td class="t_amt_<?php echo $seller_row['seller_id'];?>"><?= $settings['currency'] . '' . $total ?></td>
                                                </tr>
                                                <?php
                                                if($shortfall > 0)
                                                {
                                                    ?>
                                                    <tr class="total-shortfall-<?php echo $seller_row['seller_id'];?> shortfall-row">
                                                        <td class="t_shortfall_<?php echo $seller_row['seller_id'];?>">You are shortfall by</td>
                                                        <td class="shortfall t_shortfall_amt_<?php echo $seller_row['seller_id'];?>"><?= $settings['currency'] . '' . ($shortfall) ?></td>
                                                    </tr>
                                                    <?php
                                                    $disabled = 'disabled="true"';
                                                }
                                                else
                                                {
                                                    ?>
                                                    <tr class="total-shortfall-<?php echo $seller_row['seller_id'];?>">
                                                        <td class="t_shortfall_<?php echo $seller_row['seller_id'];?>"></td>
                                                        <td class="shortfall t_shortfall_amt_<?php echo $seller_row['seller_id'];?>"></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tfoot>
                                        </table>
                                        <p class="mfg-shortfall-msg-<?php echo $seller_row['seller_id'];?> p-3 shortfall-row" <?php echo ($shortfall > 0) ? '' : 'style="display:none;"';?>>Add more products from same manufacturer - <?php echo $seller_row['company_name'];?></p>
                                        <input type="hidden" id="min_order_value_<?php echo $seller_row['seller_id'];?>" name="min_order_value_<?php echo $seller_row['seller_id'];?>" value="<?php echo $seller_row['min_order_value'];?>" />
                                        <input type="hidden" id="is_service_category" name="is_service_category" value="<?php echo $cart[0]['is_service_category']; ?>" />
                                    </div>
                                    <?php /* ?>
                                    <?php $disabled = empty($cart['sub_total']) ? 'disabled' : '' ?>
                                    <div class="checkout-method">
                                        <a href="<?= base_url('cart/checkout') ?>" id="checkout"> <button class="block" <?= $disabled ?>><?= !empty($this->lang->line('go_to_checkout')) ? $this->lang->line('go_to_checkout') : 'Go To Checkout' ?></button></a>
                                    </div>
                                    <?php */ ?>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php
                }
            }
            ?>
            <div class="row">
                <div class="col-md-12">
                    
                    <p class="t_shortfall_msg p-2 text-danger mb-2" <?php if($disabled != 'disabled="true"') { ?> style="display: none;" <?php } ?>>* You are short falling by some amount, please check cart total</p>
                    
                    <div class="checkout-method">
                        <a href="<?= base_url('cart/checkout') ?>" id="checkout"> 
                            <button class="block checkout-proceed" <?= $disabled ?>><?= !empty($this->lang->line('go_to_checkout')) ? $this->lang->line('go_to_checkout') : 'Go To Checkout' ?></button>
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="alert alert-warning mt-5">
                Cart is empty.
            </div>
            <?php
        }
        ?>
        
        
        <?php /* ?>
        <div class="row">
            <div class="<?php echo ($cart['quantity']) ? 'col-md-9' : 'col-md-12';?> mt-5 bg-white">
                <div class="cart-table-wrapper">
                    <?php if($cart['quantity']) {  ?> 
                    <div class="text-right">
                        <button name="clear_cart" id="clear_cart" class="button button-danger mt-3 mb-4">Clear Cart</button>
                    </div>
                    <?php } ?>
                    <table class="table table-responsive-sm table-cart-product">
                        <thead>
                            <tr>
                                <th class="text-muted"><?= !empty($this->lang->line('image')) ? $this->lang->line('image') : 'Image' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('product')) ? $this->lang->line('product') : 'Product' ?></th>
                                <!--<th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Standard price per product (inc GST)' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Discounted Price per product (inc GST)' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?>(%)</th>
                                <th class="text-muted"><?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?></th>-->
                                <th width="10%" class="text-muted text-center">Carton Size</th>
                                <th width="10%" class="text-muted text-center">Min. Order Qty</th>
                                <th width="12%" class="text-muted text-center">Standard price per product <br />(inc GST)</th>
                                <th width="12%" class="text-muted text-center">Discounted Price per product <br />(inc GST)</th>
                                <th width="10%" class="text-muted text-center">GST %</th>
                                <th width="10%" class="text-muted text-center">Order Size</th>
                                <th class="text-muted"><?= !empty($this->lang->line('volume')) ? $this->lang->line('volume') : 'Volume' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></th>
                                <th class="text-muted"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_mrp = $special_total_price = $total_price = 0;
                            
                            
                            if($cart['quantity'])
                            {
                                foreach ($cart as $key => $row) {
                                    if (isset($row['qty']) && $row['qty'] != 0) {
                                        $price = $row['price'];
                                        $special_price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price'];
                                        $mrp   = $row['mrp'];
                                        
                                        $total_price += $price * $row['qty'];
                                        $special_total_price += $special_price * $row['qty']; 
                                ?>
                                        <tr class="cart-product-desc-list">
                                            <td>
                                                <div class="widget-image">
                                                    <a href="<?=base_url('products/details/'.$row['slug'])?>" target="_blank">
                                                    <img src="<?= $row['image'] ?>" alt="<?= $row['name']; ?>"></a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="id">
                                                    <input type="hidden" name="<?= 'id[' . $key . ']' ?>" id="id" value="<?= $row['id'] ?>">
                                                </div>
                                                <h2 class="cart-product-title">
                                                    <a href="<?=base_url('products/details/'.$row['slug'])?>" target="_blank"><?= $row['name']; ?></a>
                                                    <?php if (!empty($row['product_variants'])) { ?>
                                                        <br><?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                    <?php } ?>
                                                </h2>
                                                <?php /* ?>
                                                <button class="btn save-for-later remove-product button button-primary button-sm" data-id="<?= $row['id']; ?>"><?= !empty($this->lang->line('save_for_later')) ? $this->lang->line('save_for_later') : 'Save For Later' ?></button>
                                                <?php *//* ?>
                                            </td>
                                            <td class="text-muted p-0 text-center">
                                                <?php 
                                                echo $row['packing_size'].' '.$row['unit']; 
                                                echo ($row['carton_qty'] > 1) ? ' &#x2715; '.$row['carton_qty'] : '';
                                                ?>
                                            </td>
                                            <td class="text-muted p-0 text-center"><?php echo $row['minimum_order_quantity']; ?></td>
                                            <td class="text-muted p-0"><?= $settings['currency'] . ' ' . number_format($price, 2) ?></td>
                                            <td class="text-muted p-0"><?= $settings['currency'] . ' ' . number_format($special_price, 2) ?></td>
                                            <td class="text-muted text-center p-0"><?= isset($row['tax_percentage']) && !empty($row['tax_percentage']) ? $row['tax_percentage'] : '-' ?></td>
                                            <td class="item-quantity">
                                                <div class="num-block skin-2 product-quantity">
                                                    <div class="num-in">
                                                        <?php $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price']; ?>
                                                        <span class="minus dis" data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"></span>
                                                        <input type="text" class="in-num itemQty" data-page="cart" data-id="<?= $row['id']; ?>" value="<?= $row['qty'] ?>" data-std-price="<?php echo $row['price']; ?>" data-price="<?= $price ?>" data-mrp="<?php echo $mrp; ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>" data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '' ?>">
                                                        <span class="plus" data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '0' ?> " data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted p-0 text-center">
                                                <span class="product-volume-<?php echo $row['id']; ?>">
                                                <?php
                                                if($row['attribute_value_ids'])
                                                {
                                                    $attribute_value_ids = explode(',', str_replace(' ','', $row['attribute_value_ids']));
                                                    $this->db->select('a.volume, a.unit_id, u.unit');
                                                    $this->db->from('attribute_values as a');
                                                    $this->db->join('units as u','a.unit_id = u.id');
                                                    $this->db->where_in('a.id', $attribute_value_ids);
                                                    $query = $this->db->get();
                                                    $at_value = $query->row_array();
                                                    
                                                    echo ($at_value['volume']*$row['qty']).' '.$at_value['unit'];
                                                }
                                                else
                                                {
                                                    echo ($row['total_weight']*$row['qty']).' '.$row['unit'];
                                                }
                                                ?>
                                                </span>
                                            </td>
                                            <td class="text-muted p-0 total-price"><span class="product-line-price"> <?= $settings['currency'] . ' ' . number_format(($row['qty'] * $price), 2) ?></span></td>
                                            <td>
                                                <div class="product-removal">
                                                    <button type="button" class="button button-sm button-danger text-white" name="remove_inventory" id="remove_inventory" data-id="<?= $row['id']; ?>" title="Remove From Cart">
                                                        <!--<i class="remove-product fas fa-trash-alt text-danger2 "></i>-->
                                                        Remove
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                }
                            }
                            else
                            {
                                ?>
                                <tr>
                                    <td class="text-muted text-center" colspan="11">Cart is Empty</td>
                                </tr>
                                <?php   
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($save_for_later['variant_id'])) { ?>
                    <div class="cart-table-wrapper">
                        <table class="table table-responsive-sm table-cart-product">
                            <h4 class="h4"><?= !empty($this->lang->line('save_for_later')) ? $this->lang->line('save_for_later') : 'Save For Later' ?></h1>
                                <thead>
                                    <tr>
                                        <th class="text-muted"><?= !empty($this->lang->line('image')) ? $this->lang->line('image') : 'Image' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('product')) ? $this->lang->line('product') : 'Product' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Price' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?>(%)</th>
                                        <th class="text-muted"><?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></th>
                                        <th class="text-muted"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($save_for_later as $key => $row) {
                                        if (isset($row['qty']) && $row['qty'] != 0) {
                                            $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 && $row['special_price'] < $row['price'] ? $row['special_price'] : $row['price'];
                                    ?>
                                            <tr class="cart-product-desc-list">
                                                <td>
                                                    <div class="cart-product-image">
                                                        <a href="<?=base_url('products/details/'.$row['slug'])?>" target="_blank">
                                                            <img src="<?= $row['image'] ?>" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="id">
                                                        <input type="hidden" name="<?= 'id[' . $key . ']' ?>" id="id" value="<?= $row['id'] ?>">
                                                    </div>
                                                    <h2 class="cart-product-title">
                                                        <a href="<?=base_url('products/details/'.$row['slug'])?>" target="_blank"><?= $row['name']; ?></a>
                                                        <?php if (!empty($row['product_variants'])) { ?>
                                                            <br><?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                        <?php } ?>
                                                        <br><button class="btn remove-product button button-warning move-to-cart button-sm" data-id="<?= $row['id']; ?>"><?= !empty($this->lang->line('move_to_cart')) ? $this->lang->line('move_to_cart') : 'Move to cart' ?></button>
                                                    </h2>
                                                </td>
                                                <td class="text-muted p-0"><?= $settings['currency'] . ' ' . number_format($price, 2) ?></td>
                                                <td class="text-muted p-0 text-center"><?= isset($row['tax_percentage']) && !empty($row['tax_percentage']) ? $row['tax_percentage'] : '-' ?></td>
                                                <td class="itemQty">
                                                    <?= $row['qty'] ?>
                                                </td>
                                                <td class="text-muted p-0"><?= $settings['currency'] . ' ' . number_format($price, 2) ?></td>
                                                <td>

                                                    <div class="product-removal">
                                                        <i class="remove-product fas fa-trash-alt text-danger" name="remove_inventory" id="remove_inventory" data-id="<?= $row['id']; ?>" data-is-save-for-later="1"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <?php
            if($cart['quantity'])
            {
                ?>
                <div class="col-md-3 mt-5">
                    <div class="cart-product-summary">
                        <h3>Cart Total</h3>
                        <div class="cart-total-price">
                            <table class="table cart-products-table">
                                <tbody>
                                    <tr class="d-none">
                                        <td class="text-muted">Standard Total price(inc GST)</td>
                                        <td class="text-muted"><?= $settings['currency'] . ' ' . number_format($total_price, 2) ?></td>
                                    </tr>
                                    <tr class="d-none">
                                        <td class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></td>
                                        <td class="text-muted"><?= $settings['currency'] . ' ' . number_format($cart['sub_total'], 2) ?></td>
                                    </tr>
                                    <?php if (!empty($cart['tax_percentage'])) { ?>
                                        <tr class="cart-product-tax d-none">
                                            <td class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?> (<?= $cart['tax_percentage'] ?>%)</td>
                                            <td class="text-muted"><?= $settings['currency'] . ' ' . number_format($cart['tax_amount'], 2) ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php $delivery_charge = !empty($cart['sub_total']) ? number_format($cart['delivery_charge'], 2) : 0 ?>
                                    <tr class="d-none">
                                        <td class="text-muted"><?= !empty($this->lang->line('delivery_charge')) ? $this->lang->line('delivery_charge') : 'Delivery Charge' ?></td>
                                        <td class="text-muted"><?= $settings['currency'] . ' ' . $delivery_charge ?></td>
                                    </tr>
                                </tbody>
                                <tfoot>
    
                                    <?php 
                                    $total_price = $total_price;//!empty($cart['total_price']) ? number_format($cart['total_price'], 2) : 0;
                                    $total = !empty($cart['sub_total']) ? number_format($cart['overall_amount'] - $cart['delivery_charge'], 2) : 0;
                                    
                                    $profit_gain = $total_price - $cart['sub_total'];
                                    $profit_gain = !empty($profit_gain) ? number_format($profit_gain, 2) : 0;
                                    
                                    $profit_gain_percent = ($total_price - $cart['sub_total']) * 100/$total_price;
                                    $profit_gain_percent = !empty($profit_gain_percent) ? number_format($profit_gain_percent, 2).'%' : 0;
                                    
                                    ?>
                                    <tr class="total-mrp-price">
                                        <td>Standard Total price(inc GST)</td>
                                        <td class="t_mrp"><?= $settings['currency'] . ' ' . $total_price ?></td>
                                    </tr>
                                    <tr class="total-mrp-profit">
                                        <td>Profit Gain</td>
                                        <td class="t_gain"><?= $settings['currency'] . ' ' . $profit_gain ?></td>
                                    </tr>
                                    <tr class="total-mrp-profit">
                                        <td>% Profit Gain</td>
                                        <td class="t_gain_percent"><?= $profit_gain_percent ?></td>
                                    </tr>
                                    <tr class="total-price">
                                        <td>Total Amount (inc GST) </td>
                                        <td class="t_amt"><?= $settings['currency'] . ' ' . $total ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php $disabled = empty($cart['sub_total']) ? 'disabled' : '' ?>
                        <div class="checkout-method">
                            <a href="<?= base_url('cart/checkout') ?>" id="checkout"> <button class="block" <?= $disabled ?>><?= !empty($this->lang->line('go_to_checkout')) ? $this->lang->line('go_to_checkout') : 'Go To Checkout' ?></button></a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php */ ?>
        
    </div>
</div>