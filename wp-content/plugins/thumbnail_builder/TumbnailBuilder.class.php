<?php
class TumbnailBuilder {

    public static $metaBoxIds = ['wp_lt_weburl'];
    public static $postType = 'linked_thumbnail';
    public static $PosMetaName = '_thumb_glob_order';

    public function __constructor(){

    }


    static function plugin_activation(){
        return true;
    }

    static function plugin_deactivation(){
        return true;
    }


    public function init(){
        register_post_type( self::$postType,
            array(
                'labels' => array(
                    'name' => __('Linked Thumbnails'),
                    'singular_name' => __('Linked Thumbnail'),
                    'add_new' => __('Add New'),
                    'add_new_item' => __('Add New Linked Thumbnail'),
                    'edit' => __('Edit'),
                    'edit_item' => __('Edit Linked Thumbnail'),
                    'new_item' => __('New Linked Thumbnail'),
                    'view' => __('View'),
                    'view_item' => __('View Linked Thumbnail'),
                    'search_items' => __('Search Linked Thumbnail'),
                    'not_found' => __('No Linked Thumbnails found'),
                    'not_found_in_trash' => __('No Linked Thumbnails found in Trash'),
                    'parent' => __('Parent Linked Thumbnail')
                ),

                'public' => true,
                'supports' => array( 'title', 'thumbnail' ),
                'menu_icon'           => 'dashicons-images-alt',
                'has_archive' => true,
                'menu_position' => 5,
                'register_meta_box_cb' => [$this,'add_thumnail_metaboxes'],
                'taxonomies'=> ['category']

            )
        );

        add_filter('manage_edit-linked_thumbnail_columns', [$this,'editLinkedThumbnailColumns'] ) ;
        add_action('save_post', [$this,'save_thumbnail_metabox'], 1, 2);
        add_action('edit_form_after_title', [$this,'remove_metaboxes'], 100 );
        add_action('quick_edit_custom_box', [$this,'displayQuickEditInputs'], 10, 2 );
        add_action('manage_linked_thumbnail_posts_custom_column', [$this,'editLinkedThumbnailColumnsData'], 10, 2 );
        add_action('admin_menu',[$this,'registerThumbGeneratorPage']);
        add_action('admin_menu',[$this,'registerCatThumbsPage']);
        add_action('admin_head', [$this, 'includeTableStyle']);


        $this->registerAjax();
    }

    public function registerAjax(){
        add_action( 'wp_ajax_create_thumbs', [$this, 'createThumbnails'] );
        add_action( 'wp_ajax_reorder_thumbs', [$this, 'reorderThumbsAjax'] );
        add_action( 'wp_ajax_get_thumbs_in_cat', [$this, 'getThumbsInCategoryAjax'] );
    }

    public function includeTableStyle(){
        if (isset($_GET['post_type']) && $_GET['post_type'] == self::$postType){
            wp_enqueue_style('thumb.table.style', plugin_dir_url(__FILE__) . '/css/table.css');
        }
    }

    public function add_thumnail_metaboxes(){
        add_meta_box('wp_lt_weburl', 'Web Url', [$this,'wp_lt_weburl_view'], self::$postType, 'normal', 'high');
    }

    public function createThumbnails(){
        global $user_ID;
        $postIds=[];
        $pos = $this->getMaxPosition();
        if($pos == null)$pos=1;
        foreach ($_POST['thumbs'] as $thumb){
            $newPost = array(
                'post_title' => $thumb['title'],
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => self::$postType,
                'post_category' => array($thumb['tax'])
            );
            $postId = wp_insert_post($newPost);
            set_post_thumbnail( $postId, $thumb['attach_id'] );

            $url = $thumb['url'];

            if($url != '' && strpos($url,"http://") === false && strpos($url,"https://") === false) $url = 'http://'.$url;
            $this->updateMeta($postId, '_web_link', $url);
            $pos +=1;
            $this->updateMeta($postId, self::$PosMetaName, $pos);
            $postIds[] = $postId;
        }

        echo json_encode($postIds);
        wp_die();
    }

    public function remove_metaboxes(){
        global $wp_meta_boxes;
        $metaBoxes = $wp_meta_boxes[self::$postType]['normal'];

        foreach ( $metaBoxes as $lvl=>$metaArray){
            $tmpMeta = [];
            foreach($metaArray as $id=>$metaBox){
                if(in_array($id,self::$metaBoxIds))$tmpMeta[$id]=$metaBox;
            }
            $wp_meta_boxes[self::$postType]['normal'][$lvl] = $tmpMeta;
        }

    }

