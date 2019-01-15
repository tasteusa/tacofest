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
        self::$defaults['cat_title_font'] = get_option('cat_title_font')? get_option('cat_title_font') : 'PassionOne';
        self::$defaults['cat_title_transform'] = get_option('cat_title_transform')? get_option('cat_title_transform') : 'none';
        self::$defaults['cat_title_size'] = get_option('cat_title_size')? get_option('cat_title_size') : 80;
        self::$defaults['cat_title_color'] = get_option('cat_title_color')? get_option('cat_title_color') : '#dd3333';
        self::$defaults['cat_title_pos'] = get_option('cat_title_pos')? get_option('cat_title_pos') : 'default';
        self::$defaults['cat_title_weight'] = get_option('cat_title_weight')? get_option('cat_title_weight') : 'default';
        self::$defaults['show_description'] = get_option('show_description')? get_option('show_description') : 'yes';


        add_shortcode( 'LTGS', array( $this, 'render' ) );

    }

    function render($args, $content = '')
    {
        global $TBPluginUrl;
        wp_enqueue_style('ltgs_style', $TBPluginUrl . '/css/thumbnail_grid_shortcode.css');
        $args = (gettype($args) == 'array') ? array_merge(self::$defaults, $args) : self::$defaults;
        global $post;
        $realPost = $post;
        $pageId = $post->ID;
        $terms =  wp_get_post_terms( $pageId, 'location');
        $result_thumbnails = array();
        $html = '';
        $winner_arr = [];
        if (empty($args['cat'])) {

            $cats = get_categories();
        } else {
            $cats = explode(',', $args['cat']);
            if (!empty($args['cat_excl'])) {
                if (is_string($args['cat_excl'])) {
                    $cat_excl = explode(',', $args['cat_excl']);
                } else {
                    $cat_excl = $args['cat_excl'];
                }
                foreach ($cat_excl as $cl) {
                    if (($key = array_search($cl, $cats)) !== false) {
                        unset($cats[$key]);
                    }
                }
            }
        }

        if ($args['col'] > 6) $args['col'] = 6;
        if (isset($args['cat_excl'])) {
            if (is_string($args['cat_excl'])) {
                $args['cat_excl'] = explode(',', $args['cat_excl']);
            }
        }

        foreach ($cats as $cat_id) {
            $category_args = array(
                'post_status' => 'publish',
                'posts_per_page' => 100,
                'post_type' => self::$postType,
                'meta_key' => self::$PosMetaName,
                'cat' => $cat_id,
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'key' => '_is_winner',
                        'compare' => 'NOT EXISTS'
                    ],
                    [
                        'key' => '_is_winner',
                        'value'   => 'false',
                        'compare' => '='
                    ]
                ]
            );
            $thumbsq = get_posts($category_args);

            $cat_order_meta = get_term_meta($cat_id, 'category_order');
            if (!empty($cat_order_meta)) {
                $category_order = json_decode($cat_order_meta[0]);
            }
            if (isset($category_order) && is_array($category_order)) {
                $sorted_posts = [];
                foreach ($category_order as $id) {
                    foreach ($thumbsq as $key => $thumb) {
                        if ($thumb->ID == $id) {
                            $sorted_posts[] = $thumb;
                            unset($thumbsq[$key]);
                        }
                    }
                }
                $thumbsq = array_merge($sorted_posts, $thumbsq);
            }
            $result_thumbnails = array_merge($result_thumbnails, $thumbsq);

            $winners_thumbs = new WP_Query(array(
                'post_status' => 'publish',
                'posts_per_page' => 100,
                'post_type' => self::$postType,
                'meta_key' => self::$PosMetaName,
                'cat' => $cat_id,
                'meta_query' => [
                    'relation' => 'AND',
                    'winner' => [
                        'key' => '_is_winner',
                        'value' => 'true',
                        'compare' => '='
                    ],
                    'winner_place' => [
                        'key' => '_winner_place',
                        'value' => ['1','2','3'],
                        'compare' => 'IN'
                    ]
                ],
                'orderby' => ['winner_place'=>'ASC'],
            ));

            $results=[];
            $winner_html = '';
            if($winners_thumbs->have_posts()){

                $winners= $winners_thumbs->posts;
                $winner_number = $winners_thumbs->found_posts;

                foreach($winners as $winner){
                    $is_winner = get_post_meta($winner->ID, '_is_winner', true);
                    $winner_place = get_post_meta($winner->ID, '_winner_place', true);

                    $congrats_text = get_post_meta($winner->ID, '_congrats_text', true);
                    $congrats_text_align = get_post_meta($winner->ID, '_congrats_text_align', true);
                    $congrats_text_color = get_post_meta($winner->ID, '_congrats_text_color', true);
                    $congrats_font_weight = get_post_meta($winner->ID, '_congrats_font_weight', true);
                    $congrats_use_transparent_bg = get_post_meta($winner->ID, '_congrats_use_transparent_bg', true);
                    $congrats_text_background_color = get_post_meta($winner->ID, '_congrats_text_background_color', true);
                    $congrats_text_position = get_post_meta($winner->ID, '_congrats_text_position', true);
                    $congrats_image_position = get_post_meta($winner->ID, '_congrats_image_position', true);
                    $congrats_image_id = get_post_meta($winner->ID, '_congrats_image_id', true);

                    $winner_web_link = get_post_meta($winner->ID, '_web_link', true);
                    $winner_img = get_the_post_thumbnail_url($winner->ID);

                    $winner_html .= '<div class="col-xs-10 col-sm-7 col-md-4 col-lg-4 pb-15"><div class="winner-image-wrapper-block" style="background-image:url('.$winner_img.');">';

                    $winner_html .= '<img class="thumb-img" src="'.$winner_img.'" style="visibility:hidden"/>';
                    if($congrats_image_id){
                        $winner_html .= '<div class="winner-image" style=\''.$congrats_image_position.'\'></div>';

                    }
                    if($congrats_text){
                        $winner_html .= '<div class="winner-text" style=\''.$congrats_text_position.'\'><p>'.$congrats_text.'</p></div>';
                    }
                    $winner_html .= '</div></div>';
                    $winner_arr['cat_'.$cat_id] = $winner_html;
                }


            }

        }

        $args['cat'] = (gettype($args['cat']) == 'array') ? $args['cat'] : explode(',', trim($args['cat']));

        $isAllCats = (count($args['cat']) <= 0);
        $byCategoryContent = [];
        $catTitles = ['cat_0' => null];
        $pageOptName = self::$optName . '_' . $pageId;
        $catOrder = json_decode(get_option($pageOptName, '[]'));
        if (!is_array($catOrder) || empty($catOrder)) $catOrder = json_decode(get_option(self::$optName, '[]'));

        if (!$isAllCats) foreach ($catOrder as $taxId) {
            if ($taxId != '') $byCategoryContent['cat_' . $taxId] = '';
        }
        $bootstrapColumns = floor(12 / $args['col']);
        $bootstrapSmColumn = $bootstrapColumns * 2;
        if ($bootstrapSmColumn > 12) $bootstrapSmColumn = 12;
        $attr['gridClasses'] = sprintf('col-lg-%s col-md-%s col-sm-%s', $bootstrapColumns, $bootstrapColumns, $bootstrapSmColumn);


        foreach ($result_thumbnails as $thumbnail) {
            global $post;
            $post = $thumbnail;

            $tmplArgs = [
                'imageUrl' => get_the_post_thumbnail_url(get_the_ID()),
                'url' => get_post_meta(get_the_ID(), '_web_link', true),
                'text' => get_post_meta(get_the_ID(), '_add_text', true),
                'gridClasses' => $attr['gridClasses'],
                'title' => ''
            ];

            if ($args['title'] == 'yes') {
                $tmplArgs['title'] = get_the_title();
            }

            $cat = get_the_category(get_the_ID());

            if (count($cat) <= 0) {
                $byCategoryContent['cat_0'] .= LTTmplToVar('templates/short_code/grid_item.tmpl.php', $tmplArgs);
            } else {
                if (!isset($byCategoryContent['cat_' . $cat[0]->term_id])) $byCategoryContent['cat_' . $cat[0]->term_id] = '';
                $byCategoryContent['cat_' . $cat[0]->term_id] .= LTTmplToVar('templates/short_code/grid_item.tmpl.php', $tmplArgs);
                if (!isset($catTitles['cat_' . $cat[0]->term_id])) {
                    $catTitles['cat_' . $cat[0]->term_id]['name'] = $cat[0]->name;
                    if ($args['show_description'] == 'yes') {
                        $catTitles['cat_' . $cat[0]->term_id]['description'] = $cat[0]->description;
                    }
                }
            }

        }
        wp_reset_query();

        $shortcodeId = 'lgts_' . uniqid();
        $args['shortcodeId'] = $shortcodeId;
        $html .= '<div id="' . $shortcodeId . '" class="LTGridContainer">';
        foreach ($byCategoryContent as $taxId => $content) {
            if (trim($content) != '') {
                $html .= LTTmplToVar('templates/short_code/category_grid_container.tmpl.php',
                    [
                        'catName' => $catTitles[$taxId],
                        'content' => $content,
                        'winner' => $winner_arr[$taxId]
                    ]
                );
            }
        }

        $html .= '</div>';


        return $html . LTTmplToVar('templates/short_code/shortcode_styles.tmpl.php', $args, true);

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
