<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
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
            <div id="account-dashboard" class="col-md-10 col-12 row">
                <div class="col-md-12">
                    <div class="user-detail align-items-center">
                        <div class="ml-3">
                            <p class="greeting">
                                <?= !empty($this->lang->line('hello')) ? $this->lang->line('hello') : 'Hello' ?>
                                <span class="text-dark font-weight-bold"><?= $user->username ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            
                <?php /* ?>
                <div class='col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4'>
                    <a href='<?= base_url('my-account/basic_profile') ?>' class="link-color link-to-tab">
                       
                        <div class="icon-box text-center">
                            <span class="icon-box-icon icon-account">
                                <i class="w-icon-user"></i>
                            </span>
                            <div class="icon-box-content">
                                <p class="text-uppercase mb-0"><?= !empty($this->lang->line('profile')) ? $this->lang->line('profile') : 'PROFILE' ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class='col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4'>
                    <a href='<?= base_url('my-account/orders') ?>' class="link-color">
                        
                        <div class="icon-box text-center">
                            <span class="icon-box-icon icon-orders">
                                <i class="w-icon-orders"></i>
                            </span>
                            <div class="icon-box-content">
                                <p class="text-uppercase mb-0"><?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'ORDERS' ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class='col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4'>
                    <a href='<?= base_url('my-account/notifications') ?>' class="link-color">
                        
                        <div class="icon-box text-center">
                            <span class="icon-box-icon icon-orders">
                                <i class="w-icon-gift2"></i>
                            </span>
                            <div class="icon-box-content">
                                <p class="text-uppercase mb-0"><?= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'NOTIFICATION' ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class='col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4'>
                    <a href='<?= base_url('my-account/Favorite') ?>' class="link-color">
                       
                        <div class="icon-box text-center">
                            <span class="icon-box-icon icon-orders">
                                <i class="w-icon-heart"></i>
                            </span>
                            <div class="icon-box-content">
                                <p class="text-uppercase mb-0"><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <?php /* ?>
                <div class='col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4'>
                    <a href='<?= base_url('my-account/manage-address') ?>' class="link-color">
                        <?php /* ?>
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'ADDRESS' ?>
                        </div>
                        <div class='card-body'>
                            <i class="far fa-id-badge dashboard-icon link-color fa-lg"></i>
                        </div>
                        <?php */ ?><?php /* ?>
                        <div class="icon-box text-center">
                            <span class="icon-box-icon icon-orders">
                                <i class="w-icon-map-marker"></i>
                            </span>
                            <div class="icon-box-content">
                                <p class="text-uppercase mb-0"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'ADDRESS' ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php */ ?>
                
                <?php /* ?>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/wallet') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'WALLET' ?>
                        </div>
                        <div class='card-body'>
                            <i class="fa fa-wallet dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                
                
                <div class='col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4'>
                    <a href='<?= base_url('my-account/transactions') ?>' class="link-color">
                        <?php /* ?>
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('transaction')) ? $this->lang->line('transaction') : 'TRANSACTION' ?>
                        </div>
                        <div class='card-body'>
                            <i class="fas fa-exchange-alt dashboard-icon link-color fa-lg"></i>
                        </div>
                        <?php /*/ ?><?php /* ?>
                        <div class="icon-box text-center">
                            <span class="icon-box-icon icon-orders">
                                <i class="w-icon-table"></i>
                            </span>
                            <div class="icon-box-content">
                                <p class="text-uppercase mb-0"><?= !empty($this->lang->line('transaction')) ? $this->lang->line('transaction') : 'TRANSACTION' ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php */ ?>
                
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>