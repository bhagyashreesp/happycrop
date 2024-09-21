<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['address_model', 'category_model', 'cart_model', 'faq_model']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }

    /*
    public function index()
    {
       redirect(base_url('admin/login'));
    }
    */
    
    public function index()
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url().'register');
        }
        
        if($this->ion_auth->logged_in()) {
            if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2)
            {
                redirect(base_url().'register/step4/1');
            }
            
            if($this->ion_auth->member_status() == 2)
            {
                redirect(base_url().'register/step4/0');
            }
        }
        
        $this->data['main_page'] = 'home';
        $this->data['title'] = 'Home | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Home, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Home | ' . $this->data['web_settings']['meta_description'];

        $limit =  12;
        $offset =  0;
        $sort = 'row_order';
        $order =  'ASC';
        $has_child_or_item = 'false';
        $filters = [];
        /* Fetching Categories Sections */
        $categories = $this->category_model->get_categories('', $limit, $offset, $sort, $order, 'false');

        /* Fetching Featured Sections */

        $megamenus       = $this->db->limit($limit, $offset)->order_by('row_order')->get('megamenus')->result_array();
        $megamenu_blocks = $this->db->limit($limit, $offset)->order_by('row_order')->get('megamenu_blocks')->result_array();
        $category_blocks = $this->db->limit($limit, $offset)->order_by('row_order')->get('category_blocks')->result_array();
        $sections        = $this->db->limit($limit, $offset)->order_by('row_order')->get('sections')->result_array();
        $user_id = NULL;
        if ($this->data['is_logged_in']) {
            $user_id = $this->data['user']->id;
        }
        $filters['show_only_active_products'] = true;
        if (!empty($sections)) {
            for ($i = 0; $i < count($sections); $i++) {
                $product_ids = explode(',', $sections[$i]['product_ids']);
                $product_ids = array_filter($product_ids);
                $product_categories = (isset($sections[$i]['categories']) && !empty($sections[$i]['categories']) && $sections[$i]['categories'] != NULL) ? explode(',', $sections[$i]['categories']) : null;
                if (isset($sections[$i]['product_type']) && !empty($sections[$i]['product_type'])) {
                    $filters['product_type'] = (isset($sections[$i]['product_type'])) ? $sections[$i]['product_type'] : null;
                }

                if ($sections[$i]['style'] == "default") {
                    $limit = 10;
                } elseif ($sections[$i]['style'] == "style_1" || $sections[$i]['style'] == "style_2") {
                    $limit = 7;
                } elseif ($sections[$i]['style'] == "style_3" || $sections[$i]['style'] == "style_4") {
                    $limit = 5;
                } else {
                    $limit = null;
                }
                $products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $product_categories, $limit, null, null, null);
                $sections[$i]['title'] =  output_escaping($sections[$i]['title']);
                $sections[$i]['slug'] =  url_title($sections[$i]['title'], 'dash', true);
                $sections[$i]['short_description'] =  output_escaping($sections[$i]['short_description']);
                $sections[$i]['filters'] = (isset($products['filters'])) ? $products['filters'] : [];
                $sections[$i]['product_details'] =  $products['product'];
                unset($sections[$i]['product_details'][0]['total']);
                $sections[$i]['product_details'] = $products['product'];
                unset($product_details);
            }
        }
        
        if(!empty($megamenu_blocks)) {
            for ($i = 0; $i < count($megamenu_blocks); $i++) {
                $main_category = $megamenu_blocks[$i]['main_category'];
                $category_info = fetch_details(array('id'=>$main_category),'categories');
                
                $megamenu_blocks[$i]['title'] =  output_escaping($megamenu_blocks[$i]['title']);
                //$limit = 24;
                
                //$products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $main_category, $limit, null, null, null);
                //$megamenu_blocks[$i]['product_details'] = $products['product'];
                $megamenu_blocks[$i]['slug']            = $category_info[0]['slug'];
                $megamenu_blocks[$i]['image']           = $category_info[0]['image'];
                $megamenu_blocks[$i]['banner']          = $category_info[0]['banner'];
                $megamenu_blocks[$i]['name']            = $category_info[0]['name'];
                
                $sub_categories_ids = explode(',', $megamenu_blocks[$i]['sub_categories']);
                $sub_categories_ids = array_filter($sub_categories_ids);
                
                $show_type          = $megamenu_blocks[$i]['show_type'];
                
                if($show_type == 1)
                {
                    if($sub_categories_ids)
                    {
                        $sub_categories_arr = array();
                        for($j = 0; $j < count($sub_categories_ids); $j++) {
                            $sub_category_info = fetch_details(array('id'=>$sub_categories_ids[$j]),'categories');
                            $sub_categories_arr[$j]['id']   = $sub_category_info[0]['id'];
                            $sub_categories_arr[$j]['name'] = $sub_category_info[0]['name'];
                            
                            $limit = 4;
                        
                            //$products                                  = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $sub_categories_ids[$j], $limit, null, null, null);
                            //$sub_categories_arr[$j]['product_details'] = $products['product'];
                            $sub_categories_arr[$j]['slug']            = $sub_category_info[0]['slug'];
                            $sub_categories_arr[$j]['image']           = $sub_category_info[0]['image'];
                            $sub_categories_arr[$j]['banner']          = $sub_category_info[0]['banner'];
                            $sub_categories_arr[$j]['sub_categories']  = fetch_details(array('parent_id'=>$sub_categories_ids[$j]),'categories','*',$limit);
                        }
                        $megamenu_blocks[$i]['sub_cat_info'] = $sub_categories_arr;
                    }
                    else
                    {
                        $megamenu_blocks[$i]['sub_cat_info'] = array();
                        $main_category = $megamenu_blocks[$i]['main_category'];
                        //$products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $main_category, $limit, null, null, null);
                        $sub_category_id = $megamenu_blocks[$i]['main_category'];
                        $sub_categories_arr = array();
                        for($j = 0; $j < 5; $j++) 
                        {
                            if($sub_category_id)
                            {
                                $limit = 4;
                                $offset = $j*$limit;
                                //$products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $sub_category_id, $limit, $offset, null, null);
                                
                                if(!empty($products['product']))
                                {
                                    $sub_category_info = fetch_details(array('id'=>$sub_category_id),'categories');
                                    $sub_categories_arr[$j]['id']   = $sub_category_info[0]['id'];
                                    $sub_categories_arr[$j]['name'] = $sub_category_info[0]['name'];
                                    
                                    
                                    //$sub_categories_arr[$j]['product_details'] = $products['product'];
                                    $sub_categories_arr[$j]['slug']            = $sub_category_info[0]['slug'];
                                    $sub_categories_arr[$j]['image']           = $sub_category_info[0]['image'];
                                    $sub_categories_arr[$j]['banner']          = $sub_category_info[0]['banner'];
                                    $sub_categories_arr[$j]['sub_categories']  = array();
                                }
                                else
                                {
                                    break;
                                }
                            }
                            //var_dump($products["product"][$j]['category_name']);die;
                        }
                        
                        $megamenu_blocks[$i]['sub_cat_info'] = $sub_categories_arr;
                    }
                }
                else if($show_type == 2)
                {
                    $super_category = $megamenu_blocks[$i]['super_category'];
                    $category_info = fetch_details(array('super_category_id'=>$super_category),'categories');
                    
                    $megamenu_blocks[$i]['title'] =  output_escaping($megamenu_blocks[$i]['title']);
                    $limit = 24;
                    
                    //$products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $main_category, $limit, null, null, null);
                    //$category_blocks[$i]['product_details'] = $products['product'];
                    $megamenu_blocks[$i]['slug']            = $category_info[0]['slug'];
                    $megamenu_blocks[$i]['image']           = $category_info[0]['image'];
                    $megamenu_blocks[$i]['banner']          = $category_info[0]['banner'];
                    $megamenu_blocks[$i]['name']            = $category_info[0]['name'];
                    
                    $this->db->select('id');
                    $this->db->from('categories');
                    $this->db->where('super_category_id',$super_category);
                    $this->db->where('status',1);
                    $this->db->where('parent_id',0);
                    $this->db->order_by('row_order','ASC');
                    $this->db->limit(6);
                    $query = $this->db->get();
                    $sub_categories_info = $query->result_array();
                    $sub_categories_ids = array_column($sub_categories_info, 'id');
                    $sub_categories_ids = array_filter($sub_categories_ids);
                    
                    if($sub_categories_ids)
                    {
                        $sub_categories_arr = array();
                        for($j = 0; $j < count($sub_categories_ids); $j++) {
                            $sub_category_info = fetch_details(array('id'=>$sub_categories_ids[$j]),'categories');
                            $sub_categories_arr[$j]['id']   = $sub_category_info[0]['id'];
                            $sub_categories_arr[$j]['name'] = $sub_category_info[0]['name'];
                            
                            $limit = 4;
                        
                            //$products                                  = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $sub_categories_ids[$j], $limit, null, null, null);
                            //$sub_categories_arr[$j]['product_details'] = $products['product'];
                            $sub_categories_arr[$j]['slug']            = $sub_category_info[0]['slug'];
                            $sub_categories_arr[$j]['image']           = $sub_category_info[0]['image'];
                            $sub_categories_arr[$j]['banner']          = $sub_category_info[0]['banner'];
                            $sub_categories_arr[$j]['sub_categories']  = fetch_details(array('sub_category_id'=>$sub_categories_ids[$j]),'insect_pests','*',$limit);
                        }
                        $megamenu_blocks[$i]['sub_cat_info'] = $sub_categories_arr;
                    }
                }
                
            }
        }
        
        if(!empty($category_blocks)) {
            for ($i = 0; $i < count($category_blocks); $i++) {
                $main_category = $category_blocks[$i]['main_category'];
                $category_info = fetch_details(array('id'=>$main_category),'categories');
                
                $category_blocks[$i]['title'] =  output_escaping($category_blocks[$i]['title']);
                $limit = 24;
                
                //$products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $main_category, $limit, null, null, null);
                //$category_blocks[$i]['product_details'] = $products['product'];
                $category_blocks[$i]['slug']            = $category_info[0]['slug'];
                $category_blocks[$i]['image']           = $category_info[0]['image'];
                $category_blocks[$i]['banner']          = $category_info[0]['banner'];
                $category_blocks[$i]['name']            = $category_info[0]['name'];
                
                $sub_categories_ids = explode(',', $category_blocks[$i]['sub_categories']);
                $sub_categories_ids = array_filter($sub_categories_ids);
                
                $show_type          = $category_blocks[$i]['show_type'];
                
                if($show_type == 1)
                {
                    if($sub_categories_ids)
                    {
                        $sub_categories_arr = array();
                        for($j = 0; $j < count($sub_categories_ids); $j++) {
                            $sub_category_info = fetch_details(array('id'=>$sub_categories_ids[$j]),'categories');
                            $sub_categories_arr[$j]['id']   = $sub_category_info[0]['id'];
                            $sub_categories_arr[$j]['name'] = $sub_category_info[0]['name'];
                            
                            $limit = 4;
                        
                            //$products                                  = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $sub_categories_ids[$j], $limit, null, null, null);
                            //$sub_categories_arr[$j]['product_details'] = $products['product'];
                            $sub_categories_arr[$j]['slug']            = $sub_category_info[0]['slug'];
                            $sub_categories_arr[$j]['image']           = $sub_category_info[0]['image'];
                            $sub_categories_arr[$j]['banner']          = $sub_category_info[0]['banner'];
                            $sub_categories_arr[$j]['sub_categories']  = fetch_details(array('parent_id'=>$sub_categories_ids[$j]),'categories','*',$limit);
                        }
                        $category_blocks[$i]['sub_cat_info'] = $sub_categories_arr;
                    }
                    else
                    {
                        $category_blocks[$i]['sub_cat_info'] = array();
                        $main_category = $category_blocks[$i]['main_category'];
                        $products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $main_category, $limit, null, null, null);
                        $sub_category_id = $category_blocks[$i]['main_category'];
                        $sub_categories_arr = array();
                        for($j = 0; $j < 6; $j++) 
                        {
                            if($sub_category_id)
                            {
                                $limit = 4;
                                $offset = $j*$limit;
                                $products = array();//fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $sub_category_id, $limit, $offset, null, null);
                                
                                if(!empty($products['product']))
                                {
                                    $sub_category_info = fetch_details(array('id'=>$sub_category_id),'categories');
                                    $sub_categories_arr[$j]['id']   = $sub_category_info[0]['id'];
                                    $sub_categories_arr[$j]['name'] = $sub_category_info[0]['name'];
                                    
                                    
                                    //$sub_categories_arr[$j]['product_details'] = $products['product'];
                                    $sub_categories_arr[$j]['slug']            = $sub_category_info[0]['slug'];
                                    $sub_categories_arr[$j]['image']           = $sub_category_info[0]['image'];
                                    $sub_categories_arr[$j]['banner']          = $sub_category_info[0]['banner'];
                                    $sub_categories_arr[$j]['sub_categories']  = array();//fetch_details(array('parent_id'=>$sub_category_id),'categories','*',$limit);
                                }
                                else
                                {
                                    break;
                                }
                            }
                            //var_dump($products["product"][$j]['category_name']);die;
                        }
                        
                        $category_blocks[$i]['sub_cat_info'] = $sub_categories_arr;
                    }
                }
                else if($show_type == 2)
                {
                    $super_category = $category_blocks[$i]['super_category'];
                    $category_info = fetch_details(array('super_category_id'=>$super_category),'categories');
                    
                    $category_blocks[$i]['title'] =  output_escaping($category_blocks[$i]['title']);
                    $limit = 24;
                    
                    //$products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $main_category, $limit, null, null, null);
                    //$category_blocks[$i]['product_details'] = $products['product'];
                    $category_blocks[$i]['slug']            = $category_info[0]['slug'];
                    $category_blocks[$i]['image']           = $category_info[0]['image'];
                    $category_blocks[$i]['banner']          = $category_info[0]['banner'];
                    $category_blocks[$i]['name']            = $category_info[0]['name'];
                    
                    $this->db->select('id');
                    $this->db->from('categories');
                    $this->db->where('super_category_id',$super_category);
                    $this->db->where('status',1);
                    $this->db->where('parent_id',0);
                    $this->db->order_by('row_order','ASC');
                    $this->db->limit(6);
                    $query = $this->db->get();
                    $sub_categories_info = $query->result_array();
                    $sub_categories_ids = array_column($sub_categories_info, 'id');
                    $sub_categories_ids = array_filter($sub_categories_ids);
                    
                    if($sub_categories_ids)
                    {
                        $sub_categories_arr = array();
                        for($j = 0; $j < count($sub_categories_ids); $j++) {
                            $sub_category_info = fetch_details(array('id'=>$sub_categories_ids[$j]),'categories');
                            $sub_categories_arr[$j]['id']   = $sub_category_info[0]['id'];
                            $sub_categories_arr[$j]['name'] = $sub_category_info[0]['name'];
                            
                            $limit = 4;
                        
                            //$products                                  = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $sub_categories_ids[$j], $limit, null, null, null);
                            //$sub_categories_arr[$j]['product_details'] = $products['product'];
                            $sub_categories_arr[$j]['slug']            = $sub_category_info[0]['slug'];
                            $sub_categories_arr[$j]['image']           = $sub_category_info[0]['image'];
                            $sub_categories_arr[$j]['banner']          = $sub_category_info[0]['banner'];
                            $sub_categories_arr[$j]['sub_categories']  = fetch_details(array('sub_category_id'=>$sub_categories_ids[$j]),'insect_pests','*',$limit);
                        }
                        $category_blocks[$i]['sub_cat_info'] = $sub_categories_arr;
                    }
                    
                }
            }
        }
        $this->data['megamenus'] = $megamenus;
        $this->data['sections'] = $sections;
        $this->data['megamenu_blocks'] = $megamenu_blocks;
        $this->data['category_blocks'] = $category_blocks;
        $this->data['categories'] = $categories;
        $this->data['username'] = $this->session->userdata('username');
        $this->data['sliders'] = get_sliders('', '', '', 1);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function error_404()
    {
        $this->data['main_page'] = 'error_404';
        $this->data['title'] = 'Product cart | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Product cart, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Product cart | ' . $this->data['web_settings']['meta_description'];
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function categories()
    {
        $limit =  50;
        $offset =  0;
        $sort = 'row_order';
        $order =  'ASC';
        $has_child_or_item = 'false';
        $this->data['main_page'] = 'categories';
        $this->data['title'] = 'Categories | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Categories, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Categories | ' . $this->data['web_settings']['meta_description'];
        $this->data['categories'] = $this->category_model->get_categories('', $limit, $offset, $sort, $order, 'false');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function get_products()
    {

        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('id', 'Product ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search', 'trim|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean|alpha');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {

            $limit = (isset($_GET['limit'])) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_GET['offset'])) ? $this->input->post('offset', true) : 0;
            $order = (isset($_GET['order']) && !empty(trim($_GET['order']))) ? $_GET['order'] : 'DESC';
            $sort = (isset($_GET['sort']) && !empty(trim($_GET['sort']))) ? $_GET['sort'] : 'p.id';
            $filters['search'] =  (isset($_GET['search'])) ? $_GET['search'] : null;
            $filters['attribute_value_ids'] = (isset($_GET['attribute_value_ids'])) ? $_GET['attribute_value_ids'] : null;
            $category_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : null;
            $product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
            $user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : null;
            
            /*$this->db->select('a.id, a.name, a.slug, a.image');
            $this->db->from('categories as a');
            $this->db->where('a.status', 1);
            $this->db->like('a.name', trim($filters['search']));
            $query = $this->db->get();
            $categories = $query->result_array();*/
            
            $categories_search_arr = array();
            /*if($categories)
            {
                foreach($categories as $category)
                {
                    $image_sm = base_url(get_settings('favicon'));
                    
                    if(file_exists($category['image']) && $category['image']!='') {
                        $image_sm = base_url().$category['image'];
                    }
                    
                    $categories_search_arr[] = array(
                            'id' => 0,
                            'image_sm' => $image_sm,
                            'name' => $category['name'],
                            'category_name' => 'categories',
                            'link' => base_url('products/category/' . $category['slug']),
                        );
                }
            }*/
            
            $this->db->select('a.user_id, a.company_name, a.slug, a.logo');
            $this->db->from('seller_data as a');
            $this->db->where('a.status', 1);
            $this->db->like('a.company_name', trim($filters['search']));
            $query = $this->db->get();
            $sellers = $query->result_array();
            
            $sellers_search_arr = array();
            if($sellers)
            {
                foreach($sellers as $seller)
                {
                    $image_sm = base_url(get_settings('favicon'));
                    
                    if(file_exists($seller['logo']) && $seller['logo']!='') {
                        $image_sm = base_url().$seller['logo'];
                    }
                    
                    $sellers_search_arr[] = array(
                            'id' => 0,
                            'image_sm' => $image_sm,
                            'name' => $seller['company_name'],
                            'category_name' => 'businesses',
                            'link' => base_url('products?seller=' . $seller['slug']),
                        );
                }
            }
            
            $products = fetch_product($user_id, (isset($filters)) ? $filters : null, $product_id, $category_id, $limit, $offset, $sort, $order);
            $first_search_option[0] = array(
                'id' => 0,
                'image_sm' => base_url(get_settings('favicon')),
                'name' => 'Search Result for ' . $_GET['search'],
                'category_name' => 'all categories, all businesses',
                'link' => base_url('products/search?q=' . $_GET['search']),
            );
            if (!empty($products['product'])) {
                $products['product'] = array_map(function ($d) {
                    $d['link'] = base_url('products/details/' . $d['slug']);
                    $d['category_name'] = '<span class="font-weight-bold">'.$d['category_name'].'</span> <br/>from <span class="font-weight-bold">'.$d['company_name'].'</span>';
                    return $d;
                }, $products['product']);
                $this->response['error'] = false;
                $this->response['message'] = "Products retrieved successfully !";
                $this->response['filters'] = (isset($products['filters']) && !empty($products['filters'])) ? $products['filters'] : [];
                $this->response['total'] = (isset($products['total'])) ? strval($products['total']) : '';
                $this->response['offset'] = (isset($_GET['offset']) && !empty($_GET['offset'])) ? $_GET['offset'] : '0';
                
                if(!empty($sellers_search_arr))
                {
                    $products['product'] = array_merge($products['product'], $sellers_search_arr);
                }
                
                if(!empty($categories_search_arr))
                {
                    $products['product'] = array_merge($products['product'], $categories_search_arr);
                }
                
                if(!empty($products['product']))
                {
                    $names = array();
                    foreach ($products['product'] as $key => $val)
                    {
                        $names[$key] = $val['name'];
                    }
                    array_multisort($names, SORT_ASC, $products['product']);
                }
                
                $products['product'] = array_merge($first_search_option, $products['product']);
                
                $this->response['data'] = $products['product'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Products Not Found !";
                
                $search_arr = array();
                
                if(!empty($sellers_search_arr))
                {
                    $search_arr = array_merge($search_arr, $sellers_search_arr);
                }
                
                if(!empty($categories_search_arr))
                {
                    $search_arr = array_merge($search_arr, $categories_search_arr);
                }
                
                $first_search_option = array_merge($first_search_option, $search_arr);
                
                $this->response['data'] =  $first_search_option;
            }
        }
        print_r(json_encode($this->response));
    }

    public function address_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->address_model->get_address_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function checkout()
    {
        $this->data['main_page'] = 'checkout';
        $this->data['title'] = 'Checkout | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Checkout, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Checkout | ' . $this->data['web_settings']['meta_description'];
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function terms_and_conditions()
    {
        $this->data['main_page'] = 'terms-and-conditions';
        $this->data['title'] = 'Terms & Conditions | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Terms & Conditions, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Terms & Conditions | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Terms & Conditions | ' . $this->data['web_settings']['site_title'];
        $this->data['terms_and_conditions'] = get_settings('terms_conditions');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function privacy_policy()
    {
        $this->data['main_page'] = 'privacy-policy';
        $this->data['title'] = 'Privacy Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Privacy Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Privacy Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Privacy Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['privacy_policy'] = get_settings('privacy_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    public function about_us()
    {
        $this->data['main_page'] = 'about-us';
        $this->data['title'] = 'About US | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'About US, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'About US | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'About US | ' . $this->data['web_settings']['site_title'];
        $this->data['about_us'] = get_settings('about_us');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function help()
    {
        $this->data['main_page'] = 'help';
        $this->data['title'] = 'Help | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Help, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Help | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Help | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
 
    public function get_started_mfg()
    {
        $this->data['main_page'] = 'get_started_mfg';
        $this->data['title'] = 'Getting Started as a Manufacturer on Happycrop | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Getting Started as a Manufacturer on Happycrop, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Getting Started as a Manufacturer on Happycrop | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Getting Started as a Manufacturer on Happycrop | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function get_started_retailer()
    {
        $this->data['main_page'] = 'get_started_retailer';
        $this->data['title'] = 'Getting Started as a Retailer on Happycrop | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Getting Started as a Retailer on Happycrop, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Getting Started as a Retailer on Happycrop | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Getting Started as a Retailer on Happycrop | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function navigating_your_dashboard()
    {
        $this->data['main_page'] = 'navigating_your_dashboard';
        $this->data['title'] = 'Navigating Your Dashboard on Happycrop | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Navigating Your Dashboard on Happycrop, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Navigating Your Dashboard on Happycrop | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Navigating Your Dashboard on Happycrop | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function contact_us()
    {
        $this->data['main_page'] = 'contact-us';
        $this->data['title'] = 'Contact US | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Contact US, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Contact US | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Contact US | ' . $this->data['web_settings']['site_title'];
        $this->data['contact_us'] = get_settings('contact_us');
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function faq()
    {
        $this->data['main_page'] = 'faq';
        $this->data['title'] = 'FAQ | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'FAQ, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'FAQ | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'FAQ | ' . $this->data['web_settings']['site_title'];
        $this->data['faq'] = $this->faq_model->get_faqs(null, null, null, null);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function careers()
    {
        $this->data['main_page'] = 'careers';
        $this->data['title'] = 'Careers | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Careers, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Careers | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Careers | ' . $this->data['web_settings']['site_title'];
        $this->data['careers'] = get_settings('careers');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function return_policy()
    {
        $this->data['main_page'] = 'return-policy';
        $this->data['title'] = 'Return Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Return Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Return Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Return Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['return_policy'] = get_settings('return_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function shipping_policy()
    {
        $this->data['main_page'] = 'shipping-policy';
        $this->data['title'] = 'Shipping Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Shipping Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Shipping Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Shipping Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['shipping_policy'] = get_settings('shipping_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function quality_policy()
    {
        $this->data['main_page'] = 'quality-policy';
        $this->data['title'] = 'Quality Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Quality Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Quality Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Quality Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['quality_policy'] = get_settings('quality_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function pricing_policy()
    {
        $this->data['main_page'] = 'pricing-policy';
        $this->data['title'] = 'Pricing Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Pricing Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Pricing Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Pricing Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['pricing_policy'] = get_settings('pricing_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function delivery_policy()
    {
        $this->data['main_page'] = 'delivery-policy';
        $this->data['title'] = 'Delivery Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Delivery Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Delivery Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Delivery Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['delivery_policy'] = get_settings('delivery_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function payment_policy()
    {
        $this->data['main_page'] = 'payment-policy';
        $this->data['title'] = 'Payment Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Payment Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Payment Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Payment Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['payment_policy'] = get_settings('payment_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function security_policy()
    {
        $this->data['main_page'] = 'security-policy';
        $this->data['title'] = 'Security Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Security Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Security Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Security Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['security_policy'] = get_settings('security_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function partnering_policy()
    {
        $this->data['main_page'] = 'partnering-policy';
        $this->data['title'] = 'Partnering Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Partnering Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Partnering Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Partnering Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['partnering_policy'] = get_settings('partnering_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function licensing_policy()
    {
        $this->data['main_page'] = 'licensing-policy';
        $this->data['title'] = 'Licensing Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Licensing Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Licensing Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Licensing Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['licensing_policy'] = get_settings('licensing_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    /**
     * Log the user in
     */
    public function login()
    {
        $this->data['title'] = $this->lang->line('login_heading');
        $identity_column = $this->config->item('identity', 'ion_auth');
        // validate form input
        $this->form_validation->set_rules('identity', ucfirst($identity_column), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if ($this->form_validation->run() === TRUE) {
            $tables = $this->config->item('tables', 'ion_auth');
            $identity = $this->input->post('identity', true);
            $is_seller = $this->input->post('is_seller');
            $res = $this->db->select('id')->where($identity_column, $identity)->get($tables['login_users'])->result_array();
            if (!empty($res)) {
                // check to see if the user is logging in
                // check for "remember me"
                $remember = (bool)$this->input->post('remember');

                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    //if the login is successful
                    if (!$this->input->is_ajax_request()) {
                        redirect('admin/home', 'refresh');
                    }
                    $this->response['error'] = false;
                    $this->response['message'] = $this->ion_auth->messages();
                    echo json_encode($this->response);
                } else {
                    // if the login was un-successful
                    $this->response['error'] = true;
                    $this->response['message'] = $this->ion_auth->errors();
                    echo json_encode($this->response);
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = '<div>Incorrect Login</div>';
                echo json_encode($this->response);
            }
        } else {
            // the user is not logging in so display the login page
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];

            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
            ];

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
        }
    }

    public function lang($lang_name = '')
    {
        if (empty($lang_name)) {
            redirect(base_url());
        }

        $language = get_languages(null, $lang_name);
        if (empty($language)) {
            redirect(base_url());
        }
        $this->lang->load('web_labels_lang', $lang_name);
        $cookie = array(
            'name'   => 'language',
            'value'  => $lang_name,
            'expire' => time() + 1000
        );
        $this->input->set_cookie($cookie);
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(base_url());
        }
    }

    public function reset_password()
    {
        $this->form_validation->set_rules('mobile', 'Mobile No', 'trim|numeric|required|xss_clean|max_length[16]');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $identity_column = $this->config->item('identity', 'ion_auth');
        $res = fetch_details(['mobile' => $_POST['mobile']], 'users');
        if (!empty($res)) {
            $identity = ($identity_column  == 'email') ? $res[0]['email'] : $res[0]['mobile'];
            if (!$this->ion_auth->reset_password($identity, $_POST['new_password'])) {
                $this->response['error'] = true;
                $this->response['message'] = $this->ion_auth->messages();
                $this->response['data'] = array();
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = false;
                $this->response['message'] = 'Reset Password Successfully';
                $this->response['data'] = array();
                echo json_encode($this->response);
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'User does not exists !';
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        }
    }

    public function send_contact_us_email()
    {
        $this->form_validation->set_rules('username', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('company_name', 'Company / Shop Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
            return false;
        }

        $user_type_arr = array('1'=>'Mfg / Supplier','2'=>'Shop Owner');
        $user_type = $this->input->post('user_type');
        $username = $this->input->post('username', true);
        $phone = $this->input->post('phone');
        $email = $this->input->post('email', true);
        $subject = 'Contact Form Submitted @ happycrop.in';//$this->input->post('subject', true);
        $company_name = $this->input->post('company_name');
        $message = $this->input->post('message', true);
        $web_settings = get_settings('web_settings',true);
        $to = 'support@happycrop.in';//$web_settings['support_email'];
        $email_message = "<p><b>Name:</b> " . $username . "<br>"
            . "<b>Email:</b> " . $email . "<br>"
            . "<b>Phone Number:</b> ".$phone. "<br>"
            . "<b>Type of User:</b> ".$user_type_arr[$user_type]. "<br>"
            . "<b>Company / Shop Name:</b> " . $company_name . "<br>"
            . "<b>Enquiry:</b> " . $message . "</p>";
        //$mail = send_mail2($to, $subject, $email_message);
        
        $html_text =  '<p>Dear Happycrop Team,</p>';
        $html_text .= '<p>We have received a new enquiry that has been submitted through our Contact Us form on the Happycrop platform.</p>';
        $html_text .= '<h3>Enquiry Details:</h3>';
        $html_text .= $email_message;
        $html_text .= '<p>We kindly request you to take the necessary steps to respond to this enquiry promptly. If you require any additional information kindly revert to the enquirer email address.</p>';
        $html_text .= '<p>Thank you for your attention to this matter. We value the interest shown by our users and are committed to providing them with the best possible support and information.</p>';
        $html_text .= '<p>Best regards,</p> <p>Happycrop Support Team</p>';

        $mail_info = array(
            'subject'       => 'New Enquiry Received on Happycrop Platform',
            'user_msg'      => $html_text,
            'show_foot_note'=> false,   
        );
    
        $mail = send_mail2($to, 'New Enquiry Received on Happycrop Platform', $this->load->view('admin/pages/view/order-email-template.php', $mail_info, TRUE));
           
        $html_text2 =  '<p>Dear '.ucwords($username).',</p>';
        $html_text2 .= '<p>Thank you for reaching out to Happycrop. We truly appreciate your interest in our platform and services.</p>';
        $html_text2 .= '<p>This is to confirm that we have received your enquiry, and our team is working on providing you with the information and assistance you need. We understand the importance of your query and will do our best to respond promptly.</p>';
        $html_text2 .= '<p>If you have any further questions or require immediate assistance, please don\'t hesitate to contact us directly at support@happycrop.in or call us @ +91 9975548343. Your satisfaction is our top priority, and we are here to help</p>';
        $html_text2 .= '<p>Once again, thank you for considering Happycrop. We look forward to assisting you in the best possible way.</p>';   
        $html_text2 .= '<p>Best regards,</p> <p>Happycrop Support Team</p>';
        
        $mail_info2 = array(
            'subject'       => 'Your Enquiry to Happycrop',
            'user_msg'      => $html_text2,
            'show_foot_note'=> false,   
        );
        send_mail2($email, 'Your Enquiry to Happycrop', $this->load->view('admin/pages/view/order-email-template.php', $mail_info2, TRUE));
        
        if ($mail['error'] == true) {
            $this->response['error'] = true;
            $this->response['message'] = "Cannot send mail. Please try again later.";
            $this->response['data'] = $mail['message'];
            echo json_encode($this->response);
            return false;
        } else {
            $this->response['error'] = false;
            $this->response['message'] = 'Mail sent successfully. We will get back to you soon.';
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        }
    }
}