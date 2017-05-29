<script id="thumbTemplate" type="text/x-jquery-tmpl">
    <div class="col-sm-6 col-md-4 col-lg-3 single-linked-thumb">
        <div class="thumbnail">
            <img src="${img_url}" />
            <input type="hidden" class="hiddden-img-id" value="${img_id}"/>
            <div class="caption">
                <form>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="${title}" name="title" class="form-control form-control-sm title-field" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" name="url" class="form-control form-control-sm url-field" placeholder="Url">
                    </div>
                    <div class="form-group">
                        <label>Additional Text</label>
                        <input type="text" name="text" class="form-control form-control-sm text-field" placeholder="Additional Text">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="Category" class="form-control form-control-sm category-field" data-value="0" placeholder="Category">
                    </div>
                </form>
                <div>
                    <a class="btn btn-danger pull-right delete-thumb">remove</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</script>