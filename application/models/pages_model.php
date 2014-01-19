<?php

class pages_model extends CI_Model {

    public function __construct() {

        parent::__construct();		
    }
    
     public function record_count($category = 0) {	
	   
	    if ($category == 0)
			$query = $this->db->query("select * from news");
		
		if ($category > 0)			
			$query = $this->db->query("select * from news where category='$category' or category in (select id from categories where parentid = $category)");
		
		$data = array();
		foreach ($query->result() as $row) {
            $data[] = $row;
        }
		 
		return count($data);
    }

    public function fetch_news($limit, $start, $category = 0) {
        	   
		if ($category == 0)
			$query = $this->db->query("select * from news ORDER BY id DESC LIMIT $start, $limit");
		
		if ($category > 0)			
			$query = $this->db->query("select * from news where category='$category' or category in (select id from categories where parentid = $category) ORDER BY id DESC LIMIT $start, $limit");
		
		$data = array();
		
        if ($query->num_rows() > 0) {
		
            foreach ($query->result() as $row) {
			
                $data[] = $row;													
            }
            return $data;
        }
		
        return $data;
   }
}

?>
