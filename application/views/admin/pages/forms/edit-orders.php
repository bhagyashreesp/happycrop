<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Order</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="transaction_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="user_name">Order Tracking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-info">
                                            <!-- form start -->
                                            <form class="form-horizontal " id="order_tracking_form" action="<?= base_url('seller/orders/update-order-tracking/'); ?>" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="order_id" id="order_id">
                                                <input type="hidden" name="order_item_id" id="order_item_id">
                                                <div class="card-body pad">
                                                    <div class="form-group ">
                                                        <label for="courier_agency">Courier Agency</label>
                                                        <input type="text" class="form-control" name="courier_agency" id="courier_agency" placeholder="Courier Agency" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="tracking_id">Tracking Id</label>
                                                        <input type="text" class="form-control" name="tracking_id" id="tracking_id" placeholder="Tracking Id" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="url">URL</label>
                                                        <input type="text" class="form-control" name="url" id="url" placeholder="URL" />
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button type="submit" class="btn btn-success" id="submit_btn">Save</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-group" id="error_box">
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                        <!--/.card-->
                                    </div>
                                    <!--/.col-md-12-->
                                </div>
                                <!-- /.row -->

                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .shadow {
                        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
                    }

                    .h5,
                    h5 {
                        font-size: 1.0rem;
                        font-family: Poppins, sans-serif;
                    }

                    h6 {
                        font-size: 0.9rem !important;
                        font-family: Poppins, sans-serif;
                    }

                    .act_state {
                        text-align: center;
                    }

                    .billing-info span {
                        font-size: 13px;
                        font-family: Poppins, sans-serif;
                    }

                    .orange_msg {
                        background: #FFD580;
                        display: inline-block;
                        padding: 2px 10px;
                        font-weight: 600;
                        color: #555;
                        border-radius: 5px;
                    }
                </style>

                <div class="col-md-12 ">
                    <input type="hidden" name="hidden" id="order_id" value="<?php echo $order_detls[0]['id']; ?>">
                    <h5 class=" font-weight-bold">Order ID: <?php echo 'HC-A' . $order_detls[0]['id']; ?> - Order Date: <?php echo date('d-M-Y', strtotime($order_detls[0]['date_added'])); ?></h5>
                </div>

                <div class="col-md-12 ">
                    <div class="shadow billing-info p-3 mb-3 bg-white rounded">
                        <h6 class="h5 font-weight-bold"><?= !empty($this->lang->line('retailer_details')) ? $this->lang->line('retailer_details') : 'Retailer Details' ?></h6>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="h6 font-weight-bold">Billing Details</h6>
                                <span>Retailer ID- <?= "HCR" . str_pad($order_detls[0]['ret_no'], 5, "0", STR_PAD_LEFT) ?></span> <br />
                                <span>Retailer Name- <?= $order_detls[0]['company_name'] ?></span> <br />
                                <span>Address- <?= $order_detls[0]['billing_address'] ?></span> <br />
                                <span>GSTIN- <?= $order_detls[0]['r_gst_no'] ?></span> <br />
                                <span>Contact No.- <?= $order_detls[0]['mobile'] ?></span> <br />
                            </div>
                            <div class="col-md-4">
                                <h6 class="h6 font-weight-bold">License Details</h6>
                                <span>Fertilizer License No- <?= $order_detls[0]['fertilizer_license_no'] ?></span> <br />
                                <span>Pesticide License No- <?= $order_detls[0]['pesticide_license_no'] ?></span> <br />
                                <span>Seeds License No- <?= $order_detls[0]['seeds_license_no'] ?></span> <br />
                            </div>
                            <div class="col-md-4">
                                <h6 class="h6 font-weight-bold">Shipping Details</h6>
                                <span>Retailer Name- <?= $order_detls[0]['company_name'] ?></span> <br />
                                <span>Address- <?= $order_detls[0]['address'] ?></span> <br />
                                <span>Contact No.- <?= $order_detls[0]['mobile'] ?></span> <br />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="shadow billing-info p-3 mb-3 bg-white rounded">
                        <h6 class="h5 font-weight-bold">Manufacturer Details</h6>
                        <hr />
                        <?php

                        $this->db->distinct();
                        $this->db->select('a.seller_id, b.username, b.mfg_no, b.mobile, b.email, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
                        $this->db->from('order_items as a');
                        $this->db->join('users as b', 'a.seller_id = b.id', 'left');
                        $this->db->join('seller_data as c', 'a.seller_id = c.user_id', 'left');
                        $this->db->join('states as s', 'c.state_id = s.id', 'left');
                        $this->db->join('cities as cc', 'c.city_id = cc.id', 'left');
                        $this->db->where('a.order_id', $order_detls[0]['id']);
                        $query = $this->db->get();

                        $manufacture = $query->row_array();
                        ?>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <?php /* ?>
                                <h6 class="mb-1">Personal Details</h6>
                                <!--<span>Name: <?= $manufacture['username'] ?></span> <br/>-->
                                <?php */ ?>
                                <h6 class="h6 font-weight-bold">Company Name: <?= $manufacture['company_name'] ?></h6>
                                <h6 class="h6 font-weight-bold">ID: <?= "HCM" . str_pad($manufacture['mfg_no'], 4, "0", STR_PAD_LEFT) ?></h6>
                                <?php /* ?>
                                <!--<span class="font-weight-bold">Email: <?= $manufacture['email'] ?></span><br/>-->
                                <?php */ ?>
                                <span class="">Address: <?= $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin'] ?></span><br />
                                <span class="">GST No.: <?= $manufacture['gst_no'] ?></span> <br />
                                <?php /* ?>
                                <span class="font-weight-bold">Contact No.: <?= $manufacture['mobile'] ?></span> <br/>
                                <?php */ ?>
                            </div>
                            <div class="col-md-4">
                                <h6 class="h6 font-weight-bold">License Details</h6>
                                <span>Fertilizer License No: <?= $manufacture['fertilizer_license_no'] ?></span> <br />
                                <span>Pesticides License No: <?= $manufacture['pesticide_license_no'] ?></span> <br />
                                <span>Seeds License No: <?= $manufacture['seeds_license_no'] ?></span> <br />
                            </div>
                            <div class="col-md-4">
                                <h6 class="h6 font-weight-bold">Bank Details</h6>
                                <span>Acct Name: <?= $manufacture['company_name'] ?></span> <br />
                                <span>Acct No: <?= $manufacture['account_number'] ?></span> <br />
                                <span>Bank Name: <?= $manufacture['bank_name'] ?></span> <br />
                                <span>Bank IFSC: <?= $manufacture['bank_code'] ?></span> <br />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="shadow p-3 mb-3 bg-white rounded">
                        <?php /* ?>
                        <div class="row mb-5">
                            <div class="col-md-12 mb-2">
                                <label class="badge badge-info">Select status and square box of order item which you want to update</label>
                            </div>

                            <div class="col-md-4">
                                <select name="status" class="form-control status">
                                    <option value=''>Select Status</option>
                                    <!--<option value="awaiting_payment">Awaiting Payment</option>
                                    <option value="received">Received</option>
                                    <option value="processed">Processed</option>
                                    <option value="shipped">Shipped</option>
                                    <?php if (get_seller_permission($seller_id, 'view_order_otp') == true) { ?>
                                        <option value="delivered">Delivered</option>
                                    <?php } ?>
                                    <option value="cancelled">Cancel</option>
                                    <option value="returned">Returned</option>-->
                                    
                                    <option value="payment_demand" >Payment Demand</option>
                                    <option value="schedule_delivery">Schedule Delivery</option>
                                    
                                    <option value="processed">Processed</option>
                                    <option value="shipped">Shipped</option>
                                    <?php if (get_seller_permission($seller_id, 'view_order_otp') == true) { ?>
                                        <option value="delivered">Delivered</option>
                                    <?php } ?>
                                    <option value="returned">Return</option>
                                    <option value="cancelled">Cancel</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <a href="javascript:void(0);" title="Bulk Update" class="btn btn-primary col-sm-12 col-md-12 update_status_admin_bulk mr-1">
                                    Bulk Update
                                </a>
                            </div>
                        </div>
                        <?php */ ?>
                        <table class="table table-bordered">
                            <tr class="bg-primary text-white">
                                <?php /* ?>
                                <th></th>
                                <?php */ ?>
                                <th style="width: 20%;">Name</th>
                                <th style="width: 12%;">Size</th>
                                <th style="width: 9%;">Order size</th>
                                <th style="width: 14%;">Order Volume</th>
                                <th style="width: 5%;">GST%</th>
                                <th>MRP</th>
                                <th>Std Price</th>
                                <th>Disc Price</th>
                                <th>Subtotal</th>
                                <th>HC Service Charges</th>
                                <?php /* ?>
                                <th style="width: 15%;">Active Status</th>
                                <th style="width: 15%;">Change Status</th>
                                <?php */ ?>
                            </tr>
                            <?php $total = 0;
                            $tax_amount = 0;
                            $i = 0;
                            $service_total = 0;
                            foreach ($items as $item) {


                                $selected = "";

                                /*if($item['active_status'] !='cancelled')
                                {*/
                                $item['discounted_price'] = ($item['discounted_price'] == '') ? 0 : $item['discounted_price'];
                                $total += $subtotal = ($item['quantity'] != 0 && ($item['discounted_price'] != '' && $item['discounted_price'] > 0) && $item['price'] > $item['discounted_price']) ? ($item['price'] - $item['discounted_price']) : ($item['price'] * $item['quantity']);
                                $tax_amount += $item['tax_amount'];
                                $total += $subtotal = $tax_amount;
                                //}

                            ?>
                                <tr id="row_<?php echo $item['id']; ?>">
                                    <?php /* ?>
                                    <td class="check_<?php echo $item['id'];?>">
                                        <?php 
                                        if(strtolower($item['active_status']) != 'qty_update') 
                                        { 
                                            ?>
                                            <input type="checkbox" name="order_item_id[]" value=' <?= $item['id'] ?> '>
                                            <?php
                                        }  
                                        ?>
                                    </td>
                                    <?php */ ?>
                                    <td>
                                        <p><?= $item['pname'] ?><span style="font-size:10px;font-weight: 500;"><?php echo ($item['mfg_date'] != null) ? '<br/>MFG Dt: ' . date('d/m/Y', strtotime($item['mfg_date'])) : ''; ?><?php echo ($item['exp_date'] != null) ? '&nbsp;&nbsp; EXP Dt: ' . date('d/m/Y', strtotime($item['exp_date'])) : ''; ?><?php echo ($item['batch_no'] != null) ? '<br/>Batch No: ' . $item['batch_no'] : ''; ?></span></p>
                                    </td>
                                    <td>
                                        <?php //echo $item['packing_size'].' '.$item['unit']; echo ($item['carton_qty'] > 1) ? ' &#x2715; '.$item['carton_qty'] : '&#x2715; 1'; 
                                        ?>
                                        <?php
                                        $packing_size = ($item['packing_size'] != '') ? $item['packing_size'] : $item['pv_packing_size'];
                                        $unit = ($item['unit'] != '') ? $item['unit'] : $item['pv_unit'];
                                        $car_qty = ($item['carton_qty']) ? $item['carton_qty'] : $item['pv_carton_qty'];
                                        echo $packing_size . ' ' . $unit;
                                        echo ($car_qty > 1) ? ' &#x2715; ' . $car_qty : ' &#x2715; 1';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($item['active_status'] != 'cancelled' && $item['active_status'] != 'delivered') { ?>
                                            <div class="input-group">
                                                <?= $item['quantity'] ?>
                                                <?php /* ?>
                                            <input type="hidden" id="quantity_<?= $i ?>" name="quantity_<?= $i ?>" value="<?= $item['quantity'] ?>" style="width: 80px;" onkeyup="calPrice(this.value, <?= $item['price'] + $item['tax_amount'] ?>, <?php echo $i; ?>)" data-order-id="<?php echo $order_detls[0]['id'];?>" data-order-item-id="<?php echo $item['id']; ?>" />
                                            <input type="hidden" name="order_item_ids[]" value="<?php echo $item['id']; ?>" />
                                            <?php */ ?>
                                            </div>
                                        <?php } else { ?>
                                            <?= $item['quantity'] ?>
                                            <?php /* ?>
                                        <input type="hidden" id="quantity_<?= $i ?>" name="quantity_<?= $i ?>" value="<?= $item['quantity'] ?>" style="width: 80px;" readonly=""/>
                                        <?php */ ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php /* ?>
                                        <div class="input-group">
                                            <input type="text" id="volume_<?= $i ?>" name="volume_<?= $i ?>" value="<?php echo ($item['packing_size']*$item['carton_qty']*$item['quantity']);?>" style="width: 90px;" readonly="" />
                                            <input type="hidden" id="packing_size_<?= $i ?>" name="packing_size_<?= $i ?>" value="<?php echo $item['packing_size'];?>"/>
                                            <input type="hidden" id="carton_qty_<?= $i ?>" name="carton_qty_<?= $i ?>" value="<?php echo $item['carton_qty'];?>"/>
                                            <span class="input-group-text" style="font-size: 12px;"><?php echo $item['unit']; ?></span>
                                        </div>
                                        <?php */ ?>
                                        <?php //echo ($item['packing_size']*$item['carton_qty']*$item['quantity']);
                                        ?> <?php //echo $item['unit']; 
                                            ?>
                                        <?php echo $packing_size * $car_qty * $item['quantity'] . ' ' . $unit; ?>
                                    </td>
                                    <td><?php echo $item['tax_percentage']; ?></td>
                                    <td>
                                        <?php /* ?>
                                        <input type="text" id="mrp_<?= $i ?>" name="mrp_<?= $i ?>" value="<?= $item['mrp'] ?>" style="width: 100px;" readonly="" />
                                        <?php */ ?>
                                        <?= $item['mrp'] ?>
                                    </td>
                                    <td>
                                        <?php /* ?>
                                        <input type="text" id="standard_price_<?= $i ?>" name="standard_price_<?= $i ?>" value="<?= $item['standard_price'] + $item['tax_amount'] ?>" style="width: 100px;" readonly="" />
                                        <?php */ ?>
                                        <?= $item['standard_price'] + $item['tax_amount'] ?>
                                    </td>
                                    <td>
                                        <?php /* ?>
                                        <input type="text" id="price_<?= $i ?>" name="price_<?= $i ?>" value="<?= $item['price'] + $item['tax_amount'] ?>" style="width: 100px;" readonly="" />
                                        <?php */ ?>
                                        <?= $item['price'] + $item['tax_amount'] ?>
                                    </td>

                                    <td>
                                        <?php /* ?>
                                        <input class="<?php echo ($item['active_status'] !='cancelled') ? 'sub_total' : ''; ?>" type="text" id="sub_total_<?= $i ?>" name="sub_total_<?= $i ?>" value="<?= $item['price'] * $item['quantity'] ?>" style="width: 120px;" readonly="" />
                                        <?php */ ?>
                                        <?= $item['price'] * $item['quantity'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        $service_charges = 0;
                                        if (round($item['special_price_per_item']) > 0) {
                                            $service_charges = ((($item['mrp']) - ($item['special_price_per_item'] * $car_qty)) * $item['quantity']) * 10 / 100;
                                            $service_total += $service_charges;
                                        }
                                        ?>
                                        <input type="hidden" id="special_price_per_item_<?= $i ?>" value="<?php echo $item['special_price_per_item']; ?>" />
                                        <?php echo $service_charges; ?>
                                    </td>
                                    <?php /* ?>
                                    <td>
                                        <div class="act_state">
                                            <?php
                                            if(strtolower($item['active_status']) == 'payment_ack') 
                                            {
                                                echo strtolower($item['active_status']).'<br/>';
                                                $order_item_payment_demands = $this->db->get_where('order_item_payment_demand', array('order_item_id'=>$item['id']))->result_array();
                                                if($order_item_payment_demands)
                                                {
                                                    $i_count = 1;
                                                    foreach($order_item_payment_demands as $order_item_payment_demand)
                                                    {
                                                        if(file_exists($order_item_payment_demand['attachments']) && $order_item_payment_demand['attachments'])
                                                        {
                                                            ?>
                                                            <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url().$order_item_payment_demand['attachments'];?>" target="_blank">Receipt <?php echo $i_count; ?></a><br />
                                                            <?php
                                                            $i_count++;
                                                        }
                                                    }
                                                }
                                            }
                                            else if(strtolower($item['active_status']) == 'schedule_delivery') 
                                            {
                                                echo strtolower($item['active_status']);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <div class="input-group">
                                                            <input class="form-control datepicker input-sm" type="date" id="schedule_delivery_date_<?php echo $item['id']; ?>" name="schedule_delivery_date_<?php echo $item['id']; ?>" value="<?php echo ($item['schedule_delivery_date']!=null) ? $item['schedule_delivery_date'] : ''; ?>" placeholder="DD/MM/YYYY" style="width: 150px;" />
                                                            <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">
                                                                <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="basic-addon2">
                                                                    <button type="button" onclick="send_delivery_schedule(<?php echo $item['id']; ?>,<?= $order_detls[0]['id'] ?>);" title="Update Delivery Date" class="btn btn-primary btn-xs mr-1">
                                                                        <i class="far fa-save"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>                                                                        
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            else if(strtolower($item['active_status']) == 'shipped') 
                                            {
                                                ?>
                                                <form class="form-horizontal " id="send_invoice_form" action="<?= base_url('seller/orders/send_invoice/'); ?>" method="POST" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 mb-0 text-center">
                                                            <label>Send Invoice</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group mb-0 col-md-6">
                                                            <label class="btn btn-warning btn-sm btn-block" for="invoice">Browse</label>
                                                            <div class="custom-file-input" style="margin-top: -30px;">
                                                                <input type="file" class="form-control" name="attachments[]" id="invoice" style="padding:0px;min-height: 28px;" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-0 col-md-6">
                                                            <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">
                                                            <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                                            <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_invoice_btn">Send</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?php 
                                            }
                                            else
                                            {
                                                echo strtolower($item['active_status']);
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row mb-1 mt-1 order_item_status it_state_<?php echo $item['id'];?>">
                                            <?php 
                                            if(strtolower($item['active_status']) == 'qty_update' || ($item['active_status'] =='cancelled')) 
                                            { 
                                                ?>
                                                
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <div class="col-md-7 text-center">
                                                    <select class="form-control-sm w-100">
                                                        <option value="">Select</option>
                                                        <option value="payment_demand" <?= (strtolower($item['active_status']) == 'payment_demand') ? 'selected' : '' ?>>Payment Demand</option>
                                                        <option value="schedule_delivery" <?= (strtolower($item['active_status']) == 'schedule_delivery') ? 'selected' : '' ?>>Schedule Delivery</option>
                                                        
                                                        <option value="processed" <?= (strtolower($item['active_status']) == 'processed') ? 'selected' : '' ?>>Processed</option>
                                                        <option value="shipped" <?= (strtolower($item['active_status']) == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                                        <?php if (get_seller_permission($seller_id, 'view_order_otp') == true) { ?>
                                                            <option value="delivered" <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                                        <?php } ?>
                                                        <option value="returned" <?= (strtolower($item['active_status']) == 'returned') ? 'selected' : '' ?>>Return</option>
                                                        <option value="cancelled" <?= (strtolower($item['active_status']) == 'cancelled') ? 'selected' : '' ?>>Cancel</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-5 d-flex align-items-center">
                                                    <a href="javascript:void(0);" title="Update status" data-id=' <?= $item['id'] ?> ' class="btn btn-primary btn-xs update_status_admin mr-1">
                                                        <i class="far fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            
                                        </div>
                                    </td>
                                    <?php */ ?>
                                </tr>
                            <?php
                                $i++;
                            }
                            ?>
                            <tr>
                                <th colspan="8" class="text-right">Total</th>
                                <th>
                                    <input type="hidden" id="total_products" name="total_products" value="<?php echo $i; ?>" />
                                    <?php /* ?>
                                    <input type="text" id="order_total" name="order_total" value="<?php echo $total; ?>" style="width: 120px;" readonly="" />
                                    <?php */ ?>
                                    <?php echo $total; ?>
                                </th>
                                <th>
                                    <?php echo $service_total; ?>
                                </th>
                            </tr>
                        </table>
                        <style>
                            .table.table-borderless td,
                            .table.table-borderless th {
                                padding: .5rem;
                            }

                            .table-borderless tr {
                                border-bottom: 1px solid #dee2e6;
                            }
                        </style>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bg-white rounded">
                                    <h6 class="h4 font-weight-bold">Payable Balance Details</h6>
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td>Total amount receivable from retailer (Invoice value)</td>
                                                    <td width="21%"><?php echo $total; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Happycrop service charges</td>
                                                    <td><?php echo $service_total; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Payable Amount (inc GST)</th>
                                                    <th><?php echo $total - $service_total; ?></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div class="row">
                            <div class="col-md-1">
                            
                            </div>
                            <div class="col-md-2">
                                <label>Confirm Quantity</label>
                                <?php
                                if($order_detls[0]['oi_active_status'] == 'received')
                                {
                                    ?>
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="confirm_order_qty(<?php echo $order_detls[0]['id']; ?>);">Confirm</a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <button class="btn btn-primary" disabled="true">Confirm</button>
                                    <?php
                                }  
                                ?>
                            </div>
                            <div class="col-md-4">
                                <label>Schedule Delivery Date</label>
                                <div class="">
                                    <form class="form-horizontal " id="schedule_delivery_form" action="<?= base_url('seller/orders/send_delivery_schedule'); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <input class="form-control datepicker" type="date" id="schedule_delivery_date" name="schedule_delivery_date" value="<?php echo ($order_detls[0]['schedule_delivery_date'] !=null && $order_detls[0]['schedule_delivery_date'] != '0000-00-00') ?  date('Y-m-d', strtotime($order_detls[0]['schedule_delivery_date'])) : ''; ?>" placeholder="DD/MM/YYYY" required="" min="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d', strtotime($order_detls[0]['date_added'].' +20 days')); ?>" />
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="hidden" name="order_item_id" value=""/>
                                                <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>"/>
                                                <?php
                                                if($order_detls[0]['oi_active_status'] == 'received')
                                                {
                                                    ?>
                                                    <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Save</button>
                                                    <?php
                                                }  
                                                else
                                                {
                                                    ?>
                                                    <button type="button" disabled="" class="btn btn-primary btn-block" id="button">Save</button>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </form>
                                    <p><small class="text-danger p-1 bg-red font-weight-bold" style="color: #fff !important;">Note: delivery date should not beyond 20 days from the date of order.</small></p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php
                                if($order_detls[0]['oi_active_status'] == 'schedule_delivery')
                                {
                                    ?>
                                    <label>Send payment request</label>
                                    <div class="">
                                        <a class="btn btn-primary btn-block2" href="javascript:void(0);" onclick="requestPayment(<?= $order_detls[0]['id'] ?>);">Send</a>
                                    </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <label>Send payment request</label>
                                    <div class="">
                                        <button class="btn btn-primary" disabled="true">Send</button>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-md-1">
                                <label style="padding: 25px 10px;">Or</label>
                            </div>
                            <div class="col-md-2">
                                <br />
                                <?php
                                if($order_detls[0]['oi_active_status'] == 'received' || $order_detls[0]['oi_active_status'] == 'schedule_delivery' || $order_detls[0]['oi_active_status'] == 'payment_demand')
                                {
                                    ?>
                                    <a class="btn btn-primary btn-block2" href="javascript:void(0);" onclick="out_of_stock(<?php echo $order_detls[0]['id']; ?>)">Out of Stock</a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <button class="btn btn-primary" disabled="true">Out of Stock</button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php */ ?>

                    </div>
                </div>
                <div class="card-body">
                    <?php
                    if ($order_detls[0]['is_service_category']) {
                        $order = $order_detls[0];
                        $order_id = $order['id'];

                        $state_array = array('received', 'qty_update', 'schedule_delivery', 'shipped', 'send_mfg_payment_ack', 'send_mfg_payment_confirmation', 'issue_resolved');

                        $this->db->select('a.*, b.active_status, b.product_name, b.schedule_delivery_date');
                        $this->db->from('order_item_stages as a');
                        $this->db->join('order_items as b', 'a.order_item_id = b.id', 'left');
                        $this->db->where('a.order_id', $order_id);
                        $this->db->where_not_in('a.status', $state_array);
                        $query = $this->db->get();
                        $order_item_stages = $query->result_array();

                    ?>
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="shadow w-90 p-3 mb-5 bg-white rounded">
                                    <h6 class="h4">Order Stages Details</h6>
                                    <hr />
                                    <div class="tmln">
                                        <div class="tmln-outer">
                                            <div class="tmln-card">
                                                <div class="tmln-info">
                                                    <h3 class="tmln-title">Order placed. <small class="float-right font-weight-normal"><?php echo date('d M Y h:i A', strtotime($order['date_added'])); ?></small></h3>
                                                </div>
                                            </div>

                                            <?php

                                            if ($order_item_stages) {
                                                $s_count = 0;
                                                foreach ($order_item_stages as $order_item_stage) {
                                                    $s_count++;
                                            ?>
                                                    <div class="tmln-card">
                                                        <div class="tmln-info">
                                                            <?php
                                                            if ($order_item_stage['status'] == 'service_completed') { ?>
                                                                <h3 class="tmln-title">Service completed. <small class="float-right font-weight-normal"><?php echo date('d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                <small>
                                                                    <ul>
                                                                        <li>Status shared with Happycrop and manufacturer.</li>
                                                                        <li>Service completed.</li>
                                                                    </ul>
                                                                </small>
                                                            <?php }  ?>
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
                    <?php

                    } else {
                        $order_id = $order_detls[0]['id'];

                        $state_array = array('received', 'qty_update', 'schedule_delivery', 'payment_demand', 'shipped'); //

                        /*if($order_detls[0]['oi_active_status'] == 'schedule_delivery')
                    {
                        $state_array = array('received','qty_update', 'payment_demand', 'shipped');//
                    }
                    else*/
                        if ($order_detls[0]['oi_active_status'] == 'payment_demand') {
                            $state_array = array('received', 'qty_update', 'schedule_delivery', 'shipped'); //
                        }

                        $this->db->select('a.*, b.active_status, b.product_name, b.schedule_delivery_date');
                        $this->db->from('order_item_stages as a');
                        $this->db->join('order_items as b', 'a.order_item_id = b.id', 'left');
                        $this->db->where('a.order_id', $order_id);
                        //$this->db->where('a.status !=', 'received');
                        $this->db->where_not_in('a.status', $state_array);
                        $query = $this->db->get();
                        $order_item_stages = $query->result_array();
                        //echo $this->db->last_query();

                    ?>
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="shadow p-3 mb-5 bg-white rounded">
                                    <?php ?>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h6 class="h5">Order Stages Details</h6>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="btn-group float-right">
                                                <div class="btn-wrap show-code-action">
                                                    <?php /*//if($order_detls[0]['status'] !='delivered') { ?> 
                                                    <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="requestPayment(<?php echo $order_id;?>);">Request Payment</a>
                                                    <?php //}*/ ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php ?>
                                    <hr />
                                    <div class="tmln">
                                        <div class="tmln-outer">
                                            <div class="tmln-card">
                                                <div class="tmln-info">
                                                    <h3 class="tmln-title mb-0">Order placed. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_detls[0]['date_added'])); ?></small></h3>
                                                    <small class="mb-0">- Waiting for updates from manufacturer.</small>
                                                </div>
                                            </div>

                                            <?php
                                            /*if($order_detls[0]['oi_active_status']=='received')
                                            {
                                                ?>
                                                    
                                                <div class="tmln-card">
                                                    <div class="tmln-info">
                                                        <h3 class="tmln-title">Schedule Delivery Date</h3>
                                                        <div class="">
                                                            <form class="form-horizontal " id="schedule_delivery_form" action="<?= base_url('seller/orders/send_delivery_schedule'); ?>" method="POST" enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="form-group col-md-3">
                                                                        <input class="form-control datepicker" type="date" id="schedule_delivery_date" name="schedule_delivery_date" value="" placeholder="DD/MM/YYYY" required="" />
                                                                    </div>
                                                                    <div class="form-group col-md-3">
                                                                        <input type="hidden" name="order_item_id" value=""/>
                                                                        <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>"/>
                                                                        <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }*/
                                            ?>
                                            <?php
                                            $_count = 0;
                                            if ($order_item_stages) {
                                                foreach ($order_item_stages as $order_item_stage) {
                                                    $_count++;
                                            ?>
                                                    <div class="tmln-card">
                                                        <div class="tmln-info">
                                                            <!--<h3 class="tmln-title"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></h3>-->
                                                            <?php if ($order_item_stage['status'] == 'qty_update') { ?>
                                                                <?php
                                                                if ((int)$order_item_stage['order_item_id'] != 0) {
                                                                ?>
                                                                    <p><span class="stage_prod_name"><?php echo $order_item_stage['product_name']; ?></span> Quantity updated and approval request sent to retailer.</p>
                                                                    <?php
                                                                } else {
                                                                    $order_item_ids = array();
                                                                    $order_item_ids = explode(',', $order_item_stage['ids']);

                                                                    $this->db->select('a.*');
                                                                    $this->db->from('order_items as a');
                                                                    $this->db->where_in('a.id', $order_item_ids);
                                                                    $query = $this->db->get();

                                                                    $order_items_rows = $query->result_array();

                                                                    if ($order_items_rows) {
                                                                    ?>
                                                                        <p>
                                                                            <?php
                                                                            $pr = 0;
                                                                            foreach ($order_items_rows as $order_items_row) {
                                                                            ?>
                                                                                <span class="stage_prod_name"><?php echo $order_items_row['product_name']; ?></span>
                                                                            <?php
                                                                                $pr++;
                                                                                if ($pr < count($order_items_rows)) {
                                                                                    echo ", ";
                                                                                }
                                                                            }
                                                                            ?>
                                                                            Quantity updated and approval request sent to retailer.
                                                                        </p>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            <?php } else if ($order_item_stage['status'] == 'qty_approved') { ?>
                                                                <?php
                                                                if ((int)$order_item_stage['order_item_id'] != 0) {
                                                                ?>
                                                                    <p><span class="stage_prod_name"><?php echo $order_item_stage['product_name']; ?></span> Quantity approval accepted by retailer.</p>
                                                                    <?php
                                                                } else {
                                                                    $order_item_ids = array();
                                                                    $order_item_ids = explode(',', $order_item_stage['ids']);

                                                                    $this->db->select('a.*');
                                                                    $this->db->from('order_items as a');
                                                                    $this->db->where_in('a.id', $order_item_ids);
                                                                    $query = $this->db->get();

                                                                    $order_items_rows = $query->result_array();

                                                                    if ($order_items_rows) {
                                                                    ?>
                                                                        <p>
                                                                            <?php
                                                                            $pr = 0;
                                                                            foreach ($order_items_rows as $order_items_row) {
                                                                            ?>
                                                                                <span class="stage_prod_name"><?php echo $order_items_row['product_name']; ?></span>
                                                                            <?php
                                                                                $pr++;
                                                                                if ($pr < count($order_items_rows)) {
                                                                                    echo ", ";
                                                                                }
                                                                            }
                                                                            ?>
                                                                            Quantity approval accepted by retailer.
                                                                        </p>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>

                                                                <?php
                                                                if (count($order_item_stages) == $_count) {
                                                                ?>
                                                        </div>
                                                    </div>
                                                    <div class="tmln-card">
                                                        <div class="tmln-info">
                                                            <h3 class="tmln-title">Schedule Delivery Date</h3>
                                                            <div class="">
                                                                <form class="form-horizontal " id="schedule_delivery_form" action="<?= base_url('seller/orders/send_delivery_schedule'); ?>" method="POST" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-3">
                                                                            <input class="form-control datepicker" type="date" id="schedule_delivery_date" name="schedule_delivery_date" value="" placeholder="DD/MM/YYYY" required="" />
                                                                        </div>
                                                                        <div class="form-group col-md-3">
                                                                            <input type="hidden" name="order_item_id" value="<?= $order_item_stage['order_item_id'] ?>">
                                                                            <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                            <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        <?php
                                                                }
                                                        ?>

                                                    <?php } else if ($order_item_stage['status'] == 'qty_rejected') { ?>
                                                        <h3 class="tmln-title">Order cancelled. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <small>- Order Closed</small>
                                                        <?php
                                                                /*if((int)$order_item_stage['order_item_id']!=0)
                                                        {
                                                            ?>
                                                            <p><span class="stage_prod_name"><?php echo $order_item_stage['product_name']; ?></span> Quantity approval rejected by retailer.</p>
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
                                                                Quantity approval rejected by retailer.
                                                                </p>
                                                                <?php
                                                            }
                                                        }*/
                                                        ?>
                                                    <?php } else if ($order_item_stage['status'] == 'schedule_delivery') { ?>
                                                        <h3 class="tmln-title mb-0">Confirmed / updated qty and delivery schedule sent. <small class="mb-0">- Waiting for payment from retailer.</small> <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <?php
                                                                /*
                                                            if($order_detls[0]['schedule_delivery_date']!=null && $order_detls[0]['schedule_delivery_date'] != '0000-00-00')
                                                            {
                                                                echo "<p>Delivery Scheduled ON ".date('d/m/Y',strtotime($order_detls[0]['schedule_delivery_date'])).'</p>';
                                                            }
                                                            
                                                            if(count($order_item_stages) == $_count)
                                                            {
                                                                ?>
                                                                    </div>
                                                                </div>
                                                                <div class="tmln-card">
                                                                    <div class="tmln-info">
                                                                        <h3 class="tmln-title">Request for the Payment</h3>
                                                                        <div class="">
                                                                            <a class="btn btn-sm btn-primary" href="javascript:void(0);" onclick="requestPayment(<?= $order_item_stage['order_id'] ?>);">Yes</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }*/
                                                        ?>
                                                    <?php } else if ($order_item_stage['status'] == 'payment_demand') { ?>
                                                        <h3 class="tmln-title mb-0">Manufacturer reviewed the order. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <small>
                                                            <ul>
                                                                <li>For order details please check order table</li>
                                                                <li>Tentative delivery date <?php echo date('d/m/Y', strtotime($order_detls[0]['schedule_delivery_date'])); ?></li>
                                                                <li>Payment requested by manufacturer is <?php echo $settings['currency'] . ' ' . number_format($order_detls[0]['final_total'], 2); ?>/-</li>
                                                                <li>Waiting for retailer response.</li>
                                                            </ul>
                                                        </small>
                                                    <?php } else if ($order_item_stage['status'] == 'payment_ack') { ?>
                                                        <h3 class="tmln-title">Transaction details received from retailer. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <?php
                                                                $ids = explode(',', $order_item_stage['ids']);
                                                                if ($ids) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_payment_demand');
                                                                    $this->db->where_in('id', $ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_payment_demands = $query->result_array();

                                                                    //$order_item_payment_demands = $this->db->get_where('order_item_payment_demand', array('order_item_id'=>$order_item_id))->result_array();
                                                                    if ($order_item_payment_demands) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_payment_demands as $order_item_payment_demand) {
                                                                            if (file_exists($order_item_payment_demand['attachments']) && $order_item_payment_demand['attachments']) {
                                                        ?>
                                                                        <a class="btn btn-sm col-md-3 btn-primary mb-1" href="<?php echo base_url() . $order_item_payment_demand['attachments']; ?>" target="_blank">Download the transaction details</a><br />
                                                        <?php
                                                                                $i_count++;
                                                                            }
                                                                        }
                                                                    }
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
                                                        <?php //} else if($order_item_stage['status']=='payment_ack') { 
                                                        ?>
                                                        <?php
                                                                /*if($order_detls[0]['schedule_delivery_date']!=null && $order_detls[0]['schedule_delivery_date'] != '0000-00-00')
                                                            {
                                                                echo "<p>Delivery Scheduled ON ".date('d/m/Y',strtotime($order_detls[0]['schedule_delivery_date'])).'</p>';
                                                            }*/

                                                                if (count($order_item_stages) == $_count) {
                                                        ?>
                                                            <!--</div>
                                                                </div>
                                                                <div class="tmln-card">
                                                                    <div class="tmln-info">-->
                                                            <p>Please send confirmation of payment.</p>
                                                            <a href="<?= base_url('my-account/payment-receipt/') . $order_detls[0]['id'] . "/view" ?>" target="_blank" class="btn btn-primary btn-sm btn-block w-25">View Payment Receipt</a>
                                                            <form class="form-horizontal " id="send_payment_confirmation_form" action="<?= base_url('admin/orders/send_payment_confirmation/'); ?>" method="POST" enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="form-group mb-0 col-md-2">
                                                                        <label class="btn btn-warning btn-sm btn-block" for="payment_confirmation">Select file</label>
                                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                                            <input type="file" class="form-control" name="attachments[]" id="payment_confirmation" style="padding:0px;min-height: 28px;" required="" onchange="$('#f1_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                                        </div>
                                                                        <p class=""><span id="f1_text"></span></p>
                                                                    </div>
                                                                    <div class="form-group mb-0 col-md-3">
                                                                        <input type="hidden" name="order_item_id" value="">
                                                                        <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                                                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_payment_confirmation_btn">Upload the payment receipt</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <?php /* ?>
                                                                        <h3 class="tmln-title">Is Order Shipped ?</h3>
                                                                        <div class="">
                                                                            <form class="form-horizontal " id="shipped_delivery_form" action="<?= base_url('seller/orders/send_order_status'); ?>" method="POST" enctype="multipart/form-data">
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-1">
                                                                                        <input type="hidden" name="status" value="shipped" />
                                                                                        <input type="hidden" name="order_item_id" value="<?= $order_item_stage['order_item_id'] ?>">
                                                                                        <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                        <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Yes</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <?php */ ?>
                                                            <!--</div>
                                                                </div>-->
                                                        <?php
                                                                }
                                                        ?>
                                                    <?php } else if ($order_item_stage['status'] == 'send_payment_confirmation') { ?>
                                                        <h3 class="tmln-title">Payment confirmation sent to retailer and manufacturer. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <?php
                                                                $ids = explode(',', $order_item_stage['ids']);
                                                                if ($ids) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_payment_confirmation');
                                                                    $this->db->where_in('id', $ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_payment_confirmations = $query->result_array();


                                                                    if ($order_item_payment_confirmations) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_payment_confirmations as $order_item_payment_confirmation) {
                                                                            if (file_exists($order_item_payment_confirmation['attachments']) && $order_item_payment_confirmation['attachments']) {
                                                        ?><a class="btn btn-sm col-md-3 btn-primary mb-1" href="<?php echo base_url() . $order_item_payment_confirmation['attachments']; ?>" target="_blank">Download Confirmation</a><?php
                                                                                                                                                                                                                                        $i_count++;
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                }
                                                                                                                                                                                                                            }
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
                                                                <p class=""><small class="text-danger p-1 bg-red font-weight-bold" style="color: #fff !important;">Note – The order need to be delivered within scheduled date.</small></p>
                                                                <?php */ ?>
                                                        <?php
                                                                /*if(count($order_item_stages) == $_count)
                                                                {
                                                                    ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tmln-card">
                                                                        <div class="tmln-info">
                                                                            <h3 class="tmln-title">Is order dispatched ?</h3>
                                                                            <div class="">
                                                                                <form class="form-horizontal " id="shipped_delivery_form" action="<?= base_url('seller/orders/send_order_status'); ?>" method="POST" enctype="multipart/form-data">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-1">
                                                                                            <input type="hidden" name="status" value="shipped" />
                                                                                            <input type="hidden" name="order_item_id" value="<?= $order_item_stage['order_item_id'] ?>">
                                                                                            <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                            <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Yes</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }*/
                                                        ?>
                                                        <?php
                                                                /*if(count($order_item_stages) == $_count)
                                                                {
                                                                    ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tmln-card">
                                                                        <div class="tmln-info">
                                                                            <h3 class="tmln-title">Send E-way bill and Invoice</h3>
                                                                            <form class="form-horizontal " id="send_invoice_form" action="<?= base_url('seller/orders/send_invoice/'); ?>" method="POST" enctype="multipart/form-data">
                                                                                <div class="row">
                                                                                    <div class="form-group mb-0 col-md-2">
                                                                                        <label class="btn btn-warning btn-sm btn-block" for="e_way_bill">Browse E-way bill</label>
                                                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                                                            <input type="file" class="form-control" name="attachments2[]" id="e_way_bill" style="padding:0px;min-height: 28px;" required="" onchange="$('#f2_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                                                        </div>
                                                                                        <p class=""><span id="f2_text"></span></p>
                                                                                    </div>
                                                                                    <div class="form-group mb-0 col-md-2">
                                                                                        <label class="btn btn-warning btn-sm btn-block" for="invoice">Browse Invoice</label>
                                                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                                                            <input type="file" class="form-control" name="attachments[]" id="invoice" style="padding:0px;min-height: 28px;" required="" onchange="$('#f3_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                                                        </div>
                                                                                        <p class=""><span id="f3_text"></span></p>
                                                                                    </div>
                                                                                    <div class="form-group mb-0 col-md-2">
                                                                                        <input type="hidden" name="order_item_id" value="">
                                                                                        <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                                                                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_invoice_btn">Send</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                    <?php
                                                                }*/
                                                        ?>
                                                    <?php } else if ($order_item_stage['status'] == 'shipped') { ?>
                                                        <p>Order dispatch date is <?php echo date('d/m/Y', strtotime($order_item_stage['created_date'])); ?>.</p>

                                                    <?php } else if ($order_item_stage['status'] == 'send_invoice') { ?>
                                                        <h3 class="tmln-title">Order is in transit, E-way bill and invoices received from manufacturer. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <div class="btn-group">
                                                            <?php
                                                                $ids2 = explode(',', $order_item_stage['ids2']);
                                                                if ($ids2) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_eway_bill');
                                                                    $this->db->where_in('id', $ids2);
                                                                    $query = $this->db->get();
                                                                    $order_item_eway_bills = $query->result_array();

                                                                    if ($order_item_eway_bills) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_eway_bills as $order_item_eway_bill) {
                                                                            if (file_exists($order_item_eway_bill['attachments']) && $order_item_eway_bill['attachments']) {
                                                            ?>
                                                                            <div class="btn-wrap show-code-action mb-0">
                                                                                <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url() . $order_item_eway_bill['attachments']; ?>" target="_blank">Download E-way Bill</a>
                                                                            </div>
                                                            <?php
                                                                                $i_count++;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            ?>

                                                            <?php
                                                                $ids = explode(',', $order_item_stage['ids']);
                                                                if ($ids) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_invoice');
                                                                    $this->db->where_in('id', $ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_invoices = $query->result_array();

                                                                    //$order_item_invoices = $this->db->get_where('order_item_invoice', array('order_item_id'=>$order_item_id))->result_array();
                                                                    if ($order_item_invoices) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_invoices as $order_item_invoice) {
                                                                            if (file_exists($order_item_invoice['attachments']) && $order_item_invoice['attachments']) {
                                                            ?>
                                                                            <div class="btn-wrap show-code-action mb-0 ml-2">
                                                                                <a class="btn btn-sm btn-primary mb-1" href="<?php echo base_url() . $order_item_invoice['attachments']; ?>" target="_blank">Download Invoice</a>
                                                                            </div>
                                                            <?php
                                                                                $i_count++;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            ?>
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
                                                        <?php
                                                                /*if(count($order_item_stages) == $_count)
                                                                {
                                                                    ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tmln-card">
                                                                        <div class="tmln-info">
                                                                            <h3 class="tmln-title">Is Order Delivered ?</h3>
                                                                            <div class="">
                                                                                <form class="form-horizontal " id="shipped_delivery_form" action="<?= base_url('seller/orders/send_order_status'); ?>" method="POST" enctype="multipart/form-data">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-1">
                                                                                            <input type="hidden" name="status" value="delivered" />
                                                                                            <input type="hidden" name="order_item_id" value="">
                                                                                            <input type="hidden" name="order_id" value="<?= $order_item_stage['order_id'] ?>">
                                                                                            <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Yes</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }*/
                                                        ?>
                                                    <?php } else if ($order_item_stage['status'] == 'complaint') { ?>
                                                        <h3 class="tmln-title">Retailer raised his concern. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <table class="table table-bordered">
                                                            <tr class="bg-primary text-white">
                                                                <th style="width: 8%;">Sr. No.</th>
                                                                <th>Retailer Concern</th>
                                                                <th style="width: 18%;">Image</th>
                                                            </tr>
                                                            <?php
                                                                $ids = explode(',', $order_item_stage['ids']);
                                                                if ($ids) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_complaints');
                                                                    $this->db->where_in('id', $ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_complaints = $query->result_array();
                                                                    //echo $this->db->last_query();die;

                                                                    if ($order_item_complaints) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_complaints as $order_item_complaint) {

                                                            ?>
                                                                        <tr class="bg-white text-dark">
                                                                            <td class="text-center"><?php echo $i_count; ?></td>
                                                                            <td><?php echo $order_item_complaint['concern']; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if (file_exists($order_item_complaint['attachments']) && $order_item_complaint['attachments']) {
                                                                                ?>
                                                                                    <a href="<?php echo base_url() . $order_item_complaint['attachments']; ?>" target="_blank">
                                                                                        <img src="<?php echo base_url() . $order_item_complaint['attachments']; ?>" alt="" style="width: 100px;" />
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
                                                        <?php if (count($order_item_stages) == $_count) { ?>
                                                            <form class="form-horizontal " id="send_complaint_msg_form" action="<?= base_url('admin/orders/msg-about-complaint'); ?>" method="POST" enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="form-group col-md-3">
                                                                        <label class="" for="concern">Write message about issue</label>
                                                                        <div class="" style="">
                                                                            <textarea class="form-control bg-white" name="message" id="message" required=""></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label class="" for="concern">Image</label>
                                                                        <label class="btn btn-primary btn-sm btn-block  " for="image">Browse</label>
                                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                                            <input type="file" class="form-control" name="attachments[]" id="image" style="padding:0px;min-height: 28px;" onchange="$('#img_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                                        </div>
                                                                        <p class=""><span id="img_text"></span></p>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <input type="hidden" name="order_item_id" value="">
                                                                        <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                                                        <button type="submit" class="btn btn-primary btn-sm" id="submit_complaint_msg_btn">Send</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        <?php } ?>
                                                    <?php } else if ($order_item_stage['status'] == 'complaint_msg') { ?>
                                                        <h3 class="tmln-title">Issue details shared to Reatailer and Manufacturer. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                    <?php } else if ($order_item_stage['status'] == 'issue_resolved') { ?>
                                                        <h3 class="tmln-title">Issue resolved. <small class="float-right font-weight-normal"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>

                                                    <?php } else if ($order_item_stage['status'] == 'delivered') { ?>
                                                        <?php
                                                                $this->db->select('id');
                                                                $this->db->from('order_item_stages');
                                                                $this->db->where('status', 'issue_resolved');
                                                                $this->db->where('order_id', $order_id);
                                                                $q = $this->db->get();
                                                                $rw = $q->row_array();

                                                                if ($rw['id']) {
                                                        ?>
                                                            <?php /* ?>
                                                                    <h3 class="tmln-title">Order closed successfully. <small class="float-right"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                                    <?php */ ?>
                                                            <h3 class="tmln-title">Issue Resolved Successfully. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                            <small>- Please release payment to <?php echo $order_detls[0]['seller_name']; ?> and upload transaction details.</small>
                                                        <?php
                                                                } else {
                                                        ?>
                                                            <h3 class="tmln-title">Order delivered successfully. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                            <small>- Please release payment (<?php echo $settings['currency'] . ' ' . number_format(($order_detls[0]['final_total'] - $service_total), 2); ?>/-) to <?php echo $order_detls[0]['seller_name']; ?> and upload transaction details.</small>
                                                        <?php
                                                                }
                                                        ?>
                                                        <?php
                                                                if (count($order_item_stages) == $_count) {
                                                        ?>
                                                            <!-- <div class="col-md-5">
                                                                <a href="#" class='button-- button-danger-outline-- btn btn-primary btn-sm d-inline-block p-1' data-toggle="modal" data-target="#send-payment-request">Send Transaction Receipt</a>

                                                            </div> -->
                                                            <form class="form-horizontal d-none1" id="send_payment_confirmation_form" action="<?= base_url('admin/orders/send_mfg_payment_ack_form/'); ?>" method="POST" enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="form-group mb-0 col-md-2">
                                                                        <label class="btn btn-warning btn-sm btn-block" for="payment_confirmation">Select file</label>
                                                                        <div class="custom-file-input" style="margin-top: -30px;">
                                                                            <input type="file" class="form-control" name="attachments[]" id="payment_confirmation" style="padding:0px;min-height: 28px;" required="" onchange="$('#pay_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                                                        </div>
                                                                        <p class=""><span id="pay_text"></span></p>
                                                                    </div>
                                                                    <div class="form-group col-md-3">
                                                                        <div class="">
                                                                            <input type="text" class="form-control" name="transaction_no" id="transaction_no" placeholder="Transaction Number" required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mb-0 col-md-2">
                                                                        <input type="hidden" name="order_item_id" value="">
                                                                        <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                                                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_payment_confirmation_btn">Send</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        <?php
                                                                }
                                                        ?>
                                                    <?php } else if ($order_item_stage['status'] == 'send_mfg_payment_ack') { ?>
                                                        <h3 class="tmln-title">Transaction details shared with manufacturer. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <div class="row">

                                                            <div class="col-md-3">
                                                                <a href="<?= base_url('seller/orders/paymentreceipt/') . $order_detls[0]['id'] . "/view" ?>" target="_blank" class="btn btn-primary btn-sm btn-block ">View Payment Receipt</a>

                                                            </div>
                                                            <?php
                                                                $ids = explode(',', $order_item_stage['ids']);
                                                                if ($ids) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_mfg_payment_ack');
                                                                    $this->db->where_in('id', $ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_mfg_payment_acks = $query->result_array();


                                                                    if ($order_item_mfg_payment_acks) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_mfg_payment_acks as $order_item_mfg_payment_ack) {
                                                                            if (file_exists($order_item_mfg_payment_ack['attachments']) && $order_item_mfg_payment_ack['attachments']) {
                                                            ?><a class="btn btn-sm col-md-3 btn-primary mb-1" href="<?php echo base_url() . $order_item_mfg_payment_ack['attachments']; ?>" target="_blank">Download the transaction details</a><?php
                                                                                                                                                                                                                                            $i_count++;
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                }
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                            ?>
                                                        </div>
                                                    <?php } else if ($order_item_stage['status'] == 'send_mfg_payment_confirmation') { ?>
                                                        <h3 class="tmln-title">Payment receipt received. <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                        <?php
                                                                $ids = explode(',', $order_item_stage['ids']);
                                                                if ($ids) {
                                                                    $this->db->select('*');
                                                                    $this->db->from('order_item_mfg_payment_confirmation');
                                                                    $this->db->where_in('id', $ids);
                                                                    $query = $this->db->get();
                                                                    $order_item_mfg_payment_confirmations = $query->result_array();


                                                                    if ($order_item_mfg_payment_confirmations) {
                                                                        $i_count = 1;
                                                                        foreach ($order_item_mfg_payment_confirmations as $order_item_mfg_payment_confirmation) {
                                                                            if (file_exists($order_item_mfg_payment_confirmation['attachments']) && $order_item_mfg_payment_confirmation['attachments']) {
                                                        ?><a class="btn btn-sm col-md-3 btn-primary mb-1" href="<?php echo base_url() . $order_item_mfg_payment_confirmation['attachments']; ?>" target="_blank">Download Transaction Receipt</a><?php
                                                                                                                                                                                                                                                    $i_count++;
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                    ?>
                                                        <small>- Order closed.</small>
                                                    <?php } else if ($order_item_stage['status'] == 'out_of_stock') { ?>
                                                        <h3 class="tmln-title">Quantities are out of stock <small>- Notification sent to retailer and order has been closed.</small> <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                    <?php } else { ?>
                                                        <h3 class="tmln-title"><?php echo $order_item_stage['status']; ?> <small class="float-right"><?php echo date('l d M Y h:i A', strtotime($order_item_stage['created_date'])); ?></small></h3>
                                                    <?php } ?>
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
                    <?php
                    }
                    ?>
                </div>



                <?php /* ?>
                <div class="col-md-12 ">
                    <div class="shadow p-3 mb-3 bg-white rounded">
                        <table class="table table-borderless">
                            <tr>
                                <th class="w-10px">Total(<?= $settings['currency'] ?>)</th>
                                <td id=' amount'><?php echo $total; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <input type="hidden" name="hidden" id="order_id" value="<?php echo $order_detls[0]['id']; ?>">
                                    <th class="w-10px">ORDER ID</th>
                                    <td><?php echo $order_detls[0]['id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Retailer Details</th>
                                    <td>
                                        <?php //echo $order_detls[0]['uname']; ?>
                                        
                                        <p>Name: <?php echo $order_detls[0]['uname']; ?> &nbsp;&nbsp;&nbsp;&nbsp; Mobile: <?php echo $order_detls[0]['mobile']; ?> &nbsp;&nbsp;&nbsp;&nbsp; Email: <?php echo $order_detls[0]['email']; ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp; Firm Name: <?php echo $order_detls[0]['company_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<br /> Fertilizer License No.: <?php echo $order_detls[0]['fertilizer_license_no']; ?>&nbsp;&nbsp;&nbsp;&nbsp; Pesticide License No.: <?php echo $order_detls[0]['pesticide_license_no']; ?>&nbsp;&nbsp;&nbsp;&nbsp; Seeds License No.: <?php echo $order_detls[0]['seeds_license_no']; ?></p>
                                        
                                    
                                    </td>
                                </tr>
                                <!--
                                <tr>
                                    <th class="w-10px">Email</th>
                                    <td><?= ((!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) || ($this->ion_auth->is_seller() && get_seller_permission($seller_id, 'customer_privacy') == false)) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Contact</th>
                                    <td><?= ((!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) || ($this->ion_auth->is_seller() && get_seller_permission($seller_id, 'customer_privacy') == false))  ? str_repeat("X", strlen($order_detls[0]['mobile']) - 3) . substr($order_detls[0]['mobile'], -3) : $order_detls[0]['mobile']; ?>
                                    </td>
                                </tr>-->
                                <tr>
                                    <th class="w-10px">Items</th>
                                    <td>
                                            <?php /* ?>
                                            <div class="row mb-5">
                                                <div class="col-md-12 mb-2">
                                                    <label class="badge badge-info">Select status and square box of order item which you want to update</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <select name="status" class="form-control status">
                                                        <option value=''>Select Status</option>
                                                        <option value="awaiting_payment">Awaiting Payment</option>
                                                        <option value="received">Received</option>
                                                        <option value="processed">Processed</option>
                                                        <option value="shipped">Shipped</option>
                                                        <?php if (get_seller_permission($seller_id, 'view_order_otp') == true) { ?>
                                                            <option value="delivered">Delivered</option>
                                                        <?php } ?>
                                                        <option value="cancelled">Cancel</option>
                                                        <option value="returned">Returned</option>
                                                    </select>
                                                </div>
                                                <?php /*if (get_seller_permission($seller_id, 'assign_delivery_boy')) {
                                                ?>
                                                    <div class="col-md-4">
                                                        <select id='deliver_by' name='deliver_by' class='form-control'>
                                                            <option value=''>Select Delivery Boy</option>
                                                            <?php foreach ($delivery_res as $row) { ?>
                                                                <option value="<?= $row['user_id'] ?>"><?= $row['username'] ?></option>
                                                            <?php  } ?>
                                                        </select>
                                                    </div>
                                                <?php }*//* ?>
                                                <div class="col-md-4">
                                                    <a href="javascript:void(0);" title="Bulk Update" class="btn btn-primary col-sm-12 col-md-12 update_status_admin_bulk mr-1">
                                                        Bulk Update
                                                    </a>
                                                </div>
                                            </div>
                                            <?php */ ?>



                <?php
                /*
                                            $total = 0;
                                            $tax_amount = 0;
                                            echo '<div class="container-fluid row">';
                                            foreach ($items as $item) {
                                                $selected = "";
                                                $item['discounted_price'] = ($item['discounted_price'] == '') ? 0 : $item['discounted_price'];
                                                $total += $subtotal = ($item['quantity'] != 0 && ($item['discounted_price'] != '' && $item['discounted_price'] > 0) && $item['price'] > $item['discounted_price']) ? ($item['price'] - $item['discounted_price']) : ($item['price'] * $item['quantity']);
                                                $tax_amount += $item['tax_amount'];
                                                $total += $subtotal = $tax_amount;
                                            ?>
                                                <div class="  card col-md-3 col-sm-12 p-3 mb-2 bg-white rounded m-1 grow">
                                                    <div class="mb-2">
                                                        <input type="checkbox" name="order_item_id[]" value=' <?= $item['id'] ?> '>
                                                    </div>
                                                    <div class="order-product-image">
                                                        <a href='<?= base_url() . $item['product_image'] ?>' data-toggle='lightbox' data-gallery='order-images'> <img src='<?= base_url() . $item['product_image'] ?>' class='h-75'></a>
                                                    </div>
                                                    <?php if (get_seller_permission($seller_id, 'view_order_otp') == true && $item['item_otp'] != 0) { ?>
                                                        <div><span class="text-bold">Item OTP : </span><span class="badge badge-warning"><?= $item['item_otp']; ?></span></div>
                                                    <?php } ?>
                                                    <div><span class="text-bold">Product Type : </span><small><?= ucwords(str_replace('_', ' ', $item['product_type'])); ?> </small></div>
                                                    <div><span class="text-bold">Variant ID : </span><?= $item['product_variant_id'] ?> </div>
                                                    <?php if (isset($item['product_variants']) && !empty($item['product_variants'])) { ?>
                                                        <div><span class="text-bold">Variants : </span><?= str_replace(',', ' | ', $item['product_variants'][0]['variant_values']) ?> </div>
                                                    <?php } ?>
                                                    <div><span class="text-bold">Name : </span><small><?= $item['pname'] ?> </small></div>
                                                    <div><span class="text-bold">Quantity : </span><?= $item['quantity'] ?> </div>
                                                    <div><span class="text-bold">Price : </span><?= $item['price'] + $item['tax_amount'] ?></div>
                                                    <div><span class="text-bold">Discounted Price : </span> <?= $item['discounted_price'] ?> </div>
                                                    <div><span class="text-bold">Subtotal : </span><?= $item['price'] * $item['quantity'] ?> </div>
                                                    <?php
                                                    $badges = ["awaiting" => "secondary", "received" => "primary", "processed" => "info", "shipped" => "warning", "delivered" => "success", "returned" => "danger", "cancelled" => "danger"]
                                                    ?>
                                                    <div><span class="text-bold">Active Status : </span> <span class="badge badge-<?= $badges[$item['active_status']] ?>"> <small><?= $item['active_status'] ?></small></span></div>
                                                    <div class="row mb-1 mt-1 order_item_status">
                                                        <div class="col-md-7 text-center"><select class="form-control-sm w-100">
                                                                <option value="processed" <?= (strtolower($item['active_status']) == 'processed') ? 'selected' : '' ?>>Processed</option>
                                                                <option value="shipped" <?= (strtolower($item['active_status']) == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                                                <?php if (get_seller_permission($seller_id, 'view_order_otp') == true) { ?>
                                                                    <option value="delivered" <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                                                <?php } ?>
                                                                <option value="returned" <?= (strtolower($item['active_status']) == 'returned') ? 'selected' : '' ?>>Return</option>
                                                                <option value="cancelled" <?= (strtolower($item['active_status']) == 'cancelled') ? 'selected' : '' ?>>Cancel</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5 d-flex align-items-center">
                                                            <a href="javascript:void(0);" title="Update status" data-id=' <?= $item['id'] ?> ' class="btn btn-primary btn-xs update_status_admin mr-1">
                                                                <i class="far fa-arrow-alt-circle-up"></i>
                                                            </a>
                                                            <a href=" <?= BASE_URL('seller/product/view-product?edit_id=' . $item['product_id'] . '') ?> " title="View Product" class="btn btn-primary btn-xs">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php /*if (get_seller_permission($seller_id, 'assign_delivery_boy') == true) { ?>
                                                        <div class="row mb-1 mt-1 delivery_boy">
                                                            <div class="col-md-7 text-center">
                                                                <select name='single_deliver_by' class='form-control-sm w-100' required>
                                                                    <option value=''>Select Delivery Boy</option>
                                                                    <?php
                                                                    $delivery_boy_id = fetch_details(['id' => $item['id']], 'order_items', 'delivery_boy_id');
                                                                    foreach ($delivery_res as $row) {
                                                                        $selected = (isset($delivery_boy_id) && !empty($delivery_boy_id) && $delivery_boy_id[0]['delivery_boy_id'] == $row['user_id']) ? 'selected' : '';
                                                                    ?>
                                                                        <option value="<?= $row['user_id'] ?>" <?= $selected ?>><?= $row['username'] ?></option>
                                                                    <?php  } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5 d-flex align-items-center">
                                                                <a href="javascript:void(0);" title="Update Delivery Boy" data-id=' <?= $item['id'] ?> ' class="btn btn-primary btn-xs update_delivery_boy_admin mr-1">
                                                                    <i class="far fa-arrow-alt-circle-up"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 " title="Order Tracking" data-order_id=' <?= $order_detls[0]['id']; ?>' data-order_item_id=' <?= $item['id'] ?> ' data-courier_agency=' <?= $item['courier_agency'] ?> ' data-tracking_id=' <?= $item['tracking_id'] ?> ' data-url=' <?= $item['url'] ?> ' data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>
                                                            </div>
                                                        </div>
                                                    <?php }*//* ?>
                                                </div>
                                            <?php

                                            }
                                            echo '</div>';
                                            
                                            *//*
                                            ?>
                                            <div>
                                            </div>
                                        </form>
                                    </td>

                                </tr>
                                <tr>
                                    <th class="w-10px">Total(<?= $settings['currency'] ?>)</th>
                                    <td id=' amount'><?php echo $total; ?></td>
                                </tr>

                                <tr class="d-none">
                                    <th class="w-10px">Tax(<?= $settings['currency'] ?>)</th>
                                    <td id='amount'><?php echo $tax_amount;
                                    //$total = floatval($total + $tax_amount);  
                                    ?></td>
                                </tr>
                                
                                <?php /* ?>
                                <tr>
                                    <th class="w-10px">Delivery Charge(<?= $settings['currency'] ?>)</th>
                                    <td id='delivery_charge'>
                                        <?php echo $order_detls[0]['delivery_charge'];
                                        $total = $total + $order_detls[0]['delivery_charge']; ?>
                                    </td>
                                </tr>
                                <?php */ ?>

                <?php /* ?>
                                <tr>
                                    <th class="w-10px">Wallet Balance(<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['wallet_balance'];
                                        $total = $total - $order_detls[0]['wallet_balance']; ?></td>
                                </tr>
                                <?php *//* ?>

                                <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $order_detls[0]['order_total'] + $order_detls[0]['delivery_charge'] ?>">
                                <input type="hidden" name="final_amount" id="final_amount" value="<?php echo $order_detls[0]['final_total']; ?>">

                                <tr>
                                    <th class="w-10px">Promo Code Discount (<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['promo_discount'];
                                        $total = floatval($total -
                                            $order_detls[0]['promo_discount']); ?></td>
                                </tr>
                                <?php
                                if (isset($order_detls[0]['discount']) && $order_detls[0]['discount'] > 0) {
                                    $discount = $order_detls[0]['total_payable']  *  ($order_detls[0]['discount'] / 100);
                                    $total = round($order_detls[0]['total_payable'] - $discount, 2);
                                }
                                ?>
                                <tr>
                                    <th class="w-10px">Payable Total(<?= $settings['currency'] ?>)</th>
                                    <td><input type="text" class="form-control" id="final_total" name="final_total" value="<?= $total; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Payment Method</th>
                                    <td><?php echo $order_detls[0]['payment_method']; ?></td>
                                </tr>
                                <?php
                                if (!empty($bank_transfer)) { ?>
                                    <tr>
                                        <th class="w-10px">Bank Transfers</th>
                                        <td>
                                            <div class="col-md-6">
                                                <?php $i = 1;
                                                foreach ($bank_transfer as $row1) { ?>
                                                    <small>[<a href="<?= base_url() . $row1['attachments'] ?>" target="_blank">Attachment <?= $i ?> </a>] </small>
                                                    <a class="delete-receipt btn btn-danger btn-xs mr-1 mb-1" title="Delete" href="javascript:void(0)" data-id="<?= $row1['id']; ?>"><i class="fa fa-trash"></i></a>
                                                <?php $i++;
                                                } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th class="w-10px">Address</th>
                                    <td><?php echo $order_detls[0]['address']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Delivery Date & Time</th>
                                    <td><?php echo (!empty($order_detls[0]['delivery_date']) && $order_detls[0]['delivery_date'] != NUll) ? date('d-M-Y', strtotime($order_detls[0]['delivery_date'])) . " - " . $order_detls[0]['delivery_time'] : "Anytime"; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Order Date</th>
                                    <td><?php echo date('d-M-Y', strtotime($order_detls[0]['date_added'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
                 <?php */ ?>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="send-payment-request" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Payment Out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-3">
                    <form class="form-horizontal " id="send_bank_receipt_form" action="<?= base_url('my-account/send-payment-receipt'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Party Name</label>
                                    <input type="text" class="form-control" readonly disabled value="<?= $this->config->item('happycrop_name'); ?>" placeholder="Party Name" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Receipt Number</label>
                                    <input type="text" class="form-control" readonly name="receipt_no" value="<?= $receipt_no ?>" placeholder="Receipt Number" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Address</label>
                                    <input type="text" class="form-control" readonly disabled value="<?= $this->config->item('address'); ?>" placeholder="Address" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Order Number</label>
                                    <input type="text" class="form-control" readonly disabled placeholder="Order Number" value="<?= $order['id'] ?>" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" readonly disabled value="<?= $this->config->item('mobile'); ?>" placeholder="Phone Number" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="paid_date" value="<?= date('Y-m-d'); ?>" placeholder="Date" />

                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Email</label>
                                    <input type="text" class="form-control" readonly disabled value="<?= $this->config->item('support'); ?>" placeholder="Email" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>GSTIN</label>
                                    <input type="text" class="form-control" readonly disabled value="<?= $this->config->item('gstin'); ?>" placeholder="GSTIN" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label class="form-label" for="transaction_no">Transaction Number</label>
                                    <input type="text" class="form-control" name="transaction_no" id="transaction_no" placeholder="Transaction Number" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label class="form-label" for="received">Received</label>
                                    <input type="text" class="form-control" id="received" readonly disabled placeholder="Received" value="<?= number_format($total_amt, 2); ?>" />
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="">
                                    <label class="form-label" for="received">In Words</label>
                                    <input type="text" class="form-control" readonly disabled value="<?= convertNumberToWords($total_amt) ?>" />
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control w-100" name="description" rows="4" maxlength="255">

                                    </textarea>

                                </div>
                            </div>
                            <div class="form-group mb-0 col-md-6">
                                <label class="btn btn-warning btn-sm btn-block" for="payment_confirmation">Select file</label>
                                <div class="custom-file-input" style="margin-top: -30px;">
                                    <input type="file" class="form-control" name="attachments[]" id="payment_confirmation" style="padding:0px;min-height: 28px;" required="" onchange="$('#pay_text').html(this.value.replace('C:\\fakepath\\', ''));" />
                                </div>
                                <p class=""><span id="pay_text"></span></p>
                            </div>

                            <div class="form-group col-md-6">
                                <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function calPrice(qty, price, i) {
        var total = 0;
        total = qty * price;

        $("#sub_total_" + i).val(total.toFixed(2));

        var order_total = 0;

        $(".sub_total").each(function() {
            if (jQuery.isNumeric(jQuery(this).val())) {
                order_total += Number(jQuery(this).val());
            }
        });

        $("#order_total").val(order_total.toFixed(2));

        var packing_size = $("#packing_size_" + i).val();
        var carton_qty = $("#carton_qty_" + i).val();

        if (packing_size != '' && carton_qty != '' && qty != '') {
            $("#volume_" + i).val(packing_size * carton_qty * qty);
        }

    }

    function confirm_order_qty(order_id) {
        Swal.fire({
            title: 'Are You Sure!',
            text: "You want to update quantity",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise((resolve, reject) => {

                    $form = $("<form></form>");
                    var total_products = $("#total_products").val();

                    $form.append("<input name='order_id' value=" + order_id + " type='hidden'/>");

                    for (i = 0; i < total_products; i++) {
                        var qty_input = $("#quantity_" + i);
                        var field_name = "quantity_" + qty_input.attr('data-order-item-id');

                        $form.append("<input name='" + field_name + "' value='" + qty_input.val() + "' type='hidden'/>");
                        $form.append("<input name='order_item_ids[]' value=" + qty_input.attr('data-order-item-id') + " type='hidden'/>");
                    }

                    //console.log($("#order_form").serialize());

                    $.ajax({
                        url: "<?php echo base_url('seller/orders/updateBulkQty') ?>",
                        type: 'POST',
                        cache: false,
                        data: $form.serialize(),
                        dataType: "json",
                        error: function(xhr, status, error) {
                            //alert(xhr.responseText);
                            iziToast.error({
                                message: 'Something went wrong',
                            });
                            swal.close();
                            location.reload();
                        },
                        success: function(result) {
                            if (result['error'] == false) {
                                iziToast.success({
                                    message: result['message'],
                                });

                                location.reload();

                            } else {
                                iziToast.error({
                                    message: result['message'],
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

    function updateQty(order_item_id, order_id, i) {
        Swal.fire({
            title: 'Are You Sure!',
            text: "You want to update quantity",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: "<?php echo base_url('seller/orders/updateQty') ?>",
                        type: 'POST',
                        cache: false,
                        data: {
                            qty: $("#quantity_" + i).val(),
                            order_item_id: order_item_id,
                            order_id: order_id,
                            i: i
                        },
                        dataType: "json",
                        error: function(xhr, status, error) {
                            //alert(xhr.responseText);
                            iziToast.error({
                                message: 'Something went wrong',
                            });
                            swal.close();
                            location.reload();
                        },
                        success: function(result) {
                            if (result['error'] == false) {
                                iziToast.success({
                                    message: result['message'],
                                });

                                //$("#check_"+order_item_id).remove();
                                //("#it_state_"+order_item_id).remove();
                                location.reload();

                            } else {
                                iziToast.error({
                                    message: result['message'],
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

    function send_delivery_schedule(order_item_id, order_id) {
        var schedule_delivery_date = $("#schedule_delivery_date_" + order_item_id).val();
        $.ajax({
            url: "<?php echo base_url('seller/orders/send_delivery_schedule') ?>",
            type: 'POST',
            cache: false,
            data: {
                schedule_delivery_date: schedule_delivery_date,
                order_item_id: order_item_id,
                order_id: order_id
            },
            dataType: "json",
            error: function(xhr, status, error) {
                //alert(xhr.responseText);
                iziToast.error({
                    message: 'Something went wrong',
                });

            },
            success: function(result) {
                if (result['error'] == false) {
                    iziToast.success({
                        message: result['message'],
                    });

                } else {
                    iziToast.error({
                        message: result['message'],
                    });
                }
                location.reload();
            }
        });
    }

    function requestPayment(order_id) {
        Swal.fire({
            title: 'Are You Sure!',
            text: "You want to sent payment demand",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: "<?php echo base_url('seller/orders/requestPayment') ?>",
                        type: 'POST',
                        cache: false,
                        data: {
                            order_id: order_id
                        },
                        dataType: "json",
                        error: function(xhr, status, error) {
                            //alert(xhr.responseText);
                            iziToast.error({
                                message: 'Something went wrong',
                            });
                            swal.close();
                            //location.reload();
                        },
                        success: function(result) {
                            if (result['error'] == false) {
                                iziToast.success({
                                    message: result['message'],
                                });

                            } else {
                                iziToast.error({
                                    message: result['message'],
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

    function out_of_stock(order_id) {
        Swal.fire({
            title: 'Are You Sure!',
            text: "You want to cancel the order",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: "<?php echo base_url('seller/orders/out_of_stock') ?>",
                        type: 'POST',
                        cache: false,
                        data: {
                            order_id: order_id
                        },
                        dataType: "json",
                        error: function(xhr, status, error) {
                            //alert(xhr.responseText);
                            iziToast.error({
                                message: 'Something went wrong',
                            });
                            swal.close();
                            //location.reload();
                        },
                        success: function(result) {
                            if (result['error'] == false) {
                                iziToast.success({
                                    message: result['message'],
                                });

                            } else {
                                iziToast.error({
                                    message: result['message'],
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