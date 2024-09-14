<!-- breadcrumb -->
<style>.btn.btn-sm {font-size: 1.2rem !important;}.product-wrap{min-width: /*156px*/136px;}.product-wrap  .product{min-height: 358px;}.product-listing {min-height: 400px;}</style>
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
                <li class="breadcrumb-item active" aria-current="page"><?php echo "Products & Services";//echo !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->

<?php
/*
if(isset($show_type) && $show_type == 'insect')
{
    if($val_of == 'category')
    {
        $_cats = $this->db->get_where('categories',array('super_category_id'=>$cat_info['super_category_id'],'parent_id'=>0,'status'=>1))->result_array();
        
        if($_cats)
        {
            ?>
            <div class="text-center p-2">
                <a href="#" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle"></a>
            </div>
            <div class="mobile-menu-wrapper">
                <div class="mobile-menu-overlay"></div>
                <!-- End of .mobile-menu-overlay -->
            
                <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
                <!-- End of .mobile-menu-close -->
            
                <div class="mobile-menu-container scrollable">
                    <div class="tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a href="#cat-menu" class="nav-link">Categories</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="cat-menu">
                            <ul class="mobile-menu">
                                <?php
                                foreach($_cats as $_cat)
                                {
                                    ?>
                                    <li>
                                        <a <?php echo ($_cat['id'] == $cat_info['id']) ? 'class="active"' : ''; ?> href="<?php echo base_url('products/insect_pests/' . html_escape($_cat['slug'])) ?>"><?php echo $_cat['name']; ?></a>
                                        <ul>
                                            <?php
                                            $insects_list = $this->db->get_where('insect_pests',array('sub_category_id'=>$_cat['id'],'status'=>1))->result_array();
                                            if($insects_list)
                                            {
                                                foreach($insects_list as $ins)
                                                {
                                                    ?>
                                                    <li><a href="<?php echo base_url('products/insect_pests/' . html_escape($ins['slug'])) ?>"><?php echo $ins['name']; ?></a></li>
                                                    <?php
                                                    
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                    
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
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
            <div class="text-center p-2">
                <a href="#" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle"></a>
            </div>
            <div class="mobile-menu-wrapper">
                <div class="mobile-menu-overlay"></div>
                <!-- End of .mobile-menu-overlay -->
            
                <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
                <!-- End of .mobile-menu-close -->
            
                <div class="mobile-menu-container scrollable">
                    <div class="tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a href="#cat-menu" class="nav-link">Categories</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="cat-menu">
                            <ul class="mobile-menu">
                            <?php
                            foreach($_cats as $_cat)
                            {
                                ?>
                                <li>
                                    <a <?php echo ($_cat['id'] == $insect_info['sub_category_id']) ? 'class="active"' : ''; ?> href="<?php echo base_url('products/insect_pests/' . html_escape($_cat['slug'])) ?>"><?php echo $_cat['name']; ?></a>
                                    <ul>
                                    <?php
                                    $insects_list = $this->db->get_where('insect_pests',array('sub_category_id'=>$_cat['id'],'status'=>1))->result_array();
                                    if($insects_list)
                                    {
                                        
                                        foreach($insects_list as $ins)
                                        {
                                            ?>
                                            <li><a class="<?php echo ($ins['id'] == $val_id) ? 'active' : '';?>" href="<?php echo base_url('products/insect_pests/' . html_escape($ins['slug'])) ?>"><?php echo $ins['name']; ?></a></li>
                                            <?php
                                        }
                                            
                                    }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                            } 
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

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
        <div class="text-center p-2">
            <a href="#" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle"></a>
        </div>
        <div class="mobile-menu-wrapper">
            <div class="mobile-menu-overlay"></div>
            <!-- End of .mobile-menu-overlay -->
        
            <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
            <!-- End of .mobile-menu-close -->
        
            <div class="mobile-menu-container scrollable">
                <div class="tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a href="#cat-menu" class="nav-link">Categories</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="cat-menu">
                        <ul class="mobile-menu">
                        <?php
                        foreach($_cats as $_cat)
                        {
                            ?>
                            <li>
                                <a <?php echo ($_cat['id'] == $current_cat) ? 'class="active"' : ''; ?> href="<?php echo base_url('products/category/' . html_escape($_cat['slug'])) ?>"><?php echo $_cat['name']; ?></a>
                                <ul>
                                <?php
                                $cat_lists = $this->db->get_where('categories',array('parent_id'=>$_cat['id'],'status'=>1))->result_array();
                                if($cat_lists)
                                {
                                    
                                    foreach($cat_lists as $cat_list)
                                    {
                                        ?>
                                        <li><a class="<?php echo ($single_category['id'] == $cat_list['id']) ? 'active' : ''; ?>" href="<?php echo base_url('products/category/' . html_escape($cat_list['slug'])) ?>"><?php echo $cat_list['name']; ?></a></li>
                                        <?php
                                    }
                                        
                                }  
                                ?>
                                </ul>
                            </li>
                            <?php 
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} 
*/
?>

