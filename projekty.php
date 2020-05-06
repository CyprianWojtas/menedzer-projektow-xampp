<?php

function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir"){
            rrmdir($dir."/".$object);
         }else{ 
            unlink($dir."/".$object);
         }
       }
     }
     reset($objects);
     rmdir($dir);
  }
}
function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}


	$strony = fopen("strony.txt", "r") or die("Unable to open file!");
	
	$stronyArr = explode("\r\n", fread($strony, filesize("strony.txt")));
	
	$stronyArr = array_values(array_diff($stronyArr, array("")));
	
	if($_GET["dodaj"] == "true")
	{
		if (!in_array($_GET["strona"], $stronyArr) && $_GET["strona"] != "")
		{
			$stronyArr[] = $_GET["strona"]; 
		}
		
		asort($stronyArr, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
		
		recurse_copy("domyslne", "strony/".$_GET["strona"]);
	}
	else if($_GET["dodaj"] == "false")
	{
		$stronyArr = array_values(array_diff($stronyArr, array($_GET["strona"])));
		
		rrmdir("strony/".$_GET["strona"]);
	}
	
	file_put_contents ("strony.txt", implode("\r\n", $stronyArr));
	
	fclose($strony);
	
	$vhosts = 'Options +FollowSymlinks
RewriteEngine On';
	
	for($i = 0; $i < count($stronyArr); $i++)
	{
		$stronaArr = explode(".", $stronyArr[$i]);
		
		$strona = $stronaArr[0];
		for($i2 = 1; $i2 < count($stronaArr); $i2++)
		{
			$strona = $stronaArr[$i2].".".$strona;
		}
		
		$vhosts .= '

RewriteCond %{HTTP_HOST} '.$strona.'.'.$_SERVER['SERVER_NAME'].'
RewriteCond %{REQUEST_URI} !^/strony/'.$stronyArr[$i].'
RewriteRule ^(.*)$ strony/'.$stronyArr[$i].'/$1 [L]';
	}
	file_put_contents (".htaccess", $vhosts);
	if ($_GET["dodaj"] == "true")
	{
		$stronaArr = explode(".", $_GET["strona"]);
		$strona = $stronaArr[0];
		for($i = 1; $i < count($stronaArr); $i++)
		{
			$strona = $stronaArr[$i].".".$strona;
		}
		
		header("Location: http://".$strona.".".$_SERVER['SERVER_NAME']);
	}
	else
		header("Location: /");
		
?>