<?php
if(isset($_GET['string'])) {
   $f = fopen("jartest/input.txt", "wb");
   $_GET['string'] = "\xEF\xBB\xBF" . $_GET['string'];
   fputs($f, $_GET['string']);
   fclose($f);

   exec("java -jar jartest/practice.jar");
   $f = fopen("jartest/output.txt", "r");
   $output = fread($f, 1024);
   fclose($f);
   echo $output;
}
?>