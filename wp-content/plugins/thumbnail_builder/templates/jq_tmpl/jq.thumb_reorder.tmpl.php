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
                    <div class="form-group winner-select">
                        <p>
                            <label>
                                <input type="checkbox" value="1"
                                name="winner"
                                {%if is_winner == 'true' %}checked{%/if%}
                                class="is-winner form-control"> Mark as winner
                            </label>
                        </p>
                    </div>
                    <div class="form-group winner-place-select {%if is_winner != 'true' %}hidden{%/if%}">
                        <p class="set-winner-tip">You should finish setting winner thumbnail in the Winner tab</p>
                        <select name="place" class="place-select form-control" placeholder="Choose Place">
                            <option selected value="0">Choose Place</option>
                            <option {%if winner_place==1 %}selected{%/if%} value="1">1st Place Winner</option>
                            <option {%if winner_place==2 %}selected{%/if%} value="2">2nd Place Winner</option>
                            <option {%if winner_place==3 %}selected{%/if%} value="3">3rd Place Winner</option>
                        </select>
                        <p class="require-error text-danger"></p>
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