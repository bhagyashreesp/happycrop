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
                        <div class="col-lg-12">
                            <?php if (empty($external_parties)) { ?>
                                <h2>Add Parties</h2>
                            <?php } else { ?>
                                <h2>View Parties</h2>
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
                                            <label>Fertilizer License No</label>
                                            <input type="text" class="form-control" name="fertilizer_licence_no" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['fertilizer_licence_no'] : '') ?>" <?php echo $disabled; ?> />
                                        </div>
                                        <div class="my-2">
                                            <label>Pesticide License No</label>
                                            <input type="text" class="form-control" name="pesticide_licence_no" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['pesticide_licence_no'] : '') ?>" <?php echo $disabled; ?> />
                                        </div>
                                        <div class="my-2">
                                            <label>Seed License No</label>
                                            <input type="text" class="form-control" name="seed_license_no" required value="<?php echo (!empty($external_parties) ? $external_parties[0]['seed_license_no'] : '') ?>" <?php echo $disabled; ?> />
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
        </div>
    </section>
</div>