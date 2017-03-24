<?php
/*add shortcode to visual composer*/
if(function_exists('vc_add_shortcode_param')){

    function regVcParam(){
        $weightOts = ['default','bold','bolder','lighter','normal','100','200','300','400','500','600','700','800','900'];
        vc_map( array(
            "name" => __( "Linked Thumbnails Grid"),
            "base" => "LTGS",
            "class" => "",
            "category" => 'shortcodes',
            "params" => array(
                array(
                    "type" => "vc_cat_param",
                    "class" => "",
                    'emptyTitle' => "All",
                    "heading" => __("Categories"),
                    "param_name" => "cat",
                    "description" => __( "Select Categories"),
                    'group' => 'General'
                ),
                array(
                    "type" => "vc_cat_param",
                    "class" => "",
                    "heading" => __("Exclude Categories"),
                    "param_name" => "cat_excl",
                    'emptyTitle' => "none",
                    "description" => __( "Select Categories"),
                    'group' => 'General'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Columns", "js_composer"),
                    "param_name" => "col",
                    "value" => [1,2,3,4,6],
                    "description" => __( "Select Columns Number"),
                    'group' => 'General'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnail Image Size"),
                    "param_name" => "th_image_size",
                    'value' => 150,
                    "description" => __("Enter Thumbnail Image Size"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Image Sizing Type"),
                    "param_name" => "th_image_sizing",
                    "value" => ['auto','full height','full width'],
                    "description" => __("Enter Sizing Type"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Title Visibility"),
                    "param_name" => "title",
                    "value" => ['yes','no'],
                    "description" => __("Show Title?"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Title Align"),
                    "param_name" => "th_title_pos",
                    "value" => ['default','left','right','center'],
                    "description" => __("Select Alignment"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnail Title Font"),
                    "param_name" => "th_title_font",
                    "description" => __("Enter Thumbnail Title Font"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnail Title size (px)"),
                    "param_name" => "th_title_size",
                    "description" => __("Enter Font Size"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Title Weight"),
                    "param_name" => "th_title_weight",
                    "value" => $weightOts,
                    "description" => __("Select Weight"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "vc_color_picker_param",
                    "class" => "",
                    "heading" => __("Thumbnail Title color"),
                    "param_name" => "th_title_color",
                    "description" => __( "Select Color"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Category Title Size (px)"),
                    "param_name" => "cat_title_size",
                    "description" => __("Enter Font Size"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Category Title Align"),
                    "param_name" => "cat_title_pos",
                    "value" => ['default','left','right','center'],
                    "description" => __("Select Alignment"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Category Title Font"),
                    "param_name" => "th_title_font",
                    "description" => __("Enter Category Title Font"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Category Title Weight"),
                    "param_name" => "cat_title_weight",
                    "value" => $weightOts,
                    "description" => __("Select Weight"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "vc_color_picker_param",
                    "class" => "",
                    "heading" => __("Category Title color"),
                    "param_name" => "cat_title_color",
                    "description" => __( "Select Color"),
                    'group' => 'Category Title'
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
        if(gettype($value)!='array' && trim($value) != ""){
            $value = explode(',',$value);
        }elseif (gettype($value)!='array'){
            $value = [];
        }
        return LTTmplToVar('templates/short_code/vc_cat_param.tmpl.php', ['settings'=>$settings, 'value'=>$value, 'categories'=>$categories], true);
    }

    function vcColorParam($settings, $value){
        return LTTmplToVar('templates/short_code/vc_color_picker_param.tmpl.php', ['settings'=>$settings, 'value'=>$value], true);
    }

    vc_add_shortcode_param('vc_cat_param','vcCatParam');
    vc_add_shortcode_param('vc_color_picker_param','vcColorParam',$TBPluginUrl.'js/visual_composer/vc_color_picker_param.js');
    add_action( 'vc_before_init', 'regVcParam' );
}
/*complete adding shortcode to visual composer*/