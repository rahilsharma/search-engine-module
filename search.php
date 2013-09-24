<html>
<head>
<title>My Search Engine</title>
 <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
 
 
<div id="container">
 
  <div id="header">
  
    <h1><a href="#">Simple Search Engine</a></h1>
    
</div>   
 
</br>
</br>
</br></br>  
<form method='post' action='search.php'> Keyword: 
          <input type='text' size='20' name='keyword'>
   Results: <select name='results'><option value='5'>5</option>
   <option value='10'>10</option><option value='15'>15</option>
   <option value='20'>20</option></select>
 
   <input type='submit' value='Search'></form>
 
</div>
 
<?php
 
$z=$_POST['keyword'];
 
if($z )
{
   
   mysql_pconnect("localhost","","")
       or die("ERROR: Could not connect to database!");
   mysql_select_db("test");
 
   /* time stamp le liyo pehle baad kitne time mein excut hui pta chal jayega  */
   $start_time = getmicrotime();
 
   /* use addslashes() to
    *  minimize the risk of executing unwanted SQL commands: */
   $keyword = addslashes( $_POST['keyword'] );
   $results = addslashes( $_POST['results'] );
 
 
 
 
   /* Execute the query that performs the actual search in the DB: */
   $result = mysql_query(" SELECT p.page_url AS url,
                           COUNT(*) AS occurrences 
                           FROM page p, word w, occurrence o
                           WHERE p.page_id = o.page_id AND
                           w.word_id = o.word_id AND
                           w.word_word = \"$keyword[0]\" 
                           GROUP BY p.page_id
                           ORDER BY occurrences DESC
                           LIMIT $results" );
 
 
  
   $end_time = getmicrotime();
 
   /* results: */
   print "<h2>Search results for '".$_POST['keyword']."':</h2>\n";
   for( $i = 1; $row = mysql_fetch_array($result); $i++ )
   {
      print "$i. <a href='".$row['url']."'>".$row['url']."</a>\n";
      print "(occurrences: ".$row['occurrences'].")<br><br>\n";
   }
 
   /* time taken to execute the query */
   print "query executed in ".(substr($end_time-$start_time,0,5))." seconds.";
}
 
 
print "</body></html>\n";
 
function getmicrotime()
{
   list($usec, $sec) = explode(" ",microtime());
   return ((float)$usec + (float)$sec);
}
 
?>
 <div id="footer">
  
    <p>Copyright rs</a></p>
    
  </div><!-- end #footer -->
</body>
</html>
