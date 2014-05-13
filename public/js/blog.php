<?php
   $filename = "http://www.sport.es/es/rss/barca/rss.xml";
   header("Content-type:text/xml");
   readfile ($filename);
?>