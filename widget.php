<?php
function moj_widget () {
	$out = "";
	
	$out .= '<ol class="first">'."\n".
				 '	<a href="http//mojtv.net"><img src="http://mojtv.hr/img/logos/mojtv-master.png" height="25" alt="TV Program" style="position:relative"  /></a>'."\n".
				 '	<br /><strong style="font-size:10px">FILMOVI NA TV</strong>'."\n".
				 '</ol>'."\n";
				
	$xml=simplexml_load_file(MOJTV_PLUGIN_DIR."/cache/movies.xml");
	foreach($xml->children() as $child) {
		$attributes = $child->attributes();
		$dateArr = explode(' ',$attributes->start);
		$dateTmp = $dateArr[0];
		$dateTmp = substr($dateTmp, -6, 4);
		$out .= '<li onClick="document.location.href=\''. $child->url .'\'; return false">'."\n".
					 '	<div class="channel">'. $attributes->channel .'</div>'."\n".
					 '	<a href="#movieurl"><img src="http://mojtv.hr/thumb.ashx?path=images/'. str_replace("http://mojtv.net/images/","",$child->icon) .'&w=100&h=50" /></a>'."\n".
					 '	<div class="time">'. substr_replace($dateTmp, ':', 2, 0) .'</div>'."\n".
					 '	<h3 class="title"><a href="#movieurl">'. $child->title .'</a></h3>'."\n".
					 '	<div class="genres">'. $child->genres .'</div>'."\n".
					 '	<div class="year">'. $child->date .', '. $child->country .'</div>'."\n".
					 '	<div class="actors">'. $child->actors .'</div>'."\n".
					 '</li>'."\n";
	}
	
	return $out;
}


?>