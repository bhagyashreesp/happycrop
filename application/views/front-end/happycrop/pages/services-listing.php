<!-- breadcrumb -->
<style>
.product-wrap  .product{min-height: 358px;}.new-hp-tabs.tab-nav-outline2 .nav-link {min-height: 76px;align-items: center;display: flex;}
@media (max-width: 479px){
    .new-hp-tabs.tab-nav-outline2 .nav-link {min-height: 64px;}
}
</style>
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= isset($page_main_bread_crumb) ? $page_main_bread_crumb : 'Product Listing' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <?php if (isset($right_breadcrumb) && !empty($right_breadcrumb)) {
                    foreach ($right_breadcrumb as $row) {
                ?>
                        <li class="breadcrumb-item"><?= $row ?></li>
                <?php }
                } ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo /*!empty($this->lang->line('products')) ? $this->lang->line('products') :*/ 'Services' ?></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->

<section class="listing-page content main-content">
    <div class="product-listing card-solid py-4">
        <div class="row mx-0">
            <!-- Dektop Sidebar -->
            <div class="col-md-12 col-12 order-md-2- <?= (isset($products['filters']) && !empty($products['filters'])) ? "col-lg-9" : "col-lg-12" ?>">
                <div class="container-fluid2 filter-section pt-3  pb-3">
                    <?php                   
                    $_cats = $this->db->get_where('categories',array('super_category_id'=>$cat_info['super_category_id'],'parent_id'=>0,'status'=>1))->result_array();
                    
                    if($_cats)
                    {
                        ?>
                        <div class="">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="tab tab-nav-boxed new-hp-tabs tab-boxed tab-nav-outline2 show-code-action">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <?php
                                            foreach($_cats as $_cat)
                                            {
                                                ?>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php echo ($_cat['id'] == $cat_info['id']) ? 'active' : ''; ?>" href="#tab3-<?php echo $_cat['id'];?>">
                                                        <!--<p><img class="thumbnail" src="<?php echo base_url($_cat['image']); ?>" alt="<?php echo $_cat['name']; ?>" /></p>-->
                                                        <h6 class="text-primary mb-0"><?php echo $_cat['name']; ?></h6>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <div class="tab-content mb-3">
                                            <?php
                                            foreach($_cats as $_cat)
                                            {
                                                ?>
                                                <div class="tab-pane <?php echo ($_cat['id'] == $cat_info['id']) ? 'active' : ''; ?>" id="tab3-<?php echo $_cat['id'];?>">
                                                    <?php
                                                    $sub_list = $this->db->get_where('categories',array('parent_id'=>$_cat['id'],'status'=>1))->result_array();
                                                    if($sub_list)
                                                    {
                                                        ?>
                                                        <h4 class="text-primary text-uppercase h4 mb-3"><?php echo $_cat['name']; ?></h4>
                                                        <div class="row">
                                                        <?php
                                                        foreach($sub_list as $ins)
                                                        {
                                                            ?>
                                                            <div class="col-md-2 col-4">
                                                                <div class="hp-cat-list text-center">
                                                                    <a href="<?php echo base_url('products/service/' . html_escape($ins['slug'])) ?>" title="<?php echo $ins['name']; ?>" target="_self">
                                                                        <!--<p><img class="img_thumb" src="<?php echo base_url($ins['image']); ?>" alt="<?php echo $ins['name']; ?>" /></p>-->
                                                                        <?php echo $ins['name']; ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="text-center">No Records</p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                        
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>