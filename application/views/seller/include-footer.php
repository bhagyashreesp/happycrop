<div class="modal fade " id='media-upload-modal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Media</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 main-content">
                    <div class="content-area p-4">
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <input type='hidden' name='media_type' id='media_type' value='image'>
                            <input type='hidden' name='current_input'>
                            <input type='hidden' name='remove_state'>
                            <input type='hidden' name='multiple_images_allowed_state'>
                            <div class="col-md-12 mt-3 mb-3 mb-5">
                                <!-- Change /upload-target to your upload address -->
                                <div id="dropzone" class="dropzone"></div>
                                <br>
                                <a href="" id="upload-files-btn" class="btn btn-success float-right">Upload</a>
                            </div>
                            <div class="alert alert-warning">Select media and click choose media</div>
                            <div id="toolbar">
                                <button id='upload-media' class="btn btn-danger">
                                    <i class="fa fa-plus"></i> Choose Media
                                </button>
                            </div>
                            <table class='table-striped' data-toolbar="#toolbar" id='media-upload-table' data-page-size="5" data-toggle="table" data-url="<?= base_url('seller/media/fetch') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-query-params="mediaParams">
                                <thead>
                                    <tr>
                                        <th data-field="state" data-checkbox="true"></th>
                                        <th data-field="id" data-sortable="true" data-visible='false'>ID</th>
                                        <th data-field="image" data-sortable="false">Image</th>
                                        <th data-field="name" data-sortable="false">Name</th>
                                        <th data-field="size" data-sortable="false">Size</th>
                                        <th data-field="extension" data-sortable="false" data-visible='false'>Extension</th>
                                        <th data-field="sub_directory" data-sortable="false" data-visible='false'>Sub directory</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php $web_settings = get_settings('web_settings', true); ?>
<footer class="main-footer text-right--">
    <div class="footer-top pt-4">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="widget widget-about mb-3">
                    <h3 class="widget-title">Got Question? Call us</h3>
                    <div class="widget-body">
                        <a href="tel:<?= $web_settings['support_number'] ?>" class="widget-about-call"><?= $web_settings['support_number'] ?></a>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="widget mb-3">
                    <h3 class="widget-title">Happycrop</h3>
                    <ul class="widget-body row">
                        <li class="col-md-3"><a href="<?php echo base_url('about-us');?>">About Us</a></li>
                        <li class="col-md-3"><a href="<?php echo base_url('faq');?>">FAQ</a></li>
                        <li class="col-md-3"><a href="<?php echo base_url('careers');?>">Careers</a></li>
                        <li class="col-md-3"><a href="<?php echo base_url('contact-us');?>">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="widget mb-3">
                    <h4 class="widget-title">&nbsp;</h4>
                    <ul class="widget-body row">
                        <li class="col-md-6"><a href="<?php echo base_url('privacy-policy');?>">Privacy Policy</a></li>
                        <li class="col-md-6"><a href="<?php echo base_url('terms-of-use');?>">Term of Use</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="widget mb-3">
                    <h3 class="widget-title">Find us on Social</h3>
                    <div class="widget-body">
                        <div class="social-icons social-icons-colored">
                            <a href="#" class="social-icon social-facebook w-icon-facebook"></a>
                            <a href="#" class="social-icon social-twitter w-icon-twitter"></a>
                            <a href="#" class="social-icon social-instagram w-icon-instagram"></a>
                            <a href="#" class="social-icon social-youtube w-icon-youtube"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
	<strong>Copyright &copy; 2020-2021 <a href="<?=base_url('seller/home')?>"><?php $settings=get_settings('system_settings',true); echo $settings['app_name'];?></a>.</strong>
	All rights reserved.
	<div class="float-right d-none d-sm-inline-block">
	</div>
    </div>
</footer>