<!-- breadcrumb -->
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
                <h2>Add Customer</h2>

                <form class="form-horizontal pt-5" action="<?= base_url('my-account/addquickbillcustomer'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="" required placeholder="Name" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="">
                                <label>Billing Address</label>
                                <input type="text" class="form-control" name="billing_address" value="" required placeholder="Billing Address" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" value="" required placeholder="Phone Number" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="">
                                <label>Shipping Address</label>
                                <input type="text" class="form-control" name="shipping_address" value="" required placeholder="Shipping Address" />
                            </div>
                        </div>
                        <div class="form-group col-md-4 align-content-lg-start pt-3">
                            <input type="hidden" name="customer_id" id="ustomer_id" value="">
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

    function addrow() {
        index++;
        html = '<tr>\
                <td>' + index + '</td>\
                <td><input type="text" class="form-control" name="name_' + index + '" value="" placeholder="Name" required/></td>\
                <td><input type="number" step="0.01" class="form-control quantity" name="quantity_' + index + '" placeholder="Quantity" required/></td>\
                <td><input type="number" step="0.01" class="form-control price" name="price_' + index + '" placeholder="Price/Unit" required/></td>\
                <td><input type="number" step="0.01" class="form-control amount" name="amount_' + index + '" placeholder="Amount" required/></td>\
                </tr>';
        $("#item_data").append(html);
        $("#item_count").val(index);

    }
</script>