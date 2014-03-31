<html>
<?php

// Cleans out deleted xml files and images
$files = glob(dirname(__FILE__) . '/{images,content}/' . $_POST['filename'], GLOB_BRACE);
foreach ($files as $file) {
  unlink($file);
  echo "Deleted file $file<br>";
}
?>
<body onload="javascript:close()">
</body>
</html>