<!-- Demo header-->
<style>.shadow{box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;}.btn-group .btn-wrap {min-width: 11.9rem;margin-left: 1.2rem;margin-bottom: 0.5rem;}</style>
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
                                        <a href="<?= base_url('my-account/order-details/'.$order['id']) ?>" class='button button-danger-outline '><?= !empty($this->lang->line('back_to_orders')) ? $this->lang->line('back_to_orders') : 'Back to Orders' ?></a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <h4>Order ID: <?php echo $order['id']; ?></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="shadow p-3 mb-5 bg-white rounded">
                                    <h6 class="h5"><?= !empty($this->lang->line('shipping_details')) ? $this->lang->line('shipping_details') : 'Shipping Details' ?></h6>
                                    <hr/>
                                    <span><?= $order['username'] ?></span> <br/>
                                    <span><?= $order['address'] ?></span> <br/>
                                    <span><?= $order['mobile'] ?></span> <br/>
                                    <!--<span><?= $order['delivery_time'] ?></span> <br/>
                                    <span><?= $order['delivery_date'] ?></span> <br/>-->
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        $this->db->distinct();
                        $this->db->select('a.seller_id, b.username, b.mobile, b.email, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state');
                        $this->db->from('order_items as a');
                        $this->db->join('users as b','a.seller_id = b.id','left');
                        $this->db->join('seller_data as c','a.seller_id = c.user_id','left');
                        $this->db->where('a.order_id', $order_id);
                        $this->db->where('a.id', $order_item_id);
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
                                                    <h6 class="mb-1">Personal Details</h6>
                                                    <span>Name: <?= $manufacture['username'] ?></span> <br/>
                                                    <span>Company Name: <?= $manufacture['company_name'] ?></span> <br/>
                                                    <span>Mobile: <?= $manufacture['mobile'] ?></span> <br/>
                                                    <span>Email: <?= $manufacture['email'] ?></span> <br/>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="mb-1">License Details</h6>
                                                    <span>GST No.: <?= $manufacture['gst_no'] ?></span> <br/>
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
                                                        <th>Rate</th>
                                                        <th>Discount</th>
                                                        <th>Qty</th>
                                                        <th>Total Amount</th>
                                                        <th>GST</th>
                                                        <th>HSN Code</th>
                                                        <!--<th>License</th>-->
                                                        <th>Date</th>
                                                        <th style="width: 20%;">Status</th>
                                                    </tr>
                                                    <?php 
                                                    $i = 1;
                                                    $total_amt = 0; 
                                                    foreach ($order['order_items'] as $key => $item) 
                                                    { 
                                                        if($manufacture['seller_id'] == $item['seller_id'] && $order_item_id == $item['id'])
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <!--<td><?php echo $order['id'] ?></td>
                                                                <td><?php echo $item['company_name']; ?></td>-->
                                                                <td><?php echo $item['name']; ?></td>
                                                                <td><?php echo number_format(($item['price']), 2) ?></td>
                                                                <td><?php echo number_format(($item['mrp'] - $item['price']), 2) ?></td>
                                                                <td><?php echo $item['quantity'] ?></td>
                                                                <td><?php echo number_format(($item['price'] * $item['quantity']), 2) ?></td>
                                                                <td></td>
                                                                <td></td>
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
                                                                        echo $item['active_status'];   
                                                                    //} 
                                                                    ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                            $total_amt += (($item['price'] * $item['quantity'])); 
                                                            $i++;
                                                        }
                                                    } 
                                                    ?>
                                                    <tr>
                                                        <th class="text-right" colspan="5">Total Amount</th>
                                                        <th><?php echo $settings['currency'] . ' ' . number_format(($total_amt),2); ?></th>
                                                        <th colspan="5"></th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        
                        if($order_item_id)
                        {
                            $item_info = $this->db->get_where('order_items',array('id'=>$order_item_id))->row_array();
                            
                            $this->db->select('*');
                            $this->db->from('order_item_stages');
                            $this->db->where('order_item_id',$order_item_id);
                            $query = $this->db->get();
                            $order_item_stages = $query->result_array();
                            
                            if($order_item_stages)
                            {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="shadow p-3 mb-5 bg-white rounded">
                                            <h6 class="h5">Order Stages Details</h6>
                                            <hr/>
                                            <div class="tmln">
                                                <div class="tmln-outer">
                                                    <?php
                                                    foreach($order_item_stages as $order_item_stage)
                                                    {
                                                        ?>
                                                        <div class="tmln-card">
                                                          <div class="tmln-info">
                                                            <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                            <?php if($order_item_stage['status']=='received') { ?> 
                                                                <p>Your order received</p>
                                                            <?php } else if($order_item_stage['status']=='qty_update') { ?> 
                                                                <p>Quantity updated and approval request received from manafacurer.</p>
                                                                <?php
                                                                if($item_info['active_status']=='qty_update')
                                                                {
                                                                    ?>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-12">
                                                                            <div class="btn-group">
                                                                                <div class="btn-wrap show-code-action">
                                                                                    <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="approveQty(<?php echo $item_info['id'] ?>,<?php echo $item_info['order_id'] ?>);">Approve</a>
                                                                                </div>
                                                                                <div class="btn-wrap show-code-action">
                                                                                    <a class="btn btn-sm btn-secondary" href="javascript:void(0);" onclick="rejectQty(<?php echo $item_info['id'] ?>,<?php echo $item_info['order_id'] ?>);">Reject</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>     
                                                            <?php } else if($order_item_stage['status']=='qty_approved') { ?> 
                                                                <p>Quantity approval accepted by you.</p>
                                                            <?php } else if($order_item_stage['status']=='qty_rejected') { ?> 
                                                                <p>Quantity approval rejected by you.</p>
                                                            <?php } else if($order_item_stage['status']=='payment_demand') { ?> 
                                                                <p>Payment demand request by manufacturer.</p>
                                                                <?php 
                                                                if($item_info['active_status']=='payment_demand')
                                                                { 
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
                                                                                    <input type="hidden" name="order_item_id" value="<?= $item_info['id'] ?>">
                                                                                    <input type="hidden" name="order_id" value="<?= $item_info['order_id'] ?>">
                                                                                    <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Send</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                        
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='payment_ack') { ?> 
                                                                <p>Payment acknowledgement sent to manufacturer.</p>
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
                                                            <?php } else if($order_item_stage['status']=='schedule_delivery') { ?>
                                                                <?php
                                                                /*if($item_info['active_status'] == 'schedule_delivery')
                                                                {*/
                                                                    if($item_info['schedule_delivery_date']==null || $item_info['schedule_delivery_date']=='0000-00-00')
                                                                    {
                                                                        echo "Delivery scheduling, please be patience";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "Delivery Scheduled ON ".date('d/m/Y',strtotime($item_info['schedule_delivery_date']));
                                                                    }
                                                                //}
                                                                ?>
                                                            <?php } else if($order_item_stage['status']=='shipped') { ?> 
                                                                <p>Item shipped by manufacturer.</p>
                                                            <?php } else if($order_item_stage['status']=='send_invoice') { ?> 
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                            <?php } else { ?> 
                                                                <p><?php echo $order_item_stage['status']; ?></p>
                                                            <?php } ?>
                                                          </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    
                                                    if($item_info['active_status'] == 'received')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Quantity updated and approval request received from manafacurer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Quantity approval accepted/rejected by you.</p>
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
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'qty_update')
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
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'qty_approved')
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
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'payment_demand')
                                                    {
                                                        ?>
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
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'payment_ack')
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
                                                                <p>Item shipped by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'schedule_delivery')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item shipped by manufacturer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'shipped')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Invoice sent by manufacturer.</p>
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
                                                    else if($item_info['active_status'] == 'send_invoice')
                                                    {
                                                        ?>
                                                        <div class="tmln-card in-active">
                                                            <div class="tmln-info">
                                                                <h3 class="tmln-title"><?php echo date('l d M Y h:i A',strtotime($order_item_stage['created_date'])); ?></h3>
                                                                <p>Item delivered.</p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        
                        
                        ?>
         
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
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script type="text/javascript">
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
                    }  
                });
            });
        },
        allowOutsideClick: false
    });
}
</script>