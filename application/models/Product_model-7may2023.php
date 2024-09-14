<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    public function add_product($data)
    {
        $data = escape_array($data);
        $pro_type = $data['product_type'];//($data['product_type'] == 'simple_product') ? 'simple_product' : 'variable_product';
        $short_description = $data['short_description'];
        $super_category_id = $data['super_category_id'];
        $category_id = $data['category_id'];
        $parent_id = $data['parent_id'];
        $seller_id = $data['seller_id'];

        // get seller product release permission
        $permits = fetch_details(['user_id' => $seller_id], 'seller_data', 'permissions');
        $s_permits = json_decode($permits[0]['permissions'], true);
        $require_products_approval = $s_permits['require_products_approval'];

        $made_in = (isset($data['made_in'])) ? $data['made_in'] : null;
        $indicator = $data['indicator'];
        $description = $data['pro_input_description'];
        $tags = (!empty($data['tags'])) ? $data['tags'] : "";
        $slug   = create_unique_slug($data['pro_input_name'], 'products');
        $main_image_name = $data['pro_input_image'];
        $other_images = (isset($data['other_images']) && !empty($data['other_images'])) ? $data['other_images'] : [];
        $total_allowed_quantity = (isset($data['total_allowed_quantity']) && !empty($data['total_allowed_quantity'])) ? $data['total_allowed_quantity'] : null;
        $minimum_order_quantity = (isset($data['minimum_order_quantity']) && !empty($data['minimum_order_quantity'])) ? $data['minimum_order_quantity'] : 1;
        $quantity_step_size = (isset($data['quantity_step_size']) && !empty($data['quantity_step_size'])) ? $data['quantity_step_size'] : 1;
        $warranty_period = (isset($data['warranty_period']) && !empty($data['warranty_period'])) ? $data['warranty_period'] : "";
        $guarantee_period = (isset($data['guarantee_period']) && !empty($data['guarantee_period'])) ? $data['guarantee_period'] : "";
        $tax = (isset($data['pro_input_tax']) && $data['pro_input_tax'] != 0 && !empty($data['pro_input_tax'])) ? $data['pro_input_tax'] : 0;
        $video_type = (isset($data['video_type']) && !empty($data['video_type'])) ? $data['video_type'] : "";
        $video = (!empty($video_type)) ? (($video_type == 'youtube' || $video_type == 'vimeo') ? $data['video'] : $data['pro_input_video']) : "";

        $rec_crops = $data['pro_input_rec_crops'];
        $about_company = $data['pro_input_about_company'];
        $about_formulation = $data['pro_input_about_formulation'];
        $about_usage = $data['pro_input_about_usage'];
        $method_of_app = $data['pro_input_method_of_app'];
        $dosage = $data['pro_input_dosage'];
        $specifications = $data['pro_input_specifications'];
        $benefits = $data['pro_input_benefits'];
        
        $formulation_id = $data['formulation_id'];
        $target_insect_id = $data['target_insect_id'];
        $target_insect_id = (isset($target_insect_id)) ? $target_insect_id : '';
        if($target_insect_id!='')
        {
            $target_insect_id = implode(',',$target_insect_id);
        }
        
        $brand_id       = $data['brand_id'];
        $form_id        = $data['form_id'];
        $seed_type_id   = $data['seed_type_id'];
        $toxicity_id    = $data['toxicity_id'];
        $unit_size_id   = $data['unit_size_id'];
        $licence_no     = $data['licence_no'];
        
        $hsn_no = (isset($data['hsn_no'])) ? $data['hsn_no'] : null;
        
        $pro_data = [
            'name' => $data['pro_input_name'],
            'short_description' => $short_description,
            'slug' => $slug,
            'type' => $pro_type,
            'tax' => $tax,
            'super_category_id' => $super_category_id,
            'category_id' => $category_id,
            'parent_id'   => $parent_id,
            'formulation_id' => $formulation_id,
            'target_insect_id' => $target_insect_id,
            'seller_id' => $seller_id,
            'brand_id'  => $brand_id,
            'form_id'  => $form_id,
            'seed_type_id' => $seed_type_id,
            'toxicity_id'  => $toxicity_id, 
            'unit_size_id'  => $unit_size_id,
            'licence_no'  => $licence_no,
            'made_in' => $made_in,
            'indicator' => $indicator,
            'image' => $main_image_name,
            'total_allowed_quantity' => $total_allowed_quantity,
            'minimum_order_quantity' => $minimum_order_quantity,
            'quantity_step_size' => $quantity_step_size,
            'warranty_period' => $warranty_period,
            'guarantee_period' => $guarantee_period,
            'other_images' => $other_images,
            'video_type' => $video_type,
            'video' => $video,
            'tags' => $tags,
            'status' => ($require_products_approval == true) ? 2 : 1,
            'description' => $description,
            'deliverable_type' => $data['deliverable_type'],
            'deliverable_zipcodes' => ($data['deliverable_type'] == ALL || $data['deliverable_type'] == NONE) ? NULL : $data['zipcodes'],
            'rec_crops' => $rec_crops,
            'about_company' => $about_company,
            'about_formulation' => $about_formulation,
            'about_usage' => $about_usage,
            'method_of_app' => $method_of_app,
            'dosage' => $dosage,
            'specifications'=>$specifications,
            'benefits'=> $benefits,
            'hsn_no'=> $hsn_no,
        ];

        if ($data['product_type'] == 'simple_product') {

            if (isset($data['simple_product_stock_status']) && empty($data['simple_product_stock_status'])) {
                $pro_data['stock_type'] = NULL;
            }

            if (isset($data['simple_product_stock_status'])  && in_array($data['simple_product_stock_status'], array('0', '1'))) {
                $pro_data['stock_type'] = '0';
            }

            if (isset($data['simple_product_stock_status'])  && in_array($data['simple_product_stock_status'], array('0', '1'))) {
                if (!empty($data['product_sku'])) {
                    $pro_data['sku'] = $data['product_sku'];
                }
                $pro_data['stock'] = $data['product_total_stock'];
                $pro_data['availability'] = $data['simple_product_stock_status'];
            }
        }

        if ((isset($data['variant_stock_status']) ||  $data['variant_stock_status'] == '' || empty($data['variant_stock_status']) || $data['variant_stock_status'] == ' ') && $data['product_type'] == 'variable_product') {
            $pro_data['stock_type'] = NULL;
        }

        if (isset($data['variant_stock_level_type']) && !empty($data['variant_stock_level_type'])) {
            $pro_data['stock_type'] = ($data['variant_stock_level_type'] == 'product_level') ? 1 : 2;
        }


        if (isset($data['is_returnable'])  && $data['is_returnable']) {
            $pro_data['is_returnable'] = '1';
        }
        if (isset($data['cod_allowed'])  && $data['cod_allowed']) {
            $pro_data['cod_allowed'] = '1';
        } else {
            $pro_data['cod_allowed'] = '0';
        }
        if (isset($data['is_cancelable'])  && $data['is_cancelable']) {
            $pro_data['is_cancelable'] = '1';
            $pro_data['cancelable_till'] = $data['cancelable_till'];
        }
        if (isset($data['is_prices_inclusive_tax']) && $data['is_prices_inclusive_tax']) {
            $pro_data['is_prices_inclusive_tax'] = '1';
        } else {
            $pro_data['is_prices_inclusive_tax'] = '0';
        }
        $variant_images = (!empty($data['variant_images']) && isset($data['variant_images'])) ? $data['variant_images'] : [];

        if (isset($data['edit_product_id'])) {
            if (empty($main_image_name)) {
                unset($pro_data['image']);
            }
            if (!isset($data['is_returnable'])) {
                $pro_data['is_returnable'] = '0';
            }
            if (!isset($data['is_cancelable'])) {
                $pro_data['is_cancelable'] = '0';
                $pro_data['cancelable_till'] = '';
            }
            if (isset($data['cod_allowed'])  && $data['cod_allowed']) {
                $pro_data['cod_allowed'] = '1';
            } else {
                $pro_data['cod_allowed'] = '0';
            }

            $pro_data['other_images'] = json_encode($other_images, 1);

            $this->db->set($pro_data)->where('id', $data['edit_product_id'])->update('products');
        } else {

            $pro_data['other_images'] = json_encode($other_images, 1);
            $this->db->insert('products', $pro_data);
            
            //echo $this->db->last_query();die;
        }

        $p_id = (isset($data['edit_product_id'])) ? $data['edit_product_id'] : $this->db->insert_id();
        
        
        if($p_id)
        {
            if(!is_dir('uploads/specifications_file/'))
            {
                mkdir('uploads/specifications_file/', 755);
            }
            
            if($_FILES['specifications_file']['type'] == 'application/pdf')
            {
                move_uploaded_file($_FILES['specifications_file']['tmp_name'], 'uploads/specifications_file/' . $p_id . '.pdf');
            }
            
            if(!is_dir('uploads/quality_inspection_file/'))
            {
                mkdir('uploads/quality_inspection_file/', 755);
            }
            
            if($_FILES['quality_inspection_file']['type'] == 'application/pdf')
            {
                move_uploaded_file($_FILES['quality_inspection_file']['tmp_name'], 'uploads/quality_inspection_file/' . $p_id . '.pdf');
            }
            
            if(!is_dir('uploads/patent_file/'))
            {
                mkdir('uploads/patent_file/', 755);
            }
            
            if($_FILES['patent_file']['type'] == 'application/pdf')
            {
                move_uploaded_file($_FILES['patent_file']['tmp_name'], 'uploads/patent_file/' . $p_id . '.pdf');
            }
            
            if(!is_dir('uploads/certification_file/'))
            {
                mkdir('uploads/certification_file/', 755);
            }
            
            if($_FILES['certification_file']['type'] == 'application/pdf')
            {
                move_uploaded_file($_FILES['certification_file']['tmp_name'], 'uploads/certification_file/' . $p_id . '.pdf');
            }
            
            if(!is_dir('uploads/additional_information_file/'))
            {
                mkdir('uploads/additional_information_file/', 755);
            }
            
            if($_FILES['additional_information_file']['type'] == 'application/pdf')
            {
                move_uploaded_file($_FILES['additional_information_file']['tmp_name'], 'uploads/additional_information_file/' . $p_id . '.pdf');
            }
            
        }
        
                
        $pro_variance_data['product_id'] = $p_id;
        $pro_attr_data = [

            'product_id' => $p_id,
            'attribute_value_ids' => strval($data['attribute_values']),

        ];

        if (isset($data['edit_product_id'])) {


            $this->db->where('product_id', $data['edit_product_id'])->update('product_attributes', $pro_attr_data);
        } else {

            $this->db->insert('product_attributes', $pro_attr_data);
        }
        if ($pro_type == 'simple_product') {
            $pro_variance_data = [
                'product_id' => $p_id,
                'price' => $data['simple_price'],
                'mrp' => $data['simple_mrp'],
                'special_price' => (isset($data['simple_special_price']) && !empty($data['simple_special_price'])) ? $data['simple_special_price'] : '0',
                'price_per_item' => $data['simple_price_per_item'],
                'mrp_per_item' => $data['simple_mrp_per_item'],
                'special_price_per_item' => (isset($data['simple_special_price_per_item']) && !empty($data['simple_special_price_per_item'])) ? $data['simple_special_price_per_item'] : '0'
            ];

            if (isset($data['edit_product_id'])) {

                if (isset($_POST['reset_settings']) && trim($_POST['reset_settings']) == '1') {
                    $this->db->insert('product_variants', $pro_variance_data);
                } else {
                    $this->db->where('product_id', $data['edit_product_id'])->update('product_variants', $pro_variance_data);
                }
            } else {
                $this->db->insert('product_variants', $pro_variance_data);

            }
        } else {
            $flag = " ";
            if (isset($data['variant_stock_status']) && $data['variant_stock_status'] == '0') {

                if ($data['variant_stock_level_type'] == "product_level") {
                    $flag = "product_level";
                    $pro_variance_data['sku'] = $data['sku_variant_type'];
                    $pro_variance_data['stock'] = $data['total_stock_variant_type'];
                    $pro_variance_data['availability']  = $data['variant_status'];
                    $variant_price = $data['variant_price'];
                    $variant_special_price = (isset($data['variant_special_price']) && !empty($data['variant_special_price'])) ? $data['variant_special_price'] : '0';
                } else {
                    $flag = "variant_level";
                    $variant_packing_size = $data['packing_size'];
                    $variant_unit_id = $data['unit_id'];
                    $variant_carton_qty = $data['carton_qty'];
                    $variant_mrp = $data['variant_mrp'];
                    $variant_price = $data['variant_price'];
                    $variant_special_price =  (isset($data['variant_special_price']) && !empty($data['variant_special_price'])) ? $data['variant_special_price'] : '0';
                    $variant_mrp_per_item = $data['variant_mrp_per_item'];
                    $variant_price_per_item = $data['variant_price_per_item'];
                    $variant_special_price_per_item =  (isset($data['variant_special_price_per_item']) && !empty($data['variant_special_price_per_item'])) ? $data['variant_special_price_per_item'] : '0';
                    $variant_sku = $data['variant_sku'];
                    $variant_total_stock = $data['variant_total_stock'];
                    $variant_stock_status = $data['variant_level_stock_status'];
                }
            } else {
                $variant_packing_size = $data['packing_size'];
                $variant_unit_id = $data['unit_id'];
                $variant_carton_qty = $data['carton_qty'];
                $variant_mrp = $data['variant_mrp'];
                $variant_price = $data['variant_price'];
                $variant_special_price = (isset($data['variant_special_price']) && !empty($data['variant_special_price'])) ? $data['variant_special_price'] : '0';
                $variant_mrp_per_item = $data['variant_mrp_per_item'];
                $variant_price_per_item = $data['variant_price_per_item'];
                $variant_special_price_per_item = (isset($data['variant_special_price_per_item']) && !empty($data['variant_special_price_per_item'])) ? $data['variant_special_price_per_item'] : '0';
            }

            if (!empty($data['variants_ids'])) {
                $variants_ids = $data['variants_ids'];
                if (isset($data['edit_variant_id']) && !empty($data['edit_variant_id'])) {
                    $this->db->set('status', 7)->where('product_id', $data['edit_product_id'])->where('status !=', 0)->where_not_in('id', $data['edit_variant_id'])->update('product_variants');
                }

                if (!isset($data['edit_variant_id']) && isset($data['edit_product_id'])) {
                    $this->db->set('status', 7)->where('product_id', $data['edit_product_id'])->where('status !=', 0)->update('product_variants');
                }
                for ($i = 0; $i < count($variants_ids); $i++) {
                    $is_default = ($i == 0) ? 1 : 0;
                    $value = str_replace(' ', ',', trim($variants_ids[$i]));
                    if ($flag == "variant_level") {

                        $pro_variance_data['mrp'] = $variant_mrp[$i];
                        $pro_variance_data['price'] = $variant_price[$i];
                        $pro_variance_data['special_price'] =  (isset($variant_special_price[$i]) && !empty($variant_special_price[$i])) ? $variant_special_price[$i] : '0';
                        $pro_variance_data['mrp_per_item'] = $variant_mrp_per_item[$i];
                        $pro_variance_data['price_per_item'] = $variant_price_per_item[$i];
                        $pro_variance_data['special_price_per_item'] =  (isset($variant_special_price_per_item[$i]) && !empty($variant_special_price_per_item[$i])) ? $variant_special_price_per_item[$i] : '0';
                        $pro_variance_data['sku'] = $variant_sku[$i];
                        $pro_variance_data['stock'] = $variant_total_stock[$i];
                        $pro_variance_data['availability'] = $variant_stock_status[$i];
                        $pro_variance_data['is_default']   = $is_default;
                    } else {
                        $pro_variance_data['mrp'] = $variant_mrp[$i];
                        $pro_variance_data['price'] = $variant_price[$i];
                        $pro_variance_data['special_price'] = (isset($variant_special_price[$i]) && !empty($variant_special_price[$i])) ? $variant_special_price[$i] : '0';
                        $pro_variance_data['mrp_per_item'] = $variant_mrp_per_item[$i];
                        $pro_variance_data['price_per_item'] = $variant_price_per_item[$i];
                        $pro_variance_data['special_price_per_item'] =  (isset($variant_special_price_per_item[$i]) && !empty($variant_special_price_per_item[$i])) ? $variant_special_price_per_item[$i] : '0';
                        $pro_variance_data['sku'] =   (isset($variant_sku[$i]) && !empty($variant_sku)) ? $variant_sku[$i] : NULL;
                        $pro_variance_data['stock'] =  (isset($variant_total_stock[$i]) && !empty($variant_total_stock)) ? $variant_total_stock[$i] : NULL;
                        $pro_variance_data['availability'] =  (isset($variant_stock_status[$i]) && !empty($variant_stock_status)) ?  $variant_stock_status[$i] : NULL;
                        $pro_variance_data['is_default']   = $is_default;
                    }
                    if (isset($variant_images[$i]) && !empty($variant_images[$i])) {
                        $pro_variance_data['images'] = json_encode($variant_images[$i]);
                    } else {
                        $pro_variance_data['images'] = '[]';
                    }
                    $pro_variance_data['attribute_value_ids'] = $value;
                    if (isset($data['edit_variant_id'][$i]) && !empty($data['edit_variant_id'][$i])) {
                        $this->db->where('id', $data['edit_variant_id'][$i])->update('product_variants', $pro_variance_data);
                    } else {
                        $this->db->insert('product_variants', $pro_variance_data);
                    }
                }
            }
            
            if ($pro_type == 'carton_product') {
                if (isset($data['edit_variant_id']) && !empty($data['edit_variant_id'])) {
                    $this->db->set('status', 7)->where('product_id', $data['edit_product_id'])->where('status !=', 0)->where_not_in('id', $data['edit_variant_id'])->update('product_variants');
                }

                if (!isset($data['edit_variant_id']) && isset($data['edit_product_id'])) {
                    $this->db->set('status', 7)->where('product_id', $data['edit_product_id'])->where('status !=', 0)->update('product_variants');
                }
                for ($i = 0; $i < count($variant_mrp); $i++) {
                    $is_default = ($i == 0) ? 1 : 0;
                    //$value = str_replace(' ', ',', trim($variants_ids[$i]));
                    if ($flag == "variant_level") {

                        $pro_variance_data['packing_size'] = $variant_packing_size[$i];
                        $pro_variance_data['unit_id'] = $variant_unit_id[$i];
                        $pro_variance_data['carton_qty'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i] : 1;
                        $pro_variance_data['total_weight'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_packing_size[$i] : 0;
                        
                        $pro_variance_data['mrp_per_item'] = $variant_mrp_per_item[$i];
                        $pro_variance_data['price_per_item'] = $variant_price_per_item[$i];
                        $pro_variance_data['special_price_per_item'] =  (isset($variant_special_price_per_item[$i]) && !empty($variant_special_price_per_item[$i])) ? $variant_special_price_per_item[$i] : '0';
                        
                        $pro_variance_data['mrp'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_mrp_per_item[$i] : 1*$variant_mrp_per_item[$i];//$variant_mrp[$i];
                        $pro_variance_data['price'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_price_per_item[$i] : 1*$variant_price_per_item[$i];//$variant_price[$i];
                        $pro_variance_data['special_price'] =  ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_special_price_per_item[$i] : 1*$variant_special_price_per_item[$i];//(isset($variant_special_price[$i]) && !empty($variant_special_price[$i])) ? $variant_special_price[$i] : '0';
                        
                        $pro_variance_data['sku'] = $variant_sku[$i];
                        $pro_variance_data['stock'] = $variant_total_stock[$i];
                        $pro_variance_data['availability'] = $variant_stock_status[$i];
                        $pro_variance_data['is_default']   = $is_default;
                    } else {
                        $pro_variance_data['packing_size'] = $variant_packing_size[$i];
                        $pro_variance_data['unit_id'] = $variant_unit_id[$i];
                        $pro_variance_data['carton_qty'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i] : 1;
                        $pro_variance_data['total_weight'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_packing_size[$i] : 0;
                        
                        $pro_variance_data['mrp_per_item'] = $variant_mrp_per_item[$i];
                        $pro_variance_data['price_per_item'] = $variant_price_per_item[$i];
                        $pro_variance_data['special_price_per_item'] =  (isset($variant_special_price_per_item[$i]) && !empty($variant_special_price_per_item[$i])) ? $variant_special_price_per_item[$i] : '0';
                        
                        $pro_variance_data['mrp'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_mrp_per_item[$i] : 1*$variant_mrp_per_item[$i];//$variant_mrp[$i];
                        $pro_variance_data['price'] = ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_price_per_item[$i] : 1*$variant_price_per_item[$i];//$variant_price[$i];
                        $pro_variance_data['special_price'] =  ($variant_carton_qty[$i]) ? $variant_carton_qty[$i]*$variant_special_price_per_item[$i] : 1*$variant_special_price_per_item[$i];//(isset($variant_special_price[$i]) && !empty($variant_special_price[$i])) ? $variant_special_price[$i] : '0';
                        
                        $pro_variance_data['sku'] =   (isset($variant_sku[$i]) && !empty($variant_sku)) ? $variant_sku[$i] : NULL;
                        $pro_variance_data['stock'] =  (isset($variant_total_stock[$i]) && !empty($variant_total_stock)) ? $variant_total_stock[$i] : NULL;
                        $pro_variance_data['availability'] =  (isset($variant_stock_status[$i]) && !empty($variant_stock_status)) ?  $variant_stock_status[$i] : NULL;
                        $pro_variance_data['is_default']   = $is_default;
                    }
                    if (isset($variant_images[$i]) && !empty($variant_images[$i])) {
                        $pro_variance_data['images'] = json_encode($variant_images[$i]);
                    } else {
                        $pro_variance_data['images'] = '[]';
                    }
                    //$pro_variance_data['attribute_value_ids'] = $value;
                    if (isset($data['edit_variant_id'][$i]) && !empty($data['edit_variant_id'][$i])) {
                        $this->db->where('id', $data['edit_variant_id'][$i])->update('product_variants', $pro_variance_data);
                    } else {
                        $this->db->insert('product_variants', $pro_variance_data);
                    }
                }
            }
        }
    }

    public function get_product_details($flag = NULL, $seller_id = NULL,$p_status = NULL,$super_category_id = 0)
    {
        $settings = get_settings('system_settings', true);
        $low_stock_limit = isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : 5;
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "product_variants.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = trim($_GET['search']);
            $multipleWhere = ['p.`id`' => $search, 'p.`name`' => $search, 'p.`description`' => $search, 'p.`short_description`' => $search, 'c.name' => $search];
        }

        if (isset($_GET['category_id']) || isset($_GET['search'])) {
            if (isset($_GET['search']) and $_GET['search'] != '') {
                $multipleWhere['p.`category_id`'] = $search;
            }

            if (isset($_GET['category_id']) and $_GET['category_id'] != '') {
                $category_id = $_GET['category_id'];
            }
        }

        $count_res = $this->db->select(' COUNT( distinct(p.id)) as `total` ')->join(" categories c", "p.category_id=c.id ")->join('product_variants', 'product_variants.product_id = p.id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }

        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        if ($flag == 'low') {
            $count_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $count_res->where($where);
            $count_res->where('p.stock <=', $low_stock_limit);
            $count_res->where('p.availability  =', '1');
            $count_res->or_where('product_variants.stock <=', $low_stock_limit);
            $count_res->where('product_variants.availability  =', '1');
            $count_res->group_End();
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where("p.seller_id", $seller_id);
        }
        if (isset($p_status) && $p_status != "") {
            $count_res->where("p.status", $p_status);
        }

        if ($flag == 'sold') {
            $count_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $count_res->where($where);
            $count_res->where('p.stock ', '0');
            $count_res->where('p.availability ', '0');
            $count_res->or_where('product_variants.stock ', '0');
            $count_res->where('product_variants.availability ', '0');
            $count_res->group_End();
        }

        if (isset($category_id) && !empty($category_id)) {
            $count_res->group_Start();
            $count_res->or_where('p.category_id', $category_id);
            $count_res->or_where('c.parent_id', $category_id);
            $count_res->group_End();
        }
        
        if($super_category_id)
        {
            $count_res->where('p.super_category_id', $super_category_id);
        }

        $product_count = $count_res->get('products p')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('product_variants.id AS id, p.id as pid ,p.rating,p.no_of_ratings,p.name, p.type, p.image, p.status, p.product_stock_status, product_variants.price , product_variants.special_price, product_variants.stock, p.super_category_id ')
            ->join(" categories c", "p.category_id=c.id ")->join('product_variants', 'product_variants.product_id = p.id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }

        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        if ($flag != null && $flag == 'low') {

            $search_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $search_res->where($where);
            $search_res->where('p.stock <=', $low_stock_limit);
            $search_res->where('p.availability  =', '1');
            $search_res->or_where('product_variants.stock <=', $low_stock_limit);
            $search_res->where('product_variants.availability  =', '1');
            $search_res->group_End();
        }
        if ($flag != null && $flag == 'sold') {
            $search_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $search_res->where($where);
            $search_res->where('p.stock ', '0');
            $search_res->where('p.availability ', '0');
            $search_res->or_where('product_variants.stock ', '0');
            $search_res->where('product_variants.availability ', '0');

            $search_res->group_End();
        }

        if (isset($category_id) && !empty($category_id)) {
            $search_res->group_Start();
            $search_res->or_where('p.category_id', $category_id);
            $search_res->or_where('c.parent_id', $category_id);
            $search_res->group_End();
        }
        
        if($super_category_id)
        {
            $search_res->where('p.super_category_id', $super_category_id);
        }
        
        if (isset($seller_id) && $seller_id != "") {
            $count_res->where("p.seller_id", $seller_id);
        }
       
        if (isset($p_status) && $p_status != "") {
            $count_res->where("p.status", $p_status);
        }

        $pro_search_res = $search_res->group_by('pid')->order_by($sort, "DESC")->limit($limit, $offset)->get('products p')->result_array();
        
        $currency = get_settings('currency');
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($pro_search_res as $row) {
            $row = output_escaping($row);
            $operate = $activate = $product_stock_status = '';
            //$operate = "<a href='".base_url()."seller/product/view-product?edit_id=" . $row['pid'] . "&super_category_id=" . $row['super_category_id'] . "'  class='btn btn-primary btn-xs mr-1 mb-1' title='View'><i class='fa fa-eye'></i></a>";
            
            $operate .= " <a href='".base_url()."products/details/" . $row['slug'] . "' class='btn btn-primary btn-sm mr-1 mb-1' title='View' target='_blank'><!--<i class='fa fa-pen'></i>--> View</a>";
            
            if($this->ion_auth->is_admin())
            {
                $operate .= " <a href='".base_url()."admin/product/new_product/new-product?edit_id=" . $row['pid'] . "&super_category_id=" . $row['super_category_id'] . "' data-id=" . $row['pid'] . " class='btn btn-success btn-sm mr-1 mb-1' title='Edit' ><!--<i class='fa fa-pen'></i>--> Edit</a>";
            }
            else
            {
                $operate .= " <a href='".base_url()."seller/product/new_product/new-product?edit_id=" . $row['pid'] . "&super_category_id=" . $row['super_category_id'] . "' data-id=" . $row['pid'] . " class='btn btn-success btn-sm mr-1 mb-1' title='Edit' ><!--<i class='fa fa-pen'></i>--> Edit</a>";
            }
            if ($row['status'] == '2') {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Not-Approved</a>';
                if ($this->ion_auth->is_seller()) {
                    $activate .= '<a class="btn btn-secondary mr-1 mb-1 btn-sm" data-table="products" href="javascript:void(0)" title="Not-Approved" ><i class="fa fa-ban"></i> Not-Approved</a>';
                }else{
                    $activate .= '<a class="btn btn-secondary mr-1 mb-1 btn-sm update_active_status" data-table="products" href="javascript:void(0)" title="Approve" data-id="' . $row['pid'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-ban"></i> Approve</a>';
                }
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                $activate .= '<a class="btn btn-warning btn-sm update_active_status mr-1 mb-1" data-table="products" title="Deactivate" href="javascript:void(0)" data-id="' . $row['pid'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-toggle-on"></i> Active</a>';
            } else  if ($row['status'] == '0') {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                $activate .= '<a class="btn btn-secondary mr-1 mb-1 btn-sm update_active_status" data-table="products" href="javascript:void(0)" title="Active" data-id="' . $row['pid'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-toggle-off"> Inactive</i></a>';
            }
            $operate .= ' <a href="javascript:void(0)" id="delete-product" data-id=' . $row['pid'] . ' class="btn btn-danger mr-1 mb-1 btn-sm"><!--<i class="fa fa-trash"></i>--> Delete</a>';
            //$operate .= " <a href='javascript:void(0)' data-id=" . $row['pid'] . " data-toggle='modal' data-target='#product-rating-modal' class='btn btn-success btn-xs mr-1 mb-1' title='View Ratings' ><i class='fa fa-star'></i></a>";
            
            
            if($row['product_stock_status'] == 1)
            {
                $product_stock_status .= '<a class="btn btn-warning btn-sm update_stock_status mr-1 mb-1" data-table="products" title="Out of stock" href="javascript:void(0)" data-id="' . $row['pid'] . '" data-stock-status="' . $row['product_stock_status'] . '" ><i class="fa fa-toggle-on"></i> In stock</a>';
            }
            else 
            {
                $product_stock_status .= '<a class="btn btn-secondary mr-1 mb-1 btn-sm update_stock_status" data-table="products" href="javascript:void(0)" title="In stock" data-id="' . $row['pid'] . '" data-stock-status="' . $row['product_stock_status'] . '" ><i class="fa fa-toggle-off"> Out of stock</i></a>';
            }

            $attr_values  =  get_variants_values_by_pid($row['pid']);
            $tempRow['id'] = $row['pid'];
            $tempRow['varaint_id'] = $row['id'];
            $tempRow['name'] = $row['name'] . '<br><small>' . ucwords(str_replace('_', ' ', $row['type'])) . '</small>';
            $tempRow['type'] = $row['type'];
            $tempRow['price'] =  ($row['special_price'] == null || $row['special_price'] == '0') ? $currency . $row['price'] : $currency . $row['special_price'];
            $tempRow['stock'] = $row['stock'];
            $variations = '';
            foreach ($attr_values as $variants) {
                if (isset($attr_values[0]['attr_name'])) {

                    if (!empty($variations)) {
                        $variations .= '---------------------<br>';
                    }

                    $attr_name = explode(',', $variants['attr_name']);
                    $varaint_values = explode(',', $variants['variant_values']);
                    for ($i = 0; $i < count($attr_name); $i++) {
                        $variations .= '<b>' . $attr_name[$i] . '</b> : ' . $varaint_values[$i] . '<br>';
                    }
                }
            }

            $tempRow['variations'] = (!empty($variations)) ? $variations : '-';
            $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            $tempRow['image'] = '<div class="mx-auto product-image"><a href=' . $row['image'] . ' data-toggle="lightbox" data-gallery="gallery">
        <img src=' . $row['image'] . ' class="img-fluid rounded"></a></div>';

            $tempRow['rating'] = '<input type="text" class="kv-fa rating-loading" value="' . $row['rating'] . '" data-size="xs" title="" readonly> <span> (' . $row['rating'] . '/' . $row['no_of_ratings'] . ') </span>';

            $tempRow['operate'] = $operate;
            $tempRow['activate'] = $activate;
            $tempRow['product_stock_status'] = $product_stock_status;
            
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
