<!-- ####################################################################################################### -->
<div class="wrapper">
  <div class="container">
    <div class="content">
      <div id="featured_slide">
        <ul id="featurednews">
          <li><img src="images/demo/1.gif" alt="" />
            <div class="panel-overlay">
              <h2>15 фейлетона в битка за наградата "Чудомир</h2>
              <p>„Наздраве, Маестро! Бохемските часове на Иван Пенков” – така закачливо ...<br />
                <a href="#">Виж повече &raquo;</a></p>
            </div>
          </li>
          <li><img src="images/demo/2.gif" alt="" />
            <div class="panel-overlay">
              <h2>15 фейлетона в битка за наградата "Чудомир</h2>
              <p>„Наздраве, Маестро! Бохемските часове на Иван Пенков” – така закачливо ...<br />
                <a href="#">Виж повече &raquo;</a></p>
            </div>
          </li>
          <li><img src="images/demo/3.gif" alt="" />
            <div class="panel-overlay">
              <h2>15 фейлетона в битка за наградата "Чудомир</h2>
              <p>„Наздраве, Маестро! Бохемските часове на Иван Пенков” – така закачливо ...<br />
                <a href="#">Виж повече &raquo;</a></p>
            </div>
          </li>
          <li><img src="images/demo/4.gif" alt="" />
            <div class="panel-overlay">
              <h2>15 фейлетона в битка за наградата "Чудомир</h2>
              <p>„Наздраве, Маестро! Бохемските часове на Иван Пенков” – така закачливо ...<br />
                <a href="#">Виж повече &raquo;</a></p>
            </div>
          </li>
          <li><img src="images/demo/5.gif" alt="" />
            <div class="panel-overlay">
              <h2>15 фейлетона в битка за наградата "Чудомир</h2>
              <p>„Наздраве, Маестро! Бохемските часове на Иван Пенков” – така закачливо ...<br />
                <a href="#">Виж повече &raquo;</a></p>
            </div>
          </li>
        </ul>
      </div>
    </div>
	
	<!-- ####################################################################################################### -->
    <div class="column">
      
      <div class="holder"><a href="#"><img src="images/demo/300x250.png" alt=""></a></div>
      <div class="holder"><a href="#"><img src="images/demo/300x80.gif" alt=""></a></div>
    
    </div>
	<!-- ####################################################################################################### -->
    <br class="clear" />
  </div>
</div>

<!-- ####################################################################################################### -->
<h1>Водещи новини</h1>
<div class="wrapper">
<div class="newsBox">
<ul>

<?php		 

	
	$c = count($news);

	if ($c <= 0) {
		echo '<h1>Няма налична информация!</h1>';		  		   
	}
	else if ($news != "") 
	{
			
		foreach ($news as $data) 
		{
			
			//$title = strip_tags(trim(mb_substr($data->title, 0, 30), 'utf-8')); 
			$title = strip_tags(trim($data->title)); 
			$absoluteTitle = $title;
			$title = $this->article_model->transliterate($title);
			$title = str_replace(' ', '-', $title);
			$detailUrl = base_url(). '/main/detail/' .$data->id . '/' .$title .'.html';			
			$introtext = $data->introtext; 	
			
			// proveriavame za starite novini ot staria sait
			if ($data->ex_state == 1)
				$img = getOldImage($introtext);
			
			$descr = substr($data->introtext, 0, 50);
			
			echo '
					<li>
						<div class="img"><a href="'.$detailUrl.'" ><img src= "'.$img.'" /></a></div>
						
						<div class="desc"><a href="'.$detailUrl.'" >'.$absoluteTitle.'</a></div>
						<div class="desc">
							При трудова злополука днес почина 26-годишният старозагорец Калоян Марков. Инцидентът е станал около 14:00 днес. Младежът е влязъл в цистерна на бензиностанцията на „Кумакс инвест“, която се намира до село Бузовград. 
						</div>
																					
					</li>
				';	
		}	
	}
	
	function getOldImage($introtext){
	
		$pattern = '/src="([^"]*)"/';
		preg_match($pattern, $introtext, $matches);
		$src = $matches[1];			
		$ext = explode(".",$src);
		
		if ($ext[1] == 'jpeg')
		$src = preg_replace('".jpeg"', '.jpg', $src);
		$img = ' /images/thumbs/' . substr($src, 7, strlen($src)); 
				
		return $img;
	}
	
?>

</ul>
</div>

<br class="clear" />	
<?php
	if (isset($links))
		echo '<p><div class="pagination" style="text-align: center; padding: 20px; border-radius: 5px;">'.$links.'</div></p>';
?>		
</div>
<!-- ####################################################################################################### -->
