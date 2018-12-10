<div class="row category-container  center-block">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 class="lgts_category_title"><?php echo $args['catName']['name']?></h2>
    </div>
    <?php if (isset($args['catName']['description']) && $args['catName']['description']!=''):?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 category_description">
        <p><?php echo $args['catName']['description']?></p>
    </div>
    <?php endif;?>
    <div class="thumbnails-cont center-block">
        <?php echo $args['content']?>
    </div>

</div>