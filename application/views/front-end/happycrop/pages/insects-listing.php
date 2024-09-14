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
                    if(isset($show_type) && $show_type == 'insect')
                    {
                        if($val_of == 'category')
                        {
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
                                                        <div class="tab-pane <?php echo ($_cat['id'] == $cat_info['id']) ? 'active' : ''; ?>" id="tab3-<?php echo $_cat['id'];?>">
                                                            <?php
                                                            $insects_list = $this->db->get_where('insect_pests',array('sub_category_id'=>$_cat['id'],'status'=>1))->result_array();
                                                            if($insects_list)
                                                            {
                                                                ?>
                                                                <h4 class="text-primary text-uppercase h4 mb-3"><?php echo $_cat['name']; ?></h4>
                                                                <div class="row">
                                                                <?php
                                                                foreach($insects_list as $ins)
                                                                {
                                                                    ?>
                                                                    <div class="col-md-2 col-4">
                                                                        <div class=" text-center">
                                                                            <a href="<?php echo base_url('products/insect_pests/' . html_escape($ins['slug'])) ?>" title="<?php echo $ins['name']; ?>" target="_self">
                                                                                <p><img class="img_thumb" src="<?php echo base_url($ins['image']); ?>" alt="<?php echo $ins['name']; ?>" /></p>
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
                        }
                        /*else if($val_of == 'insect')
                        {
                            $_cats = $this->db->get_where('categories',array('super_category_id'=>$insect_info['super_category_id'],'parent_id'=>0,'status'=>1))->result_array();
                            
                            if($_cats)
                            {
                                ?>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="tab tab-nav-boxed tab-nav-center tab-boxed tab-nav-outline2 show-code-action">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <?php
                                                    foreach($_cats as $_cat)
                                                    {
                                                        ?>
                                                        
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                                <div class="tab-content">
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }*/
                        
                    } 
                    ?>
                    <?php
                    if(isset($show_type) && $show_type == 'insect')
                    {
                        /*
                        if($val_of == 'category')
                        {
                            $_cats = $this->db->get_where('categories',array('super_category_id'=>$cat_info['super_category_id'],'parent_id'=>0,'status'=>1))->result_array();
                            
                            if($_cats)
                            {
                                ?>
                                <div class="hp-tabs d-lg-show">
                                <?php
                                foreach($_cats as $_cat)
                                {
                                    ?>
                                    <input type="radio" name="hp-tabs" id="hp-tab<?php echo $_cat['id'];?>" <?php echo ($_cat['id'] == $cat_info['id']) ? 'checked="checked"' : ''; ?>  />
                                    <label class="col-4" for="hp-tab<?php echo $_cat['id'];?>">
                                        <a href="<?php echo base_url('products/insect_pests/' . html_escape($_cat['slug'])) ?>" title="<?php echo $_cat['name']; ?>" target="_self">
                                            <p><img class="thumbnail" src="<?php echo base_url($_cat['image']); ?>" alt="<?php echo $_cat['name']; ?>" /></p>
                                            <?php echo $_cat['name']; ?>
                                        </a>
                                    </label>
                                    <?php
                                    $insects_list = $this->db->get_where('insect_pests',array('sub_category_id'=>$_cat['id'],'status'=>1))->result_array();
                                    if($insects_list)
                                    {
                                        ?>
                                        <div class="hp-tab">
                                            <div class="row">
                                            <?php
                                            foreach($insects_list as $ins)
                                            {
                                                ?>
                                                <div class="col-md-2 col-4">
                                                    <div class=" text-center">
                                                        <a href="<?php echo base_url('products/insect_pests/' . html_escape($ins['slug'])) ?>" title="<?php echo $ins['name']; ?>" target="_self">
                                                            <p><img class="img_thumb" src="<?php echo base_url($ins['image']); ?>" alt="<?php echo $ins['name']; ?>" /></p>
                                                            <?php echo $ins['name']; ?>
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
                        } 
                        else if($val_of == 'insect')
                        {
                            $_cats = $this->db->get_where('categories',array('super_category_id'=>$insect_info['super_category_id'],'parent_id'=>0,'status'=>1))->result_array();
                            
                            if($_cats)
                            {
                                ?>
                                <div class="hp-tabs d-lg-show">
                                <?php
                                foreach($_cats as $_cat)
                                {
                                    ?>
                                    <input type="radio" name="hp-tabs" id="hp-tab<?php echo $_cat['id'];?>" <?php echo ($_cat['id'] == $insect_info['sub_category_id']) ? 'checked="checked"' : ''; ?>  />
                                    <label class="col-4" for="hp-tab<?php echo $_cat['id'];?>">
                                        <a href="<?php echo base_url('products/insect_pests/' . html_escape($_cat['slug'])) ?>" title="<?php echo $_cat['name']; ?>" target="_self">
                                            <p><img class="thumbnail" src="<?php echo base_url($_cat['image']); ?>" alt="<?php echo $_cat['name']; ?>" /></p>
                                            <?php echo $_cat['name']; ?>
                                        </a>
                                    </label>
                                    <?php
                                    $insects_list = $this->db->get_where('insect_pests',array('sub_category_id'=>$_cat['id'],'status'=>1))->result_array();
                                    if($insects_list)
                                    {
                                        ?>
                                        <div class="hp-tab">
                                            <div class="row">
                                            <?php
                                            foreach($insects_list as $ins)
                                            {
                                                ?>
                                                <div class="col-md-2 col-4">
                                                    <div class="<?php echo ($ins['id'] == $val_id) ? 'current_active' : '';?> text-center">
                                                        <a href="<?php echo base_url('products/insect_pests/' . html_escape($ins['slug'])) ?>" title="<?php echo $ins['name']; ?>" target="_self">
                                                            <p><img class="img_thumb" src="<?php echo base_url($ins['image']); ?>" alt="<?php echo $ins['name']; ?>" /></p>
                                                            <?php echo $ins['name']; ?>
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
                        }
                        */
                        
                        
                        ?>
                        <?php /* ?>
                        <!--
                        <div class="hp-tabs">
                            <input type="radio" name="hp-tabs" id="hp-tabone" checked="checked" />
                            <label for="hp-tabone">
                                
                            </label>
                            <div class="hp-tab">
                                <h1>Tab One Content</h1>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                </p>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                </p>
                            </div>
                            <input type="radio" name="hp-tabs" id="hp-tabtwo" />
                            <label for="hp-tabtwo">Tab Two</label>
                            <div class="hp-tab">
                                <h1>Tab Two Content</h1>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                </p>
                            </div>
                            <input type="radio" name="hp-tabs" id="hp-tabthree" />
                            <label for="hp-tabthree">Tab Three</label>
                            <div class="hp-tab">
                                <h1>Tab Three Content</h1>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                            </div>
                        </div>-->
                        <?php */ ?>
                        <?php
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