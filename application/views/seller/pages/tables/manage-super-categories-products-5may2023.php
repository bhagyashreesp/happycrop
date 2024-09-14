<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Products</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content">
                    <div class="content-area">
                        <div class="row">
                        <?php
                        if($super_categories)
                        {
                            foreach($super_categories as $row)
                            {
                                ?>
                                <div class="col-lg-3 col-md-3 col-sm-12 mb-3 text-center">
                                    <div class="card p-3">
                                    <a href="<?php echo base_url().'seller/product/products/'.$row['id']; ?>"><img src="<?php echo base_url().$row['image'];?>" alt="<?php echo $row['name']; ?>" /></a>
                                    <h5 class="mt-2"><a href="<?php echo base_url().'seller/product/products/'.$row['id']; ?>"><?php echo $row['name'];?></a></h5>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>