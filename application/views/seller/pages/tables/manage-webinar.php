<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?php echo $page_title;?></h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title;?></li>
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
                            <div class="card-tools row ">
                                <a href="<?= base_url() . 'seller/webinars/manage-new-webinar' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Webinar </a>
                            </div>
                        </div>
                        <div class="card-innr">
                            <div class="row col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Search</label>
                                    <input type="text" id="search_field" name="search_field" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Search</button>
                                </div>
                            </div>
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='webinar_table' data-toggle="table" data-url="<?= base_url('seller/webinars/view_seller_webinars/') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="w.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Webinar Title</th>
                                        <th data-field="date" data-sortable="false">Date</th>
                                        <th data-field="time" data-sortable="false">Time</th>
                                        <th data-field="speakers" data-sortable="false">Speakers</th>
                                        <th data-field="organization" data-sortable="false">Organization</th>
                                        <th data-field="join_link" data-sortable="false">Reg. / Join Link</th>
                                        <!--<th data-field="username" data-sortable="false">Created By</th>-->
                                        <th data-field="description" data-sortable="true" data-visible="false">Description</th>
                                        <th data-field="recording_link" data-sortable="true" data-visible="false">Recording Link</th>
                                        <th data-field="attendees" data-sortable="true" data-visible="false">Attendees</th>
                                        <th data-field="status" data-sortable="true">Status</th>
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