<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Super Categories Order</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Super Categories Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                        </div>
                        <div class="card-innr">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 offset-md-3">
                                        <label for="subsuper_category_id" class="col-form-label">Super Category List</label>
                                        <div class="row font-weight-bold">
                                            <div class="col-md-1">No.</div>
                                            <div class="col-md-3">Row Order Id</div>
                                            <div class="col-md-4">Name</div>
                                            <div class="col-md-4">Image</div>
                                        </div>
                                        <ul class="list-group bg-grey move order-container" id="sortable">
                                            <?php
                                            $i = 0;
                                            if (!empty($super_categories)) {
                                                foreach ($super_categories as $row) {
                                            ?>
                                                    <li class="list-group-item d-flex bg-gray-light align-items-center h-25" id="super_category_id-<?= $row['id'] ?>">
                                                        <div class="col-md-2"><span> <?= $i ?> </span></div>
                                                        <div class="col-md-2"><span> <?= $row['row_order'] ?> </span></div>
                                                        <div class="col-md-4"><span><?= $row['name'] ?></span></div>
                                                        <div class="col-md-4">
                                                            <img src="<?= $row['image'] ?>" class="w-25">
                                                        </div>
                                                    </li>
                                                <?php
                                                    $i++;
                                                }
                                            } else {
                                                ?>
                                                <li class="list-group-item text-center h-25"> No Super Categories Exist </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <button type="button" class="btn btn-block btn-success btn-lg mt-3" id="save_super_category_order">Save</button>
                                    </div>
                                </div>
                            </div><!-- .card-innr -->
                        </div><!-- .card -->
                    </div>

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>