<?php $settings = get_settings('system_settings', true); ?>
<style>
.main-sidebar.sidebar-dark-indigo {background: #FFF;overflow: hidden;}
.sidebar-dark-indigo .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-indigo .nav-sidebar > .nav-item > .nav-link.active, [class*="sidebar-dark-"] .nav-sidebar > .nav-item.menu-open > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item:hover > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-link:focus{background-color: #f0f1ff;color: #2d3092;}
.active > i.nav-icon{color: #2d3092 !important;}
.nav-sidebar > .nav-item{border-bottom: 1px solid #CCC;margin: 0px 5px;border-left: 1px solid #CCC;border-right: 1px solid #CCC;}
.nav-sidebar > .nav-item:first-child{border-top: 1px solid #CCC;}
.sidebar a {color: #000;}
[class*="sidebar-dark-"] .sidebar a {
  color: #000;
}
.sidebar-mini.sidebar-collapse .nav-sidebar .nav-link p{width: 55px;}
[class*="sidebar-dark"] .brand-link{border-bottom: 1px solid #CCC;}
li.nav-item {text-align: center;}
@media (min-width: 768px){
    .main-sidebar, .main-sidebar::before{width: 165px;}
    body:not(.sidebar-mini-md) .content-wrapper, body:not(.sidebar-mini-md) .main-footer, body:not(.sidebar-mini-md) .main-header{margin-left: 165px;}
    .sidebar-mini.sidebar-collapse .main-sidebar.sidebar-focused, .sidebar-mini.sidebar-collapse .main-sidebar:hover {
      width: 165px;
    }
    .layout-fixed .brand-link {width: 165px;}
    
}
</style>
<aside class="main-sidebar elevation-2 sidebar-dark-indigo"><!-- sidebar-dark-indigo -->
    <!-- Brand Logo -->
    <a href="<?= base_url('admin/home') ?>" class="brand-link">
        <img src="<?= base_url() . get_settings('favicon') ?>" alt="<?= $settings['app_name']; ?>" title="<?= $settings['app_name']; ?>" class="brand-image">
        <span class="brand-text font-weight-light small"><?= $settings['app_name']; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('seller/home') ?>" class="nav-link">
                        <i class="nav-icon fas fa-home text-black"></i><br />
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('seller/profile/') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user text-black"></i><br />
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('seller/orders/') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user text-black"></i><br />
                        <p>
                            Orders
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('seller/product/') ?>" class="nav-link">
                        <i class="fas fa-boxes nav-icon"></i><br />
                        <p>Products</p>
                    </a>
                </li>
                
                <?php /* ?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user text-success"></i><br />
                        <p>
                            Profile
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('seller/profile/') ?>" class="nav-link">
                                <i class="fa fa-user nav-icon"></i>
                                <p>Basic Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('seller/profile/bussiness_details/') ?>" class="nav-link">
                                <i class="fa fa-briefcase nav-icon"></i>
                                <p>Business Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('seller/profile/bank_details/') ?>" class="nav-link">
                                <i class="fa fa-building nav-icon"></i>
                                <p>Bank Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('seller/profile/gst_details/') ?>" class="nav-link">
                                <i class="fa fa-percentage nav-icon"></i>
                                <p>GST Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('seller/profile/license_details/') ?>" class="nav-link">
                                <i class="fa fa-certificate nav-icon"></i>
                                <p>License Details</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php */ ?>
                
                <?php /* ?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart text-warning"></i><br />
                        <p>
                            Orders
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('seller/orders/') ?>" class="nav-link">
                                <i class="fa fa-shopping-cart nav-icon"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('seller/orders/order-tracking') ?>" class="nav-link">
                                <i class="fa fa-map-marker-alt nav-icon"></i>
                                <p>Order Tracking</p>
                            </a>
                        </li>
                       
                    </ul>
                </li>
                 
                 
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link menu-open">
                        <i class="nav-icon fas fa-cubes text-danger"></i><br />
                        <p>
                            Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('seller/product/new_product') ?>" class="nav-link">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>Add Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('seller/product/') ?>" class="nav-link">
                                <i class="fas fa-boxes nav-icon"></i>
                                <p>Manage Products</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php */ ?>
                
                <?php /* ?>
                <li class="nav-item">
                    <a href="<?= base_url('seller/category/') ?>" class="nav-link">
                        <i class="nav-icon fas fa-bullseye text-success"></i>
                        <p>
                            Categories
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link menu-open">
                        <i class="nav-icon fas fa-cubes text-danger"></i>
                        <p>
                            Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= base_url('seller/attribute_set/') ?>" class="nav-link">
                                <i class="fa fa-cogs nav-icon"></i>
                                <p>Attribute Sets</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('seller/attribute/') ?>" class="nav-link">
                                <i class="fas fa-sliders-h nav-icon"></i>
                                <p>Attributes</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('seller/attribute_value/') ?>" class="nav-link">
                                <i class="fas fa-filter nav-icon"></i>
                                <p>Attribute Values</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="<?= base_url('seller/taxes/') ?>" class="nav-link">
                                <i class="fas fa-percentage nav-icon"></i>
                                <p>Tax</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('seller/product/create-product') ?>" class="nav-link">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>Add Products</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('seller/product/bulk-upload') ?>" class="nav-link">
                                <i class="fas fa-upload nav-icon"></i>
                                <p>Bulk upload</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('seller/product/') ?>" class="nav-link">
                                <i class="fas fa-boxes nav-icon"></i>
                                <p>Manage Products</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('seller/media/') ?>" class="nav-link">
                        <i class="nav-icon fas fa-icons text-success"></i>
                        <p>
                            Media
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('seller/transaction/wallet-transactions') ?>" class="nav-link">
                        <i class="fa fa-rupee-sign nav-icon text-warning"></i>
                        <p>Wallet Transactions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('seller/payment-request/withdrawal-requests') ?>" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-wave text-danger"></i>
                        <p> Withdrawal Requests</p>
                    </a>
                </li>
                <?php if (get_seller_permission($this->ion_auth->get_user_id(), 'customer_privacy')) { ?>
                    <li class="nav-item">
                        <a href="<?= base_url('seller/customer') ?>" class="nav-link">
                            <i class="fas fa-users nav-icon text-success"></i>
                            <p> View Customers</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt text-info"></i>
                        <p>
                            Location
                            <i class="right fas fa-angle-left "></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('seller/area/manage-zipcodes') ?>" class="nav-link">
                                <i class="fa fa-map-pin nav-icon "></i>
                                <p>Zipcodes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('seller/area/manage-cities') ?>" class="nav-link">
                                <i class="fa fa-location-arrow nav-icon "></i>
                                <p>City</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('seller/area/manage-areas') ?>" class="nav-link">
                                <i class="fas fa-street-view nav-icon "></i>
                                <p>
                                    Areas
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <?php */ ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>