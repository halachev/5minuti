<?php

class category_model extends CI_Model {
	
    public function __construct() {

        parent::__construct();
    }

    
    function getCategories($parentID = 0) {
		
		if ($parentID > 0)
			$query = $this->db->query("SELECT * FROM categories where parentID = '$parentID' order by id");    
        else 
			$query = $this->db->query("SELECT * FROM categories where parentID is null order by id");
    
        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row;			
        }
        return $data;
    }
	
	function get_children()
	{
	    $parent = $this->uri->segment(3, 0);
			
		$query = $this->db->query("select * from categories where parentID='$parent'");
       	
		$c = count($query->result());
		if (!$c) return;
		
		echo '<span>'; 
        foreach ($query->result() as $row) {
			
			echo '<span style="text-align: left;font-weight:bold; width: auto; margin-left: 30px;"><a href="'.base_url().''.'main/category/'.$row->id.'">'. mysql_real_escape_string($row->name).'</a></span><br/>';
        }	
			
		echo "</span>";
	}
	
	
	function cboxSubCategory()
	{
	
	    $parent = $this->uri->segment(3, 0);
		
		$query = $this->db->query("select * from categories where parentID='$parent'");
       	
		$c = count($query->result());
		if (!$c) return;
		echo '<select name="cbox_New_Sub_Category" id="cbox_New_Sub_Category">';
		echo '<option value="0"> - Изберете под категория - </option>';		
        foreach ($query->result() as $row) {
					
			echo '<option value='.$row->id.'>'.$row->name.'</option>';
        }	
		echo '</select>';
	}
	
	
	function cboxEditSubCategory()
	{
	
	    $parent = $this->uri->segment(3, 0);
		
		$query = $this->db->query("select * from categories where parentID='$parent'");
       	
		$c = count($query->result());
		if (!$c) return;		
		echo '<select name="cbox_edit_Sub_Category" id="cbox_edit_Sub_Category">';
		echo '<option value="0"> - Изберете под категория - </option>';
        foreach ($query->result() as $row) {
					
			echo '<option value='.$row->id.'>'.$row->name.'</option>';
        }	
		echo '</select>';
	}
	

    function getCategoryByID($id) {

        $query = $this->db->query("select * from categories where id='$id'");
        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row;
        }
        return $data;
    }
    
    
    function deleteCetegoryByID($id) {

        $query = $this->db->query("delete from categories where id='$id'");        
    }
    
   
    function insert_category() {

        $name = $_POST['category_name'];
        
		if (!$name) 
		{
			echo "Invalid category name!";
			exit;
		}
        $query = "INSERT INTO categories (name) VALUES ('$name')";

        $this->db->query("$query");
    }
	
	function insert_sub_category()
	{
		$CatID = $_POST['cbox_Sub_category'];
		$name = $_POST['Sub_category_name'];
		
		if (!$name) 
		{
			echo "Invalid sub category name!";
			exit;
		}
		
		if ($CatID == 0) 
		{
			echo "Invalid category id!";
			exit;
		}
        
        $query = "INSERT INTO categories (name, parentID) VALUES ('$name', '$CatID')";

        $this->db->query("$query");
	
	}
	
	

}

?>
