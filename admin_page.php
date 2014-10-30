<div class="wrap">
    <h2>TEI+WP Options</h2>
    
    <form id="file-form" style="padding-bottom: 20px;" method="post" action="/wp-content/plugins/teipluswp/upload_file.php" enctype="multipart/form-data">
        <?php wp_nonce_field('update-options'); ?>
        <label for="file" style="display: block;"><h3>Upload an image or XML file</h3></label>
        <input type="file" name="file" id="file-select">
        <div id="file-status"></div>
    </form>
    
    <div class="teiwp-list-files">
        <div class="teiwp-title">TEI Files:</div>
        <ul class="teiwp-all-files"><!-- Updated using jQuery --></ul>
    </div>
    
    <div class="teiwp-list-images">
        <div class="teiwp-title">Images:</div>
        <ul class="teiwp-all-images"><!-- Updated using jQuery --></ul>
    </div>
    
    <script type="text/javascript">
        var files;
        var i = 0;
        
        jQuery(document).ready(updateFiles());
        jQuery("input[type=file]").on("change", uploadFiles);
        
        function uploadFiles(event) {
            files = event.target.files;
            event.preventDefault();
            
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
                        jQuery('#file-select').val('');
                        updateFiles();
                    }
                    console.log(data.info);
                    setStatus(data.info);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('ERRORS: ' + textStatus);
                    setStatus('Could not connect to the server.');
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
                    console.log(data.info);
                    setStatus(data.info);
                    if (typeof data.error === 'undefined') {
                        updateFiles();
                    }
                },
                error: function(data, textStatus, jqXHR) {
                    console.log('ERRORS: ' + textStatus);
                    setStatus('Could not connect to the server.');
                }
            });
        }
        
        function updateFiles() {
            // Get the currently uploaded files from the server and display them in the given tags
            
            jQuery('.teiwp-all-images').append('<img src="/wp-content/plugins/teipluswp/ajax-loader.gif"/>');
            jQuery('.teiwp-all-files').append('<img src="/wp-content/plugins/teipluswp/ajax-loader.gif"/>');
            
            jQuery.ajax({
                url: '/wp-content/plugins/teipluswp/handler.php?getallfiles',
                type: 'POST',
                dataType: 'json',
                success: function(data, textStatus, jqXHR) {
                    // If the server response is successful, update the lists
                    
                    if (typeof data.error === 'undefined') {
                        jQuery('.teiwp-all-images').html('');
                        jQuery('.teiwp-all-files').html('');
                        
                        // Update images list
                        for (var img in data.images) {
                            var myimg = data.images[img];
                            jQuery('.teiwp-all-images').append(makeList(myimg.name, myimg.filepath));
                        }
                        
                        // Update images list
                        for (var file in data.files) {
                            var myfile = data.files[file];
                            jQuery('.teiwp-all-files').append(makeList(myfile.name, myfile.filepath));
                        }
                    }
                    console.log(data.info);
                },
                error: function(data, textStatus, jqXHR) {
                    console.log('ERRORS: ' + textStatus);
                    setStatus('Could not connect to the server.');
                }
            });
        }
        
        function makeList(filename, filepath) {
            // Standard form for creating one element in the list
            
            return '<li><a href="' + filepath + '">' + filename + '</a> (<a href="javascript:deleteFile(\'' + filename + '\')">x</a>)</li>';
        }
        
        function setStatus(stat) {
            // Display any information, pretty simple
            jQuery('#file-status').append('<div class="new-status" id="status' + i + '">' + stat + '</div>');
            
            setTimeout(function(ci) {
                jQuery('#status' + ci).slideUp();
            }, 5000, i);
            
            i++;
        }
        
        function removeDiv(id) {
            jQuery('#' + id).slideUp();
        }
    </script>
</div>