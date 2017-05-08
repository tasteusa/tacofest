<?php
class LTGridShortcode {
    public static $postType = 'linked_thumbnail';
    public static $PosMetaName = '_thumb_glob_order';
    private static $optName = '_lgts_cat_order';

    public static $defaults = [
        'class' => '',
        'col' => 4,
        'title' => 'yes',
        'cat' => [],
        'cat_excl' => [],
        'cont_max_w' => 'none',
        'thumbs_cont_max_w' => 'none',
        'cont_sep' => 'no',
        'cont_sep_last' => 'no',
        'cont_sep_color' => "#000000",
        'cont_sep_th'=>1,
        'cont_sep_mt'=>0,
        'cont_sep_mb'=>0,
        'th_title_font' => false,
        'th_title_size' => false,
        'th_title_color' => false,
        'th_title_transform' => false,
        'th_title_pos' => 'default',
        'th_title_weight' => 'default',
        'th_image_size' => 150,
        'th_image_sizing' => 'auto',
        'cat_title_font' => false,
        'cat_title_transform' => false,
        'cat_title_size' => false,
        'cat_title_color' => false,
        'cat_title_pos' => 'default',
        'cat_title_weight' => 'default',
        'show_description' => 'yes',
    ];


    public function __construct() {


        self::$defaults['col'] = get_option('col')? get_option('col') : 4 ;
        self::$defaults['title'] = get_option('title')? get_option('title') : 'yes';
        self::$defaults['cont_max_w'] = get_option('cont_max_w')? get_option('cont_max_w') : 1400;
        self::$defaults['thumbs_cont_max_w'] = get_option('thumbs_cont_max_w')? get_option('thumbs_cont_max_w') : 1132;
        self::$defaults['cont_sep'] = get_option('cont_sep')? get_option('cont_sep') : 'no';
        self::$defaults['cont_sep_last'] = get_option('cont_sep_last')? get_option('cont_sep_last') : 'no';
        self::$defaults['cont_sep_color'] = get_option('cont_sep_color')? get_option('cont_sep_color') : "#000000";
        self::$defaults['cont_sep_th']= get_option('cont_sep_th')? get_option('cont_sep_th') : 1;
        self::$defaults['cont_sep_mt']= get_option('cont_sep_mt')? get_option('cont_sep_mt') : 0;
        self::$defaults['cont_sep_mb']= get_option('cont_sep_mb')? get_option('cont_sep_mb') : 0;
        self::$defaults['th_title_font'] = get_option('th_title_font')? get_option('th_title_font') : 'sourceSansPro';
        self::$defaults['th_title_size'] = get_option('th_title_size')? get_option('th_title_size') : 18;
        self::$defaults['th_title_color'] = get_option('th_title_color')? get_option('th_title_color') : '#f23404';
        self::$defaults['th_title_transform'] = get_option('th_title_transform')? get_option('th_title_transform') : false;
        self::$defaults['th_title_pos'] = get_option('th_title_pos')? get_option('th_title_pos') : 'default';
        self::$defaults['th_title_weight'] = get_option('th_title_weight')? get_option('th_title_weight') : 'default';
        self::$defaults['th_image_size'] = get_option('th_image_size')? get_option('th_image_size') : 150;
        self::$defaults['th_image_sizing'] = get_option('th_image_sizing')? get_option('th_image_sizing') : 'auto';
        self::$defaults['cat_title_font'] = get_option('cat_title_font')? get_option('cat_title_font') : 'sourceSansPro';
        self::$defaults['cat_title_transform'] = get_option('cat_title_transform')? get_option('cat_title_transform') : 'none';
        self::$defaults['cat_title_size'] = get_option('cat_title_size')? get_option('cat_title_size') : 80;
        self::$defaults['cat_title_color'] = get_option('cat_title_color')? get_option('cat_title_color') : '#dd3333';
        self::$defaults['cat_title_pos'] = get_option('cat_title_pos')? get_option('cat_title_pos') : 'default';
        self::$defaults['cat_title_weight'] = get_option('cat_title_weight')? get_option('cat_title_weight') : 'default';
        self::$defaults['show_description'] = get_option('show_description')? get_option('show_description') : 'yes';


        add_shortcode( 'LTGS', array( $this, 'render' ) );

    }

