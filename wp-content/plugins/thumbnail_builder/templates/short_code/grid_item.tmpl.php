<div class="<?php echo $args['gridClasses']?> ltg_item recent-posts-content">
    <div class="thumbnail">
        <a href="<?php echo $args['url']; ?>">
            <img src="<?php echo $args['imageUrl']; ?>">
        </a>
        <?php if(isset($args['title'])): ?>
        <div class="caption">
            <h4 class="entry-title">
                <a href="<?php echo $args['url']; ?>"><?php echo $args['title']; ?></a>
            </h4>
        </div>
        <?php endif; ?>
    </div>
</div>