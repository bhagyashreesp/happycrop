 <?php
    $this->load->model('category_model');
    $categories = $this->category_model->get_categories(null, 8);
    $language = get_languages();
    $cookie_lang = $this->input->cookie('language', TRUE);
    $web_settings = get_settings('web_settings', true);
    ?>
    <?php
    $c_items = $this->cart_model->get_user_cart($this->session->userdata('user_id')); 
    $total_cart_qty = array_sum(array_column($c_items,'qty'));
    
    ?>
    <?php $logo = get_settings('web_logo'); ?>
    <!-- Start of Header -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-left">
                    <p class="welcome-msg">Welcome to Happy Crop</p>
                </div>
                <div class="header-right">
                    <div class="hide-arrow">
                        <a href="<?php echo base_url('about-us');?>">About Us</a>
                    </div>
                    <span class="divider d-lg-show"></span>
                    <div class="hide-arrow">
                        <a href="<?php echo base_url('contact-us');?>">Contact Us</a>
                    </div>
                    <span class="divider d-lg-show"></span>
                    <div class="hide-arrow">
                        <a href="<?php echo base_url('help');?>">Help</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Header Top -->
        <div class="header-middle bg-mobile-light-green-- sticky-content fix-top sticky-header">
            <div class="container">
                <div class="header-left mr-md-4">
                    <!--<a href="#" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle">
                    </a>-->
                    <a href="<?= base_url() ?>" class="logo ml-lg-0">
                        <img src="<?= base_url($logo) ?>" alt="logo" width="184" height="85" />
                    </a>
                    <?php if(($this->ion_auth->logged_in() && $this->ion_auth->member_status()== 1) || ($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 1) || $this->ion_auth->is_admin()) { ?>
                    <form class="mt-2 header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                         <div class="input-group md-form form-sm form-2 pl-0 h-50 mx-auto navbar-top-search-box">
                             <!-- <input > -->
                             <select class="form-control my-0 py-1 p-2 rounded-0 search_product" type="text" aria-label="Search"></select>
                             
                         </div>
                    </form>
                    <?php } ?>
                    <?php /* ?>
                    <form method="get" action="#"
                        class="">
                        
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search in..."
                            required />
                        <button class="btn btn-search" type="submit"><i class="w-icon-search"></i>
                        </button>
                    </form>
                    <?php */ ?>
                </div>
                
                <div class="header-right ml-4">
                    <?php if ($this->ion_auth->logged_in()) { ?>
                        <?php if($this->ion_auth->member_status()== 1) { ?> 
                        <?php
                        $total_wish_count = 0;//retailer_wish_count($this->ion_auth->get_user_id());
                        $total_noti_count = retailer_orders_count(array('payment_demand','send_payment_confirmation','send_invoice','complaint_msg'), $this->ion_auth->get_user_id());
                        ?>
                        <a class="wishlist label-down link "  href="<?= base_url('my-account') ?>">
                            <i class="hc-icon-dashboard hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Dashboard</span>
                        </a>
                        <a class="wishlist label-down link  d-lg-show"  href="<?= base_url('my-account/favorites') ?>">
                            <i class="hc-icon-wishlist hc-icon-top-menu position-relative">
                                <?php if($total_wish_count) { ?> 
                                <span class="note-count"><?php echo $total_wish_count; ?></span>
                                <?php } ?>
                            </i>
                            <span class="wishlist-label d-lg-show">Wishlist</span>
                        </a>
                        <a class="wishlist label-down link  d-lg-show-- position-relative"  href="<?php echo ($total_noti_count > 0) ? base_url('my-account/orders/6') : base_url('my-account/orders'); ?>">
                            <i class="hc-icon-orders hc-icon-top-menu  position-relative"></i>
                            <?php if($total_noti_count) { ?> 
                            <span class="note-count"><?php echo $total_noti_count; ?></span>
                            <?php } ?>
                            <span class="wishlist-label d-lg-show">Orders</span>
                        </a>
                        <!--<a class="wishlist label-down link "  href="<?= base_url() ?>">
                            <i class="hc-icon-msgs hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Messages</span>
                        </a>-->
                        <a class="wishlist label-down link "  href="<?= base_url('my-account/basic-profile') ?>">
                            <i class="hc-icon-profile hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Profile</span>
                        </a>
                        <?php } else if($this->ion_auth->is_seller()) { ?>
                        
                        <?php if($this->ion_auth->seller_status()== 1) { ?> 
                        <a class="wishlist label-down link "  href="<?= base_url('seller/home') ?>">
                            <i class="hc-icon-dashboard hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Dashboard</span>
                        </a>
                        <a class="wishlist label-down link  d-lg-show"  href="<?= base_url('seller/product') ?>">
                            <i class="hc-icon-my-products hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">My Products</span>
                        </a>
                        <a class="wishlist label-down link  d-lg-show"  href="<?= base_url('seller/orders') ?>">
                            <i class="hc-icon-orders hc-icon-top-menu position-relative">
                            <?php 
                            $user_id = $this->session->userdata('user_id');
                            $new_order_count = mfg_orders_count(array('received'), $user_id);
                            ?>
                            <?php if($new_order_count) { ?> 
                            <span class="note-count"><?php echo $new_order_count; ?></span>
                            <?php } ?>
                            </i>
                            <span class="wishlist-label d-lg-show">Orders</span>
                        </a>
                        <!--<a class="wishlist label-down link "  href="<?= base_url() ?>">
                            <i class="hc-icon-msgs hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Messages</span>
                        </a>-->
                        <a class="wishlist label-down link "  href="<?= base_url('seller/profile') ?>">
                            <i class="hc-icon-profile hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Profile</span>
                        </a>
                        <?php } ?>
                        
                        <?php } else if($this->ion_auth->is_admin()) { ?>
                        <a class="wishlist label-down link "  href="<?= base_url('admin/home') ?>">
                            <i class="hc-icon-dashboard hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Dashboard</span>
                        </a>
                        <a class="wishlist label-down link  d-lg-show"  href="<?= base_url('admin/product') ?>">
                            <i class="hc-icon-my-products hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Products</span>
                        </a>
                        <a class="wishlist label-down link  d-lg-show"  href="<?= base_url('admin/orders') ?>">
                            <i class="hc-icon-orders hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Orders</span>
                        </a>
                        <!--<a class="wishlist label-down link "  href="<?= base_url() ?>">
                            <i class="hc-icon-msgs hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Messages</span>
                        </a>-->
                        <a class="wishlist label-down link "  href="<?= base_url('admin/home/profile') ?>">
                            <i class="hc-icon-profile hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Profile</span>
                        </a>
                        <?php } ?> 
                        <a class="wishlist label-down link "  href="<?= base_url('my-account/quickbill') ?>">
                            <i class="hc-icon-dashboard hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Quick Bill</span>
                        </a>
                        <a class="wishlist label-down link " data-toggle="dropdown" href="#">
                            <i class="hc-icon-account hc-icon-top-menu"></i>
                            <span class="wishlist-label d-lg-show">Account</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg">
                            <?php /* ?>
                            <a href="<?= base_url('my-account/wallet') ?>" class="dropdown-item"><i class="fas fa-wallet mr-2 text-primary link-color"></i> <?= $settings['currency'] . ' ' . $user->balance ?></a>
                            <?php */ ?>
                            <?php if($this->ion_auth->member_status()== 1) { ?> 
                             <a href="<?= base_url() ?>" class="dropdown-item"> <?= ($this->lang->line('home')!='') ? $this->lang->line('home') : 'Home' ?> </a>
                            <a href="<?= base_url('my-account') ?>" class="dropdown-item"> <?= ($this->lang->line('dashboard')!='') ? $this->lang->line('dashboard') : 'Dashboard' ?> </a>
                            <a href="<?= base_url('my-account/orders') ?>" class="dropdown-item"> <?= ($this->lang->line('orders')!='') ? $this->lang->line('orders') : 'Orders' ?> </a>
                            <a href="<?= base_url('my-account/purchasebill') ?>" class="dropdown-item"> <?= ($this->lang->line('accounts')!='') ? $this->lang->line('accounts') : 'Accounts' ?> </a>
                            <a href="<?= base_url('my-account/basic-profile') ?>" class="dropdown-item"> <?= ($this->lang->line('profile')!='') ? $this->lang->line('profile') : 'Profile' ?> </a>
                            <!--<a href="#" class="dropdown-item"> <?= ($this->lang->line('messages')!='') ? $this->lang->line('messages') : 'Messages' ?> </a>-->
                            <a href="<?= base_url('my-account/favorites') ?>" class="dropdown-item"> Shortlisted Products</a>
                            <a href="<?= base_url('my-account/subscriptions') ?>" class="dropdown-item"> Subscriptions </a>
                            <a href="<?= base_url('my-account/settings') ?>" class="dropdown-item"> Settings </a>
                            <a href="<?php echo base_url('help');?>" class="dropdown-item"> Help </a>
                            <?php } ?>
                            <a href="<?= base_url('login/logout') ?>" class="dropdown-item"><?= ($this->lang->line('logout')!='') ? $this->lang->line('logout') : 'Logout' ?></a>
                        </div>
                        <?php if($this->ion_auth->member_status()== 1) { ?> 
                        <!--
                        <a class="wishlist label-down link d-xs-show" href="<?= base_url('my-account/notifications') ?>">
                            <i class="w-icon-star-square"></i>
                            <span class="wishlist-label d-lg-show">Notifications</span>
                        </a>
                        -->
                        
                        <?php $page = $this->uri->segment(2) == 'checkout' ? 'checkout' : '' ?>
                        <?php if ($page == 'checkout') { ?>
                         <a class="shopping-cart-sidebar-btn2  wishlist position-relative label-down link " href="<?= base_url('cart') ?>">
                            <i class="hc-icon-cart hc-icon-top-menu">
                                <span class="cart-count2 cart-count"><?php echo $total_cart_qty; ?></span>
                            </i>
                            <span class="wishlist-label d-lg-show">Cart</span>
                         </a>
                        
                        <?php } else { ?>
                         <a class="shopping-cart-sidebar-btn2 wishlist position-relative label-down link " href="<?= base_url('cart') ?>"><?php /*  onclick="openCartSidebar()" */ ?>
                            <i class="hc-icon-cart hc-icon-top-menu">
                                <span class="cart-count2 cart-count"><?php echo $total_cart_qty; ?></span>
                            </i>
                            <span class="wishlist-label d-lg-show">Cart</span>
                         </a>
                        <?php } ?>
                        <?php } ?>
                    <?php } else {  ?>
                        <?php /* ?>
                        <div class="header-call d-xs-show d-lg-flex align-items-center">
                            <a href="tel:#" class="w-icon-call"></a>
                            <div class="call-info d-lg-show">
                                <h4 class="chat font-weight-normal font-size-md text-normal ls-normal text-light mb-0">
                                    <a href="tel:<?= $web_settings['support_number'] ?>" class="text-capitalize">Call</a> :</h4>
                                <a href="tel:<?= $web_settings['support_number'] ?>" class="phone-number font-weight-bolder ls-50"><?= $web_settings['support_number'] ?></a>
                            </div>
                        </div>
                        <?php */ ?>
                    <?php } ?>
                    
                    <?php /* ?>
                    <div class="dropdown cart-dropdown cart-offcanvas mr-0 mr-lg-2">
                        <div class="cart-overlay"></div>
                        <a href="#" class="cart-toggle label-down link">
                            <i class="w-icon-cart">
                                <span class="cart-count2"></span>
                            </i>
                            <span class="cart-label">Cart</span>
                        </a>
                    </div>
                    <?php */ ?>
                    
                </div>
            </div>
        </div>
        <div class="shopping-cart-sidebar is-closed-right bg-white">
            <div class="container header ">
             <div class="row my-2 text-uppercase d-flex align-items-center">
                 <div class="col-8 title cart-header">
                     <span><?= !empty($this->lang->line('shopping_cart')) ? $this->lang->line('shopping_cart') : 'Shopping Cart' ?></span>
                 </div>
                 <div class="col-4 text-right close-sidebar"> <a href='#' onclick="closeNav();"><?= !empty($this->lang->line('close')) ? $this->lang->line('close') : 'Close' ?> <i class="fa fa-times"></i></a></div>
             </div>
            </div>
            
            
            
            <hr class="m-0">
            <div class="text-center mt-2"><a class="button button-danger button-rounded" href="<?= base_url('products') ?>"> <?= !empty($this->lang->line('return_to_shop')) ? $this->lang->line('return_to_shop') : 'Return To Shop' ?></a></div>
            <div class="shopping-cart-sm container bg-white rounded mt-4 mb-2" id="cart-item-sidebar">
                <div class="products">
                <?php
                if (isset($user->id)) {
                    $cart_items = $this->cart_model->get_user_cart($user->id);
                    if (count($cart_items) != 0) {
                        foreach ($cart_items as $items) {
                            $price = $items['special_price'] != '' && $items['special_price'] > 0 && $items['special_price'] != null ? $items['special_price'] : $items['price'];
                ?>
                         <div class="row product product-cart">
                         
                             <div class="cart-product product-sm col-md-12">
                                 <div class="product-image">
                                     <img class="pic-1 lazy" data-src="<?= base_url($items['image']) ?>" alt="<?= html_escape($items['name']) ?>" title="<?= html_escape($items['name']) ?>">
                                 </div>
                                 
                                 <div class="product-details2 product-detail">
                                     <div class="product-title product-name"><?= html_escape($items['name']) ?></div>
                                     <span>
                                         <?php if (!empty($items['product_variants'])) { ?>
                                             <?= str_replace(',', ' | ', $items['product_variants'][0]['variant_values']) ?>
                                         <?php } ?>
                                     </span>
                                     <!--<p class="product-descriptions"><?= html_escape($items['short_description']) ?></p>-->
                                     <div class="price-box">
                                        <div class="product-pricing d-flex py-2 px-1 w-100">
                                             <div class="product-quantity product-sm-quantity2 px-1">
                                                 <input type="number" name="header_qty" class="form-input" value="<?= $items['qty'] ?>" data-id="<?= $items['product_variant_id'] ?>" data-price="<?= $price ?>" min="<?= $items['minimum_order_quantity'] ?>" max="<?= $items['total_allowed_quantity'] ?>" step="<?= $items['quantity_step_size'] ?>" style="padding: 0px 10px !important;height: 35px;">
                                             </div>
                                             <div class="product-price align-self-center"><?= /*$settings['currency'] . ' ' .*/ $price ?></div>
                                             <div class="product-sm-removal align-self-center">
                                                 <button class="remove-product button button-danger" data-id="<?= $items['product_variant_id'] ?>">
                                                     <i class="fa fa-trash"></i>
                                                 </button>
                                             </div>
                                             <div class="product-line-price align-self-center px-1"><?= /*$settings['currency'] . ' ' .*/ number_format($items['qty'] * $price, 2) ?></div>
                                         </div>
                                     </div>
                                 </div>
                                 
                             </div>
                         </div>
                     <?php
                        } ?>
                 <?php } else { ?>
                     <h1 class="h4 text-center"><?= !empty($this->lang->line('empty_cart_message')) ? $this->lang->line('empty_cart_message') : 'Your Cart Is Empty' ?></h1>
             <?php }
                } ?>
                </div>
            </div>
            <div class="text-center mt-2"><a class="button button-success button-rounded" href="<?= base_url('cart') ?>"><?= !empty($this->lang->line('view_cart')) ? $this->lang->line('view_cart') : 'View Cart' ?></a></div>
            </div>
            <div class='block-div' onclick="closeNav()"></div>
    </header>
    <!-- End of Header -->
    <?php if(($this->ion_auth->logged_in() && $this->ion_auth->member_status()== 1) || ($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 1) || $this->ion_auth->is_admin()) { ?>
    <div class="container  bg-mobile-light-green">
        <div class="row">
            <div class="col-md-12">
                <form class="mt-2 header-search hs-expanded hs-round d-block d-flex  d-sm-none d-lg-none d-md-none input-wrapper">
                     <div class="input-group md-form form-sm form-2 pl-0 h-50 mx-auto navbar-top-search-box">
                         <!-- <input > -->
                         <select class="form-control my-0 py-1 p-2 rounded-0 search_product" type="text" aria-label="Search"></select>
                         
                     </div>
                 </form>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php /* ?>
    
 <!-- header starts -->
 <div id="mySidenav" class="sidenav is-closed-left">
     <div class="container">
         <div class="row my-2 pr-2 text-uppercase d-flex align-items-center">
             <div class="col-12 text-right close-sidenav"> <a href='#' onclick="closeNav();"><?= !empty($this->lang->line('close')) ? $this->lang->line('close') : 'Close' ?> <i class="fa fa-times"></i></a></div>
         </div>
     </div>
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <select class='search_product' name="search"></select>
             </div>
         </div>
     </div>
     <hr>
     <!-- Nav Mobile tabs -->
     <ul class="nav nav-tabs" role="tablist">
         <li class="nav-item">
             <a class="nav-link active show" data-toggle="tab" href="#Menu"><?= !empty($this->lang->line('menu')) ? $this->lang->line('menu') : 'Menu' ?></a>
         </li>
         <li class="nav-item">
             <a class="nav-link" data-toggle="tab" href="#Categories"><?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Category ' ?></a>
         </li>
     </ul>
     <!-- Tab panes -->
     <div class="tab-content p-0 mt-1">
         <div id="Menu" class="tab-pane active show">
             <aside class="sidebar">
                 <div id="leftside-navigation" class="nano">
                     <ul class="nano-content">
                         <li><a href="<?= base_url('products') ?>"><i class="fas fa-box-open fa-lg"></i> <span><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></span></a></li>
                         <?php if ($this->ion_auth->logged_in()) { ?>
                             <li><a href="<?= base_url('my-account/wallet') ?>"><i class="fa fa-wallet fa-lg"></i> <?= !empty($this->lang->line('balance')) ? $this->lang->line('balance') : 'Balance' ?> <?= ' : ' . $settings['currency'] . ' ' . $user->balance ?></a></li>
                             <li><a href="<?= base_url('my-account') ?>"><i class="far fa-user-circle fa-lg"></i> <?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
                             <li><a href="<?= base_url('my-account/orders') ?>"><i class="fa fa-history fa-lg"></i> <?= !empty($this->lang->line('my_orders')) ? $this->lang->line('my_orders') : 'My Orders' ?></a></li>
                             <li><a href="<?= base_url('my-account/favorites') ?>"><i class="far fa-heart fa-lg"></i> <?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?></a></li>
                         <?php } else { ?>
                             <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="login"><i class="far fa-user-circle fa-lg"></i> <?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
                             <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="login"><i class="fa fa-history fa-lg"></i> <?= !empty($this->lang->line('my_orders')) ? $this->lang->line('my_orders') : 'My Orders' ?></a></li>
                             <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="login"><i class="far fa-heart fa-lg"></i> <?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?></a></li>
                             <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="login"><i class="fa fa-sign-in-alt fa-lg"></i> <?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></a></li>
                             <li><a href="" class="auth_model" data-izimodal-open=".auth-modal" data-value="register"><i class="fa fa-user-check fa-lg"></i> <?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></a></li>
                         <?php } ?>
                         <li><a href="<?= base_url('home/about-us') ?>"><i class="fa fa-info fa-lg"></i> <span><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></span></a></li>
                         <li><a href="<?= base_url('home/contact-us') ?>"><i class="fa fa-envelope fa-lg"></i> <span><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></span></a></li>
                         <?php if ($this->ion_auth->logged_in()) { ?>
                             <li><a href="<?= base_url('login/logout') ?>"><i class="fa fa-sign-out-alt fa-lg"></i> <?= !empty($this->lang->line('logout')) ? $this->lang->line('logout') : 'Logout' ?></a></li>
                         <?php } ?>
                         <li class="sub-menu">
                             <a href="javascript:void(0);"><i class="fa fa-language fa-lg"></i> <span><?= !empty($this->lang->line('language')) ? $this->lang->line('language') : 'Language' ?></span><i class="arrow fa fa-angle-left float-right"></i></a>
                             <ul>
                                 <?php foreach ($language as $row) { ?>
                                     <li><a href="<?= base_url('home/lang/' . strtolower($row['language'])) ?>"><?= strtoupper($row['code']) . ' - ' . ucfirst($row['language']) ?></a></li>
                                 <?php } ?>
                             </ul>
                         </li>
                     </ul>
                 </div>
             </aside>
         </div>
         <div id="Categories" class="tab-pane fade">
             <aside class="sidebar">
                 <div id="leftside-navigation" class="nano mobile-categories">
                     <ul class="nano-content">
                         <?php
                            foreach ($categories as $row) { ?>
                             <li class="sub-menu">
                                 <a href="<?= base_url('products/category/' . $row['slug']) ?>">
                                     <span class="category-span">
                                         <img class="svg-icon-image lazy" data-src="<?= $row['image'] ?>" />
                                         <span class="category-line-height"><?= $row['name'] ?></span>
                                     </span>
                                 </a>
                             </li>
                         <?php } ?>
                         <li class="see-all-category">
                             <a href="<?= base_url('home/categories') ?>"><?= !empty($this->lang->line('see_all')) ? $this->lang->line('see_all') : 'See All' ?></a>
                         </li>
                     </ul>
                 </div>
             </aside>
         </div>
     </div>
 </div>
 <div class="shopping-cart-sidebar is-closed-right bg-white">
     <div class="container header ">
         <div class="row my-2 text-uppercase d-flex align-items-center">
             <div class="col-8 title">
                 <h1><?= !empty($this->lang->line('shopping_cart')) ? $this->lang->line('shopping_cart') : 'Shopping Cart' ?></h1>
             </div>
             <div class="col-4 text-right close-sidebar"> <a href='#' onclick="closeNav();"><?= !empty($this->lang->line('close')) ? $this->lang->line('close') : 'Close' ?> <i class="fa fa-times"></i></a></div>
         </div>
     </div>
     <hr class="m-0">
     <div class="text-center mt-2"><a class="button button-danger button-rounded" href="<?= base_url('products') ?>"> <?= !empty($this->lang->line('return_to_shop')) ? $this->lang->line('return_to_shop') : 'Return To Shop' ?></a></div>
     <div class="shopping-cart-sm container bg-white rounded mt-4 mb-2" id="cart-item-sidebar">
         <?php
            if (isset($user->id)) {
                $cart_items = $this->cart_model->get_user_cart($user->id);
                if (count($cart_items) != 0) {
                    foreach ($cart_items as $items) {
                        $price = $items['special_price'] != '' && $items['special_price'] > 0 && $items['special_price'] != null ? $items['special_price'] : $items['price'];
            ?>
                     <div class="row">
                         <div class="cart-product product-sm col-md-12">
                             <div class="product-image">
                                 <img class="pic-1 lazy" data-src="<?= base_url($items['image']) ?>" alt="<?= html_escape($items['name']) ?>" title="<?= html_escape($items['name']) ?>">
                             </div>
                             <div class="product-details">
                                 <div class="product-title"><?= html_escape($items['name']) ?></div>
                                 <span>
                                     <?php if (!empty($items['product_variants'])) { ?>
                                         <?= str_replace(',', ' | ', $items['product_variants'][0]['variant_values']) ?>
                                     <?php } ?>
                                 </span>
                                 <p class="product-descriptions"><?= html_escape($items['short_description']) ?></p>
                             </div>
                             <div class="product-pricing d-flex py-2 px-1 w-100">
                                 <div class="product-price align-self-center"><?= $settings['currency'] . ' ' . $price ?></div>
                                 <div class="product-quantity product-sm-quantity px-1">
                                     <input type="number" name="header_qty" class="form-input" value="<?= $items['qty'] ?>" data-id="<?= $items['product_variant_id'] ?>" data-price="<?= $price ?>" min="<?= $items['minimum_order_quantity'] ?>" max="<?= $items['total_allowed_quantity'] ?>" step="<?= $items['quantity_step_size'] ?>">
                                 </div>
                                 <div class="product-sm-removal align-self-center">
                                     <button class="remove-product button button-danger" data-id="<?= $items['product_variant_id'] ?>">
                                         <i class="fa fa-trash"></i>
                                     </button>
                                 </div>
                                 <div class="product-line-price align-self-center px-1"><?= $settings['currency'] . ' ' . number_format($items['qty'] * $price, 2) ?></div>

                             </div>
                         </div>
                     </div>
                 <?php
                    } ?>
             <?php } else { ?>
                 <h1 class="h4 text-center"><?= !empty($this->lang->line('empty_cart_message')) ? $this->lang->line('empty_cart_message') : 'Your Cart Is Empty' ?></h1>
         <?php }
            } ?>
     </div>
     <div class="text-center mt-2"><a class="button button-success button-rounded" href="<?= base_url('cart') ?>"><?= !empty($this->lang->line('view_cart')) ? $this->lang->line('view_cart') : 'View Cart' ?></a></div>
 </div>
 <div class='block-div' onclick="closeNav()"></div>
 <header id="header" class="topper-white header-varient">
     <div class="topbar topbar-text-color">
         <div class="main-content">
             <div class="row">
                 <div class="col-lg-6 col-md-12">
                     <div class="topbar-left text-lg-left text-center">
                         <ul class="list-inline">
                             <?php if (isset($web_settings['support_number']) && !empty($web_settings['support_number'])) { ?>
                                 <li><a href="tel:<?= $web_settings['support_number'] ?>"><i class="fa fa-phone-alt"></i> <?= $web_settings['support_number'] ?></a></li>
                             <?php } ?>
                             <?php if (isset($web_settings['support_email']) && !empty($web_settings['support_email'])) { ?>
                                 <li><a href="mailto:<?= $web_settings['support_email'] ?>"> <i class="fa fa-envelope"></i> <?= $web_settings['support_email'] ?></a></li>
                             <?php } ?>
                         </ul>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-12">
                     <div class="topbar-right text-lg-right">
                         <ul class="list-inline">
                             <?php if (isset($web_settings['facebook_link']) &&  !empty($web_settings['facebook_link'])) { ?>
                                 <li><a href="<?= $web_settings['facebook_link'] ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                             <?php } ?>
                             <?php if (isset($web_settings['twitter_link']) && !empty($web_settings['twitter_link'])) { ?>
                                 <li><a href="<?= $web_settings['twitter_link'] ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                             <?php } ?>
                             <?php if (isset($web_settings['instagram_link']) &&  !empty($web_settings['instagram_link'])) { ?>
                                 <li><a href="<?= $web_settings['instagram_link'] ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                             <?php } ?>
                             <?php if (isset($web_settings['youtube_link']) &&  !empty($web_settings['youtube_link'])) { ?>
                                 <li><a href="<?= $web_settings['youtube_link'] ?>" target="_blank"><i class="fab fa-youtube"></i></a></li>
                             <?php } ?>
                             <li><a href="<?= base_url('home/contact-us') ?>" class="hide-sec"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'CONTACT US' ?></a></li>
                             <li><a href="<?= base_url('home/faq') ?>" class="hide-sec"><?= !empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQs' ?></a></li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <nav class="navbar navbar-expand-lg navbar-light bg-white main-content">
         <button class="navbar-toggler border-0" type="button" onclick="openNav()">
             <span class="navbar-toggler-icon"></span>
         </button>
         <?php $logo = get_settings('web_logo'); ?>
         <a class="navbar-brand" href="<?= base_url() ?>"><img src="<?= base_url($logo) ?>" data-src="<?= base_url($logo) ?>" class="brand-logo-link"></a>
         <?php $page = $this->uri->segment(2) == 'checkout' ? 'checkout' : '' ?>
         <?php if ($page == 'checkout') { ?>
             <a class="shopping-cart-sidebar-btn d-none" href="<?= base_url('cart') ?>">
                 <i class="fa-cart fa-cart-plus fas link-color"></i>
             </a>

         <?php } else { ?>
             <a class="shopping-cart-sidebar-btn d-none" href="#" onclick="openCartSidebar()">
                 <i class="fa-cart fa-cart-plus fas link-color"></i>
             </a>
         <?php } ?>

         <div class="navbar-collapse collapse" id="navbarNavDropdown">
             <div class="col-md-8">
                 <form class="mt-2 w-100">
                     <div class="input-group md-form form-sm form-2 pl-0 h-50 mx-auto navbar-top-search-box">
                         <!-- <input > -->
                         <select class="form-control my-0 py-1 p-2 rounded-0 search_product" type="text" aria-label="Search"></select>
                     </div>
                 </form>
             </div>
             <div class="col-md-4">
                 <div class="navbar-nav">
                     <li class="nav-item dropdown active">
                         <a class="m-1" data-toggle="dropdown" href="#">
                             <?php if ($cookie_lang) { ?>
                                 <span class="text-dark font-weight-bold"><?= ucfirst($cookie_lang) ?></span>
                             <?php } else { ?>
                                 <span class="text-dark font-weight-bold">English</span>
                             <?php } ?>
                             <i class="fas fa-angle-down link-color"></i>
                         </a>
                         <div class="dropdown-menu dropdown-menu-lg">
                             <?php foreach ($language as $row) { ?>
                                 <a href="<?= base_url('home/lang/' . strtolower($row['language'])) ?>" class="dropdown-item"><?= strtoupper($row['code']) . ' - ' . ucfirst($row['language']) ?></a>
                             <?php } ?>
                         </div>
                     </li>
                     <?php if ($this->ion_auth->logged_in()) { ?>
                         <li class="nav-item dropdown active">
                             <a class="m-1" data-toggle="dropdown" href="#"><i class="fas fa-user fa-lg link-color"></i>
                                 <span class="text-dark font-weight-bold"> <?= (isset($user->username) && !empty($user->username)) ? "Hello " . $user->username  : 'Login / Register' ?></span>
                                 <i class="fas fa-angle-down link-color"></i>
                             </a>
                             <div class="dropdown-menu dropdown-menu-lg">
                                 <a href="<?= base_url('my-account/wallet') ?>" class="dropdown-item"><i class="fas fa-wallet mr-2 text-primary link-color"></i> <?= $settings['currency'] . ' ' . $user->balance ?></a>
                                 <a href="<?= base_url('my-account') ?>" class="dropdown-item"><i class="fas fa-user mr-2 text-primary link-color"></i> <?= !empty($this->lang->line('profile')) ? $this->lang->line('profile') : 'Profile' ?> </a>
                                 <a href="<?= base_url('my-account/orders') ?>" class="dropdown-item"><i class="fas fa-history mr-2 text-primary link-color"></i> <?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'Orders' ?> </a>
                                 <a href="<?= base_url('login/logout') ?>" class="dropdown-item"><i class="fa fa-sign-out-alt mr-2 text-primary link-color"></i><?= !empty($this->lang->line('logout')) ? $this->lang->line('logout') : 'Logout' ?></a>
                             </div>
                         </li>
                     <?php } else { ?>
                         <li class="nav-item active"><a href="" class="m-2 auth_model" data-izimodal-open=".auth-modal" data-value="login"><span class="text-dark font-weight-bold"><?= !empty($this->lang->line('login')) ? $this->lang->line('login') : 'Login' ?></a></li>/
                         <li class="nav-item active"><a href="" class="m-2 auth_model" data-izimodal-open=".auth-modal" data-value="register"><span class="text-dark font-weight-bold"><?= !empty($this->lang->line('register')) ? $this->lang->line('register') : 'Register' ?></a></li>
                     <?php } ?>
                     <li class="nav-item active">
                         <a href="<?= base_url('my-account/favorites') ?>" class="p-2 header-icon">
                             <i class="far fa-heart fa-lg link-color"></i>
                         </a>
                     </li>
                     <?php $page = $this->uri->segment(2) == 'checkout' ? 'checkout' : '' ?>
                     <?php if ($page == 'checkout') { ?>
                         <li class="nav-item active">
                             <a href="<?= base_url('cart') ?>" class="p-2 header-icon">
                                 <i class="fa-cart fa-cart-plus fa-lg fas link-color"></i>
                                 <span class="badge badge-danger badge-sm" id='cart-count'><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
                             </a>
                         </li>

                     <?php } else { ?>
                         <li class="nav-item active">
                             <a href="javascript:void(0);" class="p-2 header-icon" onclick=openCartSidebar()>
                                 <i class="fa-cart fa-cart-plus fa-lg fas link-color"></i>
                                 <span class="badge badge-danger badge-sm" id='cart-count'><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
                             </a>
                         </li>
                     <?php } ?>
                 </div>
             </div>
         </div>
     </nav>
     <div class="header-bottom">
         <div class="main-content">
             <div class="row header-bottom-inner mx-0">
                 <div class="column col-left">
                     <div class="header-categories-nav <?= (current_url() == base_url() || current_url() == base_url('home')) ? 'show-menu' : 'show-menu' ?>">
                         <div class="header-categories-nav-wrap">
                             <span class="menu-opener">
                                 <span class="burger-menu"><i class="fas fa-bars"></i></span>
                                 <span class="menu-label"><?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Browse Categories' ?></span>
                                 <span class="arrow-hover"> <i class="fas fa-angle-down"></i></span>
                             </span>
                             <div class="categories-menu-dropdown vertical-navigation">
                                 <div class="menu-categorie-container">
                                     <ul class="nav vertical-nav menu">
                                         <?php
                                            foreach ($categories as $row) { ?>
                                             <a href="<?= base_url('products/category/' . $row['slug']) ?>">
                                                 <li class="category-span"><img class="svg-icon-image lazy" data-src="<?= $row['image'] ?>" />
                                                     <span class="category-line-height"><?= $row['name'] ?></span>
                                                 </li>
                                             </a>
                                         <?php } ?>
                                         <a href="<?= base_url('home/categories') ?>">
                                             <li class="see-all-category">
                                                 <?= !empty($this->lang->line('see_all')) ? $this->lang->line('see_all') : 'See All' ?>
                                             </li>
                                         </a>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="column col-center">
                     <div class="main-nav menu-left">
                         <div class="menu-main-navigation-container">
                             <div class="cd-morph-dropdown cd-dp">
                                 <a href="#0" class="nav-trigger"><?= !empty($this->lang->line('open_nav')) ? $this->lang->line('open_nav') : 'Open Nav' ?><span aria-hidden="true"></span></a>
                                 <nav class="main-nav">
                                     <ul>
                                         <li class="morph-text">
                                             <a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a>
                                         </li>
                                         <li class="morph-text">
                                             <a href="<?= base_url('products') ?>"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></a>
                                         </li>
                                         <li class="morph-text">
                                             <a href="<?= base_url('home/contact-us') ?>"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></a>
                                         </li>
                                         <li class="morph-text">
                                             <a href="<?= base_url('home/about-us') ?>"><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></a>
                                         </li>
                                         <li class="morph-text">
                                             <a href="<?= base_url('home/faq') ?>"><?= !empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQs' ?></a>
                                         </li>
                                     </ul>
                                 </nav>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
 <!-- header ends -->
 <?php */ ?>