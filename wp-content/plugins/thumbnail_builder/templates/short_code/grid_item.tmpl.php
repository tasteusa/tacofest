<div class="<?php echo $args['gridClasses']?> ltg_item recent-posts-content">
    <div class="thumbnail">
        <div class="ltgs_image_container">
            <a href="<?php echo $args['url']; ?>">
                <img class="ltgs_image" src="<?php echo $args['imageUrl']; ?>">
            </a>
        </div>
        <?php if(isset($args['title'])): ?>
        <div class="caption">
            <h4 class="entry-title lgts_thumb_title">
                <a href="<?php echo $args['url']; ?>"><?php echo $args['title']; ?></a>
            </h4>
            <p><?php echo $args['text']; ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>