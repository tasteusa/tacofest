<?php
// Creating the widget
class TacoButton extends WP_Widget {
    static $pluginDir;
    static $pluginUrl;
    function __construct() {
        global $TBPluginDir, $TBPluginUrl;
        parent::__construct('taco_button',__('Taco Button'),array( 'description' => __( 'Insert Taco Button', 'wpb_widget_domain' )));
        self::$pluginDir = $TBPluginDir;
        self::$pluginUrl = $TBPluginUrl;
    }


    public function widget( $args, $instance ) {
        extract($instance);
        $tacoID = 'tbtn_'.uniqid();
        wp_enqueue_style('taco_button',self::$pluginUrl.'css/taco_button.css' );
        require self::$pluginDir.'templates/widget/taco_button.tmpl.php';

    }

// Widget Backend
    public function form( $instance ) {
        $defaults =  [
            'text' => '',
            'url' => '',
            'bgColor' =>'',
            'textColor' =>'',
        ];
        $args = array_merge($defaults,$instance);
        require self::$pluginDir.'templates/widget/taco_button_form.tmpl.php';
    }

// Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
        $instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
        $instance['bgColor'] = ( ! empty( $new_instance['bgColor'] ) ) ? strip_tags( $new_instance['bgColor'] ) : '';
        $instance['textColor'] = ( ! empty( $new_instance['textColor'] ) ) ? strip_tags( $new_instance['textColor'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here

// Register and load the widget
function load_taco_button_widget() {
    register_widget( 'TacoButton' );
}
add_action( 'widgets_init', 'load_taco_button_widget' );

add_shortcode('vs_form', 'vendor_sponsor_form');
// [vs_form url="https://eventslocker.com/events/Baltimore-Mac-and-Cheese-Festival_1269" styles="https://eventslocker.com/css/embedevents.css"]
function vendor_sponsor_form($atts) {
    $url = $atts['url'];
    $styles = $atts['styles'];

    ob_start();
    ?>
    <link rel='stylesheet' href='<?php echo $styles;?>'>
    <style>
        .imContainer #eventsLockerFrame{
            width: 100% !important;
        }
        .burger-menu-button.burger-menu-0 {
            display: none !important;
        }
    </style>
    <div class='imWrapper'>
        <div class='imContainer'>
            <iframe id='eventsLockerFrame' src="<?php echo $url;?>" allowfullscreen style="width: 100%;"></iframe>
        </div>
    </div>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}