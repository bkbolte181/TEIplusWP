<?php
/*

This is the main method for dealing with AJAX
calls. This is mostly for the admin page for form
uploading and whatnot.

*/

$data = array();

if (isset($_GET['upload'])) {
    // Uploading a file
    $files = array();
    $uploaddir = './content/';
    
    foreach($_FILES as $file) {
        if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
            $files[] = $uploaddir . $file['name'];
            $data = array('files' => files);
        } else {
            $data = array('error' => 'There was an error uploading your files.');
        }
    }
} elseif (isset($_GET['delete'])) {
    // Deleting a file
    $files = glob(dirname(__FILE__) . '/{images,content}/' . $_POST['filename'], GLOB_BRACE);
    foreach ($files as $file) {
        unlink($file);
    }
    $data = array('success' => 'Successfully deleted ' . $_POST['filename'], 'files' => $files, 'filename' => $_POST['filename']);
} elseif (isset($_GET['getallfiles'])) {
    // Return all the currently uploaded files
    $xmlfiles = array();
    $images = array();
    if ($handle = opendir(dirname(__FILE__) . "/content")) {
        while (false !== ($entry = readdir($handle))) {
            $filepath = "/wp-content/plugins/teipluswp/content/" . $entry;
            if ($entry[0] != "." && substr($entry, -3, 3) == "xml") {
                $filedata = array('name' => $entry, 'filepath' => $filepath);
                array_push($xmlfiles, $filedata);
            }
        }
    }
    if ($handle = opendir(dirname(__FILE__) . "/images")) {
        while (false !== ($entry = readdir($handle))) {
            $filepath = "/wp-content/plugins/teipluswp/images/" . $entry;
            if ($entry[0] != ".") {
                $filedata = array('name' => $entry, 'filepath' => $filepath);
                array_push($images, $filedata);
            }
        }
    }
    $data = array('images' => $images, 'files' => $xmlfiles);
} else {
    $data = array('error' => 'Form was submitted', 'formData' => $_POST);
}

echo json_encode($data);
?>