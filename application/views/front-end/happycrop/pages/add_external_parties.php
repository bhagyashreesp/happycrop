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
                <div class="row">
                    <div class="col-lg-10 my-2">
                        <ul class="nav nav-tabs extsys ">
                            <li class="active mx-2 "><a class="p-3  btn" data-toggle="tab" href="<?php echo base_url('my-account/statements/') ?>">System</a></li>
                            <li class="mx-2 "><a class="p-3 active btn" data-toggle="tab" href="#">External</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 my-2 float-right">
                        <ul class="nav nav-tabs extsys float-right">
                            <li class="active mx-2 "><a class="p-3 active btn" href="<?php echo base_url('my-account/statements/') ?>">Back</a></li>

                        </ul>
                    </div>
                </div>
                <div class="pt-2">
                    <?php if (empty($external_parties)) { ?>
                        <h2 class=" px-3 py-2 ">Add Parties</h2>
                    <?php } else { ?>
                        <h2 class=" px-3 py-2 ">View Parties</h2>
                    <?php } ?>

                    <form class="form-horizontal " action="<?= base_url('my-account/save_external_parties'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="my-2">
                                    <label>Party Name</label>
                                    <input type="text" class="form-control" name="party_name" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['party_name'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                                <div class="my-2">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['address'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                                <div class="my-2">
                                    <label>Phone Number</label>
                                    <input type="number" maxlength="10" class="form-control" name="mobile" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['mobile'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                                <div class="my-2">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="email" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['email'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                            </div>
                            <div class="form-group col-md-6">

                                <div class="my-2">
                                    <label>GSTN</label>
                                    <input type="text" class="form-control" name="gst" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['gst'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                                <div class="my-2">
                                    <label>Fertilizer License No (Optional)</label>
                                    <input type="text" class="form-control" name="fertilizer_licence_no"  value="<?php echo (!empty($external_parties) ? $external_parties[0]['fertilizer_licence_no'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                                <div class="my-2">
                                    <label>Pesticide License No (Optional)</label>
                                    <input type="text" class="form-control" name="pesticide_licence_no"  value="<?php echo (!empty($external_parties) ? $external_parties[0]['pesticide_licence_no'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                                <div class="my-2">
                                    <label>Seed License No (Optional)</label>
                                    <input type="text" class="form-control" name="seed_license_no"  value="<?php echo (!empty($external_parties) ? $external_parties[0]['seed_license_no'] : '') ?>" <?php echo $disabled; ?> />
                                </div>
                            </div>


                            <div class="form-group col-md-4 align-content-lg-start pt-3">
                                <?php if (empty($external_parties)) { ?>
                                    <button type="submit" class="btn btn-primary  btn-block" id="submit_btn">Save</button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>