<input type="hidden" id="product-filters" value='<?=  (!empty($filters))? escape_array($filters) :""  ?>' data-key="<?= $filters_key ?>" />
<section class="listing-page content main-content">
    <div class="product-listing card-solid py-4">
        <?php
        if($seller_info)
        {
            ?>
            <div class="row">
                <div class="col-md-2">
                    <?php if(file_exists($seller_info['logo']) && $seller_info['logo']!='') { ?> 
                    <div class="logo text-center">
                        <a href="<?php echo base_url().'products?seller='.$seller_info['slug'];?>">
                            <img style="max-height: 85px;display: inline;" src="<?php echo base_url().$seller_info['logo'];?>" alt="<?php echo $seller_info['company_name'];?>"/>
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-3">
                    <p class="mb-0">ID- <?= "HCM".str_pad($seller_info['mfg_no'],4,"0",STR_PAD_LEFT) ?></p>
                    <?php
                    if($seller_info['have_gst_no'] == 1)
                    {
                        ?>
                        <p class="mt-0"><i class="fa fa-check-circle text-success"></i> GST- <?php echo $seller_info['gst_no']; ?></p>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-3">
                    <p>
                    <?php if($seller_info['fertilizer_license_no']!='') { ?>
                    Fertilizer Lic – <?php echo $seller_info['fertilizer_license_no']; ?><br />
                    <?php } ?>
                    
                    <?php if($seller_info['pesticide_license_no']!='') { ?>
                    Pesticides Lic – <?php echo $seller_info['pesticide_license_no']; ?><br />
                    <?php } ?>
                    
                    <?php if($seller_info['seeds_license_no']!='') { ?>
                    Seeds Lic -  <?php echo $seller_info['seeds_license_no']; ?>
                    <?php } ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <p>
                    <img src="<?php echo base_url();?>assets/trust-icons.png" alt="" />
                    </p>
                </div>
                <div class="col-md-2">
                    <div class="bg-grey p-3 text-center border-radius-10">
                    <h6>Minimum Order Value<br /> <?php echo $settings['currency'] . ' ' . number_format($seller_info['min_order_value'],2);?></h6>
                    </div>
                </div>
            </div>
            
            <div class="clearfix mb-3"></div>
            <style>
            .seller_tabs.nav-tabs .nav-item .nav-link.active, .seller_tabs.nav-tabs .nav-item:hover .nav-link{background: #255946;color:#FFF;}
            .seller_tabs.nav-tabs .nav-item{margin-right: 0px;}
            .tab-pane{min-height: 350px;}
            </style>
            
            <ul class="nav nav-tabs bg-light seller_tabs" id="sellerTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="seller_products-tab" data-toggle="tab" href="#seller_products" role="tab" aria-controls="seller_products" aria-selected="true">Product & Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="about_company-tab" data-toggle="tab" href="#about_company" role="tab" aria-controls="about_company" aria-selected="false">About Company</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact_us" role="tab" aria-controls="contact_us" aria-selected="false">Contact Us</a>
                </li>
            </ul>
            <div class="tab-content" id="sellerTabContent">
                <div class="tab-pane fade show active" id="seller_products" role="tabpanel" aria-labelledby="seller_products-tab">
            <?php /* ?>
            <div class="row">
                <div class="col-md-12 mt-5 bg-white">
                    <h4 class="h4">Manufacturer - 
                        <a href="<?php echo base_url().'products?seller='.$seller_info['slug'];?>"><?php echo $seller_info['company_name'];?></a>
                        <span class="alert alert-primary alert-simple alert-inline show-code-action ml-lg-10">
                            Minimum order value is <?php echo $settings['currency'] . ' ' . number_format($seller_info['min_order_value'],2);?>
                        </span>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 bg-white">
                    <?php if(file_exists($seller_info['logo']) && $seller_info['logo']!='') { ?> 
                    <div class="logo">
                        <a href="<?php echo base_url().'products?seller='.$seller_info['slug'];?>">
                            <img src="<?php echo base_url().$seller_info['logo'];?>" alt="<?php echo $seller_info['company_name'];?>"/>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php */ ?>
            
            <?php
        }
        ?>
        <div class="row mx-0">
            <!-- Dektop Sidebar -->
            <?php /*if (isset($products['filters']) && !empty($products['filters'])) { ?>
                <div class=" order-md-1 col-lg-3 filter-section sidebar-filter-sm container pt-2 pb-2 filter-sidebar-view">
                    <div id="product-filters-desktop">
                        <?php foreach ($products['filters'] as $key => $row) {
                            $row_attr_name = str_replace(' ', '-', $row['name']);
                            $attribute_name = isset($_GET[strtolower('filter-' . $row_attr_name)]) ? $this->input->get(strtolower('filter-' . $row_attr_name), true) : 'null';
                            $selected_attributes = explode('|', $attribute_name);
                            $attribute_values = explode(',', $row['attribute_values']);
                            $attribute_values_id = explode(',', $row['attribute_values_id']);
                        ?>
                            <div class="card-custom">
                                <div class="card-header-custom" id="h1">
                                    <h2 class="clearfix mb-0">
                                        <a class="collapse-arrow btn btn-link collapsed" data-toggle="collapse" data-target="#c<?= $key ?>" aria-expanded="true" aria-controls="collapseone"><?= html_escape($row['name']) ?><i class="fa fa-angle-down rotate"></i></a>
                                    </h2>
                                </div>
                                <div id="c<?= $key ?>" class="collapse <?= ($attribute_name != 'null') ? 'show' : '' ?>" aria-labelledby="h1" data-parent="#accordionExample">
                                    <div class="card-body-custom">
                                        <?php foreach ($attribute_values as $key => $values) {
                                            $values = strtolower($values);
                                        ?>
                                            <div class="input-container d-flex">
                                                <?= form_checkbox(
                                                    $values,
                                                    $values,
                                                    (in_array($values, $selected_attributes)) ? TRUE : FALSE,
                                                    array(
                                                        'class' => 'toggle-input product_attributes',
                                                        'id' => $row_attr_name . ' ' . $values,
                                                        'data-attribute' => strtolower($row['name']),
                                                    )
                                                ) ?>
                                                <label class="toggle checkbox" for="<?= $row_attr_name . ' ' . $values ?>">
                                                    <div class="toggle-inner"></div>
                                                </label>
                                                <?= form_label($values, $row_attr_name . ' ' . $values, array('class' => 'text-label')) ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="text-center">
                        <button class="button button-rounded button-warning product_filter_btn">Filter</button>
                    </div>
                </div>
            <?php }*/ ?>
            <div class="col-md-12 order-md-2 <?= (isset($products['filters']) && !empty($products['filters'])) ? "col-lg-9" : "col-lg-12" ?>">
                <div class="container-fluid2 filter-section pt--3 pt-0  pb-3">
                    <?php
                    /*
                    if(isset($show_type) && $show_type == 'insect')
                    {
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
                        <?php *//* ?>
                        <?php
                    }*/
                    ?>
                    
                    <?php 
                    /*
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
                    } 
                    */
                    ?>
                    
                    <?php /* if (isset($sub_categories) && !empty($sub_categories)) { ?>
                        <div class="col-md-9 col-sm-12 text-left py-3">
                            <?php if (isset($single_category) && !empty($single_category)) { ?>
                                <span class="h3"><?= $single_category['name'] ?> <?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Category' ?></span>
                            <?php } ?>
                        </div>
                        <div class="category-section container-fluid text-center mb-3">
                            <div class="row">
                                <?php foreach ($sub_categories as $key => $row) { ?>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="category-image w-75">
                                            <a href="<?= base_url('products/category/' . html_escape($row->slug)) ?>">
                                                <img class="pic-1 lazy" data-src="<?= $row->image ?>">
                                            </a>
                                            <div class="social">
                                                <span><?= html_escape($row->name) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } */ ?>
                    <div class="row">
                    <div class="col-12">
                        <div class="dropdown">
                            <?php /* ?>
                            <div class="filter-bars">
                                <div class="menu js-menu">
                                    <span class="menu__line"></span>
                                    <span class="menu__line"></span>
                                    <span class="menu__line"></span>

                                </div>
                            </div>
                            <?php */ ?>
                            <div class="col-12 sort-by d-none py-3">
                                <?php if (isset($products) && !empty($products['product'])) { ?>
                                    <nav class="toolbox sticky-toolbox sticky-content fix-top">
                                        <div class="toolbox-left">
                                            <!--<a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle 
                                                btn-icon-left d-block d-lg-none"><i
                                                    class="w-icon-category"></i><span>Filters</span></a>-->
                                            <div class="toolbox-item toolbox-sort select-box text-dark">
                                                <label for="product_sort_by"><?= !empty($this->lang->line('sort_by')) ? $this->lang->line('sort_by') : 'Sort By' ?> :</label>
                                                <select id="product_sort_by" name="product_sort_by" class="form-control">
                                                    <option><?= !empty($this->lang->line('relevance')) ? $this->lang->line('relevance') : 'Relevance' ?></option>
                                                    <option value="top-rated" <?= ($this->input->get('sort') == "top-rated") ? 'selected' : '' ?>><?= !empty($this->lang->line('top_rated')) ? $this->lang->line('top_rated') : 'Top Rated' ?></option>
                                                    <option value="date-desc" <?= ($this->input->get('sort') == "date-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('newest_first')) ? $this->lang->line('newest_first') : 'Newest First' ?></option>
                                                    <option value="date-asc" <?= ($this->input->get('sort') == "date-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('oldest_first')) ? $this->lang->line('oldest_first') : 'Oldest First' ?></option>
                                                    <?php /* ?>
                                                    <option value="price-asc" <?= ($this->input->get('sort') == "price-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('price_low_to_high')) ? $this->lang->line('price_low_to_high') : 'Price - Low To High' ?></option>
                                                    <option value="price-desc" <?= ($this->input->get('sort') == "price-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('price_high_to_low')) ? $this->lang->line('price_high_to_low') : 'Price - High To Low' ?></option>
                                                    <?php */ ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="toolbox-right">
                                            <div class="toolbox-item toolbox-show select-box">
                                                <!--<label class="mr-2 dropdown-label"> <?php echo !empty($this->lang->line('show')) ? $this->lang->line('show') : 'Show' ?>:</label>-->
                                                <a class="btn btn-sm dropdown-border dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= ($this->input->get('per-page', true) ? $this->input->get('per-page', true) : '12') ?> <span class="caret"></span></a>
                                                <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="navbarDropdown" id="per_page_products">
                                                    <a class="dropdown-item" href="#" data-value=12>12</a>
                                                    <a class="dropdown-item" href="#" data-value=16>16</a>
                                                    <a class="dropdown-item" href="#" data-value=20>20</a>
                                                    <a class="dropdown-item" href="#" data-value=24>24</a>
                                                </div>
                                            </div>
                                            <?php /* ?>
                                            <div class="toolbox-item toolbox-layout">
                                                <a id="product_grid_view_btn" href="#" class="icon-mode-grid btn-layout active">
                                                    <i class="w-icon-grid"></i>
                                                </a>
                                                <a id="product_list_view_btn" href="#" class="icon-mode-list btn-layout">
                                                    <i class="w-icon-list"></i>
                                                </a>
                                            </div>
                                            <?php */ ?>
                                        </div>
                                    </nav>
                                    <?php /* ?>
                                    <div class="dropdown float-md-right d-flex mb-4">
                                        <label class="mr-2 dropdown-label"> <?php !empty($this->lang->line('show')) ? $this->lang->line('show') : 'Show' ?>:</label>
                                        <a class="btn dropdown-border btn-lg dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= ($this->input->get('per-page', true) ? $this->input->get('per-page', true) : '12') ?> <span class="caret"></span></a>
                                        <a href="#" id="product_grid_view_btn" class="grid-view"><i class="fas fa-th"></i></a>
                                        <a href="#" id="product_list_view_btn" class="grid-view"><i class="fas fa-th-list"></i></a>
                                        <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="navbarDropdown" id="per_page_products">
                                            <a class="dropdown-item" href="#" data-value=12>12</a>
                                            <a class="dropdown-item" href="#" data-value=16>16</a>
                                            <a class="dropdown-item" href="#" data-value=20>20</a>
                                            <a class="dropdown-item" href="#" data-value=24>24</a>
                                        </div>
                                    </div>
                                    <div class="ele-wrapper d-flex">
                                        <div class="form-group col-md-4 d-flex">
                                            <label for="product_sort_by" class="w-25"><?= !empty($this->lang->line('sort_by')) ? $this->lang->line('sort_by') : 'Sort By' ?>:</label>
                                            <select id="product_sort_by" class="form-control">
                                                <option><?= !empty($this->lang->line('relevance')) ? $this->lang->line('relevance') : 'Relevance' ?></option>
                                                <option value="top-rated" <?= ($this->input->get('sort') == "top-rated") ? 'selected' : '' ?>><?= !empty($this->lang->line('top_rated')) ? $this->lang->line('top_rated') : 'Top Rated' ?></option>
                                                <option value="date-desc" <?= ($this->input->get('sort') == "date-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('newest_first')) ? $this->lang->line('newest_first') : 'Newest First' ?></option>
                                                <option value="date-asc" <?= ($this->input->get('sort') == "date-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('oldest_first')) ? $this->lang->line('oldest_first') : 'Oldest First' ?></option>
                                                <option value="price-asc" <?= ($this->input->get('sort') == "price-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('price_low_to_high')) ? $this->lang->line('price_low_to_high') : 'Price - Low To High' ?></option>
                                                <option value="price-desc" <?= ($this->input->get('sort') == "price-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('price_high_to_low')) ? $this->lang->line('price_high_to_low') : 'Price - High To Low' ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php */ ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    </div>
                    
                    <?php if (isset($products) && !empty($products['product'])) { ?>

                        <?php /*if (isset($_GET['type']) && $_GET['type'] == "list") { ?>
                            <div class="col-md-12 col-sm-6">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="col-md-12 col-sm-12 text-left py-3">
                                            <h4 class="h3"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></h4>
                                        </div>
                                    </div>
                                    <?php foreach ($products['product'] as $row) { ?>
                                        <div class="col-md-3">
                                            <div class="product-grid">
                                                <div class="product-image">
                                                    <div class="product-image-container">
                                                        <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                            <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>">
                                                        </a>
                                                    </div>
                                                    <ul class="social">
                                                        <?php
                                                        if (count($row['variants']) <= 1) {
                                                            $variant_id = $row['variants'][0]['id'];
                                                            $modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $modal = "#quick-view";
                                                        }
                                                        ?>
                                                        <?php /* ?>
                                                        <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                        <?php *//* ?>
                                                        <li><a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                                    </ul>
                                                    <?php /*if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                        <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                        <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                    <?php }*//* ?>
                                                    <aside class="add-favorite">
                                                        <button type="button" class="product-icon btn-wishlist far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger w-icon-heart-full' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                                                    </aside>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="product-content">
                                                <h3 class="list-product-title title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h3>
                                                <div class="rating">
                                                    <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                                </div>
                                                <p class="text-muted list-product-desc"><?= $row['short_description'] ?></p>
                                                <div class="price mb-2 list-view-price">
                                                    <?php if(!empty($row['min_max_price']['special_price'])) {?>
                                                    <?= $settings['currency'] ?></i><?= number_format($row['min_max_price']['special_price']) ?>
                                                    <span class="striped-price"><?= $settings['currency'] . ' ' . number_format($row['min_max_price']['min_price']) ?></span>
                                                    <?php }else{?>
                                                        <?= $settings['currency'] ?></i><?= number_format($row['min_max_price']['min_price']) ?>
                                                    <?php }?>
                                                </div>
                                                <div class="button button-sm m-0 p-0">
                                                    <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else {*/ ?>
                            <div class="row w-100">
                                <div class="col-md-8 col-sm-8 col-9">
                                    <div class="text-left py-3">
                                        <h4 class="h3"><?php echo "Products & Services";//echo !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-3 text-right">
                                    <?php
                                    if($parent_category)
                                    {
                                        ?>
                                        <a class="btn btn-primary btn-sm mt-1 mb-3" href="<?php echo base_url('products/'.$back_view.'/' . html_escape($parent_category['slug'])) ?>">Back</a>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="btn btn-primary btn-sm mb-3" href="javascript:void(0);" onclick="window.history.go(-1); return false;">Back</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                                
                                <?php foreach ($products['product'] as $row) { ?>
                                    <div class="col-md-3 col-sm-6 col-6">
                                        <div class="product-wrap">
                                            <div class="product text-center2">
                                                <figure class="product-media justify-content-md-center2">
                                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>" alt="" width="300" height="338"/>
                                                    </a>
                                                    <div class="product-action-vertical">
                                                        <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                                        <span class="add-favorite position-relative">
                                                            <a href="#" class="btn-product-icon btn-wishlist far add-to-fav-btn <?php echo ($row['is_favorite'] == 1) ? 'fa text-danger w-icon-heart-full' : 'w-icon-heart' ?>" data-product-id="<?= $row['id'] ?>"></a>
                                                        </span>
                                                        <?php } ?>
                                                        
                                                        <?php
                                                        if (count($row['variants']) <= 1) {
                                                            $variant_id = $row['variants'][0]['id'];
                                                            $modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $modal = "#quick-view";
                                                        }
                                                        ?>
                                                        <?php /* ?>
                                                        <a href="" class="quick-view-btn btn-product-icon btn-quickview w-icon-search" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"></a>
                                                        <a href="" data-tip="Add to Cart" class="add_to_cart btn-product-icon btn-cart w-icon-cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"></a>
                                                        <?php */ ?>
                                                    </div>
                                                    <div class="product-label-group">
                                                        <?php /*if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                            <span class="product-new-label product-label label-discount"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                            <label class="product-label label-discount"><?= $row['min_max_price']['discount_in_percentage'] ?>%</label>
                                                        <?php }*/ ?>
                                                    </div>
                                                </figure>
                                                <div class="product-details">
                                                    <h3 class="product-name">
                                                        <a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a>
                                                    </h3>
                                                    <h3 class="product-name">
                                                        <a class="text-primary" href="<?= base_url('products?seller=' . $row['seller_slug']) ?>">
                                                            <?php echo $row['company_name']; ?>
                                                        </a>
                                                    </h3>
                                                    <?php /* ?>
                                                    <div class="ratings-container">
                                                        <div class="rating">
                                                            <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                                        </div>
                                                    </div>
                                                    <?php */ ?>
                                                    
                                                    <?php if($row['product_stock_status']) { ?> 
                                                    <div class="product-pa-wrapper2 row mb-1">
                                                        <?php 
                                                        if($row['type']== "simple_product")
                                                        {
                                                            ?>
                                                            <div class="product-price2 col col-sm-12">
                                                                <p class="mt-1 mb-1">
                                                                    <ins class="new-price"><?= $settings['currency'] ?> <?php echo $row['_special_price']; ?></ins>
                                                                </p>
                                                                <p class="mt-0 mb-0">
                                                                    <del class="old-price"><?= $settings['currency'] ?> <?php echo $row['_price']; ?></del>
                                                                </p>
                                                                <p class="mt-0 mb-0">
                                                                    <del class="old-price"><?= $settings['currency'] ?> <?php echo $row['_mrp']; ?></del>
                                                                </p>
                                                            </div>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            //$price = get_price_range_of_product($row['id']);
                                                            //echo $price['range'];
                                                            ?>
                                                            <div class="product-price2 price-sec-<?php echo $row['id'];?> col col-sm-12 col-lg-6 col-12">
                                                                <?php
                                                                //$price = get_default_price($row['id']);
                                                                //echo $price;
                                                                echo $settings['currency'].' '.$row['variants'][0]['special_price'].' <del class="old-price">'.$settings['currency'].' '.$row['variants'][0]['price'].'</del>';
                                                                ?>
                                                            </div>
                                                            <?php
                                                            if(count($row['variants']) > 0)
                                                            {
                                                                ?>
                                                                <div class="col col-sm-12 col-lg-6 col-12">
                                                                    <div class="pro_select_var">
                                                                        <select id="variant_dropdown_<?php echo $row['id'];?>" name="variant_dropdown_<?php echo $row['id'];?>" class="form-control variant_dropdown">
                                                                            <?php
                                                                            foreach($row['variants'] as $v)
                                                                            {
                                                                                ?>
                                                                                <option value="<?php echo $v['id'];?>" data-id="<?php echo $row['id'];?>" data-variant-id="<?php echo $v['id'];?>" data-mrp="<?php echo $v['mrp'];?>" data-price="<?php echo $v['price'];?>" data-special-price="<?php echo $v['special_price'];?>"><?php echo $v['packing_size'].' '.$v['unit'];?> <?php echo ($v['carton_qty'] > 1) ? ' &#x2715; '.$v['carton_qty'] : ' &#x2715; 1';?></option>  
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            /*
                                                            $attributes = array_values($row['variant_attributes']);//get_product_variants($row['id']);
                                                            var_dump($row, $attributes);die;
                                                            if($attributes)
                                                            {
                                                                ?>
                                                                <div class="col col-sm-12 col-lg-6">
                                                                    <div class="pro_select_var">
                                                                        <select id="" name="" class="form-control">
                                                                            <?php
                                                                            foreach($attributes as $attribute)
                                                                            {
                                                                                ?>
                                                                                <option><?php echo $attribute ?></option>  
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }*/
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <?php if(!$this->ion_auth->is_seller() && !$this->ion_auth->is_admin()) { ?> 
                                                    <a class="add-to-cart add-to-cart-<?php echo $row['id']; ?> add_to_cart btn-block btn-sm text-center" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a><!--  data-izimodal-open="<?= $modal ?>" -->
                                                    <?php } ?>
                                                    <?php } else { ?> 
                                                    <div class="product-pa-wrapper2 row">
                                                        <h6 class="text-danger">Out of stock</h6>
                                                    </div>
                                                    <?php } ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php /* ?>
                                        <div class="product-grid">
                                            <aside class="add-favorite">
                                                <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>">
                                                    </a>
                                                </div>
                                                <ul class="social">
                                                    <?php
                                                    if (count($row['variants']) <= 1) {
                                                        $variant_id = $row['variants'][0]['id'];
                                                        $modal = "";
                                                    } else {
                                                        $variant_id = "";
                                                        $modal = "#quick-view";
                                                    }
                                                    ?>
                                                    <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                    <li><a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                                <?php if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                    <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                <?php } ?>
                                            </div>
                                            <div class="rating">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h3>
                                                <div class="price">
                                                    <?php $price = get_price_range_of_product($row['id']);
                                                    echo $price['range'];
                                                    ?>
                                                </div>
                                                <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        
                                    </div>
                                <?php } ?>
                            </div>
                        <?php //} ?>
                    <?php } ?>

                    <?php if ((!isset($sub_categories) || empty($sub_categories)) && (!isset($products) || empty($products['product']))) { ?>
                        <div class="row w-100">
                            <div class="col-md-8 col-sm-8 col-9">
                                <div class="text-left py-3">
                                    <h4 class="h3"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></h4>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-3 text-right">
                                <?php
                                if($parent_category)
                                {
                                    ?>
                                    <a class="btn btn-primary btn-sm mt-1 mb-3" href="<?php echo base_url('products/'.$back_view.'/' . html_escape($parent_category['slug'])) ?>">Back</a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a class="btn btn-primary btn-sm mb-3" href="javascript:void(0);" onclick="window.history.go(-1); return false;">Back</a>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-12 text-center">
                                <h6 class="h6">No Products Found.</h6>
                                <!--<a href="<?= base_url('products') ?>" class="button button-rounded button-warning"><?= !empty($this->lang->line('go_to_shop')) ? $this->lang->line('go_to_shop') : 'Go to Shop' ?></a>-->
                            </div>
                        </div>
                    <?php } ?>
                    <nav class="text-center mt-4">
                        <?= (isset($links)) ? $links : '' ?>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Menu -->
        <div class="filter-nav js-filter-nav d-none filter-nav-sm">
            <div class="filter-nav__list js-filter-nav__list">
                <h3 class="mt-0">Showing <span class="text-primary">12</span> Products</h3>
                <div class="col-md-4 order-md-1 col-lg-3">
                    <div id="product-filters-mobile">
                        <?php if (isset($products['filters']) && !empty($products['filters'])) { ?>
                            <div class="accordion" id="accordionExample">
                                <?php foreach ($products['filters'] as $key => $row) {
                                    $row_attr_name = str_replace(' ', '-', $row['name']);
                                    $attribute_name = isset($_GET[strtolower('filter-' . $row_attr_name)]) ? $this->input->get(strtolower('filter-' . $row_attr_name), true) : 'null';
                                    $selected_attributes = explode('|', $attribute_name);
                                    $attribute_values = explode(',', $row['attribute_values']);
                                    $attribute_values_id = explode(',', $row['attribute_values_id']);
                                ?>
                                    <div class="card-custom">
                                        <div class="card-header-custom" id="headingOne">
                                            <h2 class="mb-0">
                                                <a class="collapse-arrow btn btn-link collapsed" data-toggle="collapse" data-target="#m<?= $key ?>" aria-expanded="false" aria-controls="#m<?= $key ?>"><?= html_escape($row['name']) ?><i class="fa fa-angle-down rotate"></i></a>
                                            </h2>
                                        </div>
                                        <div id="m<?= $key ?>" class="collapse <?= ($attribute_name != 'null') ? 'show' : '' ?>" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body-custom">
                                                <?php foreach ($attribute_values as $key => $values) {
                                                    $values = strtolower($values);
                                                ?>
                                                    <div class="input-container d-flex">
                                                        <?= form_checkbox(
                                                            $values,
                                                            $values,
                                                            (in_array($values, $selected_attributes)) ? TRUE : FALSE,
                                                            array(
                                                                'class' => 'toggle-input product_attributes',
                                                                'id' => 'm' . $row_attr_name . ' ' . $values,
                                                                'data-attribute' => strtolower($row['name']),
                                                            )
                                                        ) ?>
                                                        <label class="toggle checkbox" for="<?= 'm' . $values ?>">
                                                            <div class="toggle-inner"></div>
                                                        </label>
                                                        <?= form_label($values, 'm' . $row_attr_name . ' ' . $values, array('class' => 'text-label')) ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="text-center">
                        <button class="button button-rounded button-warning product_filter_btn"><?= !empty($this->lang->line('filter')) ? $this->lang->line('filter') : 'Filter' ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php 
        if($seller_info)
        {
                ?>
                </div>
                <div class="tab-pane fade" id="about_company" role="tabpanel" aria-labelledby="about_company-tab">
                    <h3>About Us</h3>
                    <p><?php echo $seller_info['about_us'];?></p>
                    
                    <?php if($seller_info['infrastructure']!='') { ?>
                        <h4>Infrastructure & Facilities</h4>
                        <p><?php echo $seller_info['infrastructure'];?></p>
                    <?php } ?>
                    
                    <?php if($seller_info['quality_compliance']!='') { ?>
                        <h4>Quality & Compliance</h4>
                        <p><?php echo $seller_info['quality_compliance'];?></p>
                        <?php
                        if(file_exists($seller_info['quality_compliance_file']) && $seller_info['quality_compliance_file']!='')
                        {
                            ?>
                            <a class="btn btn-primary btn-rounded btn-md mb-3" href="<?php echo base_url().$seller_info['quality_compliance_file']; ?>" target="_blank">More Details</a>
                            <?php
                        }
                        ?>
                    <?php } ?>
                    
                    <?php if($seller_info['awards_recognition']!='') { ?>
                        <h4>Awards & Recognition</h4>
                        <p><?php echo $seller_info['awards_recognition'];?></p>
                        <?php
                        if(file_exists($seller_info['awards_recognition_file']) && $seller_info['awards_recognition_file']!='')
                        {
                            ?>
                            <a class="btn btn-primary btn-rounded btn-md mb-3" href="<?php echo base_url().$seller_info['awards_recognition_file']; ?>" target="_blank">More Details</a>
                            <?php
                        }
                        ?>
                    <?php } ?>
                    
                </div>
                <div class="tab-pane fade" id="contact_us" role="tabpanel" aria-labelledby="contact_us-tab">
                    <h3>Contact Us</h3>
                    <p class="mb-0 text-dark"><strong>Contact Person Name:</strong> <?php echo $seller_info['username'];?></p>
                    <p class="mb-0 text-dark"><strong>Designation:</strong> <?php echo $seller_info['designation'];?></p>
                    <p class="mb-0 text-dark"><strong>Mobile No.:</strong> <?php echo $seller_info['mobile'];?></p>
                    <?php if($seller_info['alternate_mobile']!='') { ?> 
                    <p class="mb-0 text-dark"><strong>Alternate Mobile No.:</strong> <?php echo $seller_info['alternate_mobile'];?></p>
                    <?php } ?>
                    <p class="mb-0 text-dark"><strong>Email:</strong> <?php echo $seller_info['email'];?></p>
                    <?php if($seller_info['alternate_email']!='') { ?> 
                    <p class="mb-0 text-dark"><strong>Alternate Email:</strong> <?php echo $seller_info['alternate_email'];?></p>
                    <?php } ?>
                    <p class="mb-0 text-dark"><strong>Address:</strong> <?php echo $seller_info['plot_no'].', '.$seller_info['street_locality'],', '.$seller_info['landmark'],', '.$seller_info['city_name'].', '.$seller_info['state_name'].' '.$seller_info['pin'];?></p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</section>