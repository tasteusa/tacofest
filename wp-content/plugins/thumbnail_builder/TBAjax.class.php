<?php

class TBAjax {

    public static $postType = 'linked_thumbnail';
    public static $PosMetaName = '_thumb_glob_order';
    private static $optName = '_lgts_cat_order';

    public function __constructor(){

    }

    public function init(){
        $this->registerAjax();
    }

    public function registerAjax(){
        add_action( 'wp_ajax_tb_create_thumbs', [$this, 'createThumbnails'] );
        add_action( 'wp_ajax_tb_reorder_thumbs', [$this, 'reorderThumbsAjax'] );
        add_action( 'wp_ajax_tb_get_thumbs_in_cat', [$this, 'getThumbsInCategoryAjax'] );
        add_action( 'wp_ajax_tb_edit_thumb', [$this, 'editThumbnailAjax'] );
        add_action( 'wp_ajax_tb_reorder_cat', [$this, 'reorderCatAjax'] );
    }

    public function reorderCatAjax(){
        $orderArray = $_POST['orderArray'];
        $orderJson =json_encode($orderArray);
        $optVal = get_option(self::$optName,false);
        if($optVal != $orderJson) update_option(self::$optName, $orderJson);
    }

    public function editThumbnailAjax(){
        global $TBuilderClass;

        $postId = (isset($_POST['thumbId']))?esc_sql($_POST['thumbId']):null;
        $postTitle = (isset($_POST['thumbTitle']) && trim($_POST['thumbTitle'])!="")?esc_sql(trim($_POST['thumbTitle'])):null;
        $postUrl = (isset($_POST['thumbUrl']) && trim($_POST['thumbUrl'])!="")?esc_sql(trim($_POST['thumbUrl'])):'';
        $postImage = (isset($_POST['thumbImg']))?esc_sql(trim($_POST['thumbImg'])):null;
        $postCategory = (isset($_POST['thumbCat']) && trim($_POST['thumbCat']) != 0)?[esc_sql($_POST['thumbCat'])]:[];

        if(!isset($postId) || get_post_type($postId) != self::$postType){
            echo json_encode(['message'=>'Thumbnail not found', 'type'=>'error']);
            wp_die();
        }else if(!isset($postTitle)){
            echo json_encode(['message'=>'Invalid Data', 'type'=>'error']);
            wp_die();
        }

        wp_update_post([
            'ID'           => $postId,
            'post_title'   => $postTitle,
        ]);

        $TBuilderClass->updateMeta($postId, '_web_link', $postUrl);
        wp_set_post_categories( $postId, $postCategory, false);
        if($postImage != null) set_post_thumbnail( $postId, $postImage );

        echo json_encode(['message'=>'Thumbnail updated', 'type'=>'success']);
        wp_die();
    }

    public function reorderThumbsAjax(){
        $this->reorderThumb($_POST['targetPostId'],$_POST['supportPostId'],$_POST['put']);
    }

    public function reorderThumb($targetPostId, $supportPostId, $put = 'after'){
        global $wpdb;
        global $TBuilderClass;

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
        $TBuilderClass->updateMeta($targetPostId, $key, $targetPos);
        $changed[$targetPostId] = $currentPos.' -> '.$targetPos.' (main)';
        $newPos = $currentPos;
        foreach($posts as $post){
            $changed[$post->post_id] = $post->pos.' -> '.$currentPos;
            $TBuilderClass->updateMeta($post->post_id, $key, $currentPos);
            $currentPos = $post->pos;
        }
        echo json_encode($changed);wp_die();
    }

    public function createThumbnails(){
        global $TBuilderClass;
        global $user_ID;

        $postIds=[];
        $pos = $TBuilderClass->getMaxPosition();
        if($pos == null)$pos=1;
        foreach ($_POST['thumbs'] as $thumb){
            $newPost = array(
                'post_title' => esc_sql($thumb['title']),
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => self::$postType,
                'post_category' => [esc_sql($thumb['tax'])]
            );
            $postId = wp_insert_post($newPost);
            set_post_thumbnail( $postId, esc_sql($thumb['attach_id']) );

            $url = esc_sql($thumb['url']);

            if($url != '' && strpos($url,"http://") === false && strpos($url,"https://") === false) $url = 'http://'.$url;
            $TBuilderClass->updateMeta($postId, '_web_link', $url);
            $pos +=1;
            $TBuilderClass->updateMeta($postId, self::$PosMetaName, $pos);
            $postIds[] = $postId;
        }

        echo json_encode($postIds);
        wp_die();
    }


    public function getThumbsInCategoryAjax(){
        global $wpdb;
        $tax = $_POST['tax'];

        $args = array(
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'post_type' => self::$postType,
            'meta_key' => self::$PosMetaName,
            'orderby' => ['meta_value_num'=>'ASC','post_title'=>'ASC'],
        );

        if($tax == 0){
            $args['category__not_in'] = get_terms('category', [
                'fields'        => 'ids'
            ]);
        }else{
            $args['cat']=$tax;
        }

        $thumbs = get_posts( $args );
        $results=[];

        foreach($thumbs as $thumb){
            $results[] = [
                'id'=> $thumb->ID,
                'img'=> get_the_post_thumbnail_url($thumb->ID),
                'title'=> $thumb->post_title,
                'url'=> get_post_meta($thumb->ID, '_web_link', true),
                'taxId'=>$_POST['tax']
            ];
        }

        echo json_encode(['thumbs'=>$results]); wp_die();
    }
}