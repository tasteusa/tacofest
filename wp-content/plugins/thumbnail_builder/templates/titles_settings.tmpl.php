<div class="wrap">
    <h1>Titles Settings</h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'titles_tag-settings-group' ); ?>
        <?php do_settings_sections( 'titles_tag-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">            
                <th scope="row">H1 size</th>
                <td>
                    <input type="number" name="ttsg_h1_size" value="<?php echo $defaults['ttsg_h1_size']; ?>" />
                    <p><?php __( "Enter H1 size (px)") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">H1 color</th>
                <td>
                    <input type="text" name="ttsg_h1_color" value="<?php echo $defaults['ttsg_h1_color']; ?>" />
                    <p><?php __( "Enter H1 color") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">H1 font</th>
                <td>
                    <input type="text" name="ttsg_h1_font" value="<?php echo $defaults['ttsg_h1_font']; ?>" />
                    <p><?php __( "Enter H1 font") ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">H2 size</th>
                <td>
                    <input type="number" name="ttsg_h2_size" value="<?php echo $defaults['ttsg_h2_size']; ?>" />
                    <p><?php __( "Enter H2 size (px)") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">H2 color</th>
                <td>
                    <input type="text" name="ttsg_h2_color" value="<?php echo $defaults['ttsg_h2_color']; ?>" />
                    <p><?php __( "Enter H2 color") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">H2 font</th>
                <td>
                    <input type="text" name="ttsg_h2_font" value="<?php echo $defaults['ttsg_h2_font']; ?>" />
                    <p><?php __( "Enter H2 font") ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">H3 size</th>
                <td>
                    <input type="number" name="ttsg_h3_size" value="<?php echo $defaults['ttsg_h3_size']; ?>" />
                    <p><?php __( "Enter H3 size (px)") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">H3 color</th>
                <td>
                    <input type="text" name="ttsg_h3_color" value="<?php echo $defaults['ttsg_h3_color']; ?>" />
                    <p><?php __( "Enter H3 color") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">H3 font</th>
                <td>
                    <input type="text" name="ttsg_h3_font" value="<?php echo $defaults['ttsg_h3_font']; ?>" />
                    <p><?php __( "Enter H3 font") ?></p>
                </td>
            </tr>

        </table>

        <?php submit_button(); ?>

    </form>
</div>


<script>
    jQuery(document).ready(function($){
        $('input[name*="ttsg_h1_color"]').wpColorPicker();
        $('input[name*="ttsg_h2_color"]').wpColorPicker();
        $('input[name*="ttsg_h3_color"]').wpColorPicker();
    })
</script>