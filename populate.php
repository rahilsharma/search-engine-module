<html>
<head>
<title>My Search Engine</title>
<link href="syles2.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
session_start();
mysql_connect("localhost","","")
   or die("ERROR: Could not connect to database!");
set_time_limit(2000);
mysql_select_db("test");
 
/*  
This file creates the database..................



URL : */
 
$url = $_POST['url'];
 
if( !$url )
{
   die( "You need to define a URL to process." );
}
else if( substr($url,0,7) != "http://" )
{
   $url = "http://$url";
}
 
/* alrdy present or not link */
$result = mysql_query("SELECT page_id FROM page WHERE page_url = \"$url\"");
$row = mysql_fetch_array($result);
 
if( $row['page_id'] )
{
   /* agar yes use the old page_id: */
   $page_id = $row['page_id'];
}
 
else
{
   /* agar no  create one: */
   mysql_query("INSERT INTO page (page_url) VALUES (\"$url\")");
   $page_id = mysql_insert_id();
}
 
/* make an index in the db yahan par 3 table use kar rha hun for more see in virtual-box xp : */
if( !($fd = fopen($url,"r")) )
   die( "Could not open URL!" );
 
while( $buf = fgets($fd,1024) )
{
   /* Remove whitespace from beginning and end of string: */
   $buf = trim($buf);
 
   /*  remove all HTML-tags: */
   $buf = strip_tags($buf);
   $buf = preg_replace('/&\w;/', '', $buf);
 
   /* Extract all words matching the regexp \: */
   preg_match_all("/(\b[\w+]+\b)/",$buf,$words);
 
   /* Loop through all words/occurrences and insert them  */
   for( $i = 0; $words[$i]; $i++ )
   {
      for( $j = 0; $words[$i][$j]; $j++ )
      {
         /* if word already in table */
         $cur_word = addslashes( strtolower($words[$i][$j]) );
 
         $result = mysql_query("SELECT word_id FROM word 
                                WHERE word_word = '$cur_word'");
         $row = mysql_fetch_array($result);
         if( $row['word_id'] )
         {
            /* If yes, use the old word_id: */
            $word_id = $row['word_id'];
         }
         else
         {
            /* If not, create one: */
            mysql_query("INSERT INTO word (word_word) VALUES (\"$cur_word\")");
            $word_id = mysql_insert_id();
         }
 
       
 
      }
   }
}
  
fclose($fd);
 
header('Location: http://localhost/search%20engine/1.php');
?>
</body>
</html>
