<?php

class article_model extends CI_Model {

	public function __construct() {

		parent::__construct();
		
		$this->load->helper('date');
	}
	
	
	function getArticles($category = 0) {
		
		$config = array();
		
		if ($category == 0)
		$config["base_url"] = base_url() . "main/index";
		
		if ($category > 0)
		$config["base_url"] = base_url() . "main/category/" . $category;
		
		
		$config["total_rows"] = $this->pages_model->record_count($category);
		
		$config["per_page"] = 24;
		
		if ($category == 0)
		$config["uri_segment"] = 3;
		if ($category > 0)
		$config["uri_segment"] = 4;

		$this->pagination->initialize($config);

		if ($category == 0)
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		if ($category > 0)		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		$data["news"] = $this->pages_model->
		fetch_news($config["per_page"], $page, $category);
		
		$data['links'] = $this->pagination->create_links();
		
		return $data;
	}
	
	
	
	function GetStateByType() {
		
		$type = $this->uri->segment(3, 0);
		
		$config["base_url"] = base_url() . "main/GetStateByType/" .$type;
		
		$config["total_rows"] = $this->pages_season_model->record_count($type);
		
		$config["per_page"] = 10;
		
		$config["uri_segment"] = 4;
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		$data["news"] = $this->pages_season_model->
		fetch_news($config["per_page"], $page, $type);
		
		$data['links'] = $this->pagination->create_links();
		
		return $data;
		
	}
	
	function getArticleByCategory($categoryId) {

		$config = array();
		$config["base_url"] = base_url() . "main/index";
		$config["total_rows"] = $this->pages_model->record_count();
		$config["per_page"] = 10;
		$config["uri_segment"] = 3;

		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		$data["news"] = $this->pages_model->
		fetch_news($config["per_page"], $page, $categoryId);
		
		$data['links'] = $this->pagination->create_links();
		
		return $data;
	}

	function getArticleByID($id) {


		$query = $this->db->query("select * from news where id='$id'");
		$data = array();

		foreach ($query->result() as $row) {
			$data[] = $row;
		}
		return $data;
	}
	
	
	function deleteArticleByID($id) {

		$query = $this->db->query("delete from news where id='$id'");        
	}
	
	function deleteByType($type) {

		$query = $this->db->query("delete from news where type='$type'");        
	}
	
	
	function getTopPromotions() {

		$query = $this->db->query("SELECT * FROM news where sub_type = 1 GROUP by id ORDER BY number DESC LIMIT 7");
		
		$data = array();

		foreach ($query->result() as $row) {
			$data[] = $row;
		}
		return $data;
	}
	
	
	function topPays() {

		$query = $this->db->query("SELECT * FROM news ORDER BY number desc LIMIT 14");
		
		$data = array();

		foreach ($query->result() as $row) {
			$data[] = $row;
		}
		return $data;
	}
	
	function getAllPromotions()
	{
		$sub_type = 1;
		
		$config["base_url"] = base_url() . "main/promotions/" .$sub_type;
		
		$config["total_rows"] = $this->pages_promotion_model->record_count($sub_type);
		
		$config["per_page"] = 10;
		
		$config["uri_segment"] = 4;
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		$data["news"] = $this->pages_promotion_model->
		fetch_news($config["per_page"], $page, $sub_type);
		
		$data['links'] = $this->pagination->create_links();
		
		return $data;
		
	}
	
	
	function GetNewNumber() {

		
		$sqltext = "select max(number) as number from news";       
		$query = mysql_query($sqltext);
		$query = mysql_fetch_array($query);
		$number = $query['number'] + 1;	
		return $number;
		
	}


	function insert_article($image) {
		
		$number = $this->article_model->GetNewNumber();
		
		
		if ($_POST['category'] > 0) 
		$category = $_POST['category'];
		else
		{
			echo "Invalid category!";
			exit;
		}
		
		
		if ($_POST['cbox_New_Sub_Category'] > 0)
		$category = $_POST['cbox_New_Sub_Category'];
		else {
			
			echo "Invalid sub category!";
			exit;
		}
		
		if (!$number) 
		{
			echo "Invalid number!";
			exit;
		}
		
		
		if ($_POST['name'] == "") 
		{
			echo "Invalid name!";
			exit;
		}
		
		
		if ($_POST['category'] <= 0) 
		{
			echo "Invalid category!";
			exit;
		}
		
		if ($_POST['phone'] <= 0) 
		{
			echo "Invalid phone number!";
			exit;
		}
		
		
		if ($_POST['email_address'] == "") 
		{
			echo "email address is empty!";
			exit;
		}
		
		
		if (isset($_POST['parameter-price']))
		{
			if ($_POST['price'] <= 0)
			{
				echo "Invalid price!";
				exit;
			}
		}
		
		
		$price = 0;
		if (isset($_POST['price']))
			$price = addslashes(trim($_POST['price']));
		
		$promotion = 0;		
		if (isset($_POST['cbox_promotion']))
		  $promotion = 1;
		
		
		$old_price = 0;		
		if ($_POST['old_price'] > 0)
			$old_price = $_POST['old_price'];
			
		else if ($promotion > 0)
		{
				echo "Invalid promotion price!";
				exit;
		}
		
		$price_type = 0;		
		if (isset($_POST['price_type']))
			$price_type = $_POST['price_type'];
			
	
		$code = $this->generateRandomString();
		
		$data = array(
		'number' => $number,
		'name' => addslashes($_POST['name']),   
		'category' => $category,  				
		'descr' => addslashes($_POST['art_descr']), 
		'phone' => addslashes($_POST['phone']), 			
		'price' => $price,
		'old_price' => addslashes(trim($old_price)),
		'type' => $_POST['cboxSeason'],
		'sub_type' => $promotion,
		'image' => $image,		
		'code' =>  $code,
		'email' => addslashes(trim($_POST['email_address'])),
		'price_type' => $price_type
		);

		$this->db->insert('news', $data);
		$newId = $this->db->insert_id();
		
		//send email to user
		$user_email = $_POST['email_address'];
		$title = $_POST['name'];
		$from = "kazanlachani.com";
		$address = 'http://kazanlachani.com/main/usercontrol/'.$newId.'/'.$code.'';
		
		$body = '<html><body>
			<p>Благодарим Ви, за вашата публикувана обява в http://kazanlachani.com</p>							
			<p>За редакция или изтриване на обявата изберете следния адрес: <br/> '.$address.'</p>		
			<p>info@kazanlachani.com</p>
			<p>Copyright 2013 ©</p>			
			</body></html>';
		
		$this->article_model->InitMail($body, $title, $from, $user_email);
		$request_data = 'request_data';
		redirect('main/message_view/'.$request_data.'', 'location', 301);
		//redirect('main/', 'location', 301);
				
	}
	
