<?php
/**
 * Plugin Name: TEI + WP
 * Plugin URI: http://dcl.ils.indiana.edu/teibp/
 * Description: This plugin integrates TEI Boilerplate, a project by John Walsh, Grant Simpson, and Saeed Moaddeli with Wordpress to allow easy publishing of TEI documents within a Wordpress site. When activated, a menu will appear under "Plugins" where you can upload XML files and associated images. To use in a post, simply use the tag "@teipluswp:yourfilename.xml". It will be converted to an in-text iframe to display your document.
 * Version: 1.0
 * Author: 
 * Author URI: 
 * Liscense: 
 */

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

if (!function_exists('main_teiwp')) {
  function main_teiwp($content) {
    $curDir = dirname(__FILE__); //Get current directory
    $filesOnServer = array();

    if ($handle = opendir(dirname(__FILE__) . "/content")) {
      while (false !== ($entry = readdir($handle))) {
	if ($entry[0] != ".") {
	  array_push($filesOnServer, $entry);
	}
      }
    }
    chdir('/home/public/wp-content/plugins/teipluswp/content/');
    // Using @teipluswp:<file.xml>
    foreach ($filesOnServer as $file) {
      if (preg_match('~teipluswp:'.$file.'~', $content)) {
      $content = preg_replace('#@teipluswp:'.$file.'#',
      			      '<br><br><div id="div_frame_wrapper"><iframe class="teidoc" src="/wp-content/plugins/teipluswp/content/'.$file.'"></iframe></div><br>',
      			      $content);
      }
    }
    preg_match_all('~@teipluswp:beginxml<(.+?)>(.+?)@teipluswp:endxml~s', $content, $xmlarray);
    // Makes a new xml file from the user's data
    for ($i = 0; $i < count($xmlarray[1]); ++$i) {
      $my_file = $xmlarray[1][$i] . ".xml";
      $handle = fopen($my_file, "w") or die('Cannot open file: ' . $my_file);
      fwrite($handle, $xmlarray[2][$i]);
      fclose($handle);
      if (current_user_can('manage_options')) {
	$content = preg_replace('~@teipluswp:beginxml<'.$xmlarray[1][$i].'>(.+?)@teipluswp:endxml~s', '<b>Wrote file</b> <i>' . $my_file . '</i> <b>to server.</b><br><br><div id="div_frame_wrapper"><iframe class="teidoc" src="/wp-content/plugins/teipluswp/content/'.$my_file.'"></iframe></div><br>', $content);
      } else {
	$content = preg_replace('~@teipluswp:beginxml<'.$xmlarray[1][$i].'>(.+?)@teipluswp:endxml~s', '<br><br><div id="div_frame_wrapper"><iframe class="teidoc" src="/wp-content/plugins/teipluswp/content/'.$my_file.'"></iframe></div><br>', $content);
      }
    }
    if (current_user_can('manage_options')) {
      $content = preg_replace('~@teipluswp:(.+)~',' <b>ALERT: Invalid tag</b> <i>@teipluswp:${1}</i>', $content);
    } else {
      $content = preg_replace('#@teipluswp:.+#', '', $content);
    }

    return $content;
  }
}

if (!function_exists('tei_css')) {
  function tei_css() {
    echo "<style type='text/css'>
#div_frame_wrapper { width:950px; height:390px; padding:0; overflow:hidden; }
.teidoc { width:950px; height:500px; }
.teidoc {
-ms-zoom: 0.75;
-moz-transform: scale(0.75);
-moz-transform-origin: 0 0;
-o-transform: scale(0.75);
-o-transform-origin: 0 0;
-webkit-transform: scale(0.75);
-webkit-transform-origin: 0 0;
}
    </style>";
  }
}

add_action('wp_head', 'tei_css');

if (function_exists('main_teiwp')) {
  add_filter('the_content', 'main_teiwp');
}

// Adds an area to manage the plugin
if (is_admin()) {
  add_action('admin_menu', 'teipluswp_admin_menu');
  function teipluswp_admin_menu() {
    add_submenu_page('plugins.php',
		     'TEI + WP',
		     'TEI + WP',
		     'administrator',
		     'hello_world',
		     'teipluswp_html_page');
  }
}

function teipluswp_html_page() {
  include 'admin_page.php';
}
?>