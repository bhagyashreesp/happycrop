<style>.wd-100{width: 100px;}</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage <?php echo $super_category_name; ?> Products</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/product') ?>">Products</a></li>
                        <li class="breadcrumb-item active"><?php echo $super_category_name; ?> Products</li>
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
                                $verify_field = $row['verify_field'];
                                $fields = explode(',', $verify_field);
                                
                                $flag = false;
                                if($verify_field!='')
                                {
                                    $this->db->select($verify_field);
                                    $this->db->from('seller_data');
                                    $this->db->where('user_id', $this->session->userdata('user_id'));
                                    $query      = $this->db->get();
                                    $lic_info   = $query->row_array();
                                                                        
                                    if($lic_info[trim($fields[0])] && $lic_info[trim($fields[1])] !='' && (strtotime(date('Y-m-d')) < strtotime($lic_info[trim($fields[2])])))
                                    {
                                        $flag = true;
                                    }
                                }
                                
                                if($flag)
                                {
                                    ?>
                                    <div class="col-lg-2 col-md-2 col-sm-12 mb-3 text-center">
                                        <div class="card sp-cat-lst p-2" style="<?php echo ($row['id'] == $super_category_id) ? 'border: 2px solid #000;background: #EEE;' : ''; ?>">
                                        <a href="<?php echo base_url().'seller/product/products/'.$row['id']; ?>"><img class="p-2 wd-100" src="<?php echo base_url().$row['image'];?>" alt="<?php echo $row['name']; ?>" /></a>
                                        <h6 class="mt-2"><a href="<?php echo base_url().'seller/product/products/'.$row['id']; ?>"><?php echo $row['name'];?></a></h6>
                                        </div>
                                    </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div class="col-lg-2 col-md-2 col-sm-12 mb-3 text-center">
                                        <div class="card sp-cat-lst p-2" style="<?php echo ($row['id'] == $super_category_id) ? 'border: 2px solid #000;background: #EEE;' : ''; ?>">
                                            <a href="javascript:void(0);" onclick="notify_act_msg()" title="To activate this option, please enter valid license details in profile"><img class="p-2 wd-100" src="<?php echo base_url().$row['image'];?>" alt="<?php echo $row['name']; ?>" /></a>
                                            <h6 class="mt-2"><a onclick="notify_act_msg();" href="javascript:void(0);"><?php echo $row['name'];?></a></h6>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" id="product-rating-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">View Product Rating</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="tab-pane " role="tabpanel" aria-labelledby="product-rating-tab">
                                    <table class='table-striped' id="product-rating-table" data-toggle="table" data-url="<?= base_url('seller/product/get_rating_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="ratingParams">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="username" data-width='500' data-sortable="false" class="col-md-6">Username</th>
                                                <th data-field="rating" data-sortable="false">Rating</th>
                                                <th data-field="comment" data-sortable="false">Comment</th>
                                                <th data-field="images" data-sortable="true">Images</th>
                                                <th data-field="data_added" data-sortable="false">Data added</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <?php /* ?>
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'seller/product/new_product?super_category_id='.$super_category_id ?>" class="btn btn-block-- float-right btn-primary btn-md">Add Product</a>
                            </div>
                        </div>
                        <?php */ ?>
                        <div class="card-innr">
                            <?php
                            $verify_field = $super_category_info['verify_field'];
                            $fields = explode(',', $verify_field);
                            
                            if($verify_field!='')
                            {
                                $this->db->select($verify_field);
                                $this->db->from('seller_data');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $query      = $this->db->get();
                                $lic_info   = $query->row_array();
                                                                    
                                if($lic_info[trim($fields[0])] && $lic_info[trim($fields[1])] !='' && (strtotime(date('Y-m-d')) < strtotime($lic_info[trim($fields[2])])))
                                {
                                    ?>
                                    <div class="row">
                                        <?php /* ?>
                                        <div class="col-md-3">
                                            <label for="zipcode" class="col-form-label">Filter By Product Category</label>
                                            <select id="category_parent" name="category_parent">
                                                <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                                </option>
                                                <?php
                                                echo get_categories_option_html($categories);
                                                ?>
                                            </select>
                                        </div>
                                        <?php */ ?>
                                        <div class="col-md-2">
                                            <a href="<?= base_url() . 'seller/product/new_product?super_category_id='.$super_category_id ?>" class="btn btn-block-- float-right-- btn-primary btn-md">Add Product</a>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="zipcode" class="col-form-label">Filter By Product Status</label>
                                            <select class='form-control' name='status' id="status_filter">
                                                <option value=''>Select Status</option>
                                                <option value='1'>Approved</option>
                                                <option value='2'>Not-Approved</option>
                                                <option value='0'>Deactivated</option>
                                            </select>
                                        </div>
                                        <!--<div class="col-md-3">
                                            <label for="search" class="col-form-label">Search By Name</label>
                                            <input class="form-control" type="text" name="search" id="search_filter" />
                                        </div>-->
                                    </div>
                                    <div class="gaps-1-5x"></div>
                                    <table class='table-striped' id='products_table' data-toggle="table" data-url="<?= isset($_GET['flag']) ? base_url('seller/product/get_product_data?flag=') . $_GET['flag'].'&super_category_id='.$super_category_id : base_url('seller/product/get_product_data/'.$super_category_id) ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "products-list","ignoreColumn": ["state"] }' data-query-params="product_query_params">
                                        <thead>
                                            <tr>
                                                <th data-field="no" data-sortable="true" data-visible='true'><?php echo ($scat['is_service_category']) ? 'Service' : 'Product';?> ID</th>
                                                <th data-field="name" data-sortable="false">Name</th>
                                                <th data-field="image" data-sortable="true">Image</th>
                                                <!--<th data-field="variations" data-sortable="true" data-visible='false'>Variations</th>-->
                                                <th data-field="operate" data-sortable="false">Actions</th>
                                                <th data-field="activate" data-sortable="false">Product Status</th>
                                                <th data-field="product_stock_status" data-sortable="false">Stock Status</th>
                                                <th data-field="rating" data-sortable="true">Rating</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div class="alert alert-info">
                                        <p>To activate this option, please enter valid license details in profile</p>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script type="text/javascript">
function notify_act_msg()
{
    Swal.fire({
        title: 'Activate',
        text: "To activate this option, please enter valid license details in profile",
        type: 'warning',
        //showCancelButton: true,
        //confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok',
        showLoaderOnConfirm: true,
        allowOutsideClick: false
    });
}
</script>