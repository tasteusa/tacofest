<?php
/*
 * Plugin Name: Thumbnail Builder
 * Description: Thumbnail Builder.
 * Author: Developer
 * Author URI:
 * Plugin URI:
 * Version: 1.0
 * License: GPL2
 * =======================================================================
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
    /*isset vc*/
    if(function_exists('vc_add_shortcode_param')){
        function regVcParam(){
            vc_map( array(
                "name" => __( "Linked Thumbnails Grid"),
                "base" => "LTGS",
                "class" => "",
                "category" => 'shortcodes',
                "params" => array(
                    array(
                        "type" => "vc_cat_param",
                        "class" => "",
                        "heading" => __("Categories", "js_composer"),
                        "param_name" => "cat",
                        "description" => __( "Select Categories"),
                    ),
                    array(
                        "type" => "dropdown",
                        "class" => "",
                        "heading" => __("Columns", "js_composer"),
                        "param_name" => "col",
                        "value" => [1,2,3,4,6],
                        "description" => __( "Select Columns Number"),
                    ),
                    array(
                        "type" => "dropdown",
                        "class" => "",
                        "heading" => __("Title Visibility", "js_composer"),
                        "param_name" => "title",
                        "value" => ['yes','no'],
                        "description" => __("Show Title?"),
                    ),
                ),
            ) );
        }
        function vcCatParam($settings, $value){
            $categories = get_categories( array(
                'orderby' => 'name',
                'order'   => 'ASC',
                'hide_empty' => false,
            ) );

            require_once __DIR__.DIRECTORY_SEPARATOR.'templates/short_code/vc_cat_param.tmpl.php';
        }
        vc_add_shortcode_param('vc_cat_param','vcCatParam');
        add_action( 'vc_before_init', 'regVcParam' );
    }


    $pluginDir = __DIR__.DIRECTORY_SEPARATOR;
    $pluginUrl = plugin_dir_url(__FILE__);

    require_once $pluginDir.'TumbnailBuilder.class.php';
    require_once $pluginDir.'shortcodes/LTGridShortcode.class.php';

    register_activation_hook( __FILE__, array( 'TumbnailBuilder', 'plugin_activation' ) );

    register_deactivation_hook( __FILE__, array( 'TumbnailBuilder', 'plugin_deactivation' ) );

    $TBuilderClass = new TumbnailBuilder();
    $gridShortcode = new LTGridShortcode();

    add_action( 'init', 'thumbnail_builder_init' );
    function thumbnail_builder_init(){
        global $TBuilderClass;
        $TBuilderClass->init();
    }
    function LTTmplToVar($file, $args=[]){
        global $pluginDir;
        ob_start();
        require($pluginDir.$file);
        return ob_get_clean();
    }
    



