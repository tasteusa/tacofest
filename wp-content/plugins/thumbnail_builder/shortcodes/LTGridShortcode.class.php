<?php
class LTGridShortcode {
    public static $postType = 'linked_thumbnail';
    public static $PosMetaName = '_thumb_glob_order';
    private static $optName = '_lgts_cat_order';

    public static $defaults = [
        'class' => '',
        'col' => 4,
        'perPage' => 300,
        'title' => 'yes',
        'cat' => [],
        'cat_excl' => [],
        'th_title_font' => false,
        'th_title_size' => false,
        'th_title_color' => false,
        'th_title_pos' => 'default',
        'th_title_weight' => 'default',
        'th_image_size' => 150,
        'th_image_sizing' => 'auto',
        'cat_title_font' => false,
        'cat_title_size' => false,
        'cat_title_color' => false,
        'cat_title_pos' => 'default',
        'cat_title_weight' => 'default',
    ];


    public function __construct() {

        add_shortcode( 'LTGS', array( $this, 'render' ) );

    }

    function render( $args, $content = '') {
        global $TBPluginUrl;
        wp_enqueue_style('ltgs_style', $TBPluginUrl . '/css/thumbnail_grid_shortcode.css');
        $args = (gettype($args) == 'array')?array_merge(self::$defaults,$args):self::$defaults;

        if($args['col'] > 6) $args['col']=6;
        if($args['cat_excl'] != '') $args['cat_excl'] = explode(',',$args['cat_excl']);
        $queryArgs = array(
            'post_status' => 'publish',
            'posts_per_page' => $args['perPage'],
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

        while( $thumbs->have_posts() ) {
            $thumbs->the_post();
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
                if(!isset($catTitles['cat_'.$cat[0]->term_id]))$catTitles['cat_'.$cat[0]->term_id] = $cat[0]->name;
            }

        }
        wp_reset_query();

        $shortcodeId = 'lgts_'.uniqid();
        $args['shortcodeId'] = $shortcodeId;
        $html = '<div id="'.$shortcodeId.'" class="LTGridContainer">';
        foreach($byCategoryContent as $taxId=>$content){
           if(trim($content)!='') $html .= LTTmplToVar('templates/short_code/category_grid_container.tmpl.php',['catName'=>$catTitles[$taxId],'content'=>$content]);
        }
        $html .= LTTmplToVar('templates/short_code/shortcode_styles.tmpl.php',$args, true);
        return $html.'</div>';

    }
}