    function render( $args, $content = '') {
        global $TBPluginUrl;
        wp_enqueue_style('ltgs_style', $TBPluginUrl . '/css/thumbnail_grid_shortcode.css');
        $args = (gettype($args) == 'array')?array_merge(self::$defaults,$args):self::$defaults;


        $result_thumbnails = array();

        if(empty($args['cat'])){

            $cats = get_categories();
        }else{
            $cats = explode(',',$args['cat']);
            if(!empty($args['cat_excl'])){
                $cat_excl = explode(',',$args['cat_excl']);
                foreach ($cat_excl as $cl) {
                    if(($key = array_search($cl, $cats)) !== false) {
                        unset($cats[$key]);
                    }
                }
            }
        }
        foreach ($cats as $cat_id){
            $category_args = array(
                'post_status' => 'publish',
                'posts_per_page' => 100,
                'post_type' => self::$postType,
                'meta_key' => self::$PosMetaName,
                'cat' => $cat_id
            );
            $thumbsq = get_posts( $category_args );


            $category_order = json_decode(get_term_meta($cat_id,'category_order')[0]);
            if(isset($category_order) && is_array($category_order)){
                $sorted_posts = [];
                foreach($category_order as $id) {
                    foreach($thumbsq as $key=>$thumb){
                        if($thumb->ID == $id){
                            $sorted_posts[] = $thumb;
                            unset($thumbsq[$key]);
                        }
                    }
                }
                $thumbsq =  array_merge($sorted_posts,$thumbsq);
            }
            $result_thumbnails = array_merge($result_thumbnails,$thumbsq);
        }

        if($args['col'] > 6) $args['col']=6;
        if($args['cat_excl'] != '') $args['cat_excl'] = explode(',',$args['cat_excl']);
        $queryArgs = array(
            'post_status' => 'publish',
            'posts_per_page' => '-1',
            'post_type' => self::$postType,
            'meta_key' => self::$PosMetaName,
            'cat' => $args['cat'],
            'category__not_in' => $args['cat_excl'],
            'orderby' => ['meta_value_num'=>'ASC','post_title'=>'ASC'],
        );

        $args['cat'] = (gettype($args['cat']) == 'array')?$args['cat']:explode(',',trim($args['cat']));
        $thumbs = new WP_Query( $queryArgs );
        $isAllCats = (count($args['cat']) <= 0);
        $byCategoryContent = [];
        $catTitles = ['cat_0'=>null];
        $catOrder = json_decode(get_option(self::$optName,'[]'));

        if(!$isAllCats)foreach($catOrder as $taxId){
            if($taxId != '')$byCategoryContent['cat_'.$taxId] ='';
        }
        $bootstrapColumns = floor(12 / $args['col']);
        $bootstrapSmColumn = $bootstrapColumns*2;
        if($bootstrapSmColumn > 12) $bootstrapSmColumn = 12;
        $attr['gridClasses'] = sprintf( 'col-lg-%s col-md-%s col-sm-%s', $bootstrapColumns, $bootstrapColumns, $bootstrapSmColumn );

        foreach ($result_thumbnails as $thumbnail){
            global $post;
            $post = $thumbnail;

            $tmplArgs =[
                'imageUrl' =>get_the_post_thumbnail_url(get_the_ID()),
                'url' => get_post_meta(get_the_ID(), '_web_link', true),
                'gridClasses'=> $attr['gridClasses'],
                'title'=>''
            ];

            if( $args['title'] == 'yes') {
                $tmplArgs['title'] = get_the_title();
            }

            $cat = get_the_category(get_the_ID());

            if(count($cat)<=0){
                $byCategoryContent['cat_0'] .= LTTmplToVar('templates/short_code/grid_item.tmpl.php',$tmplArgs);
            } else {
                if(!isset($byCategoryContent['cat_'.$cat[0]->term_id])) $byCategoryContent['cat_'.$cat[0]->term_id] = '';
                $byCategoryContent['cat_'.$cat[0]->term_id] .= LTTmplToVar('templates/short_code/grid_item.tmpl.php',$tmplArgs);
                if(!isset($catTitles['cat_'.$cat[0]->term_id])){
                    $catTitles['cat_'.$cat[0]->term_id]['name'] = $cat[0]->name;
                    if( $args['show_description'] == 'yes') {
                        $catTitles['cat_' . $cat[0]->term_id]['description'] = $cat[0]->description;
                    }
                }
            }

        }
        wp_reset_query();

        $shortcodeId = 'lgts_'.uniqid();
        $args['shortcodeId'] = $shortcodeId;
        $html = '<div id="'.$shortcodeId.'" class="LTGridContainer">';
        foreach($byCategoryContent as $taxId=>$content){
           if(trim($content)!=''){
               $html .= LTTmplToVar('templates/short_code/category_grid_container.tmpl.php',['catName'=>$catTitles[$taxId],'content'=>$content]);
           }
        }

        $html .= '</div>';
        return $html.LTTmplToVar('templates/short_code/shortcode_styles.tmpl.php',$args, true);

    }
}

class ByButtonShortcode {

    public static $postType = 'dd_shortcode';
    public static $PosMetaName = '_thumb_glob_order';
    private static $optName = '_bb_cat_order';

    public static $defaults = [

    ];


    public function __construct() {

        add_shortcode( 'BBS', array( $this, 'render' ) );

    }

    function render( $args, $content = '') {
        $bb_title = get_option('bb_title') ? get_option('bb_title') : 'BUY TICKETS';

        $bb_button_url = get_option('bb_button_url') ? get_option('bb_button_url') : '#';

        echo do_shortcode('[vc_btn title="'.$bb_title.'" color="danger" align="center" el_class="t-buy-tickets-btn" link="url:'.urlencode($bb_button_url).'|title:'.urlencode($bb_title).'||" ]');

    }
}
