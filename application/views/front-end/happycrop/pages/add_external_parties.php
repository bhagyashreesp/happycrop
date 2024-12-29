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
                    <h2>Add Parties</h2>

                    <form class="form-horizontal " action="<?= base_url('my-account/save_external_parties'); ?>" method="POST" enctype="multipart/form-data">
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
                                    <input type="number" maxlength="10" class="form-control" name="mobile" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="email" value="" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6">

                                <div class="my-2">
                                    <label>GSTN</label>
                                    <input type="text" class="form-control" name="gst" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Fertilizer License No</label>
                                    <input type="text" class="form-control" name="fertilizer_licence_no" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Pesticide License No</label>
                                    <input type="text" class="form-control" name="pesticide_licence_no" value="" required />
                                </div>
                                <div class="my-2">
                                    <label>Seed License No</label>
                                    <input type="text" class="form-control" name="seed_license_no" value="" required />
                                </div>
                            </div>


                            <div class="form-group col-md-4 align-content-lg-start pt-3">
                                <button type="submit" class="btn btn-primary  btn-block" id="submit_btn">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>