    public function editLinkedThumbnailColumnsData($column, $post_id){
        global $post;

        switch( $column ) {

            case 'url' :
                $url = get_post_meta($post->ID, '_web_link', true);
                if (empty($url)){
                    echo '-';
                }else{
                    echo _($url);
                }
            break;
            case 'img' :
                echo get_the_post_thumbnail( $post_id, array( 60, 60) );
            break;
        }
    }

    public function editLinkedThumbnailColumns(){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'img' => __('Image'),
            'title' => __( 'Title' ),
            'url' => __('Url'),
            'categories' => __( 'Categories' ),
            'date' => __( 'Date' )
        );

        return $columns;
    }

    public function displayQuickEditInputs($column_name, $postType ){
        if($postType !== self::$postType) return;
        global $post;
        $location = get_post_meta($post->ID, '_web_link', true);
        $nonce = wp_create_nonce( plugin_basename(__FILE__) );
        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/quick_edit_fields.tmpl.php';
    }

    public function wp_lt_weburl_view(){
        global $post;
        echo '<input type="hidden" name="LTmeta_noncename" id="LTmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
        $location = get_post_meta($post->ID, '_web_link', true);
        echo '<input type="text" name="_web_link" value="' . $location  . '" class="widefat" />';
    }

    public function save_thumbnail_metabox($postId, $post){
        if (!isset($_POST['LTmeta_noncename']) || !wp_verify_nonce( $_POST['LTmeta_noncename'], plugin_basename(__FILE__) )) {
            return $post->ID;
        }

        if ( !current_user_can( 'edit_post', $post->ID )) return $post->ID;

        $key = '_web_link';
        $value =  isset($_POST[$key])?$_POST[$key]:'';

        if(strpos($value,"http://") === false && strpos($value,"https://") === false) $value = 'http://'.$value;
        if( $post->post_type == 'revision' ) return $post->ID;

        $this->updateMeta($post->ID, $key, $value);
        $postPos = get_post_meta($post->ID, self::$PosMetaName, true);

        if(!empty($postPos)) return $post->ID;

        $pos = $this->getMaxPosition();
        $this->updateMeta($post->ID, self::$PosMetaName, $pos+1);

        return $post->ID;
    }

    public function updateMeta($postId, $key, $value){
        $value = implode(',', (array)$value);
        if(!isset($value) || $value === false) delete_post_meta($postId, $key);
        if(get_post_meta($postId, $key, FALSE)) {
            update_post_meta($postId, $key, $value);
        } else {
            add_post_meta($postId, $key, $value);
        }
    }

    public function reorderThumbsAjax(){
        $this->reorderThumb($_POST['targetPostId'],$_POST['supportPostId'],$_POST['put']);
    }

    public function getThumbsInCategoryAjax(){
        global $wpdb;
        $tax = $_POST['tax'];

        $args = array(
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'post_type' => self::$postType,
            'meta_key' => self::$PosMetaName,
            'cat' => $tax,
            'orderby' => ['meta_value_num'=>'ASC','post_title'=>'ASC'],
        );

        $thumbs = get_posts( $args );
        $results=[];

        foreach($thumbs as $thumb){
            $results[] = [
                'id'=> $thumb->ID,
                'img'=> get_the_post_thumbnail_url($thumb->ID),
                'title'=> $thumb->post_title,
                'url'=> get_post_meta($thumb->ID, '_web_link', true),
                'pos'=>get_post_meta($thumb->ID, self::$PosMetaName, true)
            ];
        }

        echo json_encode(['thumbs'=>$results]); wp_die();
    }

    public function reorderThumb($targetPostId, $supportPostId, $put = 'after'){
        global $wpdb;
        $key = self::$PosMetaName;
        $postType = self::$postType;

        $currentPos = get_post_meta($targetPostId, self::$PosMetaName, true);
        $targetPos = ($supportPostId==0)?1:get_post_meta($supportPostId, self::$PosMetaName, true);
        $currentPos = (empty($currentPos))?1:(int)$currentPos;

        $query = "SELECT {$wpdb->posts}.ID as post_id, {$wpdb->postmeta}.meta_value as pos
                  FROM {$wpdb->postmeta}
                  LEFT JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID          
                  WHERE {$wpdb->postmeta}.meta_key='$key' 
                  AND {$wpdb->posts}.post_type='$postType' 
                  AND {$wpdb->posts}.ID != $targetPostId
                  ";

        if($targetPos < $currentPos ){
            if($put == 'after')$targetPos = $targetPos+1;
            if($put == 'before')$targetPos = ($targetPos>1)?$targetPos-1:1;
            $query.="AND {$wpdb->postmeta}.meta_value >= $targetPos AND {$wpdb->postmeta}.meta_value < $currentPos ORDER BY {$wpdb->postmeta}.meta_value DESC";
        }else{
            $query.="AND {$wpdb->postmeta}.meta_value <= $targetPos AND {$wpdb->postmeta}.meta_value > $currentPos ORDER BY {$wpdb->postmeta}.meta_value ASC";
        }
        $query.= "";
        $posts = $wpdb->get_results($query);
        $changed = [];
        $this->updateMeta($targetPostId, $key, $targetPos);
        $changed[$targetPostId] = $currentPos.' -> '.$targetPos.' (main)';
        $newPos = $currentPos;
        foreach($posts as $post){
            $changed[$post->post_id] = $post->pos.' -> '.$currentPos;
            $this->updateMeta($post->post_id, $key, $currentPos);
            $currentPos = $post->pos;
        }
        echo json_encode($changed);wp_die();
    }

    public function getMaxPosition(){
        global $wpdb;
        $key = self::$PosMetaName;
        $postType = self::$postType;

        $query = "SELECT max(cast({$wpdb->postmeta}.meta_value as unsigned)) as maxPos
                  FROM {$wpdb->postmeta}
                  LEFT JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID          
                  WHERE {$wpdb->postmeta}.meta_key='$key' 
                  AND {$wpdb->posts}.post_type='$postType'";
        $pos = $wpdb->get_results($query);
        return (isset($pos[0]->maxPos))?$pos[0]->maxPos:0;
    }

    function includeJqueryUi() {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-resize');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-button');
    }

    public function registerThumbGeneratorPage() {
        add_submenu_page(
            "edit.php?post_type=".self::$postType,
            __( 'Thumbnail Generator'),
            __( 'Thumbnail Generator'),
            'manage_options',
            'thumbnail-generator',
            [$this, 'thumbGeneratorView']
        );
    }

    function thumbGeneratorView() {

        $categories = get_categories( array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'hide_empty' => false,
        ) );

        $redirectUrl = get_admin_url( null, '/edit.php?post_type=linked_thumbnail');
        $autocompleteCat = [];
        foreach( $categories as $category ) {
            $autocompleteCat[] = ['label'=> $category->name, 'value'=>$category->term_id];
        }

        wp_enqueue_style('bootstrap.min', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
        wp_enqueue_style('thumb.generator', plugin_dir_url(__FILE__) . '/css/generator.css');

        wp_enqueue_script('jquery.tmpl', plugin_dir_url(__FILE__) . '/js/jquery.tmpl.js', ['jquery']);
        $this->includeJqueryUi();
        wp_enqueue_media();
        wp_enqueue_script('thumb.generator', plugin_dir_url(__FILE__) . '/js/generator.js', ['jquery','jquery.tmpl']);

        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/jq_tmpl/jq.thumb.tmpl.php';
        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/thumb_generator.tmpl.php';
    }

    public function registerCatThumbsPage() {
        add_submenu_page(
            "edit.php?post_type=".self::$postType,
            __( 'Thumbnails Reorder'),
            __( 'Thumbnails Reorder'),
            'manage_options',
            'thumbnail-reorder',
            [$this, 'catThumbsView']
        );
    }

    public function catThumbsView() {

        $categories = get_categories( array(
            'orderby' => 'name',
            'order'   => 'ASC'
        ) );

        $autocompleteCat = [];
        foreach( $categories as $category ) {
            $autocompleteCat[$category->term_id] = $category->name;
        }

        wp_enqueue_style('bootstrap.min', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
        wp_enqueue_style('thumb.reorder', plugin_dir_url(__FILE__) . '/css/thumbs_reorder.css');

        wp_enqueue_script('jquery.tmpl', plugin_dir_url(__FILE__) . '/js/jquery.tmpl.js', ['jquery']);
        $this->includeJqueryUi();
        wp_enqueue_media();
        wp_enqueue_script('thumb.reorder', plugin_dir_url(__FILE__) . '/js/reorder.js', ['jquery','jquery.tmpl']);

        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/jq_tmpl/jq.thumb_reorder.tmpl.php';
        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/cat_thumbs.tmpl.php';
    }
}