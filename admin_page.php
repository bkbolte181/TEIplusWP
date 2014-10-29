<div class="wrap">
    <h2>TEI+WP Options</h2>
    
    <form id="file-form" style="padding-bottom: 20px;" method="post" action="/wp-content/plugins/teipluswp/upload_file.php" enctype="multipart/form-data">
        <?php wp_nonce_field('update-options'); ?>
        <label for="file" style="display: block;"><h3>Upload an image or XML file</h3></label>
        <input type="file" name="file" id="file-select">
        <div id="file-status"></div>
    </form>
    
    <script type="text/javascript">
        var files;
        
        jQuery(document).ready(updateFiles());
        jQuery("input[type=file]").on("change", uploadFiles);
        
        function uploadFiles(event) {
            files = event.target.files;
            event.preventDefault();
            
            // Alert user that the file is uploading
            jQuery('#file-status').html('Uploading...');
            
            var data = new FormData();
            jQuery.each(files, function(key, val) {
                data.append(key, val);
            });
            
            jQuery.ajax({
                url: '/wp-content/plugins/teipluswp/handler.php?upload',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data, textStatus, jqXHR) {
                    if (typeof data.error === 'undefined') {
                        jQuery('#file-status').html('Uploaded ' + data.filename);
                        jQuery('#file-select').val('');
                        updateFiles();
                    } else {
                        console.log('ERRORS: ' + data.error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('ERRORS: ' + textStatus);
                },
            });
        }
        
        function deleteFile(myfile) {
            if (!confirm('Are you sure you want to delete ' + myfile +  '?')) {
                return;
            }
            jQuery.ajax({
                url: '/wp-content/plugins/teipluswp/handler.php?delete',
                type: 'POST',
                dataType: 'json',
                data: {
                    filename: myfile,
                },
                success: function(data, textStatus, jqXHR) {
                    if (typeof data.error === 'undefined') {
                        console.log(data)
                        updateFiles();
                    } else {
                        console.log('ERRORS: ' + data.error);
                    }
                },
                error: function(data, textStatus, jqXHR) {
                    console.log('ERRORS: ' + textStatus);
                }
            });
        }
        
        function updateFiles() {
            // Clear the list of files
            
            jQuery.ajax({
                url: '/wp-content/plugins/teipluswp/handler.php?getallfiles',
                type: 'POST',
                dataType: 'json',
                success: function(data, textStatus, jqXHR) {
                    if (typeof data.error === 'undefined') {
                        jQuery('.teiwp-all-images').html('');
                        for (var img in data.images) {
                            var myimg = data.images[img];
                            jQuery('.teiwp-all-images').append(makeList(myimg.name, myimg.filepath));
                        }
                        jQuery('.teiwp-all-files').html('');
                        for (var file in data.files) {
                            var myfile = data.files[file];
                            jQuery('.teiwp-all-files').append(makeList(myfile.name, myfile.filepath));
                        }
                    } else {
                        console.log('ERRORS: ' + data.error);
                    }
                },
                error: function(data, textStatus, jqXHR) {
                    console.log('ERRORS: ' + textStatus);
                }
            });
        }
        
        function makeList(filename, filepath) {
            return '<li><a href=' + filepath + '">' + filename + '</a> (<a href="javascript:deleteFile(\'' + filename + '\')">x</a>)</li>';
        }
    </script>
    
    <div class="teiwp-list-files">
        <div class="teiwp-title">TEI Files:</div>
        <ul class="teiwp-all-files">
        <?php
        //Lists all currently uploaded files
            if ($handle = opendir(dirname(__FILE__) . "/content")) {
                while (false !== ($entry = readdir($handle))) {
                    $filepath = "/wp-content/plugins/teipluswp/content/" . $entry;
                    if ($entry[0] != "." && substr($entry, -3, 3) == "xml") {
                        echo "<li>";
                        echo "<a href='" . $filepath . "'>$entry</a> ";
                        echo "<form style='display:inline;' name='deletefile" . $entry . "' action='/wp-content/plugins/teipluswp/delete_file.php' target='_blank' method='post'><input type='hidden' name='filename' value='" . $entry . "'>";
                        echo "(<a href='javascript:document.forms[&quot;deletefile" . $entry . "&quot;].submit();'>x</a>)";
                        echo "</form>";
                        echo "</li>";
                    }
                }
                echo "</ol>";
            }
        ?>
        </ul>
    </div>
    
    <div class="teiwp-list-images">
        <div class="teiwp-title">Images:</div>
        <ul class="teiwp-all-images">
        <?php
        //Lists all currently uploaded images
            if ($handle = opendir(dirname(__FILE__) . "/images")) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry[0] != ".") {
                        echo "<li>";
                        echo "<a href='" . "/wp-content/plugins/teipluswp/images/" . $entry . "'>$entry</a> ";
                        echo "<form style='display:inline;' name='deletefile" . $entry . "' action='/wp-content/plugins/teipluswp/delete_file.php' target='_blank' method='post'><input type='hidden' name='filename' value='" . $entry . "'>";
                        echo "(<a href='javascript:document.forms[&quot;deletefile" . $entry . "&quot;].submit();'>x</a>)";
                        echo "</form>";
                        echo "</li>";
                    }
                }
            }
        ?>
        </ul>
    </div>
</div>