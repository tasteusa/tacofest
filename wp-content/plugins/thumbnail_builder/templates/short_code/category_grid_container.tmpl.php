<div class="row category-container  center-block">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 class="lgts_category_title"><?php echo $args['catName']['name']?></h2>
    </div>
    <?php if (isset($args['catName']['description']) && $args['catName']['description']!=''):?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 category_description">
        <p><?php echo $args['catName']['description']?></p>
    </div>
    <?php endif;?>
    <?php if (isset($args['winner']) && $args['winner']!=''):?>
        <div class="vc_row wpb_row pb-15">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="center-block py-15">
                    <h3 class="text-center"><?php echo date("Y",strtotime("-1 year"));?> Best <?php echo $args['catName']['name']?></h3>
                </div>
            </div>
        </div>
        <div class="thumbnails-cont center-block py-15">
            <div class="vc_row wpb_row thumbnails-row thumbnails-winners-row">
                <?php echo $args['winner']?>
            </div>
        </div>
    <?php endif;?>
    <div class="thumbnails-cont center-block">
        <?php echo $args['content']?>
    </div>

</div>