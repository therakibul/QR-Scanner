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
    $countries = [
        "None", "AFG", "BD", "IND", "PAK", "AUS", "NZ", "SU", "ZIM", "WI" 
    ];

    function qrc_init(){
        global $countries;
       
        $countries = apply_filters( "qrc_mofify_country", $countries );

    }
    add_action("init", "qrc_init");
    function qrc_post_scanner($content){
        $current_post_id = get_the_ID();
        $permalink = get_the_permalink($current_post_id);
        $title = get_the_title($current_post_id);
        $current_post_type = get_post_type($current_post_id);
        $image_size = apply_filters("qrc_image_size", "180x180");
        $exclude_post_type = apply_filters("qrc_remove_scanner_image", array());
        if(in_array($current_post_type, $exclude_post_type)){
            return $content;
        }
        $image_src = sprintf("https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s",$image_size, $permalink);
        $content .= sprintf("<img src='%s' alt='%s'>", $image_src, $title);
        return $content;
    }
    add_filter("the_content", "qrc_post_scanner");

    function qrc_settings_field(){
        add_settings_section("qrc_section", "Scanner Input Section", "scanner_section", "general" );
        add_settings_field( "qrc_height", "Height", "display_input_field", "general", "qrc_section", array("qrc_height") );
        add_settings_field( "qrc_width", "Width", "display_input_field", "general", "qrc_section", array("qrc_width") );
        add_settings_field( "qrc_select", "Select Country", "display_select_country", "general", "qrc_section" );
        add_settings_field( "qrc_checkbox", "Select Country", "display_country_checkbox", "general", "qrc_section" );
        
        register_setting("general", "qrc_height");
        register_setting("general", "qrc_width");
        register_setting("general", "qrc_select");
        register_setting("general", "qrc_checkbox");
        
    }

    
    function display_country_checkbox(){
        $option = get_option("qrc_checkbox");
        global $countries;
        foreach($countries as $country){
            $selected = "";
            if(is_array($option) && in_array($country, $option)){
                $selected  = "checked";
            }
            printf("<input type='checkbox' name='qrc_checkbox[]' value='%s' %s>%s<br>", $country, $selected, $country);
        }
    }
    function display_select_country(){
        $option = get_option("qrc_select");
        global $countries;
        ?>
        <select name="qrc_select">
            <?php 
                foreach($countries as $country){
                    $selected = "";
                    if($option == $country){
                        $selected = "selected";
                    }
                    printf("<option value='%s' %s>%s</option>", $country, $selected, $country);
                }
            ?>
        </select>
        <?php
    }
    function scanner_section(){
        printf("<p>Enter your input.<p>");
    }

    function display_input_field($args){
        $option = get_option($args[0]);
        printf("<input type='text' name='{$args[0]}' value='%s'>", $option);
    }
    add_action("admin_init", "qrc_settings_field");
?>