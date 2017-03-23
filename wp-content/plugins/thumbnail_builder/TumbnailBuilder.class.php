<?php
class TumbnailBuilder {

    public static $metaBoxIds = ['wp_lt_weburl'];
    public static $postType = 'linked_thumbnail';
    public static $PosMetaName = '_thumb_glob_order';
    private static $optName = '_lgts_cat_order';
    public static $categories = [];

    public function __constructor(){

    }


    static function plugin_activation(){
        return true;
    }

    static function plugin_deactivation(){
        return true;
    }


    public function init(){
        $this->checkCatOrderOpt();
        add_action('admin_enqueue_scripts', [$this,'enqueueAdminScripts']);
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
        //add_action('quick_edit_custom_box', [$this,'displayQuickEditInputs'], 10, 2 );
        add_action('manage_linked_thumbnail_posts_custom_column', [$this,'editLinkedThumbnailColumnsData'], 10, 2 );
        add_action('admin_menu',[$this,'registerThumbGeneratorPage']);
        add_action('admin_menu',[$this,'registerCatThumbsPage']);
        add_action('admin_menu',[$this,'registerCatReorderPage']);
        add_action('admin_head', [$this, 'includeTableStyle']);
        add_filter( 'wp_terms_checklist_args', [$this,'termRadioChecklist'] );
    }

    private function checkCatOrderOpt(){

        $optVal = get_option(self::$optName,false);
        $changedVal = ['0'];
        $keyValCategories =[];
        $CategoriesSorted =[];

        if($optVal !== false){
            $optVal = json_decode($optVal,true);
            $changedVal = $optVal;
        }

        $categories = get_categories( [
            'orderby' => 'name',
            'hide_empty' => false,
        ] );

        foreach( $categories as $category ) {
            $keyValCategories[$category->term_id] = $category;
            if(!in_array((string)$category->term_id,$changedVal))$changedVal[]=(string)$category->term_id;
        }

        $changedValNew = $changedVal;
        foreach ($changedVal as $id=>$catId){

            if(!isset($keyValCategories[$catId])){
                if($catId != '0') unset($changedValNew[$id]);
                continue;
            }

            $CategoriesSorted[] = $keyValCategories[$catId];
        }

        if($changedValNew !=  $optVal){
            (!$optVal)?add_option(self::$optName,json_encode($changedValNew)):update_option(self::$optName,json_encode($changedValNew));
        }
        /*echo '<pre>';
        var_dump($optVal);
        var_dump($CategoriesSorted);
        echo '</pre>';exit;*/
        self::$categories = $CategoriesSorted;
    }

    public function enqueueAdminScripts(){
        wp_enqueue_script('jqColorPicker', plugin_dir_url(__FILE__) . 'js/jqColorPicker.min.js', ['jquery']);
        wp_enqueue_style('jqColorPicker.custom', plugin_dir_url(__FILE__) . 'css/jqColorPicker.css');
    }

    function termRadioChecklist( $args ) {
        $screen = get_current_screen();
        if(!isset($screen->post_type) || $screen->post_type != self::$postType)  return $args;
        global $TBPluginDir;
        require_once $TBPluginDir.'TermRadioChecklist.class.php';

        if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'category') {
            if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) {
                $args['walker'] = new TermRadioChecklistClass;
            }
        }

        return $args;
    }

    public function includeTableStyle(){
        if (isset($_GET['post_type']) && $_GET['post_type'] == self::$postType){
            wp_enqueue_style('thumb.table.style', plugin_dir_url(__FILE__) . '/css/table.css');
        }
    }

    public function add_thumnail_metaboxes(){
        add_meta_box('wp_lt_weburl', 'Web Url', [$this,'wp_lt_weburl_view'], self::$postType, 'normal', 'high');
    }

    public function remove_metaboxes(){
        global $wp_meta_boxes;
        if(!isset($wp_meta_boxes[self::$postType])) return;
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

        $categories = self::$categories;

        $redirectUrl = get_admin_url( null, '/edit.php?post_type=linked_thumbnail');
        $autocompleteCat = [];
        foreach( $categories as $category ) {
            $autocompleteCat[] = ['label'=> $category->name, 'value'=>$category->term_id];
        }

        wp_enqueue_style('bootstrap.min', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
        wp_enqueue_style('ltgs.thumb.generator', plugin_dir_url(__FILE__) . '/css/generator.css');

        wp_enqueue_script('jquery.tmpl', plugin_dir_url(__FILE__) . '/js/jquery.tmpl.js', ['jquery']);
        $this->includeJqueryUi();
        wp_enqueue_media();
        wp_enqueue_script('ltgs.thumb.generator', plugin_dir_url(__FILE__) . '/js/generator.js', ['jquery','jquery.tmpl']);

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

        $categories = self::$categories;

        $autocompleteCat = [];
        foreach( $categories as $category ) {
            $autocompleteCat[$category->term_id] = $category->name;
        }

        wp_enqueue_style('bootstrap.min', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
        wp_enqueue_style('ltgs.thumb.reorder', plugin_dir_url(__FILE__) . '/css/thumbs_reorder.css');

        wp_enqueue_script('jquery.tmpl', plugin_dir_url(__FILE__) . '/js/jquery.tmpl.js', ['jquery']);
        $this->includeJqueryUi();
        wp_enqueue_media();
        wp_enqueue_script('ltgs.thumb.reorder', plugin_dir_url(__FILE__) . '/js/reorder.js', ['jquery','jquery.tmpl']);

        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/jq_tmpl/jq.thumb_reorder.tmpl.php';
        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/cat_thumbs.tmpl.php';
    }


    public function registerCatReorderPage() {
        add_submenu_page(
            "edit.php?post_type=".self::$postType,
            __( 'Categories Reorder'),
            __( 'Categories Reorder'),
            'manage_options',
            'thumbnail-cat-reorder',
            [$this, 'catReorderView']
        );
    }

    public function catReorderView() {

        $categories = self::$categories;

        wp_enqueue_style('bootstrap.min', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
        wp_enqueue_style('ltgs.cat.reorder', plugin_dir_url(__FILE__) . '/css/cat_reorder.css');

        wp_enqueue_script('jquery.tmpl', plugin_dir_url(__FILE__) . '/js/jquery.tmpl.js', ['jquery']);
        $this->includeJqueryUi();
        wp_enqueue_media();
        wp_enqueue_script('thumb.reorder', plugin_dir_url(__FILE__) . '/js/cat_reorder.js', ['jquery','jquery.tmpl']);

        require_once __DIR__.DIRECTORY_SEPARATOR.'templates/cat_reorder.tmpl.php';
    }
}