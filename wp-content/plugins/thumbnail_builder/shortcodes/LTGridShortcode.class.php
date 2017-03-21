<?php
class LTGridShortcode {
    public static $postType = 'linked_thumbnail';
    public static $PosMetaName = '_thumb_glob_order';
    public static $defaults = [
        'class' => '',
        'col' => 4,
        'perPage' => 100,
        'title' => 'yes',
        'cat' => []
    ];


    public function __construct() {

        add_shortcode( 'LTGS', array( $this, 'render' ) );

    }

    function render( $args, $content = '') {
        global $pluginUrl;
        wp_enqueue_style('ltgs_style', $pluginUrl . '/css/thumbnail_grid_shortcode.css');
        $args = (gettype($args) == 'array')?array_merge(self::$defaults,$args):self::$defaults;

        if($args['col'] > 6) $args['col']=6;
        $queryArgs = array(
            'post_status' => 'publish',
            'posts_per_page' => $args['perPage'],
            'post_type' => self::$postType,
            'meta_key' => self::$PosMetaName,
            'cat' => $args['cat'],
            'orderby' => ['meta_value_num'=>'ASC','post_title'=>'ASC'],
        );

        $args['cat'] = (gettype($args['cat']) == 'array')?$args['cat']:explode(',',trim($args['cat']));
        $thumbs = new WP_Query( $queryArgs );
        $isAllCats = (count($args['cat']) <= 0);
        $byCategoryContent = ['cat_0'=>''];
        $catTitles = ['cat_0'=>null];

        if(!$isAllCats)foreach($args['cat'] as $taxId){
            $byCategoryContent[$taxId] ='';
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
        $html = '<div class="LTGridContainer">';
        foreach($byCategoryContent as $taxId=>$content){
            $html .= LTTmplToVar('templates/short_code/category_grid_container.tmpl.php',['catName'=>$catTitles[$taxId],'content'=>$content]);
        }

        return $html.'</div>';

    }
}