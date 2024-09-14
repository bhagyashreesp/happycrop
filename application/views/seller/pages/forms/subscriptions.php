<style>
.pricing-table {
  margin-bottom: 30px;
  position: relative;
}
.pricing-table .table-price {
  color: #111111;
  font-size: 45px;
}
.pricing-table .table-price span {
  font-size: 13px;
  vertical-align: middle;
}
.pricing-table.featured {
  border: 1px solid #111111;
}
.pricing-table .table-type {
  display: inline;
}
.pricing-table .btn-signup {
  margin-right: 0;
  width: 100%;
}
.pricing-table .table-list {
  list-style: outside none none;
  padding: 0 0px 20px;
}
.pricing-table .table-list li {
  border-bottom: 0;
  font-size: 12px;
  padding: 10px 0;
}
.pricing-table .table-list li i {
  margin-right: 8px;
}
.pricing-table .package-type span {
  position: relative;
  padding-left: 15px;
  margin-left: 10px;
}
.pricing-table .package-type span::after {
  background: #2d915b;
  content: "";
  height: 20px;
  left: -20px;
  position: absolute;
  top: 11px;
  width: 20px;
}
.pricing-table .table-list li:nth-child(2n) {
  background: #F2F2F2;
}
.pricing-table .table-list li i.fa-check {color: #2fe85b;font-weight: bold;font-size: 15px;}
.pricing-table .table-list li i.fa-times {color: #ff0000;font-weight: bold;font-size: 15px;}

.pricing-table .head_bg {
  border-color: #2ECC71 rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #2ECC71;
  color: #fff;
}
.pricing-table .head_bg {
  border-style: solid;
  border-width: 90px 1411px 23px 399px;
  position: absolute;
}
.package-type {
  background-color: #F2F2F2;
  padding: 15px 9px;
  min-height: 105px;
  font-size: 20px;
}
.package-type i{display: block;margin-bottom: 10px;font-size: 20px;}
ol li{list-style: none;}
ul{padding-left: 15px;}
ol{padding-left: 0px;}
.subscribe-page{max-width: 1100px;padding: 20px;}
.subscribe-page h4{font-size: 1.3rem;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Subscriptions</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Subscriptions</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid p-3">
            <div class="card  content-area p-2">    
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="subscribe-page">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="<?php echo base_url().'assets/subscription.png'; ?>" alt="" />
                                </div>
                                <div class="col-md-8">
                                    <br />
                                    <h4 class="pt-4 text-justify">At Happycrop, we understand the importance of supporting manufacturers in their journey to success. That's why we're excited to offer a special promotion for manufacturers who join our platform.</h4>
                                    <br /><br />
                                    <h4 class="pt-5 mt-md-10">Limited Time Offer</h4>
                                    <h4 class=" text-justify">For a limited time, new manufacturers who sign up for Happycrop will receive a one-year free subscription. This exclusive promotion is designed to help you experience the full benefits of our platform without any subscription fees for the first year.</h4>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-primary">How It Works</h4>
                                    <ol class="mb-3">
                                        <li>1. Sign Up: Create your manufacturer account on Happycrop.</li>
                                        <li>2. Free Subscription: Enjoy a one-year free subscription with all the platform's benefits.</li>
                                        <li>3. Grow Your Business: Use Happycrop to expand your reach and increase sales.</li>
                                        <li>4. Continued Success: After the free period, choose from our affordable subscription plans.</li>
                                    </ol>
                                    <h4 class="text-primary">What You Get</h4>
                                    <ul class="list mb-3">
                                        <li>Access to a Growing Network: Connect with a vast network of retailers across regions.</li>
                                        <li>Increased Visibility: Showcase your products to a wider audience of potential buyers.</li>
                                        <li>Streamlined Transactions: Simplify order processing and reduce administrative overhead.</li>
                                        <li>Quality Assurance: Ensure your products meet the highest standards through our quality checks.</li>
                                        <li>Real-Time Insights: Access valuable data and analytics to make informed decisions.</li>
                                        <li>Dedicated Support: Our support team is here to assist you every step of the way.</li>
                                    </ul>
                                    <h4 class="text-primary">Contact Information:</h4>
                                    <ol class="mb-3">
                                        <li class="pl-5">E mail - <a href="mailto:marketing@happycrop.in" target="_blank">marketing@happycrop.in</a></li>
                                        <li class="pl-5">Phone - <a href="tel:+919975548343" target="_blank">+91 9975548343</a></li>
                                    </ol>
                                    <h5 class="text-primary">Join Happycrop, where your success is our mission.</h5>
                                </div>
                            </div>
                        </div>
                        
                        <?php /* ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="pricing-table text-center">
                                    <div class="bg-lighter  border-1px p-30 pt-20 pb-20">
                                        <h3 class="package-type font-28 m-0 text-black"><i class="fa fa-dollar"></i></h3>
                                        <ul class="table-list list-icon theme-colored pb-0">
                                            <li>Feature 1</li>
                                            <li>Feature 2</li>
                                            <li>Feature 3</li>
                                            <li>Feature 4</li>
                                            <li>Feature 5</li>
                                            <li>Feature 6</li>
                                            <li>Feature 7</li>
                                            <li>Feature 8</li>
                                            <li>Feature 9</li>
                                            <li>Feature 10</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pricing-table text-center">
                                    <div class="position-relative blue-area">
                                        <h3 class="package-type basic-plan-bg font-28 m-0">
                                            <i class="fa fa-map"></i>
                                            Basic
                                        </h3>
                                        <ul class="table-list list-icon theme-colored pb-0">
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                        </ul>
                                    </div>
                                    <a href="#" class="btn btn-outline-primary mt-3 text-uppercase btn-block pt-20 pb-20 btn-flat">Subscribe</a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pricing-table text-center">
                                    <div class="bg-lighter border-1px p-30 pt-20 pb-20">
                                        <h3 class="package-type std-plan-bg font-28 m-0 text-black">
                                            <i class="fa fa-suitcase"></i>
                                            Standard
                                        </h3>
                                        <ul class="table-list list-icon theme-colored pb-0">
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                            <li><i class="fa fa-times"></i></li>
                                        </ul>
                                    </div>
                                    <a href="#" class="btn btn-outline-primary mt-3 text-uppercase btn-block pt-20 pb-20 btn-flat">Subscribe</a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pricing-table text-center">
                                    <div class="bg-lighter border-1px p-30 pt-20 pb-20">
                                        <h3 class="package-type plus-plan-bg font-28 m-0 text-black">
                                            <i class="fa fa-plus-circle"></i>
                                            Plus
                                        </h3>
                                        <ul class="table-list list-icon theme-colored pb-0">
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                            <li><i class="fa fa-check"></i></li>
                                        </ul>
                                    </div>
                                    <a href="#" class="btn btn-outline-primary mt-3 text-uppercase btn-block pt-20 pb-20 btn-flat">Subscribe</a>
                                </div>
                            </div>
                        </div>
                        <?php */ ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>