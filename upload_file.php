<html>
<head>
<?php
$file = $_FILES["file"];
$type = substr($file["name"], -3, 3);
$imageformats = array("gif", "jpg", "pxm");

if ($file["error"] > 0) {
  echo "Error: " . $file["error"] . "<br>";
} else {
  if ($type == "xml") {
    move_uploaded_file($file["tmp_name"], "content/" . $file["name"]);
    echo "Stored in: content/" . $file["name"] . "<br>";
  } else if (in_array($type, $imageformats)) {
    move_uploaded_file($file["tmp_name"], "images/" . $file["name"]);
    echo "Stored in: images/" . $file["name"] . "<br>";
  } else {
    echo "File " . $file["name"] . " was not stored on the server. It did not match a valid filetype.<br>";
  }
}

?>
<head>
<body>
</body>
</html>