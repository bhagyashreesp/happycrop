<!-- IziModal -->

<script src="<?= THEME_ASSETS_URL . 'js/happycrop-bundle-js.js?v=7.2' ?>"></script>


<!-- Custom -->
<?php if ($this->session->flashdata('message')) { ?>
    <script>
        Toast.fire({
            icon: '<?= $this->session->flashdata('message_type'); ?>',
            title: "<?= $this->session->flashdata('message'); ?>"
        });
    </script>
<?php } ?>

<!-- Dark mode -->

<script src="<?= THEME_ASSETS_URL . 'vendor/jquery.plugin/jquery.plugin.min.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'vendor/imagesloaded/imagesloaded.pkgd.min.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'vendor/zoom/jquery.zoom.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'vendor/jquery.countdown/jquery.countdown.min.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'vendor/magnific-popup/jquery.magnific-popup.min.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'vendor/skrollr/skrollr.min.js' ?>"></script>

<!-- ChartJS -->
<script src="<?= base_url('assets/admin/chart.js/Chart.min.js') ?>"></script>
<!-- Chartist -->
<script src="<?= base_url('assets/admin/js/chartist.js') ?>"></script>

<script src="<?= THEME_ASSETS_URL . 'vendor/jquery.count-to/jquery.count-to.min.js' ?>"></script>

<!-- Swiper JS -->
<script src="<?= THEME_ASSETS_URL . 'vendor/swiper/swiper-bundle.min.js' ?>"></script>

<!-- Main JS -->
<script src="<?= THEME_ASSETS_URL . 'js/main.min.js?v='.time(); ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'js/custom-swipe.js?v='.time(); ?>"></script>