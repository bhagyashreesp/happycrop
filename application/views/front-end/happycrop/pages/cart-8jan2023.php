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
.widget-image {height: 55px;line-height: 90px;width: 55px;}
.cart-product-desc-list td, .table-cart-product thead th{font-size: 12px;color: #000 !important;}
.cart-product-desc-list td i{font-size: 12px;}
.num-in input{width: 52%;}
.num-in span{width: 24%;}
.total-price td {font-size: 15px;}
</style>
<!-- add to cart -->
<div class="wrapper">
    <div class="main-content" style="min-height: 350px;margin-bottom: 40px;">
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
                                                <?php */ ?>
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
    </div>
</div>