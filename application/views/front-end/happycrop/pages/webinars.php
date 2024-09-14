<!-- breadcrumb -->
<style>
.light-blue-bg {background: #cfd5eb;}
.dark-blue-bg {background: #4473c5;color:#FFF;}
</style>
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1>Webinars</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('webinars')) ? $this->lang->line('webinars') : 'Webinars' ?></a></li>
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
                <div class="pt-3 pr-lg-2">
                    <style>.fixed-table-toolbar {display: none;}</style>
                    <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('my-account/get_front_webinar_list/') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="w.date_time" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                        <thead>
                            <tr class="dark-blue-bg">
                                <th data-field="id" data-sortable='false' data-footer-formatter="totalFormatter">#</th>
                                <th data-field="title" data-sortable="false">Webinar Title</th>
                                <th data-field="date" data-sortable="false">Date</th>
                                <th data-field="time" data-sortable="false">Time</th>
                                <th data-field="speakers" data-sortable="false">Speakers</th>
                                <th data-field="organization" data-sortable="false">Organization</th>
                                <th data-field="description" data-sortable="false">Details</th>
                                <!--<th data-field="actions" data-sortable="false">Actions</th>-->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>