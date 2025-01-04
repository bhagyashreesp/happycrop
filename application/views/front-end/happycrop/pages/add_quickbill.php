<!-- breadcrumb -->
<link rel="stylesheet" href="<?= base_url('assets/front_end/happycrop/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/front_end/happycrop/css/select2-bootstrap4.min.css') ?>">
<script src="<?= base_url('assets/front_end/happycrop/js/select2.full.min.js') ?>"></script>

<style>
    .light-blue-bg {
        background: #cfd5eb;
    }

    .dark-blue-bg {
        background: #4473c5;
        color: #FFF;
    }
</style>
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
                <div class="pt-3 pr-lg-2">
                    <div class="d-flex justify-content-between">
                        <h2 class="m-0">Quick Bill</h2>
                        <a href="<?php echo base_url('my-account/addcustomer') ?>" class='button-- button-danger-outline-- btn btn-primary btn-sm d-inline-block p-3'>Add Customer</a>

                    </div>
                    <form class="form-horizontal py-2" action="<?= base_url('my-account/save_quickbill'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Customer</label>
                                    <select class="form-control" name="customer_id">
                                        <?php foreach ($getcustomerlist as $key => $value) { ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['customer_name']; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th>#</th>
                                            <th>Item Name</th>
                                            <th>HSN</th>
                                            <th>Quantity</th>
                                            <th>Price/Unit</th>
                                            <th>Discount</th>
                                            <th>Tax Applied</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_data">
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" class="form-control item_code" name="item_code_1" required /></td>
                                            <td><input type="text" class="form-control item_name" name="item_name_1" required /></td>
                                            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_1" required onkeyup="calculateAmount('1')" /></td>
                                            <td><input type="number" step="0.01" class="form-control price" name="price_1" required onkeyup="calculateAmount('1')" /></td>
                                            <td><input type="number" step="0.01" class="form-control discount" name="discount_1" required /></td>
                                            <td><input type="number" step="0.01" class="form-control tax_applied" name="tax_applied_1" required /></td>
                                            <td><input type="number" step="0.01" class="form-control total" name="total_1" required /></td>
                                        </tr>
                                    </tbody>

                                </table>
                                <a href="#" class="py-3 btn btn-primary" onclick="addrow();">Add Row</a>
                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <p class="font-bold">Cash/UPI</p>
                                <div class="py-2">
                                    <label> Payment Mode</label>
                                    <select class="form-control" name="payment_mode">
                                        <option value="1">Cash</option>
                                        <option value="2">UPI</option>
                                        <option value="3">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="">
                                    <label>Amount Received</label>
                                    <input type="number" step="0.01" class="form-control" name="amount_received" value="" required />
                                </div>
                                <div class="">
                                    <label>Balance</label>
                                    <input type="number" step="0.01" class="form-control" name="balance" value="" required />
                                </div>
                                <div class="">
                                    <label>Remark</label>
                                    <textarea name="remark" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <p class="font-bold">Bill Details</p>
                                <div class="">
                                    <label>Sub Total</label>
                                    <input type="number" step="0.01" class="form-control" name="subtotal" value="" required />
                                </div>
                                <div class="py-2">
                                    <label>Discount</label>
                                    <input type="number" step="0.01" class="form-control" name="discount_total" id="discount_total" value="" required readonly />
                                </div>
                                <div class="py-2">
                                    <label>Tax Applied</label>
                                    <input type="number" step="0.01" class="form-control" name="tax_applied_total" id="tax_applied_total" value="" required readonly />
                                </div>
                                <div class="py-2">
                                    <label>Total Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="total_amt" id="total_amt" value="" required readonly />
                                </div>
                            </div>


                            <div class="form-group col-md-4 align-content-lg-start pt-3">
                                <input type="hidden" name="item_count" id="item_count" value="1">
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
    $(document).ready(function() {
        $('.select2').select2();
    });

    function addrow() {
        index++;
        html = '<tr>\
            <td>' + index + '</td>\
            <td><input type="text" class="form-control item_code" name="item_code_' + index + '"  required /></td>\
            <td><input type="text" class="form-control item_name" name="item_name_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_' + index + '" required onkeyup="calculateAmount(' + index + ')" /></td>\
            <td><input type="number" step="0.01" class="form-control price" name="price_' + index + '"  required onkeyup="calculateAmount(' + index + ')"/></td>\
            <td><input type="number" step="0.01" class="form-control discount" name="discount_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control tax_applied" name="tax_applied_' + index + '"  required /></td>\
            <td><input type="number" step="0.01" class="form-control total" name="total_' + index + '"  required /></td>\
            </tr>';
        $("#item_data").append(html);
        $("#item_count").val(index);

    }

    function calculateAmount(Index) {
        quantity = $('input[name="quantity_' + Index + '"]').val();
        price = $('input[name="price_' + Index + '"]').val();
        amtTotal = quantity * price;
        $('input[name="total_' + Index + '"]').val(amtTotal);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('.total');
        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                total += value;
            }
        });
        document.getElementById('total_amt').value = total;
    }
    document.querySelectorAll('.total').forEach(input => {
        input.addEventListener('keyup', calculateTotal);
    });

    function calculateTotaldiscount() {
        let total = 0;
        const inputs = document.querySelectorAll('.discount');
        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                total += value;
            }
        });
        document.getElementById('discount_total').value = total;
    }
    document.querySelectorAll('.discount').forEach(input => {
        input.addEventListener('keyup', calculateTotaldiscount);
    });

    function calculateTotaltax() {
        let total = 0;
        const inputs = document.querySelectorAll('.tax_applied');
        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                total += value;
            }
        });
        document.getElementById('tax_applied_total').value = total;
    }
    document.querySelectorAll('.tax_applied').forEach(input => {
        input.addEventListener('keyup', calculateTotaltax);
    });
</script>