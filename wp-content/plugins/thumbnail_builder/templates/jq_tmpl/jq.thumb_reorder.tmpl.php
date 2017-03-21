<script id="thumbTemplate" type="text/x-jquery-tmpl">
    <div class="col-sm-6 col-md-4 col-lg-3 single-linked-thumb">
        <div class="thumbnail">
            <img src="${img}" />
            <input type="hidden" class="hiddden-thumb-id" value="${id}"/>
            <div class="caption">
                <form>
                    <div class="form-group">
                        <label>Title: </label> <span>${title}</span>
                    </div>
                    <div class="form-group">
                        <label>Url: </label> <span>${url}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>