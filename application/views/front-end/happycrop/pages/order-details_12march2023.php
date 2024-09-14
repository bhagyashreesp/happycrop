<!-- Demo header-->
<style>.shadow{box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;}.btn.btn-sm{text-transform: capitalize;}</style>
<section class="header settings-tab">
    <div class="container py-4">
        <div class="row w-100">
            <div class="col-md-12 orders-section settings-tab-content">
                <div class="mb-4 card border-0">
                    <div class="card-header bg-white">
                        <div class="row justify-content-between">
                            <?php /* ?>
                            <div class="col">
                                <p class="text-muted"> <?= !empty($this->lang->line('order_id')) ? $this->lang->line('order_id') : 'Order ID' ?><span class="font-weight-bold text-dark"> : <?= $order['id'] ?></span></p>
                                <p class="text-muted"> <?= !empty($this->lang->line('place_on')) ? $this->lang->line('place_on') : 'Place On' ?><span class="font-weight-bold text-dark"> : <?= $order['date_added'] ?></span> </p>
                                <?php if ($order['otp'] != 0) { ?>
                                    <p class="text-muted"> <?= !empty($this->lang->line('otp')) ? $this->lang->line('otp') : 'OTP' ?> <span class="font-weight-bold text-dark"> : <?= $order['otp'] ?></span> </p>
                                <?php } ?>
                            </div>
                            <?php */ ?>

                            <div class="flex-col my-auto">
                                <div class="col-md-12">
                                    <h6 class="ml-auto mr-3">
                                        <!--<a target="_blank" href="<?= base_url('my-account/order-invoice/' . $order['id']) ?>" class='button button-primary-outline '><?= !empty($this->lang->line('invoice')) ? $this->lang->line('invoice') : 'Invoice' ?></a>-->
                                        <a href="<?= base_url('my-account/orders/') ?>" class='button button-danger-outline '><?= !empty($this->lang->line('back_to_orders')) ? $this->lang->line('back_to_orders') : 'Back to Orders' ?></a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <?php if ($order['payment_method'] == "Bank Transfer") { ?>
                            <div class="row">
                                <form class="form-horizontal " id="send_bank_receipt_form" action="<?= base_url('cart/send-bank-receipt'); ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <div class="form-group ">
                                        <label for="receipt"> <strong> Bank Payment Receipt</strong> </label>
                                        <input type="file" class="form-control" name="attachments[]" id="receipt" multiple />
                                    </div>
                                    <div class="form-group">
                                        <button type="reset" class="button button-warning-outline">Reset</button>
                                        <button type="submit" class="button button-success-outline" id="submit_btn">Send</button>
                                    </div>
                                </form>

                            </div>
                        <?php } ?>
                        <div class="row">
                            <?php if (!empty($bank_transfer)) { ?>
                                <div class="col-md-6">
                                    <?php $i = 1;
                                    foreach ($bank_transfer as $row1) { ?>
                                        <small>[<a href="<?= base_url() . $row1['attachments'] ?>" target="_blank">Attachment <?= $i ?> </a>]</small>
                                    <?php $i++;
                                    }
                                    if ($bank_transfer[0]['status'] == 0) { ?>
                                        <label class="badge badge-warning">Pending</label>
                                    <?php } else if ($bank_transfer[0]['status'] == 1) { ?>
                                        <label class="badge badge-danger">Rejected</label>
                                    <?php } else if ($bank_transfer[0]['status'] == 2) { ?>
                                        <label class="badge badge-primary">Accepted</label>
                                    <?php } else { ?>
                                        <label class="badge badge-danger">Invalid Value</label>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <h4>Order ID: <?php echo 'HC-A'.$order['id']; ?></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="shadow p-3 mb-5 bg-white rounded">
                                    <h6 class="h5"><?= !empty($this->lang->line('billing_details')) ? $this->lang->line('billing_details') : 'Billing Details' ?></h6>
                                    <hr/>
                                    <span>Retailer Name- <?= $order['retailer_company_name'] ?></span> <br/>
                                    <span>Address- <?= $order['billing_address'] ?></span> <br/>
                                    <span>GST No.- <?= $order['retailer_gst_no'] ?></span> <br/>
                                    <span>Contact No.- <?= $order['mobile'] ?></span> <br/>
                                    <!--<span><?= $order['delivery_time'] ?></span> <br/>
                                    <span><?= $order['delivery_date'] ?></span> <br/>-->
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="shadow p-3 mb-5 bg-white rounded">
                                    <h6 class="h5"><?= !empty($this->lang->line('shipping_details')) ? $this->lang->line('shipping_details') : 'Shipping Details' ?></h6>
                                    <hr/>
                                    <span>Retailer Name- <?= $order['retailer_company_name'] ?></span> <br/>
                                    <span>Address- <?= $order['address'] ?></span> <br/>
                                    <span>Contact No.- <?= $order['mobile'] ?></span> <br/>
                                    <!--<span><?= $order['delivery_time'] ?></span> <br/>
                                    <span><?= $order['delivery_date'] ?></span> <br/>-->
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        $this->db->distinct();
                        $this->db->select('a.seller_id, b.username, b.mobile, b.email, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
                        $this->db->from('order_items as a');
                        $this->db->join('users as b','a.seller_id = b.id','left');
                        $this->db->join('seller_data as c','a.seller_id = c.user_id','left');
                        $this->db->join('states as s','c.state_id = s.id','left');
                        $this->db->join('cities as cc','c.city_id = cc.id','left');
                        $this->db->where('a.order_id', $order['id']);
                        $query = $this->db->get();
                        
                        $manufactures = $query->result_array();
                        
                        if($manufactures)
                        {
                            foreach($manufactures as $manufacture)
                            {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="shadow p-3 mb-5 bg-white rounded">
                                            <h6 class="h5">Manufacturer Details</h6>
                                            <hr/>
                                            <div class="row mb-2">
                                                <div class="col-md-4">
                                                    <?php /* ?>
                                                    <h6 class="mb-1">Personal Details</h6>
                                                    <!--<span>Name: <?= $manufacture['username'] ?></span> <br/>-->
                                                    <?php */ ?>
                                                    <span class="font-weight-bold">Company Name: <?= $manufacture['company_name'] ?></span> <br/>
                                                    <?php /* ?>
                                                    <!--<span class="font-weight-bold">Email: <?= $manufacture['email'] ?></span><br/>-->
                                                    <?php */ ?>
                                                    <span class="font-weight-bold">Address: <?= $manufacture['plot_no'].' '.$manufacture['street_locality'].' '.$manufacture['landmark'].' '.$manufacture['city'].' '.$manufacture['state'].' '.$manufacture['pin'] ?></span><br/>
                                                    <span class="font-weight-bold">GST No.: <?= $manufacture['gst_no'] ?></span> <br/>
                                                    <?php /* ?>
                                                    <span class="font-weight-bold">Contact No.: <?= $manufacture['mobile'] ?></span> <br/>
                                                    <?php */ ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="mb-1">License Details</h6>
                                                    <span>Fertilizer License No: <?= $manufacture['fertilizer_license_no'] ?></span> <br/>
                                                    <span>Pesticides License No: <?= $manufacture['pesticide_license_no'] ?></span> <br/>
                                                    <span>Seeds License No: <?= $manufacture['seeds_license_no'] ?></span> <br/>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="mb-1">Bank Details</h6>
                                                    <span>Acct Name: <?= $manufacture['company_name'] ?></span> <br/>
                                                    <span>Acct No: <?= $manufacture['account_number'] ?></span> <br/>
                                                    <span>Bank Name: <?= $manufacture['bank_name'] ?></span> <br/>
                                                    <span>Bank IFSC: <?= $manufacture['bank_code'] ?></span> <br/>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr class="bg-primary text-white">
                                                        <th>No.</th>
                                                        <!--<th>Order ID</th>
                                                        <th>Manufacture Name</th>-->
                                                        <th width="25%">Product</th>
                                                        <th width="12%">Size</th>
                                                        <th>Order Size</th>
                                                        <th>Order Volume</th>
                                                        <th>GST %</th>
                                                        <th class="text-center">MRP</th>
                                                        <th class="text-center">Standard price per product (inc GST)</th>
                                                        <th class="text-center">Discounted Price per product (inc GST)</th>
                                                        <th class="text-center">Profit Gain per product</th>
                                                        <th class="text-center">Sub Total</th>
                                                        
                                                        <!--<th>HSN Code</th>
                                                        <th>License</th>-->
                                                        <?php /* ?>
                                                        <th>Date</th>
                                                        <th style="width: 20%;">Status</th>
                                                        <th></th>
                                                        <?php */ ?>
                                                    </tr>
                                                    <?php 
                                                    $i = 1;
                                                    $total_amt = $total_mrp = $total_std = 0; 
                                                    
                                                    foreach ($order['order_items'] as $key => $item) 
                                                    { 
                                                        if($manufacture['seller_id'] == $item['seller_id'])
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <!--<td><?php echo $order['id'] ?></td>
                                                                <td><?php echo $item['company_name']; ?></td>-->
                                                                <td><a href="<?=base_url('products/details/'.$item['slug'])?>" target="_blank"><?php echo $item['name']; ?></a></td>
                                                                <td class="text-center"><?php echo $item['packing_size'].' '.$item['unit']; echo ($item['carton_qty'] > 1) ? ' &#x2715; '.$item['carton_qty'] : ' &#x2715; 1'; ?></td>
                                                                <td class="text-center"><?php echo $item['quantity'] ?></td>
                                                                <td class="text-center"><?php echo ($item['packing_size']*$item['carton_qty']*$item['quantity']).' '.$item['unit']; ?></td>
                                                                <td class="text-center"><?= isset($item['tax_percentage']) && !empty($item['tax_percentage']) ? $item['tax_percentage'].' %' : '-' ?></td>
                                                                <td class="text-right"><?php echo number_format(($item['mrp']), 2) ?></td>
                                                                <td class="text-right"><?php echo number_format(($item['standard_price']), 2) ?></td>
                                                                <td class="text-right"><?php echo number_format(($item['price']), 2) ?></td>
                                                                <td class="text-right"><?php echo number_format(($item['standard_price'] - $item['price']), 2) ?></td>
                                                                
                                                                <td width="15%" class="text-right"><?php echo number_format(($item['price'] * $item['quantity']), 2) ?></td>
                                                                
                                                                <!--<td></td>-->
                                                                <?php /* ?>
                                                                <!--<td></td>-->
                                                                <td><?php echo date('d/m/Y g:i A', strtotime($order['date_added'])); ?></td>
                                                                <td class="text-center">
                                                                    <div id="order_state_<?php echo $item['id']; ?>">
                                                                    <?php 
                                                                    /*if($item['active_status']=='qty_update')
                                                                    {
                                                                        //echo $item['active_status']; 
                                                                        ?>
                                                                        <div class="mb-1">Qty Update</div>
                                                                        <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="approveQty(<?php echo $item['id'] ?>,<?php echo $order['id'] ?>);">Approve</a>
                                                                        <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="rejectQty(<?php echo $item['id'] ?>,<?php echo $order['id'] ?>);">Reject</a>
                                                                        <?php  
                                                                    }
                                                                    else if($item['active_status']=='payment_demand')
                                                                    {
                                                                        echo "Payment Demand";   
                                                                        ?>
                                                                        <div class="row">
                                                                            <form class="form-horizontal " id="send_bank_receipt_form" action="<?= base_url('my-account/send-payment-receipt'); ?>" method="POST" enctype="multipart/form-data">
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6">
                                                                                        <label class="btn btn-warning btn-sm btn-block" for="receipt">Browse</label>
                                                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                                                            <input type="file" class="form-control" name="attachments[]" id="receipt" style="padding:0px;min-height: 28px;"  multiple />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">
                                                                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                                                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Send</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                            
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    else if($item['active_status'] == 'schedule_delivery')
                                                                    {
                                                                        if($item['schedule_delivery_date']==null || $item['schedule_delivery_date']=='0000-00-00')
                                                                        {
                                                                            echo "Delivery scheduling, please be patience";
                                                                        }
                                                                        else
                                                                        {
                                                                            echo "Delivery Scheduled ON ".date('d/m/Y',strtotime($item['schedule_delivery_date']));
                                                                        }
                                                                    }
                                                                    else if($item['active_status'] == 'shipped')
                                                                    {
                                                                        echo $item['active_status'].'<br/>';
                                                                        $order_item_invoices = $this->db->get_where('order_item_invoice', array('order_item_id'=>$item['id']))->result_array();
                                                                        if($order_item_invoices)
                                                                        {
                                                                            $i_count = 1;
                                                                            foreach($order_item_invoices as $order_item_invoice)
                                                                            {
                                                                                if(file_exists($order_item_invoice['attachments']) && $order_item_invoice['attachments'])
                                                                                {
                                                                                    ?>
                                                                                    <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_invoice['attachments'];?>" target="_blank">Invoice <?php //echo $i_count; ?></a><br />
                                                                                    <?php
                                                                                    $i_count++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    else
                                                                    {*/
                                                                        //echo $item['active_status'];   
                                                                    //} 
                                                                    /*
                                                                    ?>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url().'my-account/orderitemdetail/'.$item['id'].'/'.$order['id']; ?>"><i class="fa fa-eye"></i></a>
                                                                </td>
                                                                <?php */ ?>
                                                            </tr>
                                                            <?php 
                                                            if($item['active_status'] !='cancelled')
                                                            {
                                                                $total_amt += (($item['price'] * $item['quantity']));
                                                                $total_mrp += (($item['mrp'] * $item['quantity']));
                                                                $total_std += (($item['standard_price'] * $item['quantity']));
                                                            } 
                                                            $i++;
                                                        }
                                                    } 
                                                    ?>
                                                    <tr>
                                                        <th class="text-right" colspan="10">Total Amount</th>
                                                        <th class="text-right"><?php echo $settings['currency'] . ' ' . number_format(($total_amt),2); ?></th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        
                        
                        ?>
                        <?php /* ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="bg-primary text-white">
                                    <th>Sr. No.</th>
                                    <th>Order ID</th>
                                    <th>Manufacture Name</th>
                                    <th>Product</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Qty</th>
                                    <th>Total Amount</th>
                                    <th>GST</th>
                                    <th>HSN Code</th>
                                    <th>License</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                                <?php 
                                $i = 1;
                                foreach ($order['order_items'] as $key => $item) 
                                { 
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $order['id'] ?></td>
                                        <td><?php echo $item['company_name']; ?></td>
                                        <td><?php echo $item['name']; ?></td>
                                        <td><?php echo number_format(($item['price']), 2) ?></td>
                                        <td><?php echo number_format(($item['mrp'] - $item['price']), 2) ?></td>
                                        <td><?php echo $item['quantity'] ?></td>
                                        <td><?php echo number_format(($item['price'] * $item['quantity']), 2) ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo date('d/m/Y g:i A', strtotime($order['date_added'])); ?></td>
                                        <td><?php echo $item['active_status']; ?></td>
                                    </tr>
                                    <?php 
                                    $i++;
                                } 
                                ?>
                            </table>
                        </div>
                        <?php */ ?>   
                    
                        <?php /* ?>
                        <?php foreach ($order['order_items'] as $key => $item) { ?>
                            <div class="media flex-column flex-sm-row">
                                <div class="media-body ">
                                    <h5 class="bold"><?= ($key + 1) . '. ' . $item['name'] ?></h5>
                                    <p class="text-muted"> <?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?> : <?= $item['quantity'] ?></p>

                                    <h4 class="mt-3 mb-2 bold"> <span class="mt-5"><i><?= $settings['currency'] ?></i></span> <?= number_format(($item['price'] * $item['quantity']), 2) ?> <span class="small text-muted"></span></h4>
                                    <?php if (!$item['is_already_cancelled'] && $item['is_cancelable']) { ?>
                                        <button class="button button-danger button-sm update-order-item" data-status="cancelled" data-item-id="<?= $item['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                                    <?php } ?>
                                    <?php if (!$item['is_already_cancelled'] && !$item['is_already_returned'] && $item['is_returnable'] && $item['active_status'] == 'delivered') { ?>
                                        <button class="button button-warning button-sm update-order-item" data-status="returned" data-item-id="<?= $item['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></button>
                                    <?php } ?>
                                </div>
                                <img class="align-self-center img-fluid" src="<?= $item['image_sm'] ?>" width="180 " height="180">
                            </div>
                            <div class="row">
                                <div class="col p-0">
                                    <ul id="progressbar">
                                        <?php
                                        $status = array('received', 'processed', 'shipped', 'delivered');
                                        $i = 1;
                                        foreach ($item['status'] as $value) { ?>
                                            <?php
                                            $class = '';
                                            if ($value[0] == "cancelled" || $value[0] == "returned") {
                                                $class = 'cancel';
                                                $status = array();
                                            } elseif (($ar_key = array_search($value[0], $status)) !== false) {
                                                unset($status[$ar_key]);
                                            }
                                            ?>
                                            <li class="active <?= $class ?>" id="step<?= $i ?>">
                                                <p><?= strtoupper($value[0]) ?></p>
                                                <p><?= $value[1] ?></p>
                                            </li>
                                        <?php
                                            $i++;
                                        } ?>
                                        <?php foreach ($status as $value) { ?>
                                            <li id="step<?= $i ?>">
                                                <p><?= strtoupper($value) ?></p>
                                            </li>
                                        <?php $i++;
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                            <hr/>
                        <?php } ?>
                        <?php */ ?>
                       
                        <div class="row">
                            <?php /* ?>
                            <div class="col-md-6">
                                <h6 class="h5"><?= !empty($this->lang->line('shipping_details')) ? $this->lang->line('shipping_details') : 'Shipping Details' ?></h6>
                                <hr/>
                                <span><?= $order['username'] ?></span> <br/>
                                <span><?= $order['address'] ?></span> <br/>
                                <span><?= $order['mobile'] ?></span> <br/>
                                <span><?= $order['delivery_time'] ?></span> <br/>
                                <span><?= $order['delivery_date'] ?></span> <br/>
                            </div>
                            <?php */ ?>
                            
                            <div class="col-md-12">
                                <div class="shadow p-3 mb-5 bg-white rounded">
                                    <h6 class="h5"><?= !empty($this->lang->line('price_details')) ? $this->lang->line('price_details') : 'Price Details' ?></h6>
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th>Total standard price (inc GST)</th>
                                                    <td><?php echo $settings['currency'].' '.number_format($total_std, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Discounted price (inc GST) </th>
                                                    <td><?php echo $settings['currency'].' '.number_format(($total_amt), 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total profit gain </th>
                                                    <td><?php echo $settings['currency'].' '.number_format(($total_std-$total_amt), 2); ?></td>
                                                </tr>
                                                
                                                <!--<tr>
                                                    <th><?= !empty($this->lang->line('delivery_charge')) ? $this->lang->line('delivery_charge') : 'Delivery Charge' ?></th>
                                                    <td>+ <?= $settings['currency'] . ' ' . number_format($order['delivery_charge'], 2) ?></td>
                                                </tr>-->
                                                <tr class="d-none">
                                                    <th><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?> - (<?= $order['total_tax_percent'] ?>%)</th>
                                                    <td>+ <?= $settings['currency'] . ' ' . number_format($order['total_tax_amount'], 2) ?></td>
                                                </tr>
                                                <?php if (!empty($order['promo_code']) && !empty($order['promo_discount'])) { ?>
                                                    <tr>
                                                        <th><?= !empty($this->lang->line('promocode_discount')) ? $this->lang->line('promocode_discount') : 'Promocode Discount' ?> - (<?= $order['promo_code'] ?>)
                                                        </th>
                                                        <td>- <?= $settings['currency'] . ' ' . number_format($order['promo_discount'], 2) ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <!--<tr>
                                                    <th><?= !empty($this->lang->line('wallet_used')) ? $this->lang->line('wallet_used') : 'Wallet Used' ?></th>
                                                    <td>- <?= $settings['currency'] . ' ' . number_format($order['wallet_balance'], 2) ?></td>
                                                </tr>-->
                                                <tr class="h4">
                                                    <th><!--<?= !empty($this->lang->line('final_total')) ? $this->lang->line('final_total') : 'Final total (inc GST)' ?>-->Final total (inc GST)</th>
                                                    <th><?= $settings['currency'] . ' ' . number_format($total_amt, 2) //$order['final_total'] ?> <!--<span class="small text-muted"> <?= !empty($this->lang->line('via')) ? $this->lang->line('via') : 'via' ?> (<?= $order['payment_method'] ?>) </span>--></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- /.col -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white px-sm-3 pt-sm-4 px-0">
                        <div class="row text-center ">
                            <?php if ($order['is_cancelable'] && !$order['is_already_cancelled']) { ?>
                                <div class="col my-auto">
                                    <a class="update-order block button-sm buttons btn-6-1 mt-3 m-0" data-status="cancelled" data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></a>
                                </div>
                            <?php } ?>
                            <?php if ($order['is_returnable'] && !$order['is_already_returned']) { ?>
                                <div class="col my-auto ">
                                    <a class="update-order block buttons button-sm btn-6-3 mt-3 m-0" data-status="returned" data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $order_id = $order['id'];
                        
                        $state_array = array('received','qty_update', 'schedule_delivery', 'shipped');
                        
                        $this->db->select('a.*, b.active_status, b.product_name, b.schedule_delivery_date');
                        $this->db->from('order_item_stages as a');
                        $this->db->join('order_items as b','a.order_item_id = b.id','left');
                        $this->db->where('a.order_id',$order_id);
                        $this->db->where_not_in('a.status', $state_array);
                        $query = $this->db->get();
                        $order_item_stages = $query->result_array();
                        
                        
                            ?>
                            <div class="row">
                                <div class="col-md-12 ">
                                    <div class="shadow p-3 mb-5 bg-white rounded">
                                        <h6 class="h5">Order Stages Details</h6>
                                        <hr/>
                                        <div class="tmln">
                                            <div class="tmln-outer">
                                                <div class="tmln-card">
                                                    <div class="tmln-info">
                                                        <h3 class="tmln-title">Your order placed. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order['date_added'])); ?></small></h3>
                                                        <p>
                                                        <?php
                                                        /*if($order['order_status']=='received')
                                                        {*/
                                                            ?>
                                                            <small>- Waiting for updates from manufacturer.</small>
                                                            <?php
                                                        //}
                                                        ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php
                                                if($order_item_stages)
                                                {
                                                    $s_count = 0;
                                                    foreach($order_item_stages as $order_item_stage)
                                                    {
                                                        $s_count++;
                                                        ?>
                                                        <div class="tmln-card">
                                                          <div class="tmln-info">
                                                            <?php /*if($order_item_stage['status']=='received') { ?> 
                                                                <p>Your order received</p>
                                                            <?php } else*/ if($order_item_stage['status']=='qty_update') { ?> 
                                                                <?php
                                                                if((int)$order_item_stage['order_item_id']!=0)
                                                                {
                                                                    ?>
                                                                    <p><span class="stage_prod_name"><?php echo $order_item_stage['product_name']; ?></span> Quantity updated and approval request received from manafacurer.</p>
                                                                    <?php
                                                                    if($order_item_stage['active_status']=='qty_update' && count($order_item_stages) == $s_count)
                                                                    {
                                                                        ?>
                                                                        <div class="col-lg-12">
                                                                            <div class="col-lg-12">
                                                                                <div class="btn-group">
                                                                                    <div class="btn-wrap show-code-action">
                                                                                        <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="approveQty(<?php echo $order_item_stage['order_item_id'] ?>,<?php echo $order_item_stage['order_id'] ?>);">Approve</a>
                                                                                    </div>
                                                                                    <div class="btn-wrap show-code-action">
                                                                                        <a class="btn btn-sm btn-secondary" href="javascript:void(0);" onclick="rejectQty(<?php echo $order_item_stage['order_item_id'] ?>,<?php echo $order_item_stage['order_id'] ?>);">Reject</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    $order_item_ids = array();
                                                                    $order_item_ids = explode(',',$order_item_stage['ids']);
                                                                    
                                                                    $this->db->select('a.*');
                                                                    $this->db->from('order_items as a');
                                                                    $this->db->where_in('a.id', $order_item_ids);
                                                                    $query = $this->db->get();
                                                                    
                                                                    $order_items_rows = $query->result_array();
                                                                    
                                                                    if($order_items_rows)
                                                                    {
                                                                        ?>
                                                                        <p>
                                                                        <?php
                                                                        $pr = 0;
                                                                        foreach($order_items_rows as $order_items_row)
                                                                        {
                                                                            ?>
                                                                            <span class="stage_prod_name"><?php echo $order_items_row['product_name']; ?></span> 
                                                                            <?php
                                                                            $pr++;
                                                                            if($pr < count($order_items_rows))
                                                                            {
                                                                                echo ", ";
                                                                            }
                                                                        }
                                                                        ?>
                                                                        Quantity updated and approval request received from manafacurer.
                                                                        </p>
                                                                        <?php
                                                                        if($order_items_rows[0]['active_status'] == 'qty_update' && count($order_item_stages) == $s_count)
                                                                        {
                                                                            ?>
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="col-lg-12">
                                                                                        <div class="btn-group">
                                                                                            <div class="btn-wrap show-code-action">
                                                                                                <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="approveBulkQty(<?php echo $order_item_stage['id'] ?>,<?php echo $order_item_stage['order_id'] ?>);">Approve</a>
                                                                                            </div>
                                                                                            <div class="btn-wrap show-code-action">
                                                                                                <a class="btn btn-sm btn-secondary" href="javascript:void(0);" onclick="rejectBulkQty(<?php echo $order_item_stage['id'] ?>,<?php echo $order_item_stage['order_id'] ?>);">Reject</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    
                                                                    
                                                                }
                                                                ?>     
                                                            <?php } else if($order_item_stage['status']=='qty_approved') { ?> 
                                                                <?php
                                                                if((int)$order_item_stage['order_item_id']!=0)
                                                                {
                                                                    ?>
                                                                    <p><span class="stage_prod_name"><?php echo $order_item_stage['product_name']; ?></span> Quantity approval accepted by you.</p>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    $order_item_ids = array();
                                                                    $order_item_ids = explode(',',$order_item_stage['ids']);
                                                                    
                                                                    $this->db->select('a.*');
                                                                    $this->db->from('order_items as a');
                                                                    $this->db->where_in('a.id', $order_item_ids);
                                                                    $query = $this->db->get();
                                                                    
                                                                    $order_items_rows = $query->result_array();
                                                                    
                                                                    if($order_items_rows)
                                                                    {
                                                                        ?>
                                                                        <p>
                                                                        <?php
                                                                        $pr = 0;
                                                                        foreach($order_items_rows as $order_items_row)
                                                                        {
                                                                            ?>
                                                                            <span class="stage_prod_name"><?php echo $order_items_row['product_name']; ?></span> 
                                                                            <?php
                                                                            $pr++;
                                                                            if($pr < count($order_items_rows))
                                                                            {
                                                                                echo ", ";
                                                                            }
                                                                        }
                                                                        ?>
                                                                        Quantity approval accepted by you.
                                                                        </p>
                                                                        <?php
                                                                    }
                                                                    
                                                                }
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='qty_rejected') { ?> 
                                                                <h3 class="tmln-title">Order Cancelled. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <?php
                                                                /*
                                                                if((int)$order_item_stage['order_item_id']!=0)
                                                                {
                                                                    ?>
                                                                    <p><span class="stage_prod_name"><?php echo $order_item_stage['product_name']; ?></span> Quantity approval rejected by you.</p>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    $order_item_ids = array();
                                                                    $order_item_ids = explode(',',$order_item_stage['ids']);
                                                                    
                                                                    $this->db->select('a.*');
                                                                    $this->db->from('order_items as a');
                                                                    $this->db->where_in('a.id', $order_item_ids);
                                                                    $query = $this->db->get();
                                                                    
                                                                    $order_items_rows = $query->result_array();
                                                                    
                                                                    if($order_items_rows)
                                                                    {
                                                                        ?>
                                                                        <p>
                                                                        <?php
                                                                        $pr = 0;
                                                                        foreach($order_items_rows as $order_items_row)
                                                                        {
                                                                            ?>
                                                                            <span class="stage_prod_name"><?php echo $order_items_row['product_name']; ?></span> 
                                                                            <?php
                                                                            $pr++;
                                                                            if($pr < count($order_items_rows))
                                                                            {
                                                                                echo ", ";
                                                                            }
                                                                        }
                                                                        ?>
                                                                        Quantity approval rejected by you.
                                                                        </p>
                                                                        <?php
                                                                    }
                                                                    
                                                                }
                                                                */
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='payment_demand') { ?> 
                                                                <h3 class="tmln-title">Manufacturer reviewed your order. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <ul>
                                                                    <li>- For order details please check order table</li>
                                                                    <li>- Tentative delivery date <?php echo date('d/m/Y',strtotime($order['schedule_delivery_date'])); ?></li>
                                                                    <li>- Payment requested by manufacturer is <?php echo $settings['currency'] . ' ' . number_format($total_amt, 2); ?>/-</li>
                                                                </ul>
                                                                <?php /* ?>
                                                                <p>Payment demand request by manufacturer and your order total amount is <?php echo $settings['currency'] . ' ' . number_format($total_amt, 2); ?></p>
                                                                <?php */ ?>
                                                                <?php 
                                                                if($order_item_stage['active_status']=='payment_demand' && $order_item_stage['order_item_id']!=0 && count($order_item_stages) == $s_count)
                                                                { 
                                                                    ?>
                                                                    <p>Please make payment and upload payment receipt.</p>
                                                                    <div class="row">
                                                                        <form class="form-horizontal " id="send_bank_receipt_form" action="<?= base_url('my-account/send-payment-receipt'); ?>" method="POST" enctype="multipart/form-data">
                                                                            <div class="row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label class="btn btn-warning btn-sm btn-block" for="receipt">Browse</label>
                                                                                    <div class="custom-file-input" style="margin-top: -30px;">
                                                                                        <input required="" type="file" class="form-control" name="attachments[]" id="receipt" style="padding:0px;min-height: 28px;"  multiple  />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <input type="hidden" name="order_item_id" value="<?= $order_item_stage['order_item_id'] ?>">
                                                                                    <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                    <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Upload the transaction details</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                        
                                                                    </div>
                                                                    <?php
                                                                }
                                                                else if($order['order_status'] == 'payment_demand' && count($order_item_stages) == $s_count) 
                                                                {
                                                                    ?>
                                                                    <p>Please make payment and upload payment receipt.</p>
                                                                    <div class="row">
                                                                        <form class="form-horizontal " id="send_bank_receipt_form" action="<?= base_url('my-account/send-payment-receipt'); ?>" method="POST" enctype="multipart/form-data">
                                                                            <div class="row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label class="btn btn-primary btn-sm btn-block " for="receipt">Select the transaction receipt</label>
                                                                                    <div class="custom-file-input mb-2" style="margin-top: -30px;">
                                                                                        <input type="file" class="form-control" name="attachments[]" id="receipt" style="padding:0px;min-height: 28px;" required="" onchange="$('#f1_text').html(this.value.replace('C:\\fakepath\\', ''));" /><!-- multiple-->
                                                                                    </div>
                                                                                    <p class=""><span id="f1_text"></span></p>
                                                                                </div>
                                                                                <div class="form-group col-md-2">
                                                                                    <input type="hidden" name="order_item_id" value="">
                                                                                    <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                    <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Upload</button>
                                                                                </div>
                                                                                
                                                                                <div class="form-group col-md-5">
                                                                                    <div class="btn-group mb-0 pb-0 float-right">
                                                                                        <div class="btn-wrap mb-0 pb-0 show-code-action">
                                                                                            <a class="btn btn-sm  btn-secondary" href="javascript:void(0);" title="If you are not OK with manufacture response." onclick="rejectBulkQty(<?php echo $order_item_stage['id'] ?>,<?php echo $order_item_stage['order_id'] ?>);">Reject the order</a>
                                                                                            <p><small>If not OK with manufacture response.</small></p>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <p class="">Note – This order will be auto- canceled, if action not taken within 5 days from the date of payment request form manufacturer.</p>
                                                                    <?php
                                                                }
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='payment_ack') { ?> 
                                                                <h3 class="tmln-title">Transaction details shared with manufacturer. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <p><small>- Waiting for confirmation.</small></p>
                                                                <?php /* ?>
                                                                <table class="table table-bordered">
                                                                    <tr class="bg-primary text-white">
                                                                        <th style="width: 8%;">Sr. No.</th>
                                                                        <th>Date</th>
                                                                        <th style="width: 18%;">Download</th>
                                                                    </tr>
                                                                <?php
                                                                $ids = explode(',',$order_item_stage['ids']);
                                                                if($ids)
                                                                {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_payment_demand');
                                                                    $this->db->where_in('id',$ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_payment_demands = $query->result_array();
                                                                    
                                                                    //$order_item_payment_demands = $this->db->get_where('order_item_payment_demand', array('order_item_id'=>$order_item_id))->result_array();
                                                                    if($order_item_payment_demands)
                                                                    {
                                                                        $i_count = 1;
                                                                        foreach($order_item_payment_demands as $order_item_payment_demand)
                                                                        {
                                                                            if(file_exists($order_item_payment_demand['attachments']) && $order_item_payment_demand['attachments'])
                                                                            {
                                                                                ?>
                                                                                <tr class="bg-white  text-dark">
                                                                                    <td><?php echo $i_count; ?></td>
                                                                                    <td><?php echo date('d/m/Y',strtotime($order_item_payment_demand['date_created']));?></td>
                                                                                    <td><a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_payment_demand['attachments'];?>" target="_blank">Download Receipt</a><br /></td>
                                                                                </tr>
                                                                                <?php
                                                                                $i_count++;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                </table>
                                                                <?php */ ?>
                                                            <?php } else if($order_item_stage['status']=='schedule_delivery') { ?>
                                                                <?php
                                                                /*if($item_info['active_status'] == 'schedule_delivery')
                                                                {*/
                                                                    /*if($order_item_stage['schedule_delivery_date']==null || $order_item_stage['schedule_delivery_date']=='0000-00-00')
                                                                    {
                                                                        echo "Delivery scheduling, please be patience";
                                                                    }
                                                                    else
                                                                    {*/
                                                                        echo "Delivery Scheduled ON ".date('d/m/Y',strtotime($order['schedule_delivery_date']));
                                                                    //}
                                                                //}
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='send_payment_confirmation') { ?>
                                                                <h3 class="tmln-title">Payment receipt received. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3> 
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <p class="mt-2">Once order get dispatched, you will receive the E-way bill and invoices.</p>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="btn-group ">
                                                                        <?php
                                                                        $ids = explode(',',$order_item_stage['ids']);
                                                                        if($ids)
                                                                        {
                                                                            $this->db->select('*');
                                                                            $this->db->from('order_item_payment_confirmation');
                                                                            $this->db->where_in('id',$ids);
                                                                            $query = $this->db->get();
                                                                            $order_item_payment_confirmations = $query->result_array();
                                                                            
                                                                            if($order_item_payment_confirmations)
                                                                            {
                                                                                $i_count = 1;
                                                                                foreach($order_item_payment_confirmations as $order_item_payment_confirmation)
                                                                                {
                                                                                    if(file_exists($order_item_payment_confirmation['attachments']) && $order_item_payment_confirmation['attachments'])
                                                                                    {
                                                                                        ?>
                                                                                        <div class="btn-wrap show-code-action mb-0">
                                                                                            <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_payment_confirmation['attachments'];?>" target="_blank">Download transaction receipt</a>
                                                                                        </div>
                                                                                        <?php
                                                                                        $i_count++;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php /* ?>
                                                                <table class="table table-bordered">
                                                                    <tr class="bg-primary text-white">
                                                                        <th style="width: 8%;">Sr. No.</th>
                                                                        <th>Date</th>
                                                                        <th style="width: 18%;">Download</th>
                                                                    </tr>
                                                                    <?php
                                                                    $ids = explode(',',$order_item_stage['ids']);
                                                                    if($ids)
                                                                    {
                                                                        $this->db->select('*');
                                                                        $this->db->from('order_item_payment_confirmation');
                                                                        $this->db->where_in('id',$ids);
                                                                        $query = $this->db->get();
                                                                        $order_item_payment_confirmations = $query->result_array();
                                                                        
                                                                        if($order_item_payment_confirmations)
                                                                        {
                                                                            $i_count = 1;
                                                                            foreach($order_item_payment_confirmations as $order_item_payment_confirmation)
                                                                            {
                                                                                if(file_exists($order_item_payment_confirmation['attachments']) && $order_item_payment_confirmation['attachments'])
                                                                                {
                                                                                    ?>
                                                                                    <tr class="bg-white text-dark">
                                                                                        <td><?php echo $i_count; ?></td>
                                                                                        <td><?php echo date('d/m/Y',strtotime($order_item_payment_confirmation['date_created']));?></td>
                                                                                        <td><a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_payment_confirmation['attachments'];?>" target="_blank">Download Confirmation</a></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $i_count++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </table>
                                                                <?php */ ?>
                                                            <?php } else if($order_item_stage['status']=='shipped') { ?> 
                                                                <p>Your order shipped by manufacturer.</p>
                                                            <?php } else if($order_item_stage['status']=='send_invoice') { ?> 
                                                                <h3 class="tmln-title">Order is in transit, E-way bill and invoices received from manufacturer. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <div class="btn-group">
                                                                    <?php
                                                                    $ids2 = explode(',',$order_item_stage['ids2']);
                                                                    if($ids2)
                                                                    {
                                                                        $this->db->select('*');
                                                                        $this->db->from('order_item_eway_bill');
                                                                        $this->db->where_in('id',$ids2);
                                                                        $query = $this->db->get();
                                                                        $order_item_eway_bills = $query->result_array();
                                                                        
                                                                        if($order_item_eway_bills)
                                                                        {
                                                                            $i_count = 1;
                                                                            foreach($order_item_eway_bills as $order_item_eway_bill)
                                                                            {
                                                                                if(file_exists($order_item_eway_bill['attachments']) && $order_item_eway_bill['attachments'])
                                                                                {
                                                                                    ?>
                                                                                    <div class="btn-wrap show-code-action mb-0">
                                                                                        <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_eway_bill['attachments'];?>" target="_blank">Download E-way Bill</a>
                                                                                    </div>
                                                                                    <?php
                                                                                    $i_count++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                    <?php
                                                                    $ids = explode(',',$order_item_stage['ids']);
                                                                    if($ids)
                                                                    {
                                                                        $this->db->select('*');
                                                                        $this->db->from('order_item_invoice');
                                                                        $this->db->where_in('id',$ids);
                                                                        $query = $this->db->get();
                                                                        $order_item_invoices = $query->result_array();
                                                                        
                                                                        //$order_item_invoices = $this->db->get_where('order_item_invoice', array('order_item_id'=>$order_item_id))->result_array();
                                                                        if($order_item_invoices)
                                                                        {
                                                                            $i_count = 1;
                                                                            foreach($order_item_invoices as $order_item_invoice)
                                                                            {
                                                                                if(file_exists($order_item_invoice['attachments']) && $order_item_invoice['attachments'])
                                                                                {
                                                                                    ?>
                                                                                    <div class="btn-wrap show-code-action mb-0">
                                                                                        <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_invoice['attachments'];?>" target="_blank">Download Invoice</a>
                                                                                    </div>    
                                                                                    <?php
                                                                                    $i_count++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <p class="">Note - Please make sure timely unloading of material.</p>
                                                                
                                                                <?php
                                                                if(count($order_item_stages) == $s_count)
                                                                {
                                                                    ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tmln-card">
                                                                        <div class="tmln-info">
                                                                            <h3 class="tmln-title">Is your order delivered.</h3>
                                                                            <div class="">
                                                                                <form class="form-horizontal " id="shipped_delivery_form" action="<?= base_url('my-account/send_order_status'); ?>" method="POST" enctype="multipart/form-data">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-1">
                                                                                            <input type="hidden" name="status" value="delivered" />
                                                                                            <input type="hidden" name="order_item_id" value="">
                                                                                            <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                            <button type="submit" class="btn btn-sm btn-primary mb-1" id="submit_btn">Yes</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                                <p>Or</p>
                                                                                <form class="form-horizontal " id="send_complaint_form" action="<?= base_url('my-account/send-complaint'); ?>" method="POST" enctype="multipart/form-data">
                                                                                    <p>For any queries, please raise the ticket</p>
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label class="" for="concern">Write Your Concern</label>
                                                                                            <div class="" style="">
                                                                                                <textarea class="form-control bg-white" name="concern" id="concern" required=""></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group col-md-2">
                                                                                            <label class="" for="concern">Image</label>
                                                                                            <label class="btn btn-primary btn-sm btn-block  " for="image">Browse</label>
                                                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                                                <input type="file" class="form-control" name="attachments[]" id="image" style="padding:0px;min-height: 28px;"  multiple />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group col-md-12">
                                                                                            <input type="hidden" name="order_item_id" value="">
                                                                                            <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                            <button type="submit" class="btn btn-primary btn-sm" id="submit_complaint_btn">Send</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                                
                                                                <?php /* ?>
                                                                <table class="table table-bordered">
                                                                    <tr class="bg-primary text-white">
                                                                        <th style="width: 8%;">Sr. No.</th>
                                                                        <th>Date</th>
                                                                        <th style="width: 18%;">Download</th>
                                                                    </tr>
                                                                    <?php
                                                                    $ids = explode(',',$order_item_stage['ids']);
                                                                    if($ids)
                                                                    {
                                                                        $this->db->select('*');
                                                                        $this->db->from('order_item_invoice');
                                                                        $this->db->where_in('id',$ids);
                                                                        $query = $this->db->get();
                                                                        $order_item_invoices = $query->result_array();
                                                                        
                                                                        //$order_item_invoices = $this->db->get_where('order_item_invoice', array('order_item_id'=>$order_item_id))->result_array();
                                                                        if($order_item_invoices)
                                                                        {
                                                                            $i_count = 1;
                                                                            foreach($order_item_invoices as $order_item_invoice)
                                                                            {
                                                                                if(file_exists($order_item_invoice['attachments']) && $order_item_invoice['attachments'])
                                                                                {
                                                                                    ?>
                                                                                    <tr class="bg-white text-dark">
                                                                                        <td><?php echo $i_count; ?></td>
                                                                                        <td><?php echo date('d/m/Y',strtotime($order_item_invoice['date_created']));?></td>
                                                                                        <td><a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_invoice['attachments'];?>" target="_blank">Download Invoice</a></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $i_count++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </table>
                                                                <?php */ ?>
                                                            <?php } else if($order_item_stage['status']=='complaint') { ?> 
                                                                <h3 class="tmln-title">You raised your concern. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <table class="table table-bordered">
                                                                    <tr class="bg-primary text-white">
                                                                        <th style="width: 8%;">Sr. No.</th>
                                                                        <th>Your Concern</th>
                                                                        <th style="width: 18%;">Image</th>
                                                                    </tr>
                                                                    <?php
                                                                    $ids = explode(',',$order_item_stage['ids']);
                                                                    if($ids)
                                                                    {
                                                                        $this->db->select('*');
                                                                        $this->db->from('order_item_complaints');
                                                                        $this->db->where_in('id',$ids);
                                                                        $query = $this->db->get();
                                                                        $order_item_complaints = $query->result_array();
                                                                        //echo $this->db->last_query();die;
                                                                        
                                                                        if($order_item_complaints)
                                                                        {
                                                                            $i_count = 1;
                                                                            foreach($order_item_complaints as $order_item_complaint)
                                                                            {
                                                                                
                                                                                    ?>
                                                                                    <tr class="bg-white text-dark">
                                                                                        <td class="text-center"><?php echo $i_count; ?></td>
                                                                                        <td><?php echo $order_item_complaint['concern'];?></td>
                                                                                        <td>
                                                                                            <?php
                                                                                            if(file_exists($order_item_complaint['attachments']) && $order_item_complaint['attachments'])
                                                                                            {
                                                                                                ?>
                                                                                                <a href="<?php echo base_url().$order_item_complaint['attachments'];?>" target="_blank">
                                                                                                    <img src="<?php echo base_url().$order_item_complaint['attachments'];?>" alt="" style="width: 100px;" />
                                                                                                </a>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $i_count++;
                                                                                
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </table>
                                                                <?php
                                                                if(count($order_item_stages) == $s_count)
                                                                {
                                                                    ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tmln-card">
                                                                        <div class="tmln-info">
                                                                            <h3 class="tmln-title">Is your Issue resolved.</h3>
                                                                            <div class="">
                                                                                <form class="form-horizontal " id="complaint_resolved_form" action="<?= base_url('my-account/send_order_status'); ?>" method="POST" enctype="multipart/form-data">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-1">
                                                                                            <input type="hidden" name="status" value="delivered" />
                                                                                            <input type="hidden" name="order_item_id" value="">
                                                                                            <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                            <button type="submit" class="btn btn-sm btn-primary mb-1" id="submit_btn">Yes</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='delivered') { ?> 
                                                                <h3 class="tmln-title">Order has been closed. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                            <?php } else if($order_item_stage['status']=='out_of_stock') { ?> 
                                                                <h3 class="tmln-title">Manufacturer canceled order <small>- Unfortunately, products are out of stock. Order has been closed.</small> <small class="float-right"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <p>Please check with other manufacturer.</p>
                                                            <?php } else { ?> 
                                                                <h3 class="tmln-title"><?php echo $order_item_stage['status']; ?> <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                            <?php } ?>
                                                          </div>
                                                        </div>
                                                        <?php
                                                        
                                                    }
                                                    }
                                                    
                                                    
                                                    
                                                    if($order_item_stage['active_status'] == 'received' || count($order_item_stages) == 0)
                                                    {
                                                        ?>
                                                        <?php /* ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order['date_added'])); ?></h3>
                                                                <p>Quantity updated and approval request received from manafacurer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order['date_added'])); ?></h3>
                                                                <p>Quantity approval accepted/rejected by you.</p>
                                                            </div>
                                                        </div>
                                                        <?php */ ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Manufacturer reviewed your order. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                                <ul>
                                                                    <li>- For order details please check order table</li>
                                                                    <li>- Tentative delivery date XX/XX/XXXX</li>
                                                                    <li>- Payment requested by manufacturer is XXXXX/-</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Transaction details shared with manufacturer. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                                <p><small>- Waiting for confirmation.</small></p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Payment receipt received. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                                <p>Once order get dispatched, you will receive the E-way bill and invoices.</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order is in transit, E-way bill and invoices received from manufacturer. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order has been closed. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    /*
                                                    else if($order_item_stage['active_status'] == 'qty_update')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Delivery Scheduled</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Payment demand request by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Payment acknowledgement sent to manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item shipped by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Eway Bill and Invoice sent by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item delivered.</p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else if($order_item_stage['active_status'] == 'qty_approved')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Delivery Scheduled</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Payment demand request by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Payment acknowledgement sent to manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item shipped by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Eway Bill and Invoice sent by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item delivered.</p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    */
                                                    else if($order_item_stage['active_status'] == 'payment_demand' || $order['order_status'] == 'payment_demand')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Transaction details shared with manufacturer. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                                <p><small>- Waiting for confirmation.</small></p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Payment receipt received. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                                <p>Once order get dispatched, you will receive the E-way bill and invoices.</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order is in transit, E-way bill and invoices received from manufacturer. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order has been closed. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else if($order_item_stage['active_status'] == 'payment_ack' || $order['order_status'] == 'payment_ack')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Payment receipt received. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                                <p>Once order get dispatched, you will receive the E-way bill and invoices.</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order is in transit, E-way bill and invoices received from manufacturer. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order has been closed. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else if($order_item_stage['active_status'] == 'send_payment_confirmation' || $order['order_status'] == 'send_payment_confirmation')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order is in transit, E-way bill and invoices received from manufacturer. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order has been closed. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    /*else if($order_item_stage['active_status'] == 'schedule_delivery' || $order['order_status'] == 'schedule_delivery')
                                                    {
                                                        ?>
                                                        
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Payment demand request by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Payment acknowledgement sent to manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item shipped by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Eway Bill and Invoice sent by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item delivered.</p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else if($order_item_stage['active_status'] == 'shipped' || $order['order_status'] == 'shipped')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Eway Bill and Invoice sent by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item delivered.</p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }*/
                                                    else if($order_item_stage['active_status'] == 'send_invoice' || $order['order_status'] == 'send_invoice')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title">Order has been closed. <small class="float-right font-weight-normal">XXX XX XXX XXXX XX:XX XX</small></h3>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else if($order_item_stage['order_item_id'] == 0)
                                                    {
                                                        $order_item_ids = array();
                                                        $order_item_ids = explode(',',$order_item_stage['ids']);
                                                        
                                                        $this->db->select('a.*');
                                                        $this->db->from('order_items as a');
                                                        $this->db->where_in('a.id', $order_item_ids);
                                                        $query = $this->db->get();
                                                        
                                                        $order_items_rows = $query->result_array();
                                                        
                                                        if($order_items_rows[0]['active_status'] == 'qty_update')
                                                        {
                                                            ?>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Quantity approval accepted/rejected by you.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Delivery Scheduled</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Payment demand request by manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Payment acknowledgement sent to manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Item shipped by manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Eway Bill and Invoice sent by manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Item delivered.</p>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        else if($order_items_rows[0]['active_status'] == 'qty_approved')
                                                        {
                                                            ?>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Payment demand request by manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Payment acknowledgement sent to manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Delivery Scheduled</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Item shipped by manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Eway Bill and Invoice sent by manufacturer.</p>
                                                                </div>
                                                            </div>
                                                            <div class="tmln-card in-active">
                                                                <div class="tmln-info">
                                                                    <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                    <p>Item delivered.</p>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script type="text/javascript">
function approveBulkQty(order_stage_id, order_id)
{
    Swal.fire({
        title: 'Are You Sure!',
        text: "You want to approve updated quantity",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?php echo base_url('my-account/approveBulkQty') ?>",
                    type: 'POST',
                    cache: false,
                    data:{order_stage_id:order_stage_id,order_id:order_id},
                    dataType: "json",
                    error: function (xhr, status, error) {
                        //alert(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong.'
                        });
                        swal.close();
                        location.reload();
                    },
                    success: function (result) {
                        if (result['error'] == false) {
                            Toast.fire({
                                icon: 'success',
                                title: result['message']
                            });
                            $("#order_state_"+result['order_item_id']).html(result['html']);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result['message']
                            });
                        }
                        swal.close();
                        location.reload();
                    }  
                });
            });
        },
        allowOutsideClick: false
    });
}

function rejectBulkQty(order_stage_id, order_id)
{
    Swal.fire({
        title: 'Are You Sure!',
        text: "If you are not OK with manufacture response then you can cancel the order.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?php echo base_url('my-account/rejectBulkQty') ?>",
                    type: 'POST',
                    cache: false,
                    data:{order_stage_id:order_stage_id,order_id:order_id},
                    dataType: "json",
                    error: function (xhr, status, error) {
                        //alert(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong.'
                        });
                        swal.close();
                        location.reload();
                    },
                    success: function (result) {
                        if (result['error'] == false) {
                            Toast.fire({
                                icon: 'success',
                                title: result['message']
                            });
                            //$("#order_state_"+result['order_item_id']).html(result['html']);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result['message']
                            });
                        }
                        swal.close();
                        location.reload();
                    }  
                });
            });
        },
        allowOutsideClick: false
    });
}

function approveQty(order_item_id, order_id)
{
    Swal.fire({
        title: 'Are You Sure!',
        text: "You want to approve updated quantity",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?php echo base_url('my-account/approveQty') ?>",
                    type: 'POST',
                    cache: false,
                    data:{order_item_id:order_item_id,order_id:order_id},
                    dataType: "json",
                    error: function (xhr, status, error) {
                        //alert(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong.'
                        });
                        swal.close();
                        location.reload();
                    },
                    success: function (result) {
                        if (result['error'] == false) {
                            Toast.fire({
                                icon: 'success',
                                title: result['message']
                            });
                            $("#order_state_"+result['order_item_id']).html(result['html']);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result['message']
                            });
                        }
                        swal.close();
                        location.reload();
                    }  
                });
            });
        },
        allowOutsideClick: false
    });
}
function rejectQty(order_item_id, order_id)
{
    Swal.fire({
        title: 'Are You Sure!',
        text: "You want to reject quantity. Please note that it will cancel this item.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?php echo base_url('my-account/rejectQty') ?>",
                    type: 'POST',
                    cache: false,
                    data:{order_item_id:order_item_id,order_id:order_id},
                    dataType: "json",
                    error: function (xhr, status, error) {
                        //alert(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong.'
                        });
                        swal.close();
                        location.reload();
                    },
                    success: function (result) {
                        if (result['error'] == false) {
                            Toast.fire({
                                icon: 'success',
                                title: result['message']
                            });
                            $("#order_state_"+result['order_item_id']).html(result['html']);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result['message']
                            });
                        }
                        swal.close();
                        location.reload();
                    }  
                });
            });
        },
        allowOutsideClick: false
    });
}
</script>