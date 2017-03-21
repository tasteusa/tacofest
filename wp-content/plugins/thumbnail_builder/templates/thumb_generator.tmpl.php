<script>
    var ThumbCategories=<?php echo json_encode($autocompleteCat); ?>;
    var RedirectUrl="<?php echo $redirectUrl ?>";
</script>
<div class="wrap">
    <h3>Thumbnail Generator</h3>
    <h4>Create Thumbnails in one click</h4>
    <div class="row thumbs-container">

    </div>
    <div class="buttons-container">
        <a class="btn btn-primary select-img-btn">Select Images</a>
        <a class="btn btn-success submit-thumb-btn hidden">Submit Thumbnails</a>
    </div>
</div>
