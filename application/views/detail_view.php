<div class="wrapper">
<div class="container">
<div class="content">

	<?php


	if (isset($news[0]))
	{
		$data = $news[0];
		//proverka dali novinata e ot staria sait
		if ($data->ex_state == 1)
		{
			$desc = $data->introtext;
			$desc = preg_replace('"src"', '', $desc); // mahame src ot string
			
			$pattern = '/src="([^"]*)"/';
			preg_match($pattern, $data->introtext, $matches);
			$src = $matches[1];
			
			echo "<h1>$data->title</h1>";		
			echo "<img src='/$src'</h1>";
			echo "<p>$desc</h1>";
		}
				
	}		
						
	?>
	

</div>
</div>
<br class="clear" />
</div>
		