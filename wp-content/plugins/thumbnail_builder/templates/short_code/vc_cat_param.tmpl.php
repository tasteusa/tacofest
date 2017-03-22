<div class="vc_cat_param_block">
    <select multiple  name="<?php echo esc_attr( $settings['param_name'] ) ?>" class="wpb_vc_param_value wpb-select <?php echo esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) ?>_field"  >
        <?php foreach($categories as $cat): ?>
           <option <?php echo (in_array($cat->term_id, $value))?'selected':'' ?> value="<?php echo $cat->term_id ?>"><?php echo $cat->name ?></option>
        <?php endforeach;?>
    </select>
</div>