<p>
    <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $args['text'] ); ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Url:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $args['url'] ); ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'textColor' ); ?>"><?php _e( 'Text Color:' ); ?></label>
    <input class="widefat color-picker-field" id="<?php echo $this->get_field_id( 'textColor' ); ?>" name="<?php echo $this->get_field_name( 'textColor' ); ?>" type="text" value="<?php echo esc_attr( $args['textColor'] ); ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'bgColor' ); ?>"><?php _e( 'Background Color:' ); ?></label>
    <input class="widefat color-picker-field" id="<?php echo $this->get_field_id( 'bgColor' ); ?>" name="<?php echo $this->get_field_name( 'bgColor' ); ?>" type="text" value="<?php echo esc_attr( $args['bgColor'] ); ?>" />
</p>

<script>
    ( function( $ ) {
        $(document).ready(function () {
            $('.color-picker-field').colorPicker();
        });
    })(jQuery);
</script>