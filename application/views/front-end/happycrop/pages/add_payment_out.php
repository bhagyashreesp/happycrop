<link rel="stylesheet" href="<?= base_url('assets/front_end/happycrop/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/front_end/happycrop/css/select2-bootstrap4.min.css') ?>">
<script src="<?= base_url('assets/front_end/happycrop/js/select2.full.min.js') ?>"></script>

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
                    <h2>Add Payment Out</h2>

                    <form class="form-horizontal " action="<?= base_url('my-account/addexternalpurchaseout'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="my-2">
                                    <label>Party Name</label>
                                    <select class="select-control select2 w-100" name="party_name" required>
                                        <?php foreach ($partieslist as $key => $item) { ?>
                                            <option value="<?php echo $item['party_name']; ?>"><?php echo $item['party_name']; ?></option>
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
</section>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>