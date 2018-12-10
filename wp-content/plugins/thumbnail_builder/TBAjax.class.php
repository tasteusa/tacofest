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
        add_action( 'wp_ajax_tb_delete_thumb', [$this, 'deleteThumbnails'] );
        add_action( 'wp_ajax_tb_reorder_thumbs', [$this, 'reorderThumbsAjax'] );
        add_action( 'wp_ajax_tb_get_thumbs_in_cat', [$this, 'getThumbsInCategoryAjax'] );
        add_action( 'wp_ajax_tb_get_cat_by_page', [$this, 'getCatByPage'] );
        add_action( 'wp_ajax_tb_edit_thumb', [$this, 'editThumbnailAjax'] );
        add_action( 'wp_ajax_tb_reorder_cat', [$this, 'reorderCatAjax'] );
    }

    public function reorderCatAjax(){
        $orderArray = $_POST['orderArray'];
        $tmpOptName = (isset($_POST['pageId']) && !empty($_POST['pageId'])) ? self::$optName.'_'.$_POST['pageId'] : self::$optName;
        $orderJson =json_encode($orderArray);
        $optVal = get_option($tmpOptName,false);
        if($optVal != $orderJson) update_option($tmpOptName, $orderJson);
    }

    public function editThumbnailAjax(){
        global $TBuilderClass;

        $postId = (isset($_POST['thumbId']))?$_POST['thumbId']:null;
        $postTitle = (isset($_POST['thumbTitle']) && trim($_POST['thumbTitle'])!="")?trim($_POST['thumbTitle']):null;
        $postUrl = (isset($_POST['thumbUrl']) && trim($_POST['thumbUrl'])!="")?trim($_POST['thumbUrl']):'';
        $postText = (isset($_POST['thumbText']) && trim($_POST['thumbText'])!="")?trim($_POST['thumbText']):'';
        $postImage = (isset($_POST['thumbImg']))?trim($_POST['thumbImg']):null;
        $postCategory = (isset($_POST['thumbCat']) && trim($_POST['thumbCat']) != 0)?[$_POST['thumbCat']]:[];

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
        $TBuilderClass->updateMeta($postId, '_add_text', $postText);
        wp_set_post_categories( $postId, $postCategory, false);
        if($postImage != null) set_post_thumbnail( $postId, $postImage );

        echo json_encode(['message'=>'Thumbnail updated', 'type'=>'success']);
        wp_die();
    }

    public function getCatByPage(){
        $pageId = (isset($_POST['pageId']) && !empty($_POST['pageId'])) ? $_POST['pageId'] : null;
        global $TBuilderClass;
        $categories = $TBuilderClass->getCatOrderByPage($pageId);
        $result = [];

        if(!$categories){
            echo json_encode(['type'=>'error', 'message'=>'Thumbnail updated']);
            wp_die();
        }

        $resKeys = ['term_id', 'name','slug'];
        foreach ($categories as $category){
            $result[] = array_intersect_key((array)$category, array_flip($resKeys));
        }

        echo json_encode(['type'=>'success', 'categories' => $result]);
        wp_die();
    }

    public function reorderThumbsAjax(){
        $this->reorderThumb();
    }

    public function reorderThumb(){

        $items = $_POST['items'];
        $category = $_POST['category'];

        if(isset($items) && is_array($items) && isset($category)){
            update_term_meta($category, 'category_order',json_encode($items));
        }

        echo json_encode($items);wp_die();
    }

    public function createThumbnails(){
        global $TBuilderClass;
        global $user_ID;

        $postIds=[];
        $pos = $TBuilderClass->getMaxPosition();
        if($pos == null)$pos=1;
        foreach ($_POST['thumbs'] as $thumb){
            $newPost = array(
                'post_title' => $thumb['title'],
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => self::$postType,
                'post_category' => [$thumb['tax']]
            );
            $postId = wp_insert_post($newPost);
            set_post_thumbnail( $postId, $thumb['attach_id'] );

            $url = $thumb['url'];
            $postText = (isset($thumb['text']) && trim($thumb['text'])!="")?trim($thumb['text']):'';

            if($url != '' && strpos($url,"http://") === false && strpos($url,"https://") === false) $url = 'http://'.$url;
            $TBuilderClass->updateMeta($postId, '_web_link', $url);
            $TBuilderClass->updateMeta($postId, '_add_text', $postText);
            $pos +=1;
            $TBuilderClass->updateMeta($postId, self::$PosMetaName, $pos);
            $postIds[] = $postId;
        }

        echo json_encode($postIds);
        wp_die();
    }

    public function deleteThumbnails(){
        global $TBuilderClass;

        $postId = (isset($_POST['thumbId']))?$_POST['thumbId']:null;
        wp_delete_post( $postId, true );
        wp_die();
    }


    public function getThumbsInCategoryAjax(){
        global $wpdb;
        $tax = $_POST['tax'];
        $order = $_POST['order'];

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

        if($order == 'a-z'){
            usort($thumbs, array($this,'compareByName'));
            $items = array_map(create_function('$thumb', 'return $thumb->ID;'), $thumbs);
            update_term_meta($tax, 'category_order',json_encode($items));
        }else {
            $category_order = json_decode(get_term_meta($tax,'category_order')[0]);
            if(isset($category_order) && is_array($category_order)){
                $sorted_posts = [];
                foreach($category_order as $id) {
                    foreach($thumbs as $key=>$thumb){
                        if($thumb->ID == $id){
                            $sorted_posts[] = $thumb;
                            unset($thumbs[$key]);
                        }
                    }
                }
                $thumbs =  array_merge($sorted_posts,$thumbs);
            }
        }

        foreach($thumbs as $thumb){
            $results[] = [
                'id'=> $thumb->ID,
                'img'=> get_the_post_thumbnail_url($thumb->ID),
                'title'=> $thumb->post_title,
                'url'=> get_post_meta($thumb->ID, '_web_link', true),
                'text'=> get_post_meta($thumb->ID, '_add_text', true),
                'taxId'=>$_POST['tax']
            ];
        }

        echo json_encode(['thumbs'=>$results]); wp_die();
    }
    function compareByName($a, $b) {
        return strcmp($a->post_title, $b->post_title);
    }
}