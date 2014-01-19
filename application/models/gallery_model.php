<?php
class gallery_model extends CI_Model {

    var $gallery_path;
    var $gallery_path_url;

    public function __construct() {
        parent::__construct();

        $this->gallery_path = realpath(APPPATH . '../images');
        $this->gallery_path_url = base_url() . 'images/';
    }

    function do_upload() {
				
		$count = count($_FILES['userfile']['size']);
		$images = array();
		
		foreach($_FILES as $key => $value)
		{
			for($s = 0; $s <= $count - 1; $s++) {
				
				$_FILES['userfile']['name']= $value['name'][$s];
				$_FILES['userfile']['type'] = $value['type'][$s];
				$_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
				$_FILES['userfile']['error'] = $value['error'][$s];
				$_FILES['userfile']['size'] = $value['size'][$s];   
				
				$config = array(
					'allowed_types' => "jpg|jpeg|gif|png",
					'upload_path' => $this->gallery_path,
					'max_size' => 10000
				);
				
				$this->load->library('upload', $config);
				$this->upload->do_upload(); //do upload
				
				$image_data = $this->upload->data(); //get image data
					
				//save as new image
				$config = array(				
					'source_image' => $image_data['full_path'], //get original image
					'new_image' => $this->gallery_path, //save as new image 
					'maintain_ratio' => true,	
					//'create_thumb' => true,	
									
					'wm_text' => 'kazanlachani.com',
					'wm_font_path' => './system/fonts/texb.ttf',
					'wm_type' => 'text',
					'wm_font_size'	=> '24',
					'wm_font_color' => 'f5f5f5',
					'wm_vrt_alignment' => 'bottom',
					'wm_hor_alignment' => 'center',
					
					'width' => 800,
					'height' => 800
				);
							
				$this->load->library('image_lib', $config); //load library
				$this->image_lib->initialize($config);
						
				$this->image_lib->watermark();						
				
				if (!$this->image_lib->resize()) {			  
				 // echo $this->image_lib->display_errors();
				}
				
				$this->image_lib->clear();
				unset($config);
				
				
				//want to create thumbnail		
				$config = array(				
					'source_image' => $image_data['full_path'], //get original image
					'new_image' => $this->gallery_path . '/thumbs', //need to create thumbs first
					'maintain_ratio' => true,					
					'width' => 150,
					'height' => 100
				);
							
				$this->load->library('image_lib', $config); //load library
				$this->image_lib->initialize($config);
							
				if (!$this->image_lib->resize()) {			  
				 // echo $this->image_lib->display_errors();
				}
							
				$this->image_lib->clear();
				unset($config);
				
				
				if ($image_data['file_name'] != "")
					$images[] = $image_data['file_name'] .';' .$image_data['raw_name']  . $image_data['file_ext'];    
									
			}
			
		}
		
		return $images;
       
    }

}
?>