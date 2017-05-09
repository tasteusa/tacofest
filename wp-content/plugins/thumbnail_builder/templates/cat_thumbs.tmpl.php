<script>
    var ThumbCategories=<?php echo json_encode($autocompleteCat); ?>;
</script>
<div class="wrap">
    <h3>Reorder Thumbnails in Category</h3>
    <div class="form-container row">
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <select type="text" class="form-control category-select" placeholder="Search">
                    <option value="0">Without category</option>
                    <?php foreach($autocompleteCat as $id=>$name): ?>
                        <option value="<?php echo $id ?>"><?php echo $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <a class="btn btn-primary btn-sm load-thumbs">load thumbnails</a>
        </form>
        <div class="clearfix"></div>
    </div>
    <div class="form-container row">
        <div class="navbar-form navbar-left">
            <a class="btn btn-primary btn-sm sort-thumbs-a-z disabled" >Sort A-Z</a>
        </div>
    </div>
    <div class="row thumbs-container">

    </div>
</div>