	function UpdateTopPayed($product_id, $value)
	{
		$data = array(           
		'topPayed' => $value
		);

		$this->db->where('id', $product_id);

		$this->db->update('news', $data);
	}
	
	function InitMail($body, $subject, $from, $user_email)
	{
		$body  = eregi_replace("[\]",'',$body);
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://smtp.googlemail.com',
		'smtp_port' => 465,
		'smtp_user' => 'jakomena@gmail.com',
		'smtp_pass' => 'mobileapplication',
		'mailtype'  => 'html', 
		'charset' => 'utf-8',
		'wordwrap' => TRUE
		);
		
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$this->email->from($from, $from);
		$this->email->to($user_email);

		$this->email->subject($subject);
		$this->email->message($body);

		if (!$this->email->send())
		
		show_error($this->email->print_debugger());		
		
	}
	
	
	function LikeSearch()
	{
		//reading search text from session ...
		$str = $this->session->userdata('searchStr');
		
		$config = array();
		
		$config["base_url"] = base_url() . "main/search/";
		
		$config["total_rows"] = $this->pages_search_model->record_count($str);
		
		$config["per_page"] = 10;
		
		$config["uri_segment"] = 3;
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["news"] = $this->pages_search_model->
		fetch_news($config["per_page"], $page, $str);
		
		$data['links'] = $this->pagination->create_links();
		
		return $data;
		
	}
	
	
	function insert_news($image) {
		
		
		if ($_POST['news_name'] == "") 
		{
			echo "Invalid news title!";
			exit;
		}
		
		if ($_POST['news_descr'] == "") 
		{
			echo "Invalid news description!";
			exit;
		}
		
		$name = addslashes($_POST['news_name']);
		$descr = addslashes($_POST['news_descr']);		
		$code = addslashes($_POST['news_video_code']);		
		$date = date('Y-m-d H:i:s');
		
		$data = array(
		
		'title' => $name,   						
		'descr' => $descr, 
		'date' => $date,		
		'image' => $image,
		'code' => $code
		);

		$this->db->insert('news', $data);
		
		redirect('main/', 'location', 301);
		
		
	}
	
	
	function getNews($per_page) {
		
		$config = array();
		
		
		$config["base_url"] = base_url() . "main/news_view";
		
		$config["total_rows"] = $this->pages_news_model->record_count();
		
		if ($per_page > 0)
		$config["per_page"] = $per_page;
		else
		$config["per_page"] = 12;
		
		$config["uri_segment"] = 3;
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["news"] = $this->pages_news_model->fetch_news($config["per_page"], $page);
		
		$data['links'] = $this->pagination->create_links();
		
		return $data;
	}
	
	
	function getNewsByID($id) {


		$query = $this->db->query("select * from news where id='$id'");
		$data = array();

		foreach ($query->result() as $row) {
			$data[] = $row;
		}
		return $data;
	}
	
	function getTopNews() {

		$query = $this->db->query("SELECT * FROM news ORDER BY id DESC LIMIT 5");
		
		$data = array();

		foreach ($query->result() as $row) {
			$data[] = $row;
		}
		return $data;
	}
		
		
	function getUserCode($code) {
		
		return $this->db->query("SELECT * FROM news where code = '$code'")->row()->code;
	
	}	
		
	function transliterate($textcyr = null, $textlat = null) {
		$cyr = array(
		'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
		'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
		$lat = array(
		'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
		'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q');
	  
		if($textcyr) return str_replace($cyr, $lat, $textcyr);
		else if($textlat) return str_replace($lat, $cyr, $textlat);
		else return null;
	}


    function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		
		return $randomString;
	}	
	
}

?>
