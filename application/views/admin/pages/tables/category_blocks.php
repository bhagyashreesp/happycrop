<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Category Block</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Category Block </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/Category_blocks/add_category_block'); ?>" method="POST" enctype="multipart/form-data">
                            <?php if (isset($fetched_data[0]['id'])) { ?>
                                <input type="hidden" id="edit_category_block" name="edit_category_block" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="title" class="control-label">Title for Category Block <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="title" id="title" value="<?= (isset($fetched_data[0]['title']) ? $fetched_data[0]['title'] : '') ?>" placeholder="Title">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="short_description" class="control-label">Category Block Description <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <textarea class="form-control textarea" name="short_description" id="short_description" placeholder="Short description"><?= (isset($fetched_data[0]['short_description']) ? stripallslashes(($fetched_data[0]['short_description'])) : '') ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="super_category" class="control-label">Super Category <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <select name="super_category" class=" select_multiple w-100"  data-placeholder=" Type to search and select categories">
                                            <option value=""><?= (isset($super_categories) && empty($super_categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                            </option>
                                            <?php
                                            $selected_val = (isset($fetched_data[0]['id']) &&  !empty($fetched_data[0]['id'])) ? $fetched_data[0]['super_category'] : '';
                                            $selected_vals = explode(',', $selected_val);
                                            echo get_categories_option_html($super_categories, $selected_vals);

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="type" class="control-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <select id="show_type" name="show_type" onchange="show_type_fn();" class=" form-control w-100">
                                            <option value="">Select</option>
                                            <option value="1" <?php echo (isset($fetched_data[0]['show_type']) && $fetched_data[0]['show_type']=='1') ? 'selected="selected"' :  '';?>>Show Categories</option>
                                            <option value="2" <?php echo (isset($fetched_data[0]['show_type']) && $fetched_data[0]['show_type']=='2') ? 'selected="selected"' :  '';?>>Show Insect / Pests</option>
                                            <option value="3" <?php echo (isset($fetched_data[0]['show_type']) && $fetched_data[0]['show_type']=='3') ? 'selected="selected"' :  '';?>>Show Services</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row show_type_1" style="<?php echo (isset($fetched_data[0]['show_type']) && (($fetched_data[0]['show_type']=='1') || ($fetched_data[0]['show_type']=='3'))) ? '' :  'display: none;';?>">
                                    <label for="main_category" class="control-label">Main Category <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <select name="main_category" class=" select_multiple w-100"  data-placeholder=" Type to search and select categories">
                                            <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                            </option>
                                            <?php
                                            $selected_val = (isset($fetched_data[0]['id']) &&  !empty($fetched_data[0]['id'])) ? $fetched_data[0]['main_category'] : '';
                                            $selected_vals = explode(',', $selected_val);
                                            echo get_categories_option_html($categories, $selected_vals);

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row show_type_1" style="<?php echo (isset($fetched_data[0]['show_type']) && (($fetched_data[0]['show_type']=='1') || ($fetched_data[0]['show_type']=='3'))) ? '' :  'display: none;';?>">
                                    <label for="sub_categories" class="control-label">Sub Category</label>
                                    <div class="col-md-12">
                                        <select name="sub_categories[]" class=" select_multiple w-100" multiple data-placeholder=" Type to search and select categories">
                                            <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                            </option>
                                            <?php
                                            $selected_val = (isset($fetched_data[0]['id']) &&  !empty($fetched_data[0]['id'])) ? $fetched_data[0]['sub_categories'] : '';
                                            $selected_vals = explode(',', $selected_val);
                                            echo get_categories_option_html($categories, $selected_vals);

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Category Block' : 'Add Category Block' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center form-group">
                                <div id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/.card-->
            </div>
            <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Edit Category Block Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 main-content">
                <div class="card content-area p-4">
                    <div class="card-head">
                        <h4 class="card-title">Category Blocks</h4>
                    </div>
                    <div class="card-innr">
                        <div class="gaps-1-5x"></div>
                        <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/Category_blocks/get_category_block_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="title" data-sortable="true">Title</th>
                                    <!--<th data-field="short_description" data-sortable="false">Short description</th>-->
                                    <th data-field="date" data-sortable="true">Date</th>
                                    <th data-field="operate">Actions</th>
                                </tr>
                            </thead>
                        </table>
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
$(document).on("change", "#show_type", function (e) {

    var show_type = this.value;
    
    if(show_type == '1' || show_type == '3')
    {
        $(".show_type_1").show();
    }
    else
    {
        $(".show_type_1").hide();
    }
});
</script>