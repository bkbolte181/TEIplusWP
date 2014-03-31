<html>
<head>
<script type='text/javascript'>
<?php
$file = $_FILES["file"];
$type = substr($file["name"], -3, 3);
$imageformats = array("gif", "jpg", "pxm");

if ($file["error"] > 0) {
  echo "Error: " . $file["error"] . "<br>";
} else {
  if ($type == "xml") {
    move_uploaded_file($file["tmp_name"], "content/" . $file["name"]);
    echo "console.log('Stored in: " . "content/" . $file["name"] . "')";
  } else if (in_array($type, $imageformats)) {
    move_uploaded_file($file["tmp_name"], "images/" . $file["name"]);
    echo "console.log('Stored in: " . "images/" . $file["name"] . "')";
  } else {
    echo "console.log('File " . $file["name"] . " was not stored on the server. It did not match a valid filetype.')";
  }
}

?>
</script>
<head>
<body onload="javascript:close()">
</body>
</html>