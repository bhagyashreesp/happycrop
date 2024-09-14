<!-- breadcrumb -->
<style>.product-wrap  .product{min-height: 358px;}</style>
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
                <li class="breadcrumb-item active" aria-current="page"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></li>
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
                    
                    if(isset($single_category) && !empty($single_category)) 
                    { 
                        $_cats = array();
                        
                        $pr_id = get_category_parent_0($single_category['id']);
                        $_cats = $this->db->get_where('categories',array('parent_id'=>$pr_id,'status'=>1))->result_array();
                        
                        $current_cat = 0;
                        $mcat_ids = array_column($_cats, 'id');
                        
                        if(in_array($single_category['id'], $mcat_ids))
                        {
                            $current_cat = $single_category['id'];
                        }
                        else
                        {
                            $this->db->select('parent_id');
                            $this->db->from('categories');
                            $this->db->where('id',$single_category['id']);
                            $q = $this->db->get();
                            $cu_cat= $q->row_array();
                            
                            $current_cat = $cu_cat['parent_id'];
                            
                            if(!$current_cat)
                            {
                                $current_cat = $_cats[0]['id'];
                            }
                        }
                            
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
                                                            <a class="nav-link <?php echo ($_cat['id'] == $current_cat) ? 'active' : ''; ?>" href="#tab3-<?php echo $_cat['id'];?>">
                                                                <p><img class="thumbnail" src="<?php echo base_url($_cat['image']); ?>" alt="<?php echo $_cat['name']; ?>" /></p>
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
                                                        <div class="tab-pane <?php echo ($_cat['id'] == $current_cat) ? 'active' : ''; ?>" id="tab3-<?php echo $_cat['id'];?>">
                                                            <?php
                                                            $cat_lists = $this->db->get_where('categories',array('parent_id'=>$_cat['id'],'status'=>1))->result_array();
                                                            if($cat_lists)
                                                            {
                                                                ?>
                                                                <h4 class="text-primary text-uppercase h4 mb-3"><?php echo $_cat['name']; ?></h4>
                                                                <div class="row">
                                                                <?php
                                                                foreach($cat_lists as $cat_list)
                                                                {
                                                                    ?>
                                                                    <div class="col-md-2">
                                                                        <div class="hp-cat-list <?php echo ($single_category['id'] == $cat_list['id']) ? 'current_cat' : ''; ?> text-center">
                                                                            <a href="<?php echo base_url('products/category/' . html_escape($cat_list['slug'])) ?>" title="<?php echo $cat_list['name']; ?>" target="_self">
                                                                                <?php echo $cat_list['name']; ?>
                                                                            </a>
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
                    } 
                    ?>
                    <?php 
                    /*if(isset($single_category) && !empty($single_category)) 
                    { 
                        $_cats = array();
                        
                        $pr_id = get_category_parent_0($single_category['id']);
                        $_cats = $this->db->get_where('categories',array('parent_id'=>$pr_id,'status'=>1))->result_array();
                        
                        $current_cat = 0;
                        $mcat_ids = array_column($_cats, 'id');
                        
                        if(in_array($single_category['id'], $mcat_ids))
                        {
                            $current_cat = $single_category['id'];
                        }
                        else
                        {
                            $this->db->select('parent_id');
                            $this->db->from('categories');
                            $this->db->where('id',$single_category['id']);
                            $q = $this->db->get();
                            $cu_cat= $q->row_array();
                            
                            $current_cat = $cu_cat['parent_id'];
                            
                            if(!$current_cat)
                            {
                                $current_cat = $_cats[0]['id'];
                            }
                        }
                            
                        if($_cats)
                        {
                            ?>
                            <div class="hp-tabs d-lg-show">
                            <?php
                            foreach($_cats as $_cat)
                            {
                                ?>
                                <input type="radio" name="hp-tabs" id="hp-tab<?php echo $_cat['id'];?>" <?php echo ($_cat['id'] == $current_cat) ? 'checked="checked"' : ''; ?>  />
                                <label class="col-4" for="hp-tab<?php echo $_cat['id'];?>">
                                    <a href="<?php echo base_url('products/category/' . html_escape($_cat['slug'])) ?>" title="<?php echo $_cat['name']; ?>" target="_self">
                                        <p><img class="thumbnail" src="<?php echo base_url($_cat['image']); ?>" alt="<?php echo $_cat['name']; ?>" /></p>
                                        <?php echo $_cat['name']; ?>
                                    </a>
                                </label>
                                <?php
                                $cat_lists = $this->db->get_where('categories',array('parent_id'=>$_cat['id'],'status'=>1))->result_array();
                                if($cat_lists)
                                {
                                    ?>
                                    <div class="hp-tab">
                                        <div class="row">
                                        <?php
                                        foreach($cat_lists as $cat_list)
                                        {
                                            ?>
                                            <div class="col-md-2">
                                                <div class="hp-cat-list <?php echo ($single_category['id'] == $cat_list['id']) ? 'current_cat' : ''; ?> text-center">
                                                    <a href="<?php echo base_url('products/category/' . html_escape($cat_list['slug'])) ?>" title="<?php echo $cat_list['name']; ?>" target="_self">
                                                        <?php echo $cat_list['name']; ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div class="hp-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="text-center">No Records</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                
                            }
                            ?>
                            </div>
                            <?php
                        }
                    } */
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>