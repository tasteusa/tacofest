<?php
if( class_exists('WP_Widget') && !class_exists('ByButtonWidget')) {

    class ByButtonWidget extends WP_Widget
    {

        /**
         * Sets up the widgets name etc
         */
        public function __construct()
        {
            $widget_ops = array(
                'classname' => 'by_button_widget',
                'description' => 'Widget for by tickets button',
            );
            parent::__construct('by_button_widget', 'By Button Widget', $widget_ops);
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
            $bb_title = get_option('bb_title') ? get_option('bb_title') : 'BUY TICKETS';
            $bb_text_html = !empty($instance['bb_text_html']) ? $instance['bb_text_html'] : '';
            $bb_button_url = get_option('bb_button_url') ? get_option('bb_button_url') : '#';
            echo $args['before_widget'];
            echo '<h4>'.$title.'</h4>';

            echo '<div class="textwidget">';
                echo $bb_text_html;
                echo do_shortcode('[vc_btn title="'.$bb_title.'" color="danger" align="center" el_class="t-buy-tickets-btn" link="url:'.urlencode($bb_button_url).'|title:'.urlencode($bb_title).'||" ]');
            echo '</div>';
            echo $args['after_widget'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance)
        {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
            $bb_text_html = !empty($instance['bb_text_html']) ? $instance['bb_text_html'] : '';
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bb_text_html' ) ); ?>"><?php esc_attr_e('Content:', 'text_domain'); ?></label>
                <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'bb_text_html' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bb_text_html' ) ); ?>" ><?php echo esc_attr($bb_text_html); ?></textarea>
            </p>
            <?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['bb_text_html'] = (!empty($new_instance['bb_text_html'])) ? $new_instance['bb_text_html'] : '';

            return $instance;
        }
    }

    add_action( 'widgets_init', function(){
        register_widget( 'ByButtonWidget' );
    });
}


// create custom plugin settings menu
add_action('admin_menu', 'create_bb_settings_page');

function create_bb_settings_page() {

    //create new top-level menu
    add_menu_page('By Button Settings', 'By Button Settings', 'administrator', __FILE__, 'by_button_settings_page'  );

    //call register settings function
    add_action( 'admin_init', 'register_by_button_settings' );
}


function register_by_button_settings() {
    //register our settings
    register_setting( 'by_button-settings-group', 'bb_title' );
    register_setting( 'by_button-settings-group', 'bb_button_url' );
    register_setting( 'by_button-settings-group', 'bb_fest_id' );
}

function by_button_settings_page() {

    ?>
    <div class="wrap">
        <h1>By Button Settings</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'by_button-settings-group' ); ?>
            <?php do_settings_sections( 'by_button-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Button Title</th>
                    <td><input type="text" name="bb_title" value="<?php echo esc_attr( get_option('bb_title') ); ?>" placeholder="BUY TICKETS"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Button URL</th>
                    <td><input type="text" name="bb_button_url" value="<?php echo esc_attr( get_option('bb_button_url') ); ?>" placeholder="http://my-site.com" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Festival ID</th>
                    <td><input type="text" name="bb_fest_id" value="<?php echo esc_attr( get_option('bb_fest_id') ); ?>" placeholder="" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php } ?>
