<script>
    var ThumbCategories=<?php echo json_encode($autocompleteCat); ?>;
    var RedirectUrl="<?php echo $redirectUrl ?>";

    var generatorBlock = '#generator';
    var reorderBlock = '#reorder';
    var reorderCatBlock = '#reorder-cat';
    var winnerBlock = '#winners'

</script>
<div class="wrap">
    <h3>Thumbnails Manager</h3>

    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#generator" role="tab" data-toggle="tab">Thumbnail Generator</a></li>
        <li><a href="#reorder" role="tab" data-toggle="tab">Reorder/Edit Thumbnails</a></li>
        <li><a href="#reorder-cat" role="tab" data-toggle="tab">Reorder Categories</a></li>
        <li><a href="#winners" role="tab" data-toggle="tab">Winners</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">

        <div role="tabpanel" class="tab-pane tab-pa active" id="generator">
            <h4>Create Thumbnails in one click</h4>
            <div class="row thumbs-container">

            </div>
            <div class="buttons-container">
                <a class="btn btn-primary select-img-btn">Select Images</a>
                <a class="btn btn-success submit-thumb-btn hidden">Submit Thumbnails</a>
            </div>
        </div>


        <div role="tabpanel" class="tab-pane" id="reorder">
            <h4>Reorder/Edit Thumbnails in Category</h4>
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

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="form-inline">
                            <div class="form-group">
                                <label for="custom">Custom</label>
                                <input id="custom" type="radio" name="sort_thumbs" value="custom" checked="checked">
                            </div>
                            <div class="form-group">&nbsp;</div>
                            <div class="form-group">
                                <label for="az">A-Z</label>
                                <input type="radio" id="az" name="sort_thumbs" value="asc">
                            </div>
                            <div class="form-group">&nbsp;</div>
                            <div class="form-group">
                                <label for="za">A-Z</label>
                                <input type="radio" id="za" name="sort_thumbs" value="desc">
                            </div>
                            <div class="form-group">&nbsp;</div>
                            <a class="btn btn-primary btn-sm sort-thumbs-a-z disabled" >Sort A-Z</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row thumbs-container"></div>
        </div>


        <div role="tabpanel" class="tab-pane" id="reorder-cat">
            <h4>Reorder Thumbnail Categories</h4>

            <div class="row">
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <select type="text" class="form-control pages-select" placeholder="Search">
                            <option value="0">General</option>
                            <?php foreach($pagesList as $page): ?>
                                <option value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <a class="btn btn-primary btn-sm load-cats">load</a>
                </form>
                <div class="clearfix"></div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group lgts-categories-list">
                        <?php foreach($categories as $category):?>
                            <li class="list-group-item lgts-categories-list-item" data-term_id="<?php echo $category->term_id?>">
                                <label>Name: </label><span> "<?php echo $category->name?>"</span>
                                <label>Slug: </label><span> "<?php echo $category->slug?>"</span>
                                <label>Category Id: </label><span> "<?php echo $category->term_id?>"</span>
                                <a class="pull-right view-thumbnails-link" data-cat-id="<?php echo $category->term_id?>">View Thumbnails</a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="clearfix"></div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="winners">
            <h4>Winners in Categories</h4>
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


            <div class="row thumbs-container"></div>
        </div>

</div>
