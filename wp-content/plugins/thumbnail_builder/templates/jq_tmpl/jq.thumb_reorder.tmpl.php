<script id="thumbReorderTemplate" type="text/x-jquery-tmpl">
    <div class="col-sm-6 col-md-4 col-lg-3 single-linked-thumb">
        <div class="thumbnail">
            <img class='thumb-img' src="${img}" />
            <input type="hidden" class="hiddden-thumb-id" value="${id}"/>
            <input type="hidden" class="hiddden-img-id" value=""/>
            <div class="caption">
                <form>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="${title}" name="title" class="form-control form-control-sm title-field" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" name="url" class="form-control form-control-sm url-field" placeholder="Url" value="${url}">
                    </div>
                    <div class="form-group">
                        <label>Text</label>
                        <input type="text" name="thumbText" class="form-control form-control-sm text-field" placeholder="Additional text" value="${text}">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="Category" class="form-control form-control-sm category-field" value="${taxId}">
                            <option {%if taxId==0 %}selected{%/if%} value="${catId}">Without category</option>
                            {%each(catId, catName) ThumbCategories%}
                                <option {%if taxId==catId %}selected{%/if%} value="${catId}">${catName}</option>
                            {%/each%}
                        </select>
                    </div>
                </form>
                <div>
                    <a class="btn btn-danger pull-left delete-thumb">remove</a>
                    <a class="btn btn-success disabled pull-right save-thumb">Save</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</script>