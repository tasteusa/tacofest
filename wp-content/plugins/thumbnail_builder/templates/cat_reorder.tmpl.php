<div class="wrap">
    <h3>Reorder Thumbnail Categories</h3>
    <div class="row">
        <div class="col-md-6">
            <ul class="list-group lgts-categories-list">
                <?php foreach($categories as $category):?>
                    <li class="list-group-item lgts-categories-list-item" data-term_id="<?php echo $category->term_id?>">
                        <label>Name: </label><span> "<?php echo $category->name?>"</span>
                        <label>Slug: </label><span> "<?php echo $category->slug?>"</span>
                        <label>Category Id: </label><span> "<?php echo $category->term_id?>"</span>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="clearfix">
    </div>
</div>
