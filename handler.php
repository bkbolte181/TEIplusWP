<?php
/*

This is the main method for dealing with AJAX
calls. This is mostly for the admin page for form
uploading and whatnot.

*/

define('WP_USE_THEMES', false);

require('../../../wp-blog-header.php');

global $current_user;
get_currentuserinfo();

$data = array();

if (!current_user_can('delete_plugins')) {
    // Authentication with Wordpress's current_user_can method
    $data = array('error' => 'error', 'info' => 'Authentication failed.');
} else {
    if (isset($_GET['upload'])) {
        // Method for uploading a file, called by passing 'upload' as a GET argument

        $files = array();
        $uploaddir = './content/';

        foreach($_FILES as $file) {
            if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
                $files[] = $uploaddir . $file['name'];
                $data = array('info' => 'Uploaded ' . $file['name']);
            } else {
                $data = array('error' => 'error', 'info' => 'There was an error uploading your file.');
            }
        }
    } elseif (isset($_GET['delete'])) {
        // Method for deleting a file, called by passing 'delete' as a GET argument

        // Get all files with name 'filename'
        $files = glob(dirname(__FILE__) . '/{images,content}/' . $_POST['filename'], GLOB_BRACE);

        // Delete first matched file
        $file = $files[0];
        unlink($file);

        $data = array('info' => 'Deleted ' . basename($file));
    } elseif (isset($_GET['getallfiles'])) {
        // Method for getting all uploaded files, called by passing 'getallfiles' as a GET argument

        // For storing the XML files' info
        $xmlfiles = array();

        // For storing the image files' info
        $images = array();

        // Get all XML files
        if ($handle = opendir(dirname(__FILE__) . "/content")) {
            while (false !== ($entry = readdir($handle))) {
                $filepath = "/wp-content/plugins/teipluswp/content/" . $entry;
                if ($entry[0] != "." && substr($entry, -3, 3) == "xml") {
                    $filedata = array('name' => $entry, 'filepath' => $filepath);
                    array_push($xmlfiles, $filedata);
                }
            }
        }

        // Get all image files
        if ($handle = opendir(dirname(__FILE__) . "/images")) {
            while (false !== ($entry = readdir($handle))) {
                $filepath = "/wp-content/plugins/teipluswp/images/" . $entry;
                if ($entry[0] != ".") {
                    $filedata = array('name' => $entry, 'filepath' => $filepath);
                    array_push($images, $filedata);
                }
            }
        }

        // Make data array, to return as JSON
        if (empty($images) && empty($xmlfiles)) {
            $data = array('error' => 'error', 'info' => 'There are no currently uploaded files.');
        } else {
            $data = array('images' => $images, 'files' => $xmlfiles, 'info' => 'Read all files');
        }
    } else {
        // Default error handling

        $data = array('error' => 'error', 'info' => 'You must pass a GET argument from one of the following: ["upload", "delete", "getallfiles"]');
    }
}

// Return JSON to the user
echo json_encode($data);
?>