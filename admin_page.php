
<div class="wrap">
<h2>TEI Plus WP Options</h2>

<form method="post" action="/wp-content/plugins/teipluswp/upload_file.php" enctype="multipart/form-data" target="_blank">
   <?php wp_nonce_field('update-options'); /* Security measure */?>
   <table width="510">
   <tr valign="top">
   <th width="92" scope="row">File name:</th>
   <td width="406">
   <input type="file" name="file" id="file"><br>
   </td>
   </tr>
   </table>

   <input type="hidden" name="action" value="update" />
   <input type="hidden" name="page_options" value="file" />

   <p>
   <input type="submit" value="Upload File" />
   </p>
</form>
   <?php //Lists all currently uploaded files
   if ($handle = opendir(dirname(__FILE__) . "/content")) {
     echo "XML files on server:<br>";
     echo "<ol>";

     while (false !== ($entry = readdir($handle))) {
       $filepath = "/wp-content/plugins/teipluswp/content/" . $entry;
       if ($entry[0] != "." && substr($entry, -3, 3) == "xml") {
	 echo "<li>";
	 echo "<a href='" . $filepath . "'>$entry</a> ";
	 echo "<form style='display:inline;' name='deletefile" . $entry . "' action='/wp-content/plugins/teipluswp/delete_file.php' target='_blank' method='post'><input type='hidden' name='filename' value='" . $entry . "'><a href='javascript:document.forms[&quot;deletefile" . $entry . "&quot;].submit();'>Delete</a></form>";
	 echo "</li>";
       }
     }
     
     echo "</ol>";
   }
?>
<br>
   <?php //Lists all currently uploaded images
   if ($handle = opendir(dirname(__FILE__) . "/images")) {
     echo "Images on server:<br>";
     echo "<ol>";

     while (false !== ($entry = readdir($handle))) {
       if ($entry[0] != ".") {
	 echo "<li>";
	 echo "<a href='" . "/wp-content/plugins/teipluswp/images/" . $entry . "'>$entry</a> ";
	 echo "<form style='display:inline;' name='deletefile" . $entry . "' action='/wp-content/plugins/teipluswp/delete_file.php' target='_blank' method='post'><input type='hidden' name='filename' value='" . $entry . "'><a href='javascript:document.forms[&quot;deletefile" . $entry . "&quot;].submit();'>Delete</a></form>";
	 echo "</li>";
       }
     }
     
     echo "</ol>";
   }
?>
</div>