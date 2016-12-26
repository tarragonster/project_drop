<?php

require_once APPPATH . 'core/Base_Controller.php';

class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Madmin");
        $admin = $this->session->userdata('admin');
        if ($admin == null) {
            redirect(base_url('admincp/login'));
        }
        $lockdata = $this->session->userdata('lockdata');
        if ($lockdata != null) {
            redirect(base_url('admincp/lockscreen'));
        }

        $this->account = $this->Madmin->getAdminAccountByEmail($admin['email']);
        $this->load->model('Mcategory');
    }

    public function index()
    {
        $categories = $this->Mcategory->getTops();
        if (is_array($categories)) {
            foreach ($categories as $key => $row) {
                if ($row['cat_id'] == 2) {
                    $categories[$key]['items'] = $this->Mcategory->countSales();
                } else if ($row['cat_id'] == 3) {
                    $categories[$key]['items'] = $this->Mcategory->countVbExclusived();
                } else {
                    $categories[$key]['items'] = $this->Mcategory->countItems($row['cat_id']) + $this->Mcategory->countSubItems($row['cat_id']);
                }
            }
        }
        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 56;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admincp/category_list', array('categories' => $categories), true);
        $this->load->view('admin_main_layout', $data);
    }

    public function subs($cat_id = 0)
    {
        $cat = $this->Mcategory->get($cat_id);
        if ($cat == null || $cat['has_sub'] != 1) {
            redirect('admincp/category');
        }
        $categories = $this->Mcategory->getSubs($cat_id);
        if (is_array($categories)) {
            foreach ($categories as $key => $row) {
                $categories[$key]['items'] = $this->Mcategory->countItems($row['cat_id']);
            }
        }
        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 56;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admincp/category_sub_list', array('cat' => $cat, 'categories' => $categories), true);
        $this->load->view('admin_main_layout', $data);
    }

    public function edit($cat_id)
    {
        $cat = $this->Mcategory->get($cat_id);
        if ($cat == null) {
            redirect('admincp/category');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $params['name'] = $this->input->post('name');
            $params['short_bio'] = $this->input->post('short_bio');
            if ($this->input->post('has_sub') != '') {
                $params['has_sub'] = 1;
            } else {
                $params['has_sub'] = 0;
            }
            if ($this->input->post('has_giveaway') != '') {
                $params['has_giveaway'] = 1;
            } else {
                $params['has_giveaway'] = 0;
            }

            $this->Mcategory->update($params, $cat_id);
            if ($cat['parent'] > 1)
                redirect(base_url('admincp/category/subs/' . $cat['parent']));
            else
                redirect(base_url('admincp/category'));
        }

        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 56;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admincp/category_edit', $cat, true);;
        $this->load->view('admin_main_layout', $data);
    }

    public function up($cat_id)
    {
        $cat = $this->Mcategory->get($cat_id);
        if ($cat == null)
            redirect('admincp/category');

        $this->Mcategory->up($cat);
        if ($cat['parent'] > 1)
            redirect(base_url('admincp/category/subs/' . $cat['parent']));
        else
            redirect(base_url('admincp/category'));
    }

    public function down($cat_id)
    {
        $cat = $this->Mcategory->get($cat_id);
        if ($cat == null)
            redirect('admincp/category');

        $this->Mcategory->down($cat);
        if ($cat['parent'] > 1)
            redirect(base_url('admincp/category/subs/' . $cat['parent']));
        else
            redirect(base_url('admincp/category'));
    }

    public function delete($cat_id)
    {
        $cat = $this->Mcategory->get($cat_id);

        if ($cat == null || $cat['parent'] == 0 || $cat_id < 5)
            redirect('admincp/category');

        $items = $this->Mcategory->countItems($cat_id) + $this->Mcategory->countSubItems($cat_id);
        if ($items == 0) {
            $this->Mcategory->deleteWithPriority($cat);
        }
        if ($cat['parent'] > 1)
            redirect(base_url('admincp/category/subs/' . $cat['parent']));
        else
            redirect(base_url('admincp/category'));
    }

    public function create($parent = 0)
    {
        if ($parent != 0 && $this->Mcategory->get($parent) == null)
            redirect('admincp/category/create');
        $parent = $parent <= 0 ? 1 : $parent;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $params['name'] = $this->input->post('shop_name');
            $params['short_bio'] = $this->input->post('short_bio');
            if ($this->input->post('has_sub') != '') {
                $params['has_sub'] = 1;
            } else {
                $params['has_sub'] = 0;
            }
            if ($this->input->post('has_giveaway') != '') {
                $params['has_giveaway'] = 1;
            } else {
                $params['has_giveaway'] = 0;
            }

            $params['has_show'] = 1;
            $params['size_type'] = 2;
            $params['parent'] = $parent;

            $this->db->where('cat_id < ', 1000);
            $this->db->order_by('cat_id', 'desc');
            $query = $this->db->get('category');
            $item = $query->first_row();
            $params['cat_id'] = $item->cat_id + 1;
            $params['priority'] = $this->Mcategory->getMax($parent) + 1;
            $this->Mcategory->insert($params);

            if ($parent > 1)
                redirect(base_url('admincp/category/subs/' . $parent));
            else
                redirect(base_url('admincp/category'));
        }

        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 56;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admincp/category_create', array('parent' => $parent), true);
        $this->load->view('admin_main_layout', $data);
    }

    public function viewsub($parent = 0, $page = 0)
    {
        $categoryParent = $this->Mcategory->get($parent);
        if ($categoryParent == null)
            redirect('admincp/category');
        $this->load->model("Mcatalog");
        $this->Mcatalog->setSearchParam(array('shop' => $parent, 'status' => 1));
        $this->load->library('pagination');
        $page = ($page <= 0) ? 1 : $page;
        $config['base_url'] = base_url('admincp/category/viewsub/' . $parent);
        $config['total_rows'] = $this->Mcatalog->count_all_product();
        $config['per_page'] = PERPAGE_ADMIN;
        $config['cur_page'] = $page;
        $config['add_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $pinfo = array(
            'from' => PERPAGE_ADMIN * ($page - 1) + 1,
            'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
        );
        $products = $this->Mcatalog->getProductSearch($page - 1);
        $featureProducts = $this->Mcatalog->getSubCategoryFeature($parent);
        if (count($featureProducts) > 0) {
            foreach ($products as $key => $product) {
                foreach ($featureProducts as $feature) {
                    if ($product['product_id'] == $feature['product_id']) {
                        $products[$key]['is_feature'] = 1;
                    }
                }
            }
        }
        $countFeature = $this->Mcatalog->checkSubCategoryFeature($parent);
        $content = $this->load->view('admincp/category_sub_product_list', array(
            'products' => $products,
            'pinfo' => $pinfo,
            'countFeature' => $countFeature,
            'parent' => $parent
        ), true);
        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 56;
        $data['account'] = $this->account;
        $data['content'] = $content;
        $this->load->view('admin_main_layout', $data);
    }

    public function addFeature($cat_id, $product_id, $page = 1)
    {
        $product = $this->Mcatalog->getProductById($product_id);
        if (!$this->Mcategory->get($cat_id) == null && $product != null) {
            if ($product['cat_id'] == $cat_id) {
                $this->Mcatalog->addSubCategoryFeature(array(
                    'cat_id' => $cat_id,
                    'product_id' => $product_id
                ));
            } else {
                $this->Mcatalog->addSubCategoryFeature(array(
                    'cat2_id' => $cat_id,
                    'product_id' => $product_id
                ));
            }
        }
        redirect(base_url('admincp/category/viewsub/' . $cat_id . '/' . $page));
    }

    public function removeFeature($cat_id, $product_id, $page = 1)
    {
        $product = $this->Mcatalog->getProductById($product_id);
        if (!$this->Mcategory->get($cat_id) == null && $product != null) {
            if ($product['cat_id'] == $cat_id) {
                $this->Mcatalog->removeSubCategoryFeature(array(
                    'cat_id' => $cat_id,
                    'product_id' => $product_id
                ));
            } else {
                $this->Mcatalog->removeSubCategoryFeature(array(
                    'cat2_id' => $cat_id,
                    'product_id' => $product_id
                ));
            }
        }
        redirect(base_url('admincp/category/viewsub/' . $cat_id . '/' . $page));
    }

    public function show($cat_id)
    {
        $cat = $this->Mcategory->get($cat_id);

        if ($cat == null || $cat['parent'] == 0)
            redirect(base_url('admincp/category'));

        $this->Mcategory->updateValue($cat_id, array('has_show' => 1));
        if ($cat['parent'] == 1) {
            redirect(base_url('admincp/category'));
        } else {
            redirect(base_url('admincp/category/subs/' . $cat['parent']));
        }
    }

    public function hide($cat_id)
    {
        $cat = $this->Mcategory->get($cat_id);

        if ($cat == null || $cat['parent'] == 0)
            redirect(base_url('admincp/category'));

        $this->Mcategory->updateValue($cat_id, array('has_show' => 0));
        if ($cat['parent'] == 1) {
            redirect(base_url('admincp/category'));
        } else {
            redirect(base_url('admincp/category/subs/' . $cat['parent']));
        }
    }
}