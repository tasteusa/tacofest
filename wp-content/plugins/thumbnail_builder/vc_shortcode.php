<?php
/*add shortcode to visual composer*/
if(function_exists('vc_add_shortcode_param')){

    function regVcParam(){
        $weightOts = ['default','bold','bolder','lighter','normal','100','200','300','400','500','600','700','800','900'];
        $transfOts = ['none','capitalize','uppercase','lowercase'];
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
                    "std" => get_option('col')? get_option('col') : 4,
                    "description" => __( "Select Columns Number"),
                    'group' => 'General'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Container Max Width (px)"),
                    "param_name" => "cont_max_w",
                    'value' => get_option('cont_max_w')? get_option('cont_max_w') : 1400,
                    "description" => __("Enter Width"),
                    'group' => 'General'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnails Container Max Width (px)"),
                    "param_name" => "thumbs_cont_max_w",
                    'value' => get_option('thumbs_cont_max_w')? get_option('thumbs_cont_max_w') : 1132,
                    "description" => __("Enter Width"),
                    'group' => 'General'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Category Separator Line"),
                    "param_name" => "cont_sep",
                    "value" => ['no','yes'],
                    'std' => get_option('cont_sep')? get_option('cont_sep') : 'no',
                    "description" => __("Show Separator?"),
                    'group' => 'Category Separator'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Last Category Separator"),
                    "param_name" => "cont_sep_last",
                    "value" => ['no','yes'],
                    "std" => get_option('cont_sep_last')? get_option('cont_sep_last') : 'no',
                    "description" => __("Show Last Separator?"),
                    'group' => 'Category Separator'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Separator Thickness (px)"),
                    "param_name" => "cont_sep_th",
                    'value' => get_option('cont_sep_th')? get_option('cont_sep_th') : 1,
                    "description" => __("Enter Separator Thickness"),
                    'group' => 'Category Separator'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Separator Top Margin (px)"),
                    "param_name" => "cont_sep_mt",
                    'value' => get_option('cont_sep_mt')? get_option('cont_sep_mt') : 0,
                    "description" => __("Enter Separator Top Margin"),
                    'group' => 'Category Separator'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Separator Bottom Margin (px)"),
                    "param_name" => "cont_sep_mb",
                    'value' => get_option('cont_sep_mb')? get_option('cont_sep_mb') : 0,
                    "description" => __("Enter Separator Bottom Margin"),
                    'group' => 'Category Separator'
                ),
                array(
                    "type" => "vc_color_picker_param",
                    "class" => "",
                    "heading" => __("Separator Line Color"),
                    "param_name" => "cont_sep_color",
                    "value" => get_option('cont_sep_color')? get_option('cont_sep_color') : "#000000",
                    "description" => __( "Select Color"),
                    'group' => 'Category Separator'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnail Image Size"),
                    "param_name" => "th_image_size",
                    'value' => get_option('th_image_size')? get_option('th_image_size') : 150,
                    "description" => __("Enter Thumbnail Image Size"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Image Sizing Type"),
                    "param_name" => "th_image_sizing",
                    "std" => get_option('th_image_sizing')? get_option('th_image_sizing') : 'auto',
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
                    "std" => get_option('title')? get_option('title') : 'yes',
                    "description" => __("Show Title?"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Title Align"),
                    "param_name" => "th_title_pos",
                    "value" => ['default','left','right','center'],
                    "std" => get_option('th_title_pos')? get_option('th_title_pos') : 'default',
                    "description" => __("Select Alignment"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnail Title Font"),
                    "param_name" => "th_title_font",
                    "value" => get_option('th_title_font')? get_option('th_title_font') : 'sourceSansPro',
                    "description" => __("Enter Thumbnail Title Font"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Thumbnail Title size (px)"),
                    "param_name" => "th_title_size",
                    "value" => get_option('th_title_size')? get_option('th_title_size') : 18,
                    "description" => __("Enter Font Size"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Title transform"),
                    "param_name" => "th_title_transform",
                    "value" => $transfOts,
                    "std" => get_option('th_title_transform')? get_option('th_title_transform') : 'none',
                    "description" => __("Select Text Transform"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Thumbnail Title Weight"),
                    "param_name" => "th_title_weight",
                    "value" => $weightOts,
                    "std" => get_option('th_title_weight')? get_option('th_title_weight') : 'default',
                    "description" => __("Select Weight"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "vc_color_picker_param",
                    "class" => "",
                    "heading" => __("Thumbnail Title color"),
                    "param_name" => "th_title_color",
                    "value" => get_option('th_title_color')? get_option('th_title_color') : '#f23404',
                    "description" => __( "Select Color"),
                    'group' => 'Thumbnail'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Category Title Size (px)"),
                    "param_name" => "cat_title_size",
                    "value" => get_option('cat_title_size')? get_option('cat_title_size') : 80,
                    "description" => __("Enter Font Size"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Category Title Align"),
                    "param_name" => "cat_title_pos",
                    "value" => ['default','left','right','center'],
                    "std" => get_option('cat_title_pos')? get_option('cat_title_pos') : 'default',
                    "description" => __("Select Alignment"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Category Title Font"),
                    "param_name" => "cat_title_font",
                    "value" => get_option('cat_title_font')? get_option('cat_title_font') : 'PassionOne',
                    "description" => __("Enter Category Title Font"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Category Title transform"),
                    "param_name" => "cat_title_transform",
                    "value" => $transfOts,
                    "std" => get_option('cat_title_transform')? get_option('cat_title_transform') : 'none',
                    "description" => __("Select Text Transform"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Category Title Weight"),
                    "param_name" => "cat_title_weight",
                    "value" => $weightOts,
                    "std" => get_option('cat_title_weight')? get_option('cat_title_weight') : 'default',
                    "description" => __("Select Weight"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "vc_color_picker_param",
                    "class" => "",
                    "heading" => __("Category Title color"),
                    "param_name" => "cat_title_color",
                    "value" => get_option('cat_title_color')? get_option('cat_title_color') : '#dd3333',
                    "description" => __( "Select Color"),
                    'group' => 'Category Title'
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Show description?"),
                    "param_name" => "show_description",
                    "value" => ['yes','no'],
                    "std" => get_option('show_description')? get_option('show_description') : 'yes',
                    "description" => __("Show category description under title?"),
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


    function reg_By_Button(){

        vc_map( array(
            "name" => __( "By Button"),
            "base" => "BBS",
            "class" => "",
            "category" => 'shortcodes',
            "params" => array(
            ),
        ) );
    }

    add_action( 'vc_before_init', 'reg_By_Button' );


}
/*complete adding shortcode to visual composer*/