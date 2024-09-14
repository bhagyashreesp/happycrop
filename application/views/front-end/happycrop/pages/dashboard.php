<!-- breadcrumb -->
<style>.avt_img{max-height: 80px;}</style>
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
    <div class="main-content container -fluid">
        <div class="row mt-5 mb-5">
            <div class="col-md-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div id="account-dashboard" class="col-md-9 col-12 ">
                <?php /* ?>
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
                <?php */ ?>
                
                
                <div class="col-md-12">   
                    <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle btn-icon-left d-block d-lg-none mb-3"><!--<i class="w-icon-hamburger"></i>-->Menu</a>
                    <div class="row border-bottom- pb-3">
                        <div class="col-md-10 col-8 bg-white ">
                            <h4 class="h4 rtr-name mt-2">Hello <?php echo $retailer_data['company_name'];?></h4>
                            <h4 class="h4 rtr-name mt-0 mb-0">ID - <?php echo 'HCR'.str_pad($user->ret_no,5,"0",STR_PAD_LEFT);?></h4>
                            <p>Joined Date - <?php echo $join_date;?></p>
                        </div>
                        <div class="col-md-2 col-4 bg-white text-center">
                            <?php if(file_exists($retailer_data['logo']) && $retailer_data['logo']!='') { ?> 
                            <div class="logo">
                                <img class="avt_img" src="<?php echo base_url().$retailer_data['logo'];?>" alt="<?php echo $retailer_data['company_name'];?>"/>
                            </div>
                            <?php } else { ?>
                                <img class="avt_img" src="<?php echo base_url('assets/avatar-icon.png');?>" alt="<?php echo $retailer_data['company_name'];?>" />
                            <?php } ?>
                        </div>
                    </div>
                    <!--<div class="row pt-3 pb-3">
                        <div class="col-md-12">
                            <br />
                        </div>
                    </div>-->
                    <hr class="dsh-hr" />             
                    <h5 class="mt-2 mb-0 text-dark text-uppercase text-left">Dashboard</h5>
                    <div class="row dash-bg-gray mt-2 mb-2">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dash-widget-1 min-height-162 mt-3 mb-3">
                                        <i class="fa fa-money-bill-alt"></i>
                                        <h3><i class="fa fa-rupee-sign"></i> <?php echo number_format($orders_total_amt); ?></h3>
                                        <h4 class="">Total Purchase</h4>
                                    </div>
                                </div>
                                <!--
                                <div class="col-md-6">
                                    <div class="dash-widget-1 min-height-162 mt-3 mb-3">
                                        <i class="fa fa-chart-bar"></i>
                                        <h3>250</h3>
                                        <h4 class="">Total Volume</h4>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row mt-3">
                                <div class="col-md-4 col-6">
                                    <div class="dash-widget-1 mt-3- mb-3">
                                        <a href="<?php echo base_url('my-account/orders');?>" target="_self">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $total_orders; ?></h5>
                                            <h5 class="">Total Orders</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="dash-widget-1 mt-3- mb-3">
                                        <a href="<?php echo base_url('my-account/orders/1');?>" target="_self">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $in_process_orders; ?></h5>
                                            <h5 class="">In Process Orders</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="dash-widget-1 mt-3- mb-3">
                                        <a href="<?php echo base_url('my-account/orders/2');?>" target="_self">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $shipped_orders; ?></h5>
                                            <h5 class="">Shipped Orders</h5>
                                        </a>
                                    </div>
                                </div>
                            <!--</div>
                            <div class="row">-->
                                <div class="col-md-4 col-6">
                                    <div class="dash-widget-1 mb-3">
                                        <a href="<?php echo base_url('my-account/orders/3');?>" target="_self">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $issue_raised_orders; ?></h5>
                                            <h5 class="">Issue Raised</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="dash-widget-1 mb-3">
                                        <a href="<?php echo base_url('my-account/orders/4');?>" target="_self">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $cancelled_orders; ?></h5>
                                            <h5 class="">Cancelled Orders</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="dash-widget-1 mb-3">
                                        <a href="<?php echo base_url('my-account/orders/5');?>" target="_self">
                                            <h5 class=""><i class="fa fa-check-square"></i> <?php echo $delivered_orders; ?></h5>
                                            <h5 class="">Delivered Orders</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="dsh-hr" />
                    <style>.ct-label{color:#000;font-size: 0.9rem;font-family: Poppins, sans-serif;font-weight: 600;}</style>
                    <div class="row">
                        <div class="col-xl-6 col-12 mb-2" id="orderChartView">
                            <div class="card card-shadow chart-height">
                                <!--<div class="m-3">Product Sales</div>-->
                                <div class="card-header card-header-transparent py-20 border-0">
                                    <h4 class="float-left h4 pt-2 pb-0 pl-3">Product Orders</h4>
                                    <ul class="nav nav-pills mt-0 nav-pills-rounded chart-action float-right btn-group" role="group">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#scoreLineToDay">Day</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToWeek">Week</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToMonth">Month</a></li>
                                    </ul>
                                </div>
                                <div class="widget-content tab-content bg-white p-20">
                                    <div class="ct-chart tab-pane active scoreLineShadow" id="scoreLineToDay"></div>
                                    <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToWeek"></div>
                                    <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToMonth"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-12 mb-2" id="orderChartView2">
                            <div class="card card-shadow chart-height">
                                <!--<div class="m-3">Product Sales</div>-->
                                <div class="card-header card-header-transparent py-20 border-0">
                                    <h4 class="float-left h4 pt-2 pb-0 pl-3">Orders Count</h4>
                                    <ul class="nav nav-pills mt-0 nav-pills-rounded chart-action float-right btn-group" role="group">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#scoreLineToDay2">Day</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToWeek2">Week</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToMonth2">Month</a></li>
                                    </ul>
                                </div>
                                <div class="widget-content tab-content bg-white p-20">
                                    <div class="ct-chart tab-pane active scoreLineShadow" id="scoreLineToDay2"></div>
                                    <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToWeek2"></div>
                                    <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToMonth2"></div>
                                </div>
                            </div>
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

<section class="intro-section">
    <div class="swiper-container banner-swiper swiper-theme nav-inner pg-inner swiper-nav-lg animation-slider pg-xxl-hide nav-xxl-show nav-hide">
        <div class="swiper-wrapper">
            <?php if (isset($sliders) && !empty($sliders)) { ?>
                <?php foreach ($sliders as $row) { ?>
                    <div class="swiper-slide center-swiper-slide">
                        <a href="<?= $row['link'] ?>">
                            <img src="<?= base_url($row['image']) ?>">
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="swiper-pagination"></div>
        <button class="swiper-button-next"></button>
        <button class="swiper-button-prev"></button>
    </div>
    <!-- End of .swiper-container -->
</section>
<!-- End of .intro-section -->