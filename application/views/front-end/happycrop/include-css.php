<!-- Izimodal -->
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/iziModal.min.css' ?>" />
<!-- Favicon -->
<?php $favicon = get_settings('web_favicon');

$path = ($is_rtl == 1) ? 'rtl/' : "";
?>
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/eshop-bundle.css' ?>" />
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/' . $path . 'eshop-bundle-main.css' ?>">
<link rel="icon" href="<?=base_url($favicon)?>" type="image/gif" sizes="16x16">

<!-- Color CSS 
<link rel="stylesheet" href="<?=THEME_ASSETS_URL.'css/colors/peach.css'?>" id="color-switcher">
-->


<!-- WebFont.js -->
<script>
    WebFontConfig = {
        google: { families: ['Poppins:400,500,600,700,800'] }
    };
    (function (d) {
        var wf = d.createElement('script'), s = d.scripts[0];
        wf.src = '<?= THEME_ASSETS_URL . 'js/webfont.js'?>';
        wf.async = true;
        s.parentNode.insertBefore(wf, s);
    })(document);
</script>

<link rel="preload" href="<?= THEME_ASSETS_URL . 'vendor/fontawesome-free/webfonts/fa-regular-400.woff2'?>" as="font" type="font/woff2"
    crossorigin="anonymous"/>
<link rel="preload" href="<?= THEME_ASSETS_URL . 'vendor/fontawesome-free/webfonts/fa-solid-900.woff2'?>" as="font" type="font/woff2"
    crossorigin="anonymous"/>
<link rel="preload" href="<?= THEME_ASSETS_URL . 'vendor/fontawesome-free/webfonts/fa-brands-400.woff2'?>" as="font" type="font/woff2"
    crossorigin="anonymous">
<link rel="preload" href="<?= THEME_ASSETS_URL . 'fonts/wolmart87d5.woff?png09e'?>" as="font" type="font/woff" crossorigin="anonymous"/>

<!-- Vendor CSS -->
<link rel="stylesheet" type="text/css" href="<?= THEME_ASSETS_URL . 'vendor/fontawesome-free/css/all.min.css' ?>"/>

<!-- Plugins CSS -->
<!-- <link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'vendor/swiper/swiper-bundle.min.css' ?>"> -->
<link rel="stylesheet" type="text/css" href="<?= THEME_ASSETS_URL . 'vendor/animate/animate.min.css' ?>"/>
<link rel="stylesheet" type="text/css" href="<?= THEME_ASSETS_URL . 'vendor/magnific-popup/magnific-popup.min.css' ?>"/>
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'vendor/swiper/swiper-bundle.min.css' ?>"/>

<!-- Chartist -->
<link rel="stylesheet" href="<?= base_url('assets/admin/css/chartist.css') ?>">

<!-- Default CSS -->
<link rel="stylesheet" type="text/css" href="<?= THEME_ASSETS_URL . 'css/demo1.min.css' ?>"/>
<link rel="stylesheet" type="text/css" href="<?= THEME_ASSETS_URL . 'css/style.min.css' ?>">

<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="<?= THEME_ASSETS_URL . 'css/custom.css?v='.time(); ?>"/>


<!-- Jquery -->
<script src="<?= THEME_ASSETS_URL . 'js/jquery.min.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'js/happycrop-bundle-top-js.js' ?>"></script>
<script type="text/javascript">
    base_url = "<?= base_url() ?>";
    currency = "<?= $settings['currency'] ?>";
    csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    csrfHash = "<?= $this->security->get_csrf_hash() ?>";
</script>