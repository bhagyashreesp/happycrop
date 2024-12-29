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
        </div>
    </section>
</div>