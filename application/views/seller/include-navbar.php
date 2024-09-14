<?php $current_version = get_current_version(); ?>
<nav class="main-header navbar navbar-expand navbar-warning navbar-light navbar-orange-- bg-white">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <?php /* ?>
        <li class="nav-item my-auto">
            <span class="badge badge-success">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
        </li>
        <?php */ ?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <?php /*if (ALLOW_MODIFICATION == 0) { ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        <?php }*/ ?>
        
        <?php /* ?>
        <li class="nav-item my-auto">
            <a class="btn-sm btn-danger" href="<?php echo base_url();?>" target="_blank">View Site</a>
        </li>
        <?php */ ?>
        <li class="nav-item">
            <a class="label-down text-dark"  href="<?= base_url() ?>">
                <i class="fas fa-globe" style="font-size: 24px;"></i>
                <span class="wishlist-label d-lg-show">Visit Site</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="label-down"  href="<?= base_url('seller/home') ?>">
                <i class="hc-icon-dashboard hc-icon-top-menu"></i>
                <span class="wishlist-label d-lg-show">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="label-down"  href="<?= base_url('seller/product') ?>">
                <i class="hc-icon-my-products hc-icon-top-menu"></i>
                <span class="wishlist-label d-lg-show">My Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="label-down"  href="<?= base_url('seller/orders/show_orders/7') ?>">
                <i class="hc-icon-orders hc-icon-top-menu">
                    <?php 
                    $user_id = $this->session->userdata('user_id');
                    //$new_order_count = mfg_orders_count(array('received'), $user_id);
                    $new_order_count = mfg_orders_count(array('received','send_payment_confirmation','send_mfg_payment_ack'), $user_id);
                    ?>
                    <?php if($new_order_count) { ?> 
                    <span class="note-count"><?php echo $new_order_count; ?></span>
                    <?php } ?>
                </i>
                <span class="wishlist-label d-lg-show">Orders</span>
            </a>
        </li>
        <!--<li class="nav-item">
            <a class="label-down"  href="#">
                <i class="hc-icon-msgs hc-icon-top-menu"></i>
                <span class="wishlist-label d-lg-show">Messages</span>
            </a>
        </li>-->
        <li class="nav-item">
            <a class="label-down"  href="<?= base_url('seller/profile') ?>">
                <i class="hc-icon-profile hc-icon-top-menu"></i>
                <span class="wishlist-label d-lg-show">Profile</span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="label-down" data-toggle="dropdown" href="#">
                <i class="hc-icon-account hc-icon-top-menu"></i>
                <span class="wishlist-label d-lg-show">Account</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <div class="dropdown-divider">

                </div>
                <div class="dropdown-divider"></div>

                <?php if ($this->ion_auth->is_admin()) { ?>
                    <a href="#" class="dropdown-item">Welcome <b><?= ucfirst($this->ion_auth->user()->row()->username) ?> </b> ! </a>
                    <a href="<?= base_url('admin/home/profile') ?>" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="<?= base_url('admin/home/logout') ?>" class="dropdown-item">
                        <i class="fa fa-sign-out-alt mr-2"></i> Log Out
                    </a>
                <?php } else { ?>
                    <!--<a href="#" class="dropdown-item">Welcome <b><?= ucfirst($this->ion_auth->user()->row()->username) ?> </b>! </a>-->
                    <!--<a href="<?= base_url('seller/home/profile') ?>" class="dropdown-item"><i class="fas fa-user mr-2"></i> Profile </a>-->
                    <a href="<?= base_url('seller/home') ?>" class="dropdown-item "> Dashboard </a>
                    <a href="<?= base_url('seller/product') ?>" class="dropdown-item "> My Products </a>
                    <a href="<?= base_url('seller/orders') ?>" class="dropdown-item "> Orders </a>
                    <a href="<?= base_url('seller/profile') ?>" class="dropdown-item "> Profile </a>
                    <a href="<?= base_url('seller/promotion') ?>" class="dropdown-item "> Promotion </a>
                    <a href="<?= base_url('seller/subscriptions') ?>" class="dropdown-item "> Subscriptions </a>
                    <a href="<?= base_url('seller/profile/settings') ?>" class="dropdown-item "> Settings </a>
                    <a href="<?= base_url('help') ?>" class="dropdown-item "> Help </a>
                    <a href="<?= base_url('seller/home/logout') ?>" class="dropdown-item "> Log Out </a>
                <?php } ?>
            </div>
        </li>
    </ul>
</nav>