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

// Stop from automatically converting to paragraphs, to let the XSLT take over
// remove_filter('the_content', 'wpautop');
// remove_filter('the_excerpt', 'wpautop');

if (!function_exists('main_teiwp')) {
    function main_teiwp($content) {
        // Collect the currently uploaded files
        $filesOnServer = array();
        
        // Read in all the files in the "/content" directory
        if ($handle = opendir(dirname(__FILE__) . "/content")) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] != ".") {
                    $e = array("path" => $entry, "realpath" => dirname(__FILE__) . "/content/" . $entry);
                    array_push($filesOnServer, $e);
                }
            }
        }
        
        // Change to "/xsl" directory
        chdir(dirname(__FILE__) . '/xsl/');
        
        // Load xsl document
        $xslDoc = new DOMDocument();
        $xslDoc->load("teialt.xsl");
        
        // Replace tags with formatted XSL
        foreach ($filesOnServer as $file) {
            if (preg_match('~teipluswp:'.$file['path'].'~', $content)) {
                $proc = new XSLTProcessor();
                $proc->importStylesheet($xslDoc);
                $xmlDoc = new DOMDocument();
                $xmlDoc->load($file['realpath']);
                $content = preg_replace('#@teipluswp:'.$file['path'].'#',
                                        $proc->transformToXML($xmlDoc),
                                        $content);
            }
        }
        
        // If the user is the admin, alert for incorrect tags
        if (current_user_can('manage_options')) {
            $content = preg_replace('~@teipluswp:(.+)~',' <div class="alert">ALERT: Invalid tag <i>@teipluswp:${1}</i></div>', $content);
        } else {
            $content = preg_replace('#@teipluswp:.+#', '', $content);
        }
        return $content;
    }
}

function tei_css() {
    wp_enqueue_style('teipluswp', plugins_url() . '/teipluswp/teialt.css');
    wp_enqueue_style('teipluswp', plugins_url() . '/teipluswp/build_tools/node_modules/less/bench.css');
}

add_action('wp_enqueue_scripts', 'tei_css');

function tei_js() {
  wp_enqueue_script('teipluswp', plugins_url() . '/teipluswp/js/teibp.js');
}

add_action('wp_enqueue_scripts', 'tei_js');

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