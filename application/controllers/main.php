<?php

class main extends CI_Controller {

	public function __construct() {
		
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('form');

		$this->load->model('article_model');
		$this->load->model('category_model');				
		$this->load->model('pages_model');		
								
		$this->load->library('pagination');					
		$this->load->helper('cookie');
		

	}

	function index() {

		$this->load->view("headerview");
		
		$data = $this->article_model->getArticles();					
		$this->load->view("main_view", $data);	
		
		
		$this->load->view("footerview");

	}


	function about() {
		
		$this->load->view("headerview");		
		$this->load->view("about_view");
		$this->load->view("footerview");
	}

	function contact() {
		
		$this->load->view("headerview");		
		$this->load->view("contact_view");
		$this->load->view("footerview");
		
	}


	function admin() {

		$data = array('logged_in' => TRUE);
		$this->session->set_userdata($data);

		$this->load->view("headerview");
		$this->load->view("admin_view");
		$this->load->view("footerview");
	}


	function logout() {

		$this->load->view("headerview");

		$this->session->unset_userdata("logged_in");
		
		$data['news'] = $this->article_model->getArticles();
		$this->load->view("main_view", $data);

		$this->load->view("footerview");
	}

	function article_insert() {

		$images = $this->gallery_model->do_upload();
		
		$image = "";
		
		foreach ($images as $value) {
			$image .= $value . ';';			
		}
		
		$this->article_model->insert_article($image);

		redirect('main/', 'location', 301);
		
		
	}

	function detail() {
		
		$data = array(
				'user_code' => "",			
				'logged_in' => false
			);
			
		$this->session->set_userdata($data);
			
		$product_id = $this->uri->segment(3, 0);
		$data['news'] = $this->article_model->getArticleByID($product_id);

		$this->load->view("headerview");
		$this->load->view("detail_view", $data);
		$this->load->view("footerview");
	}
	
	
	function usercontrol() {
		
		$user_code = $this->uri->segment(4, 0);
		
		$base_code = $this->article_model->getUserCode($user_code);
		
		if ($base_code == $user_code) {
			
			$data = array(
				'user_code' => $user_code,			
				'logged_in' => TRUE
			);
			
			$this->session->set_userdata($data);
			
			$product_id = $this->uri->segment(3, 0);
			$data['news'] = $this->article_model->getArticleByID($product_id);

			$this->load->view("headerview");
			$this->load->view("detail_view", $data);
			$this->load->view("footerview");
		
		}
		else
		{
			$this->load->view("headerview");
			echo "<center><h1>Invalid user code!</h1></center>";
			$this->load->view("footerview");
		}
	
	}
	

	function delete($canDelete = true) {

		
		$product_id = $this->uri->segment(3, 0);
		$data = $this->article_model->getArticleByID($product_id);
		
		$images = explode(";", $data[0]->image);
		$path = realpath(APPPATH . '/../images/');
		
		$img = "";
		$tmps = "";
		
		foreach ($images as $image) {

			$img = $path . '/' . $image;
			$tmps = $path . '/thumbs/' .$image;
			
			unlink($img);
			unlink($tmps);
		}
		
		if ($canDelete)
		$this->article_model->deleteArticleByID($product_id);
	}
	
	
	function deleteByType($type) {
		
		$data = $this->article_model->deleteByType($type);
		
	}

	function edit() {

		
		$product_id = $this->uri->segment(3, 0);
		$data['news'] = $this->article_model->getArticleByID($product_id);

		$this->load->view("headerview");
		$this->load->view("edit_view", $data);
		$this->load->view("footerview");
	}

