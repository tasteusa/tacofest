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

    $TBPluginDir = __DIR__.DIRECTORY_SEPARATOR;
    $TBPluginUrl = plugin_dir_url(__FILE__);

    require_once $TBPluginDir.'TumbnailBuilder.class.php';
    require_once $TBPluginDir.'/widgets/TacoButton.widget.php';
    require_once $TBPluginDir.'vc_shortcode.php';
    require_once $TBPluginDir.'TBAjax.class.php';
    require_once $TBPluginDir.'shortcodes/LTGridShortcode.class.php';
    require_once $TBPluginDir.'by_button_widget.php';

    register_activation_hook( __FILE__, array( 'TumbnailBuilder', 'plugin_activation' ) );

    register_deactivation_hook( __FILE__, array( 'TumbnailBuilder', 'plugin_deactivation' ) );

    $TBuilderClass = new TumbnailBuilder();
    $gridShortcode = new LTGridShortcode();
    $bbShortcode = new ByButtonShortcode();
    $TBAjax = new TBAjax();

    add_action( 'init', 'thumbnail_builder_init' );
    function thumbnail_builder_init(){
        global $TBuilderClass;
        global $TBAjax;

        $TBuilderClass->init();
        $TBAjax->init();
    }
    function LTTmplToVar($file, $args=[], $extract=false){
        global $TBPluginDir;
        if($extract)extract($args);
        ob_start();
        require($TBPluginDir.$file);
        return ob_get_clean();
    }
    



