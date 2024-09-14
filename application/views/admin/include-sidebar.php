<?php $settings = get_settings('system_settings', true); ?>
<style>
.main-sidebar.sidebar-dark-indigo {background: #FFF;overflow: hidden;}
.sidebar-dark-indigo .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-indigo .nav-sidebar > .nav-item > .nav-link.active, [class*="sidebar-dark-"] .nav-sidebar > .nav-item.menu-open > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item:hover > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-link:focus{background-color: #f0f1ff;color: #2d3092;}
.active > i.nav-icon{color: #2d3092 !important;}
.nav-sidebar > .nav-item{border-bottom: 1px solid #CCC;margin: 0px 5px;border-left: 1px solid #CCC;border-right: 1px solid #CCC;}
.nav-sidebar > .nav-item:first-child{border-top: 1px solid #CCC;}
.sidebar a {color: #000;}
[class*="sidebar-dark-"] .sidebar a,  [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link:hover, [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link {
  color: #000;
}
.sidebar-mini.sidebar-collapse .nav-sidebar .nav-link p{width: 55px;}
[class*="sidebar-dark"] .brand-link{border-bottom: 1px solid #CCC;}
.nav-sidebar li.nav-item {text-align: center;}
.nav-sidebar li.nav-item li.nav-item{text-align: left;}

@media (min-width: 768px){
    .main-sidebar, .main-sidebar::before{width: /*165px*/220px;}
    body:not(.sidebar-mini-md) .content-wrapper, body:not(.sidebar-mini-md) .main-footer, body:not(.sidebar-mini-md) .main-header{margin-left: /*165px*/220px;}
    .sidebar-mini.sidebar-collapse .main-sidebar.sidebar-focused, .sidebar-mini.sidebar-collapse .main-sidebar:hover {
      width: /*165px*/220px;
    }
    .layout-fixed .brand-link {width: /*165px*/220px;}
    .nav-sidebar > .nav-item {width: 210px;}
    .sidebar-mini .main-sidebar:not(.sidebar-no-expand):hover .nav-flat.nav-sidebar.nav-child-indent .nav-treeview .nav-icon, .nav-flat .nav-item > .nav-link > .nav-icon {margin-left: .1rem;}
    
}
</style>
<aside class="main-sidebar elevation-2 sidebar-dark-indigo">
    <!-- Brand Logo -->
    <a href="<?= base_url('admin/home') ?>" class="brand-link">
        <img src="<?= base_url()  . get_settings('favicon') ?>" alt="<?= $settings['app_name']; ?>" title="<?= $settings['app_name']; ?>" class="brand-image">
        <span class="brand-text font-weight-light small"><!--<?= $settings['app_name']; ?>-->&nbsp;</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


                <li class="nav-item has-treeview">
                    <a href="<?= base_url('/admin/home') ?>" class="nav-link">
                        <i class="nav-icon fas fa-th-large text-primary--"></i><br />
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <?php if (has_permissions('read', 'orders')) { ?>
                    <li class="nav-item"><!--  has-treeview -->
                        <a href="<?= base_url('admin/orders/') ?>" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart text-warning--"></i><br />
                            <p>
                                Orders
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>
                        
                    </li>
                <?php } ?>
                
                <li class="nav-item">
                    <a href="<?= base_url('admin/orders/accounts/') ?>" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag text-black"></i><br />
                        <p>
                            Accounts
                        </p>
                    </a>
                </li>
                
                
                <?php if (has_permissions('read', 'product') || has_permissions('read', 'attribute') || has_permissions('read', 'attribute_set') || has_permissions('read', 'attribute_value') || has_permissions('read', 'tax') || has_permissions('read', 'product_order')) { ?>
                    <li class="nav-item has-treeview ">
                        <a href="#" class="nav-link menu-open">
                            <i class="nav-icon fas fa-cubes text-primary--"></i><br />
                            <p>
                                Products
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            
                            
                            <?php if (has_permissions('read', 'product')) { ?>
                                <?php /* ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/product/bulk-upload') ?>" class="nav-link">
                                        <i class="fas fa-upload nav-icon"></i>
                                        <p>Bulk upload</p>
                                    </a>
                                </li>
                                <?php */ ?>
                            <?php } ?>
                            <?php if (has_permissions('read', 'product')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/product/') ?>" class="nav-link">
                                        <i class="fas fa-boxes nav-icon"></i>
                                        <p>Manage Products</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (has_permissions('read', 'product_order')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/product/product-order') ?>" class="nav-link">
                                        <i class="fa fa-bars nav-icon"></i>
                                        <p>Products Order</p>
                                    </a>
                                </li>
                            <?php } ?>
                            
                            <?php if (has_permissions('read', 'tax')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/taxes/manage-taxes') ?>" class="nav-link">
                                        <i class="fas fa-percentage nav-icon"></i>
                                        <p>Tax</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                
                

                <?php if (has_permissions('read', 'seller')) { ?>
                    <li class="nav-item"><!--  has-treeview-->
                        <a href="<?= base_url('admin/sellers/') ?>" class="nav-link">
                            <i class="nav-icon fas fa-store text-danger--"></i><br />
                            <p>
                                Manufactures
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>
                    </li>
                <?php } ?>
                
                <?php if (has_permissions('read', 'retailer')) { ?>
                    <li class="nav-item"><!--  has-treeview -->
                        <a href="<?= base_url('admin/retailers/') ?>" class="nav-link">
                            <i class="nav-icon fas fa-store text-danger--"></i><br />
                            <p>
                                Retailers
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a href="<?= base_url('admin/webinars/') ?>" class="nav-link">
                        <i class="nav-icon fas fa-video text-black"></i><br />
                        <p>
                            Webinars
                        </p>
                    </a>
                </li>

                <?php if (has_permissions('read', 'media')) { ?>
                    <?php /* ?>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/media/') ?>" class="nav-link">
                            <i class="nav-icon fas fa-icons text-danger"></i>
                            <p>
                                Media
                            </p>
                        </a>
                    </li>
                    <?php */ ?>
                <?php } ?>
                
                
                <?php //if (has_permissions('read', 'super_categories')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-bullseye text-success--"></i><br />
                            <p>
                                Categories
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (has_permissions('read', 'super_categories')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/super_category/') ?>" class="nav-link">
                                        <i class="fa fa-bullseye nav-icon"></i>
                                        <p>Super Categories</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php /*if (has_permissions('read', 'super_category_order')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/super_category/super_category-order') ?>" class="nav-link">
                                        <i class="fa fa-bars nav-icon"></i>
                                        <p>Super Category Order</p>
                                    </a>
                                </li>
                            <?php }*/ ?>
                            
                            <?php if (has_permissions('read', 'categories')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/category/') ?>" class="nav-link">
                                        <i class="fa fa-bullseye nav-icon"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php /*if (has_permissions('read', 'category_order')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/category/category-order') ?>" class="nav-link">
                                        <i class="fa fa-bars nav-icon"></i>
                                        <p>Category Order</p>
                                    </a>
                                </li>
                            <?php }*/ ?>
                            
                            <?php if (has_permissions('read', 'insect_pests')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/insect_pest/') ?>" class="nav-link">
                                        <i class="fa fa-bullseye nav-icon"></i>
                                        <p>Insect / Pests</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php //} ?>

                <?php /*if (has_permissions('read', 'categories')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-bullseye text-success--"></i><br />
                            <p>
                                Categories
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (has_permissions('read', 'categories')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/category/') ?>" class="nav-link">
                                        <i class="fa fa-bullseye nav-icon"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (has_permissions('read', 'category_order')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/category/category-order') ?>" class="nav-link">
                                        <i class="fa fa-bars nav-icon"></i>
                                        <p>Category Order</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }*/ ?>
                
                <?php /*if (has_permissions('read', 'insect_pests')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-bullseye text-success--"></i><br />
                            <p>
                                Insect / Pests
                                <!--<i class="right fas fa-angle-left"></i>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (has_permissions('read', 'insect_pests')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/insect_pest/') ?>" class="nav-link">
                                        <i class="fa fa-bullseye nav-icon"></i>
                                        <p>Insect / Pests</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php /*if (has_permissions('read', 'insect_pests_order')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/insect_pest/insect_pest-order') ?>" class="nav-link">
                                        <i class="fa fa-bars nav-icon"></i>
                                        <p>Insect / Pests Order</p>
                                    </a>
                                </li>
                            <?php }*//* ?>
                        </ul>
                    </li>
                <?php }*/ ?>

                <?php if (has_permissions('read', 'home_slider_images')) { ?>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/slider/manage-slider') ?>" class="nav-link">
                            <i class="nav-icon far fa-image text-success--"></i><br />
                            <p>
                                Sliders
                            </p>
                        </a>
                    </li>
                <?php } ?>

                <?php if (has_permissions('read', 'new_offer_images')) { ?>
                    <?php /* ?>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/offer/manage-offer') ?>" class="nav-link">
                            <i class="nav-icon fa fa-gift text-primary"></i>
                            <p>
                                Offers
                            </p>
                        </a>
                    </li>
                    <?php */ ?>
                <?php } ?>
                <?php if (has_permissions('read', 'support_tickets')) { ?>
                    <?php /* ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link menu-open">
                            <i class="nav-icon fas fa-ticket-alt text-danger"></i>
                            <p>
                                Support Tickets
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/tickets/ticket-types') ?>" class="nav-link">
                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                    <p>Ticket Types</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/tickets') ?>" class="nav-link">
                                    <i class="fas fa-ticket-alt nav-icon"></i>
                                    <p>Tickets</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php */ ?>
                <?php } ?>
                <?php if (has_permissions('read', 'promo_code')) { ?>
                    <?php /* ?>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/promo-code/manage-promo-code') ?>" class="nav-link">
                            <i class="nav-icon fa fa-puzzle-piece text-warning"></i>
                            <p>
                                Promo code
                            </p>
                        </a>
                    </li>
                    <?php */ ?>
                <?php } ?>
                <?php if (has_permissions('read', 'featured_section')) { ?>
                    <?php /* ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link menu-open">
                            <i class="nav-icon fas fa-layer-group text-danger"></i>
                            <p>
                                Featured Sections
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/featured-sections/') ?>" class="nav-link">
                                    <i class="fas fa-folder-plus nav-icon"></i>
                                    <p>Manage Sections</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/featured-sections/section-order') ?>" class="nav-link">
                                    <i class="fa fa-bars nav-icon"></i>
                                    <p>Sections Order</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php */ ?>
                <?php } ?>
                <?php if (has_permissions('read', 'megamenu')) { ?>
                    <?php /* ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link menu-open">
                            <i class="nav-icon fas fa-layer-group text-danger"></i>
                            <p>
                                Mega Menus
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/megamenus/') ?>" class="nav-link">
                                    <i class="fas fa-folder-plus nav-icon"></i>
                                    <p>Manage Mega Menu</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/megamenus/megamenu-order') ?>" class="nav-link">
                                    <i class="fa fa-bars nav-icon"></i>
                                    <p>Mega Menu Order</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php */ ?>
                <?php } ?>
                
                <?php if (has_permissions('read', 'category_block')) { ?>
                    <?php /* ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link menu-open">
                            <i class="nav-icon fas fa-layer-group text-primary"></i>
                            <p>
                                Category Blocks
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/category_blocks/') ?>" class="nav-link">
                                    <i class="fas fa-folder-plus nav-icon"></i>
                                    <p>Manage Category Block</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/category_blocks/category_block-order') ?>" class="nav-link">
                                    <i class="fa fa-bars nav-icon"></i>
                                    <p>Category Block Order</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php */ ?>
                <?php } ?>
                
                <?php if (has_permissions('read', 'megamenu_block')) { ?>
                    <?php /* ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link menu-open">
                            <i class="nav-icon fas fa-layer-group text-success"></i>
                            <p>
                                Megamenu Blocks
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/megamenu_blocks/') ?>" class="nav-link">
                                    <i class="fas fa-folder-plus nav-icon"></i>
                                    <p>Manage Megamenu Block</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/megamenu_blocks/megamenu_block-order') ?>" class="nav-link">
                                    <i class="fa fa-bars nav-icon"></i>
                                    <p>Megamenu Block Order</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php */ ?>
                <?php } ?>
                
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-wrench text-primary--"></i><br />
                        <p>
                            Settings
                            <!--<i class="right fas fa-angle-left"></i>-->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/about-us') ?>" class="nav-link">
                                <i class="fas fa-info-circle nav-icon "></i>
                                <p>About Us</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/careers') ?>" class="nav-link">
                                <i class="fas fa-briefcase nav-icon "></i>
                                <p>Careers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/faq') ?>" class="nav-link">
                                <i class="fas fa-briefcase nav-icon "></i>
                                <p>FAQ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/privacy-policy') ?>" class="nav-link">
                                <i class="fa fa-user-secret nav-icon "></i>
                                <p>Privacy Policy</p>
                            </a>
                        </li>
                        <!--<li class="nav-item">
                            <a href="<?= base_url('admin/contact-us') ?>" class="nav-link">
                                <i class="fa fa-phone-alt nav-icon "></i>
                                <p>Contact Us</p>
                            </a>
                        </li>-->
                    </ul>
                </li>
                   
                <?php /* ?>    
                <li class="nav-item">
                    <a href="<?php echo base_url();?>" class="nav-link">
                        <i class="nav-icon fa fa-globe text-primary--"></i><br />
                        <p>
                            Visit Site
                        </p>
                    </a>
                </li>
                <?php */ ?>
                
                <li class="nav-item">
                    <a href="<?= base_url('admin/home/logout/') ?>" class="nav-link">
                        <i class="fas fa-lock nav-icon"></i><br />
                        <p>Logout</p>
                    </a>
                </li>
                
                <?php /* ?>
                <?php if (has_permissions('read', 'customers')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-user text-success"></i>
                            <p>
                                Customer
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/customer/') ?>" class="nav-link">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p> View Customers </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/customer/addresses') ?>" class="nav-link">
                                    <i class="far fa-address-book nav-icon"></i>
                                    <p> Addresses </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/transaction/view-transaction') ?>" class="nav-link">
                                    <i class="fas fa-money-bill-wave nav-icon "></i>
                                    <p> Transactions </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/transaction/customer-wallet') ?>" class="nav-link">
                                    <i class="fas fa-wallet nav-icon "></i>
                                    <p>Wallet Transactions</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                <?php } ?>
                <?php if (has_permissions('read', 'return_request')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="<?= base_url('admin/return-request') ?>" class="nav-link">
                            <i class="nav-icon fas fa-undo text-warning"></i>
                            <p>
                                Return Requests
                            </p>
                        </a>
                    </li>
                <?php } ?>
                
                <?php if (has_permissions('read', 'delivery_boy') || has_permissions('read', 'fund_transfer')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-id-card-alt text-info"></i>
                            <p>
                                Delivery Boys
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (has_permissions('read', 'delivery_boy')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/delivery-boys/manage-delivery-boy') ?>" class="nav-link text-sm">
                                        <i class="fas fa-user-cog nav-icon "></i>
                                        <p> Manage Delivery Boys </p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (has_permissions('read', 'fund_transfer')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/fund-transfer/') ?>" class="nav-link">
                                        <i class="fa fa-rupee-sign nav-icon "></i>
                                        <p>Fund Transfer</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <!-- I will permission for this module later. -->
                <?php if (has_permissions('read', 'return_request')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="<?= base_url('admin/payment-request') ?>" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave text-danger"></i>
                            <p>Payment Request</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permissions('read', 'send_notification')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="<?= base_url('admin/Notification-settings/manage-notifications') ?>" class="nav-link">
                            <i class="nav-icon fas fa-paper-plane text-success"></i>
                            <p>
                                Send Notification
                            </p>
                        </a>
                    </li>
                <?php } ?>
                
                <?php if (has_permissions('read', 'settings')) { ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-wrench text-primary"></i>
                            <p>
                                System
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/setting') ?>" class="nav-link">
                                    <i class="fas fa-store nav-icon "></i>
                                    <p>Store Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/contact-us') ?>" class="nav-link">
                                    <i class="fa fa-phone-alt nav-icon "></i>
                                    <p>Contact Us</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/about-us') ?>" class="nav-link">
                                    <i class="fas fa-info-circle nav-icon "></i>
                                    <p>About Us</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/privacy-policy') ?>" class="nav-link">
                                    <i class="fa fa-user-secret nav-icon "></i>
                                    <p>Privacy Policy</p>
                                </a>
                            </li>
                            <li class="nav-item text-sm">
                                <a href="<?= base_url('admin/admin-privacy-policy') ?>" class="nav-link">
                                    <i class="fa fa-exclamation-triangle nav-icon  "></i>
                                    <p>Admin Policies</p>
                                </a>
                            </li>
                            <li class="nav-item text-sm">
                                <a href="<?= base_url('admin/delivery-boy-privacy-policy') ?>" class="nav-link">
                                    <i class="fa fa-exclamation-triangle nav-icon  "></i>
                                    <p>Delivery Boy Policies</p>
                                </a>
                            </li>
                            <li class="nav-item text-sm">
                                <a href="<?= base_url('admin/seller-privacy-policy') ?>" class="nav-link">
                                    <i class="fa fa-exclamation-triangle nav-icon  "></i>
                                    <p>Seller Policies</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-globe-asia text-warning"></i>
                            <p>
                                Web Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/web-setting') ?>" class="nav-link">
                                    <i class="fa fa-laptop nav-icon "></i>
                                    <p>General Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/themes') ?>" class="nav-link">
                                    <i class="fa fa-palette nav-icon "></i>
                                    <p>Themes</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (has_permissions('read', 'area') || has_permissions('read', 'city') || has_permissions('read', 'zipcodes')) { ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-map-marked-alt text-danger"></i>
                            <p>
                                Location
                                <i class="right fas fa-angle-left "></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (has_permissions('read', 'zipcodes')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/area/manage-zipcodes') ?>" class="nav-link">
                                        <i class="fa fa-map-pin nav-icon "></i>
                                        <p>Zipcodes</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (has_permissions('read', 'city')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/area/manage-cities') ?>" class="nav-link">
                                        <i class="fa fa-location-arrow nav-icon "></i>
                                        <p>City</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (has_permissions('read', 'area')) { ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('admin/area/manage-areas') ?>" class="nav-link">
                                        <i class="fas fa-street-view nav-icon "></i>
                                        <p>
                                            Areas
                                        </p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-pie nav-icon text-primary"></i>
                        <p>Reports
                            <i class="right fas fa-angle-left "></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/invoice/sales-invoice') ?>" class="nav-link">
                                <i class="fa fa-chart-line nav-icon "></i>
                                <p>Sales Report</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if (has_permissions('read', 'faq')) { ?>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/faq/') ?>" class="nav-link">
                            <i class="nav-icon fas fa-question-circle text-warning"></i>
                            <p class="text">FAQ</p>
                        </a>
                    </li>
                    <?php }
                 ?>
                
                <?php */ ?>
                
                
                
                
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>