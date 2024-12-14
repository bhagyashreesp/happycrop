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
                            <h2>Add Payment In</h2>

                            <form class="form-horizontal " action="<?= base_url('my-account/addexternalpurchaseout'); ?>" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <div class="my-2">
                                            <label>Party Name</label>
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
                                            <label>Receipt Number</label>
                                            <input type="text" class="form-control" name="receipt_number" value="" required />
                                        </div>
                                        <div class="my-2">
                                            <label>Order Number</label>
                                            <input type="text" class="form-control" name="order_number" value="" required />
                                        </div>
                                        <div class="my-2">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date" value="" required />
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <div class="">
                                            <label>Reference Number </label>
                                            <input type="text" class="form-control" name="ref_no" />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="">
                                            <label>Received</label>
                                            <input type="number" step="0.01" class="form-control" name="received" />
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
                                            <label>Upload Transaction Receipt</label>
                                            <input type="file" class="form-control" name="transaction_receipt" />
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 align-content-lg-start pt-5">
                                        <button type="submit" class="btn btn-primary  btn-block" id="submit_btn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>



                    </div>
                </div>
            </div>

        </div>
</div>
</section>
</div>
<script>
    var index = 1;

    function addrow(event) {
        event.preventDefault();
        index++;
        html = '<tr>\
            <td>' + index + '</td>\
            <td><input type="text" class="form-control" name="name_' + index + '" value=""  required/></td>\
            <td><input type="text" step="0.01" class="form-control hsn" name="hsn_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_' + index + '"  required/></td>\
            <td><input type="number" step="0.01" class="form-control price" name="price_' + index + '"  required/></td>\
            <td><input type="number" step="0.01" class="form-control gst" name="gst_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control amount" name="amount_' + index + '"  required/></td>\
            </tr>';
        $("#item_data").append(html);
        $("#item_count").val(index);

    }
</script>