<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1>Accounts</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('accounts')) ? $this->lang->line('accounts') : 'Accounts' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-2">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>

            <div class="col-md-10 col-12">
                <div class="">
                    <?php $this->load->view('front-end/' . THEME . '/pages/account_subheader') ?>

                </div>
                <div class="pt-2">
                    <h2>Add Purchase Return</h2>
                    <form class="form-horizontal " action="<?= base_url('my-account/addexternalpurchasereturn'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="my-2">
                                    <label>Seller Name</label>
                                    <input type="text" class="form-control" name="party_name" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Phone Number</label>
                                    <input type="number" maxlength="10" class="form-control" name="phone_no" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="email_id" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>GSTN</label>
                                    <input type="text" class="form-control" name="gstn" value="" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="my-2">
                                    <label>Return Number</label>
                                    <input type="text" class="form-control" name="return_number" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Order Number</label>
                                    <input type="text" class="form-control" name="order_number" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Invoice Date</label>
                                    <input type="date" class="form-control" name="invoice_date" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>State of Supply</label>
                                    <input type="text" class="form-control" name="state_supply" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Date(Current)</label>
                                    <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d') ?>" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>HSN</th>
                                            <th>Quantity</th>
                                            <th>Price/Unit</th>
                                            <th>GST</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_data">
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" class="form-control" name="name_1" value=""  required /></td>
                                            <td><input type="text" step="0.01" class="form-control hsn" name="hsn_1"  required /></td>
                                            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_1" required /></td>
                                            <td><input type="number" step="0.01" class="form-control price" name="price_1"  required /></td>
                                            <td><input type="number" step="0.01" class="form-control gst" name="gst_1"  required /></td>
                                            <td><input type="number" step="0.01" class="form-control amount" name="amount_1"  required /></td>
                                        </tr>
                                    </tbody>

                                </table>
                                <a href="#" class="py-2 btn" onclick="addrow(event);">Add Row</a>
                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <div class="">
                                    <label>Payment Type</label>
                                    <input type="text"  class="form-control" name="payment_type" value="" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <div class="">
                                    <label>Paid Amount</label>
                                    <input type="number" step="0.01"  class="form-control" name="paid_amount" value="" required />
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Add Image</label>
                                    <input type="file" class="form-control" name="add_image" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Add Document</label>
                                    <input type="file" class="form-control" name="add_document" />
                                </div>
                            </div>

                            <div class="form-group col-md-4 align-content-lg-start pt-3">
                                <input type="hidden" name="item_count" id="item_count" value="1">
                                <input type="hidden" name="type" id="type" value="1">
                                <button type="submit" class="btn btn-primary  btn-block" id="submit_btn">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var index = 1;

    function addrow(event) {
        event.preventDefault();
        index++;
        html = '<tr>\
            <td>' + index + '</td>\
            <td><input type="text" class="form-control" name="name_' + index + '" value=""  required/></td>\
            <td><input type="text" step="0.01" class="form-control hsn" name="hsn_'+index+'"  required /></td>\
            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_' + index + '"  required/></td>\
            <td><input type="number" step="0.01" class="form-control price" name="price_' + index + '"  required/></td>\
            <td><input type="number" step="0.01" class="form-control gst" name="gst_'+index+'"  required /></td>\
            <td><input type="number" step="0.01" class="form-control amount" name="amount_' + index + '"  required/></td>\
            </tr>';
        $("#item_data").append(html);
        $("#item_count").val(index);

    }
</script>