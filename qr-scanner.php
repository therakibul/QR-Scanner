<?php
/*
 * Plugin Name:       Qr Scanner
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       qrc
 * Domain Path:       /languages
*/

    function qrc_post_scanner($content){
        $current_post_id = get_the_ID();
        $permalink = get_the_permalink($current_post_id);
        $title = get_the_title($current_post_id);
        $image_src = sprintf("https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=%s", $permalink);
        $content .= sprintf("<img src='%s' alt='%s'>", $image_src, $title);
        return $content;
    }
    add_filter("the_content", "qrc_post_scanner");
?>