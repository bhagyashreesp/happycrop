<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?= (isset($fetched_data[0]['id'])) ? 'Update Webinar' : 'Add Webinar' ?></h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?= (isset($fetched_data[0]['id'])) ? 'Update Webinar' : 'Add Webinar' ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-info">
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/webinars/add_new_webinar'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <?php if (isset($fetched_data[0]['id'])) { ?>
                                    <input type="hidden" name="edit_id" value="<?= @$fetched_data[0]['id'] ?>">
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Webinar Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title" name="title" value="<?= @$fetched_data[0]['title'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="date" class="col-sm-2 col-form-label">Date &amp; Time <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-5 col-md-3">
                                        <input type="date" class="form-control" id="date" placeholder="Date" name="date" value="<?= @$fetched_data[0]['date'] ?>" min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="col-sm-5 col-md-3">
                                        <input type="time" class="form-control" id="time" placeholder="Time" name="time" value="<?= @$fetched_data[0]['time'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="speakers" class="col-sm-2 col-form-label">Speakers <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="speakers" placeholder="Speakers" name="speakers" value="<?= @$fetched_data[0]['speakers'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="organization" class="col-sm-2 col-form-label">Organization <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="organization" placeholder="Organization" name="organization" value="<?= @$fetched_data[0]['organization'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="join_link" class="col-sm-2 col-form-label">Reg./Join Link</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="join_link" placeholder="Registration/Join Link" name="join_link" value="<?= @$fetched_data[0]['join_link'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="recording_link" class="col-sm-2 col-form-label">Recording Link</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="recording_link" placeholder="Recording Link" name="recording_link" value="<?= @$fetched_data[0]['recording_link'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-2 col-form-label">Webinar Details <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <?php echo $this->ckeditor->editor("description",$fetched_data[0]['description']);?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <label for="status">
                                            <input type="checkbox" class="" id="status" placeholder="status" name="status" value="1" <?php echo (@$fetched_data[0]['status']) ? 'checked="checked"' : ''; ?> />
                                            Is Active
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Webinar' : 'Add Webinar' ?></button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="form-group" id="error_box">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>