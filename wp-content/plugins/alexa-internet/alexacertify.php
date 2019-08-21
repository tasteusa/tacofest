<?php
/*
Plugin Name: Alexa Certify
Plugin URI: http://www.alexa.com/
Description: The official Alexa plugin for WordPress.
Version: 4.1.2
Author: Alexa Internet
Author URI: http://www.alexa.com/
Text Domain: alexa-internet
*/

if(!class_exists('WP_Alexa_Certify')) {

    class WP_Alexa_Certify {

        public function __construct() {
            $this->plugin_basename = plugin_basename(__FILE__);
            $this->admin_page_id = "Alexa-Internet";
            $this->certify_snippet_oid = "alexacertify_certify";
            $this->admin_init();
        }

        /********************
         * Plugin Functions *
         ********************/

        /**
         * Add the metatag and certify snippet
         * actions
         */
        public function run() {
            //Add the alexa certify snippet to the footer of the WP site
            add_action('login_head', array(&$this, 'alexa_certify_snippet'));
            add_action('wp_head', array(&$this, 'alexa_certify_snippet'));
        }

        public function alexa_certify_snippet() {
            if(!defined('AX_PLUGIN_CERT_SNIPPET')) {
                $certifyCode = get_option("alexacertify_certify");
                if ($certifyCode) {
                    echo $certifyCode . "\n";
                }
                define('AX_PLUGIN_CERT_SNIPPET', "certify_snippet");
            }
        }


        /*******************
         * Admin Setup Functions *
         *******************/

        /**
         * Initialize the admin capabilities
         */
        private function admin_init() {
            //set up the settings admin page
            add_action('admin_menu', array(&$this, 'add_settings_page'));
            //create the link to the settings page
            add_filter("plugin_action_links_".$this->plugin_basename, array(&$this, 'add_settings_action_link'));
        }


        public function add_settings_page() {
            $pageTitle = "Alexa Internet";
            $menuTitle = "Alexa Internet";
            $capability = 'manage_options';
            $callback =  array(&$this,"alexa_plugin_settings");
            add_options_page($pageTitle, $menuTitle, $capability, $this->admin_page_id, $callback);
        }

        /*
         * Modify the $links array to add a 'Settings' link.
         * The 'Settings' link will take users to a page where they can set up their certify code
         * and certify snippets
         */
        public function add_settings_action_link( $links ) {
            $links = isset($links) ? $links : array();
            $links["_ax_settings"] = '<a href="options-general.php?page='.$this->admin_page_id.'">Settings</a>';
            return $links;
        }



        /***************************
         * Settings Page Functions *
         ***************************/

        /*
         * Render the settings page, where users can define their certify snippet
         */
        public function alexa_plugin_settings() {

            $current_certify_snippet = get_option($this->certify_snippet_oid);
            $errors = array();
            $updated = false;
            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST["alexacertify_submit"])) {
                //The user just sent us new data, fetch it, validate it and store it
                $form_certify_snippet = isset($_POST["alexacertify_certify"])
                    ? $this->_clean_input($_POST["alexacertify_certify"]) : $current_certify_snippet;

                if(strcmp($form_certify_snippet, $current_certify_snippet)) {
                    if($this->_validCertifySnippet($form_certify_snippet)) {
                        update_option($this->certify_snippet_oid, $form_certify_snippet);
                        $updated["certify"] = "Your certify snippet has been updated.";
                    } else {
                        $errors["certify"] = "We could not validate your snippet. "
                            ."Please make sure you copy the Alexa Certify Code exactly as it appears on your Certify page.";
                    }
                }
                if(empty($errors) && empty($updated)) {
                    $updated["nochange"] = "No changes found.";
                }
            }

            //these values are needed in the admin script
            $this->updated = $updated;
            $this->errors = $errors;
            $this->certify_snippet = get_option($this->certify_snippet_oid);
            include('alexacertify_admin.php');
        }


        private function _validCertifySnippet($snippet) {
            $certifyRegex = "/<script [^>]*>[^<].+atrk_acct:.{14}.+domain.+<\/script>[^<]*<noscript><img [^>].+certify.alexametrics.com\/atrk.gif.+<\/noscript>/is";
            return empty($snippet) || preg_match($certifyRegex, $snippet);
        }

        private function _clean_input($value) {
            return trim(stripslashes($value));
        }
    }
}

if(class_exists('WP_Alexa_Certify')) {
    $alexa_certify_plugin = new WP_Alexa_Certify();
    $alexa_certify_plugin->run();
}

?>
