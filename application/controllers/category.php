<?php


class category extends CI_Controller {

    public function __construct() {
        
		parent::__construct();

        $this->load->helper('url');
        $this->load->helper('form');
        
		$this->load->model('category_model');
	        
    }

    function index() {

      //      
    }
	
	function delete(){
	
		$categoryId = $this->uri->segment(3, 0);
		$this->category_model->deleteCetegoryByID($categoryId);	
		
	}
	
    function insert() {
	
        $this->category_model->insert_category();
		redirect('main/', 'location', 301);
    }	
	
	
	function insertSubCategory()
	{
		$this->category_model->insert_sub_category();
		redirect('main/', 'location', 301);
	}
	
}
?>