	function ArticleUpdate() {

		// get id
		$product_id = $this->uri->segment(3, 0);
		
		//check for user images
		$images = $this->gallery_model->do_upload();
		
		$image = "";
		
		//prepare images for current record
		foreach ($images as $value) {
			$image .= $value . ';';			
		}
		
		//get article by id
		$article = $this->article_model->getArticleByID($product_id);
		
		//check count of images
		$c = count($images);
		
		if ($c > 0) {
			//delete old images
			$this->delete(false);
		}
		else
		$image = $article[0]->image; // get and store old images

		
		if ($_POST['cbox_edt_category'] > 0) 
		$category = $_POST['cbox_edt_category'];
		
		if ($_POST['cbox_edit_Sub_Category'] > 0)
		$category = $_POST['cbox_edit_Sub_Category'];
		
		$old_price = 0;
		if (isset($_POST['edt_old_price']) > 0)
		$old_price = $_POST['edt_old_price'];
		
		
		$data = array(
		'name' => $_POST['edt_name'],  
		'category' => $category,				
		'descr' => $_POST['edt_descr'],            
		'price' => $_POST['edt_price'],
		'old_price' => $old_price,
		'image' => $image
		);

		$this->db->where('id', $product_id);

		$this->db->update('news', $data);
		
		redirect('main/', 'location', 301);
	}
	
	
	function category() {
		
		$categoryId = $this->uri->segment(3, 0);
		$data = $this->article_model->getArticles($categoryId);	
		
		$this->load->view("headerview");
		$this->load->view("main_view", $data);
		$this->load->view("footerview");
		
	}
	
	

	function getSubGetegory() {
		
		$this->category_model->get_children();		
		
	}
	
	function cboxFillSubCategory() {
		
		$this->category_model->cboxSubCategory();		
		
	}
	
	
	function cboxEditSubCategory() {
		
		$this->category_model->cboxEditSubCategory();		
		
	}
	
	
	
	function GetStateByType() {
		
		$this->load->view("headerview");
		
		$data = array();

		$data = $this->article_model->GetStateByType();		
		$this->load->view("season_view", $data);
		
		$this->load->view("footerview");
	}
	
	
	function promotions() 
	{
		
		$this->load->view("headerview");
		
		$data = $this->article_model->getAllPromotions();		
		$this->load->view("promotion_view", $data);
		
		$this->load->view("footerview");
	}
	
	function search(){
		
		$data = array();
		
		// store search text in session ...
		
		if (isset($_POST['searchText']))
		$searchData = array('searchStr' => $_POST['searchText']);
		else{
			$str = $this->session->userdata('searchStr');
			$searchData = array('searchStr' => $str);
		}

		$this->session->set_userdata($searchData);
		
		$data = $this->article_model->LikeSearch();	
		
		$this->load->view("headerview");
		$this->load->view("main_view", $data);
		$this->load->view("footerview");
		
	}
	
	function contact_send() {
		
		$name = $_POST['user_name'];
		$from = $_POST['requestEmail'];
		
		$b = '<html><body>
			<p>Име: '.$name.'</p>				
			<p>Email: '.$from.'</p>
			<p>Съобщение:<br/> '.$_POST['user_message'].'</p>
			</body></html>';
		
		$body = $b;
		
		$this->article_model->InitMail($body, $name, $from, 'bgjoin@gmail.com');
		
		redirect('main/', 'location', 301);
		
	}
	
	

	function add_view()
	{
		$this->load->view("headerview");		       
		$this->load->view("add_view");		
		$this->load->view("footerview");
	}
	
	
	function jobs_view(){
		
		$this->load->view("headerview");		       
		$this->load->view("jobs_view");		
		$this->load->view("footerview");
		
	}
	
	
	function news_view(){
		
		$this->load->view("headerview");	
		
		$news = $this->article_model->getNews(0);					
		$this->load->view("news_view", $news);	
		
		$this->load->view("footerview");
	}
	
	
	function news_insert() {

		$images = $this->gallery_model->do_upload();
		
		$image = "";
		
		foreach ($images as $value) {
			$image .= $value . ';';			
		}
		
		$this->article_model->insert_news($image);

		redirect('main/', 'location', 301);
		
		
	}
	
	
	function news_detail() {
		
		$newsId = $this->uri->segment(3, 0);
		$data['news'] = $this->article_model->getNewsByID($newsId);

		$this->load->view("headerview");
		$this->load->view("news_detail_view", $data);
		$this->load->view("footerview");
	}
	
	
	function horoskop(){
		$this->load->view("headerview");
		$this->load->view("horoskop_view");
		$this->load->view("footerview");
	}
	
	
	
	function message_view()
	{
		$data['message_state'] = $this->uri->segment(3, 0);
		$this->load->view("headerview");		
        $this->load->view("message_view", $data);
        $this->load->view("footerview");
	
	}
	
	
}
?>
