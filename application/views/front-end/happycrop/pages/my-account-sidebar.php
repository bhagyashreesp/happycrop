<style>
.nav-item:not(:last-child) {margin-right: 0;}
.nav-pills .nav-link.active, .nav-pills .show > .nav-link, .nav-pills .nav-link:hover{ background-color: #f0f1ff !important;color: #2d3092;}
.nav-link.active h6{color: #2d3092;line-height: 22px;}
.nav-link h6{line-height: 22px;}
.nav-pills .nav-link {
  border-bottom: 1px solid #CCC;
  border-left: 1px solid #CCC;
  border-right: 1px solid #CCC;
  margin-bottom: 0px;
  margin-top: 0px;
  border-radius: 0 !important;
  padding: 10px;
}
.nav-pills .nav-link:first-child{border-top: 1px solid #CCC;}
.my-account-tab{border:none medium;}
</style>
<?php $current_url = current_url(); ?>
<aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
    <!-- Start of Sidebar Overlay -->
    <div class="sidebar-overlay"></div>
    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
    
    <!-- Start of Sidebar Content -->
    <div class="sidebar-content scrollable">
        <!-- Start of Sticky Sidebar -->
        <div class="sticky-sidebar">
            <div class ="widget widget-collapsible">
                <ul class="nav widget-body filter-items search-ul nav-pills nav-justified flex-column bg-white rounded-- shadow-none p-3 mb-0 my-account-tab" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link rounded <?=($current_url==base_url()) ? 'active' : ''?>" id="home" href="<?= base_url('') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-home fa-lg left-aside"></i> <br />-->
                                    <i class="nav-icon fas fa-home fa-lg text-black"></i><br />
                                    <?=!empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account')) ? 'active' : ''?>" id="dashboard" href="<?= base_url('my-account') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-th-large fa-lg left-aside"></i> <br />-->
                                    <i class="nav-icon fas fa-th-large fa-lg text-black"></i><br />
                                    <?=!empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'DASHBOARD'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/basic-profile')) ? 'active' : ''?>" id="dashboard" href="<?= base_url('my-account/basic-profile') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-user-circle fa-lg left-aside"></i> <br />-->
                                    <i class="nav-icon fas fa-user fa-lg text-black"></i> <br />
                                    <?=!empty($this->lang->line('profile')) ? $this->lang->line('profile') : 'PROFILE'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/orders')) ? 'active' : ''?>" id="order-history" href="<?= base_url('my-account/orders') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-history fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-shopping-cart fa-lg text-black "></i> <br />
                                    <?=!empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'ORDERS'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/quickbill')) ? 'active' : ''?>" id="quickbill" href="<?= base_url('my-account/quickbill') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-history fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-shopping-bag fa-lg text-black "></i> <br />
                                    <?=!empty($this->lang->line('quickbill')) ? $this->lang->line('quickbill') : 'QUICK BILL'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/purchasebill')) ? 'active' : ''?>" id="accounts" href="<?= base_url('my-account/purchasebill') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-history fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-shopping-bag fa-lg text-black "></i> <br />
                                    <?=!empty($this->lang->line('accounts')) ? $this->lang->line('accounts') : 'ACCOUNTS'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/webinars')) ? 'active' : ''?>" id="webinars" href="<?= base_url('my-account/webinars') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-history fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-video fa-lg text-black "></i> <br />
                                    <?=!empty($this->lang->line('webinars')) ? $this->lang->line('webinars') : 'WEBINARS'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <!--
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/notifications')) ? 'active' : ''?>" id="notification" href="<?= base_url('my-account/notifications') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-bell fa-lg left-aside"></i> <br />
                                    <?=!empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'NOTIFICATION'?> 
                                </h6>
                            </div>
                        </a>
                    </li>
                    -->
                    
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/favorites')) ? 'active' : ''?>" id="wishlist" href="<?= base_url('my-account/favorites') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="far fa-heart fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-heart fa-lg text-dark"></i> <br />
                                    <?=!empty($this->lang->line('Shortlisted')) ? $this->lang->line('Shortlisted') : 'Shortlisted'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/subscriptions')) ? 'active' : ''?>" id="wishlist" href="<?= base_url('my-account/subscriptions') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="far fa-heart fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-briefcase fa-lg nav-icon"></i> <br />
                                    <?=!empty($this->lang->line('Subscriptions')) ? $this->lang->line('Subscriptions') : 'Subscriptions'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/settings')) ? 'active' : ''?>" id="wishlist" href="<?= base_url('my-account/settings') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="far fa-heart fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-cog fa-lg nav-icon"></i> <br />
                                    <?=!empty($this->lang->line('settings')) ? $this->lang->line('settings') : 'Settings'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    
                    <?php /* ?>
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/manage-address')) ? 'active' : ''?>" id="v-pills-settings-tab" href="<?= base_url('my-account/manage-address') ?>" id="addresses" href="<?= base_url('my-account/manage-address') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-map-marked-alt fa-lg left-aside"></i> <br />-->
                                    <?=!empty($this->lang->line('address')) ? $this->lang->line('address') : 'ADDRESS'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <?php */ ?>
                    
                    
                    <?php /* ?>
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/wallet')) ? 'active' : ''?>" id="wallet-details" href="<?= base_url('my-account/wallet') ?>">
                            <div class="text-left py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-wallet fa-lg left-aside"></i>--> 
                                    <?=!empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'WALLET'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('my-account/transactions')) ? 'active' : ''?>" id="transaction-details" href="<?= base_url('my-account/transactions') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="far fa-money-bill-alt fa-lg left-aside"></i> <br />-->
                                    <?=!empty($this->lang->line('transaction')) ? $this->lang->line('transaction') : 'TRANSACTION'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                    <?php */ ?>
                    
                    <li class="nav-item mt-0">
                        <a class="nav-link rounded <?=($current_url==base_url('login/logout')) ? 'active' : ''?>" id="logout_btn" href="<?= base_url('login/logout') ?>">
                            <div class="text-center py-1 px-3">
                                <h6 class="mb-0">
                                    <!--<i class="fas fa-sign-out-alt fa-lg left-aside"></i> <br />-->
                                    <i class="fas fa-sign-out-alt fa-lg text-dark"></i> <br />
                                    <?=!empty($this->lang->line('logout')) ? $this->lang->line('logout') : 'LOGOUT'?>
                                </h6>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>