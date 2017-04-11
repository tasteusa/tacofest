<div class="wrap">
    <h1>By Button Settings</h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'thumb_cat-settings-group' ); ?>
        <?php do_settings_sections( 'thumb_cat-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">            
                <th scope="row">Columns</th>
                <td>
                    <select name="col">
                        <option value="1" <?php echo ($defaults['col'] == '1')? 'selected="selected"' :''; ?>>1</option>
                        <option value="2" <?php echo ($defaults['col'] == '2')? 'selected="selected"' :''; ?>>2</option>
                        <option value="3" <?php echo ($defaults['col'] == '3')? 'selected="selected"' :''; ?>>3</option>
                        <option value="4" <?php echo ($defaults['col'] == '4')? 'selected="selected"' :''; ?>>4</option>
                        <option value="5" <?php echo ($defaults['col'] == '5')? 'selected="selected"' :''; ?>>5</option>
                        <option value="6" <?php echo ($defaults['col'] == '6')? 'selected="selected"' :''; ?>>6</option>
                    </select>
                    <p><?php __( "Select Columns Number") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Container Max Width (px)</th>
                <td>
                    <input type="number" name="cont_max_w" value="<?php echo $defaults['cont_max_w']; ?>" />
                    <p><?php __( "Enter Width") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnails Container Max Width (px)</th>
                <td>
                    <input type="number" name="thumbs_cont_max_w" value="<?php echo $defaults['thumbs_cont_max_w']; ?>" />
                    <p><?php __( "Enter Width") ?></p>
                </td>
            </tr><tr valign="top">
                <th scope="row">Category Separator Line</th>
                <td>
                    <select name="cont_sep">
                        <option value="yes" <?php echo ($defaults['cont_sep'] == 'yes')? 'selected="selected"' :''; ?>>Yes</option>
                        <option value="no" <?php echo ($defaults['cont_sep'] == 'no')? 'selected="selected"' :''; ?>>No</option>
                    </select>
                    <p><?php __( "Show Separator?") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Last Category Separator</th>
                <td>
                    <select name="cont_sep_last">
                        <option value="yes" <?php echo ($defaults['cont_sep_last'] == 'yes')? 'selected="selected"' :''; ?>>Yes</option>
                        <option value="no" <?php echo ($defaults['cont_sep_last'] == 'no')? 'selected="selected"' :''; ?>>No</option>
                    </select>
                    <p><?php __( "Show Last Separator?") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Separator Thickness (px)</th>
                <td>
                    <input type="number" name="cont_sep_th" value="<?php echo $defaults['cont_sep_th']; ?>" />
                    <p><?php __( "Enter Separator Thickness") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Separator Top Margin (px)</th>
                <td>
                    <input type="number" name="cont_sep_mt" value="<?php echo $defaults['cont_sep_mt']; ?>" />
                    <p><?php __( "Enter Separator Top Margin") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Separator Bottom Margin (px)</th>
                <td>
                    <input type="number" name="cont_sep_mb" value="<?php echo $defaults['cont_sep_mb']; ?>" />
                    <p><?php __( "Enter Separator Bottom Margin") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Separator Line Color</th>
                <td>
                    <input type="text" name="cont_sep_color" value="<?php echo $defaults['cont_sep_color']; ?>" />
                    <p><?php __( "Select Color") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Image Size</th>
                <td>
                    <input type="number" name="th_image_size" value="<?php echo $defaults['th_image_size']; ?>" />
                    <p><?php __( "Enter Thumbnail Image Size") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Image Sizing Type</th>
                <td>
                    <select name="th_image_sizing">
                        <option value="auto" <?php echo ($defaults['th_image_sizing'] == 'auto')? 'selected="selected"' :''; ?>>auto</option>
                        <option value="full height" <?php echo ($defaults['th_image_sizing'] == 'full height')? 'selected="selected"' :''; ?>>full height</option>
                        <option value="full width" <?php echo ($defaults['th_image_sizing'] == 'full width')? 'selected="selected"' :''; ?>>full width</option>
                    </select>
                    <p><?php __( "Enter Sizing Type") ?></p>
                </td>
            </tr><tr valign="top">
                <th scope="row">Thumbnail Title Visibility</th>
                <td>
                    <select name="title">
                        <option value="yes" <?php echo ($defaults['title'] == 'yes')? 'selected="selected"' :''; ?>>Yes</option>
                        <option value="no" <?php echo ($defaults['title'] == 'no')? 'selected="selected"' :''; ?>>No</option>
                    </select>
                    <p><?php __( "Show Title?") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Title Align</th>
                <td>
                    <select name="th_title_pos">
                        <option value="default" <?php echo ($defaults['th_title_pos'] == 'default')? 'selected="selected"' :''; ?>>default</option>
                        <option value="left" <?php echo ($defaults['th_title_pos'] == 'left')? 'selected="selected"' :''; ?>>left</option>
                        <option value="right" <?php echo ($defaults['th_title_pos'] == 'right')? 'selected="selected"' :''; ?>>right</option>
                        <option value="center" <?php echo ($defaults['th_title_pos'] == 'center')? 'selected="selected"' :''; ?>>center</option>
                    </select>
                    <p><?php __( "Select Alignment") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Title Font</th>
                <td>
                    <input type="text" name="th_title_font" value="<?php echo $defaults['th_title_font']; ?>" />
                    <p><?php __( "Enter Thumbnail Title Font") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Title size (px)</th>
                <td>
                    <input type="number" name="th_title_size" value="<?php echo $defaults['th_title_size']; ?>" />
                    <p><?php __( "Enter Font Size") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Title transform</th>
                <td>
                    <select name="th_title_transform">
                        <option value="none" <?php echo ($defaults['th_title_transform'] == 'none')? 'selected="selected"' :''; ?>>none</option>
                        <option value="capitalize" <?php echo ($defaults['th_title_transform'] == 'capitalize')? 'selected="selected"' :''; ?>>capitalize</option>
                        <option value="uppercase" <?php echo ($defaults['th_title_transform'] == 'uppercase')? 'selected="selected"' :''; ?>>uppercase</option>
                        <option value="lowercase" <?php echo ($defaults['th_title_transform'] == 'lowercase')? 'selected="selected"' :''; ?>>lowercase</option>
                    </select>
                    <p><?php __( "Select Text Transform") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Title Weight</th>
                <td>
                    <select name="th_title_weight">
                        <option value="default" <?php echo ($defaults['th_title_weight'] == 'default')? 'selected="selected"' :''; ?>>default</option>
                        <option value="bold" <?php echo ($defaults['th_title_weight'] == 'bold')? 'selected="selected"' :''; ?>>bold</option>
                        <option value="bolder" <?php echo ($defaults['th_title_weight'] == 'bolder')? 'selected="selected"' :''; ?>>bolder</option>
                        <option value="lighter" <?php echo ($defaults['th_title_weight'] == 'lighter')? 'selected="selected"' :''; ?>>lighter</option>
                        <option value="normal" <?php echo ($defaults['th_title_weight'] == 'normal')? 'selected="selected"' :''; ?>>normal</option>
                        <option value="100" <?php echo ($defaults['th_title_weight'] == '100')? 'selected="selected"' :''; ?>>100</option>
                        <option value="200" <?php echo ($defaults['th_title_weight'] == '200')? 'selected="selected"' :''; ?>>200</option>
                        <option value="300" <?php echo ($defaults['th_title_weight'] == '300')? 'selected="selected"' :''; ?>>300</option>
                        <option value="400" <?php echo ($defaults['th_title_weight'] == '400')? 'selected="selected"' :''; ?>>400</option>
                        <option value="500" <?php echo ($defaults['th_title_weight'] == '500')? 'selected="selected"' :''; ?>>500</option>
                        <option value="600" <?php echo ($defaults['th_title_weight'] == '600')? 'selected="selected"' :''; ?>>600</option>
                        <option value="700" <?php echo ($defaults['th_title_weight'] == '700')? 'selected="selected"' :''; ?>>700</option>
                        <option value="800" <?php echo ($defaults['th_title_weight'] == '800')? 'selected="selected"' :''; ?>>800</option>
                        <option value="900" <?php echo ($defaults['th_title_weight'] == '900')? 'selected="selected"' :''; ?>>900</option>
                    </select>
                    <p><?php __( "Select Weight") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Thumbnail Title color</th>
                <td>
                    <input type="text" name="th_title_color" value="<?php echo $defaults['th_title_color']; ?>" />
                    <p><?php __( "Select Color") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Category Title Size (px)</th>
                <td>
                    <input type="number" name="cat_title_size" value="<?php echo $defaults['cat_title_size']; ?>" />
                    <p><?php __( "Enter Font Size") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Category Title Align</th>
                <td>
                    <select name="cat_title_pos">
                        <option value="default" <?php echo ($defaults['cat_title_pos'] == 'default')? 'selected="selected"' :''; ?>>default</option>
                        <option value="left" <?php echo ($defaults['cat_title_pos'] == 'left')? 'selected="selected"' :''; ?>>left</option>
                        <option value="right" <?php echo ($defaults['cat_title_pos'] == 'right')? 'selected="selected"' :''; ?>>right</option>
                        <option value="center" <?php echo ($defaults['cat_title_pos'] == 'center')? 'selected="selected"' :''; ?>>center</option>
                    </select>
                    <p><?php __( "Select Alignment") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Category Title Font</th>
                <td>
                    <input type="text" name="cat_title_font" value="<?php echo $defaults['cat_title_font']; ?>" />
                    <p><?php __( "Enter Category Title Font") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Category Title transform</th>
                <td>
                    <select name="cat_title_transform">
                        <option value="none" <?php echo ($defaults['cat_title_transform'] == 'none')? 'selected="selected"' :''; ?>>none</option>
                        <option value="capitalize" <?php echo ($defaults['cat_title_transform'] == 'capitalize')? 'selected="selected"' :''; ?>>capitalize</option>
                        <option value="uppercase" <?php echo ($defaults['cat_title_transform'] == 'uppercase')? 'selected="selected"' :''; ?>>uppercase</option>
                        <option value="lowercase" <?php echo ($defaults['cat_title_transform'] == 'lowercase')? 'selected="selected"' :''; ?>>lowercase</option>
                    </select>
                    <p><?php __( "Select Text Transform") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Category Title Weight</th>
                <td>
                    <select name="cat_title_weight">
                        <option value="default" <?php echo ($defaults['cat_title_weight'] == 'default')? 'selected="selected"' :''; ?>>default</option>
                        <option value="bold" <?php echo ($defaults['cat_title_weight'] == 'bold')? 'selected="selected"' :''; ?>>bold</option>
                        <option value="bolder" <?php echo ($defaults['cat_title_weight'] == 'bolder')? 'selected="selected"' :''; ?>>bolder</option>
                        <option value="lighter" <?php echo ($defaults['cat_title_weight'] == 'lighter')? 'selected="selected"' :''; ?>>lighter</option>
                        <option value="normal" <?php echo ($defaults['cat_title_weight'] == 'normal')? 'selected="selected"' :''; ?>>normal</option>
                        <option value="100" <?php echo ($defaults['cat_title_weight'] == '100')? 'selected="selected"' :''; ?>>100</option>
                        <option value="200" <?php echo ($defaults['cat_title_weight'] == '200')? 'selected="selected"' :''; ?>>200</option>
                        <option value="300" <?php echo ($defaults['cat_title_weight'] == '300')? 'selected="selected"' :''; ?>>300</option>
                        <option value="400" <?php echo ($defaults['cat_title_weight'] == '400')? 'selected="selected"' :''; ?>>400</option>
                        <option value="500" <?php echo ($defaults['cat_title_weight'] == '500')? 'selected="selected"' :''; ?>>500</option>
                        <option value="600" <?php echo ($defaults['cat_title_weight'] == '600')? 'selected="selected"' :''; ?>>600</option>
                        <option value="700" <?php echo ($defaults['cat_title_weight'] == '700')? 'selected="selected"' :''; ?>>700</option>
                        <option value="800" <?php echo ($defaults['cat_title_weight'] == '800')? 'selected="selected"' :''; ?>>800</option>
                        <option value="900" <?php echo ($defaults['cat_title_weight'] == '900')? 'selected="selected"' :''; ?>>900</option>
                    </select>
                    <p><?php __( "Select Weight") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Category Title color</th>
                <td>
                    <input type="text" name="cat_title_color" value="<?php echo $defaults['cat_title_color']; ?>" />
                    <p><?php __( "Select Color") ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Show description?</th>
                <td>
                    <select name="show_description">
                        <option value="yes" <?php echo ($defaults['show_description'] == 'yes')? 'selected="selected"' :''; ?>>Yes</option>
                        <option value="no" <?php echo ($defaults['show_description'] == 'no')? 'selected="selected"' :''; ?>>No</option>
                    </select>
                    <p><?php __( "Show category description under title?") ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
</div>


<script>
    jQuery(document).ready(function($){
        $('input[name*="cont_sep_color"]').wpColorPicker();
        $('input[name*="th_title_color"]').wpColorPicker();
        $('input[name*="cat_title_color"]').wpColorPicker();
    })
</script>