<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Pagination_test extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
    }

    function index()
    {
        ###################################################
        #	Search
        #
        #   Demo: pagination_test/?search_in=email&search_key=@gmail.com
        ###################################################
        $search_key = (isset($_GET['search_key']) ? $_GET['search_key'] : null);
        $search_in = (isset($_GET['search_in']) ? $_GET['search_in'] : null);
        $safe_columns = array('first_name', 'last_name', 'email');
        if (!is_null($search_key) && !is_null($search_in) && in_array($search_in, $safe_columns)) {
            $where = " `" . $search_in . "` LIKE '%" . $this->db->escape_like_str($search_key) . "%' ";

            $config['add_pars']['search_key'] = $search_key;
            $config['add_pars']['search_in'] = $search_in;
        }

        ###################################################
        #	Sorting
        ###################################################
        $col = (isset($_GET['col']) ? $_GET['col'] : null);
        $dir = (isset($_GET['dir']) ? $_GET['dir'] : null);
        if (!is_null($col) && !is_null($dir)) {
            $order = " $col $dir ";

            $config['add_pars']['col'] = $col;
            $config['add_pars']['dir'] = $dir;
        } else {
            $order = " `last_name` ASC ";
        }

        ###################################################
        #	Main Query
        ###################################################
        if (!isset($where)) {
            $where = '';
        }
        $count = $this->users_model->get_count($where, $order);

        ###################################################
        #	Pagination
        ###################################################
        $this->load->library('page');
        $cur_page = (isset($_GET['page']) ? $_GET['page'] : null);

        if (!is_numeric($cur_page) && $cur_page != 'all') {
            $cur_page = 1;
        }

        $config['rows_per_page'] = 25;
        $config['page_limit'] = 10;
        $config['base_url'] = base_url() . 'pagination_test/'; //always put trailing slash
        $config['total_rows'] = $count;
        $config['cur_page'] = $cur_page;
        $config['stats_title'] = 'users';
        $config['url_type'] = 'q';

        $this->page->initialize($config);

        $view['query'] = $this->users_model->get_pagination($cur_page, $config['rows_per_page'], $where, $order);
        $this->load->view('pagination_view', $view);
    }

    function page_directory()
    {
        ###################################################
        #	Sorting
        ###################################################
        $col = (isset($_GET['col']) ? $_GET['col'] : null);
        $dir = (isset($_GET['dir']) ? $_GET['dir'] : null);
        if (!is_null($col) && !is_null($dir)) {
            $order = " $col $dir ";

            $config['add_pars']['col'] = $col;
            $config['add_pars']['dir'] = $dir;
        } else {
            $order = " `last_name` ASC ";
        }

        ###################################################
        #	Main Query
        ###################################################
        if (!isset($where)) {
            $where = '';
        }
        $count = $this->users_model->get_count($where, $order);

        ###################################################
        #	Pagination
        ###################################################
        $this->load->library('page');
        $cur_page = ($this->uri->segment(3) ? $this->uri->segment(3) : null);

        if (!is_numeric($cur_page) && $cur_page != 'all') {
            $cur_page = 1;
        }

        $config['rows_per_page'] = 25;
        $config['page_limit'] = 10;
        $config['base_url'] = base_url() . 'pagination_test/page_directory/'; //always put trailing slash
        $config['total_rows'] = $count;
        $config['cur_page'] = $cur_page;
        $config['stats_title'] = 'users';
        $config['url_type'] = 'd';
        $config['show_trai_sl'] = true;

        $this->page->initialize($config);

        $view['query'] = $this->users_model->get_pagination($cur_page, $config['rows_per_page'], $where, $order);
        $this->load->view('pagination_view', $view);
    }

    public function page_ajax()
    {
        $this->load->view('pagination_ajax_view');
    }

    public function page_ajax_browse()
    {
        ###################################################
        #	Sorting
        ###################################################
        $col = (isset($_POST['col']) ? $_POST['col'] : null);
        $dir = (isset($_POST['dir']) ? $_POST['dir'] : null);
        if (!is_null($col) && !is_null($dir)) {
            $order = " $col $dir ";

            $config['add_pars']['col'] = $col;
            $config['add_pars']['dir'] = $dir;
        } else {
            $order = " `last_name` ASC ";
        }

        ###################################################
        #	Main Query
        ###################################################
        if (!isset($where)) {
            $where = '';
        }
        $count = $this->users_model->get_count($where, $order);

        ###################################################
        #	Pagination
        ###################################################
        $this->load->library('page');
        $cur_page = (isset($_POST['page']) ? $_POST['page'] : null);

        if (!is_numeric($cur_page) && $cur_page != 'all') {
            $cur_page = 1;
        }

        $config['rows_per_page'] = 25;
        $config['page_limit'] = 10;
        $config['base_url'] = base_url() . 'pagination_test/page_ajax_browse/'; //always put trailing slash
        $config['total_rows'] = $count;
        $config['cur_page'] = $cur_page;
        $config['stats_title'] = 'users';
        $config['url_type'] = 'q';
        $config['is_ajax'] = true;
        $config['update_div'] = 'browse';

        $this->page->initialize($config);

        $view['query'] = $this->users_model->get_pagination($cur_page, $config['rows_per_page'], $where, $order);
        $this->load->view('pagination_ajax_browse', $view);
    }
}

/* End of file pagination_test.php */
/* Location: ./application/controllers/pagination_test.php */