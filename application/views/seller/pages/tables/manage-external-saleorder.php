<link rel="stylesheet" href="<?= base_url('assets/front_end/happycrop/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/front_end/happycrop/css/select2-bootstrap4.min.css') ?>">
<script src="<?= base_url('assets/front_end/happycrop/js/select2.full.min.js') ?>"></script>

<style>
    .table td,
    .table th {
        font-size: 13px;
    }

    .fixed-table-toolbar {
        display: none;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $page_title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content ">
                    <div class="card- card-info mb-0">
                        <?php $this->load->view("seller/pages/tables/seller_subheader"); ?>


                    </div>
                    <div class="card content-area pt-4">
                        <div class="col-md-12">
                            <h2>Add Sale Order</h2>
                            <form class="form-horizontal " action="<?= base_url('my-account/addexternalpurchasebill'); ?>" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <div class="my-2">
                                            <label>Retailer Name</label>
                                            <select class="select-control select2 w-100 party_name" name="party_name" required>
                                                <option value="">Select Party</option>
                                                <?php foreach ($partieslist as $key => $item) { ?>
                                                    <option value="<?php echo $item['party_name']; ?>" data-id="<?php echo $item['id']; ?>"><?php echo $item['party_name']; ?></option>
                                                <?php } ?>

                                            </select>
                                            <!-- <input type="text" class="form-control" name="party_name" value="" required /> -->
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
                                            <label>Place of Supply</label>
                                            <input type="text" class="form-control" name="place_supply" value="" required />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="my-2">
                                            <label>Order Number</label>
                                            <input type="text" class="form-control" name="order_number" value="" required />
                                        </div>
                                        <div class="my-2">
                                            <label>Order Date</label>
                                            <input type="date" class="form-control" name="date" value="" required />
                                        </div>
                                        <div class="my-2">
                                            <label>Due Date</label>
                                            <input type="date" class="form-control" name="due_date" value="" required />
                                        </div>

                                        <div class="my-2">
                                            <label>GSTN</label>
                                            <input type="text" class="form-control" name="gstn" value="" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
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
                                                    <td><input type="text" class="form-control" name="name_1" value="" required /></td>
                                                    <td><input type="text" step="0.01" class="form-control hsn" name="hsn_1" required /></td>
                                                    <td><input type="number" step="0.01" class="form-control quantity" name="quantity_1" required  onkeyup="calculateAmount('1')"/></td>
                                                    <td><input type="number" step="0.01" class="form-control price" name="price_1" required  onkeyup="calculateAmount('1')"/></td>
                                                    <td><input type="number" step="0.01" class="form-control gst" name="gst_1" required /></td>
                                                    <td><input type="number" step="0.01" class="form-control amount" name="amount_1" required /></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <a href="#" class="py-2 btn btn-primary" onclick="addrow(event);">Add Row</a>
                                    </div>
                                    <div class="form-group col-md-12 mt-2">
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
                                        <input type="hidden" name="type" id="type" value="2">
                                        <button type="submit" class="btn btn-primary  btn-block" id="submit_btn">Save</button>
                                    </div>
                                </div>
                            </form>



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
<script>
    var index = 1;
    $(document).ready(function() {
        $('.select2').select2();
    });
    amt = 0;

    function calculateAmount(Index) {
        quantity = $('input[name="quantity_' + Index + '"]').val();
        price = $('input[name="price_' + Index + '"]').val();
        amtTotal = quantity * price;
        $('input[name="amount_' + Index + '"]').val(amtTotal);
    }

    function addrow(event) {
        event.preventDefault();
        index++;
        html = '<tr>\
            <td>' + index + '</td>\
            <td><input type="text" class="form-control" name="name_' + index + '" value=""  required/></td>\
            <td><input type="text" step="0.01" class="form-control hsn" name="hsn_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_' + index + '"  required onkeyup="calculateAmount(' + index + ')"/></td>\
            <td><input type="number" step="0.01" class="form-control price" name="price_' + index + '"  required onkeyup="calculateAmount(' + index + ')"/></td>\
            <td><input type="number" step="0.01" class="form-control gst" name="gst_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control amount" name="amount_' + index + '"  required/></td>\
            </tr>';
        $("#item_data").append(html);
        $("#item_count").val(index);

    }
</script>