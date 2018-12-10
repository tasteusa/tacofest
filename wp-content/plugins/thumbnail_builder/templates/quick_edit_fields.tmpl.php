<fieldset class="inline-edit-col-right inline-edit-book">
    <div class="inline-edit-col column-<?php echo $column_name; ?>">
        <label class="">
            <input type="hidden" name="LTmeta_noncename" id="LTmeta_noncename" value="<?php echo $nonce ?>" />

            <span class="title">Url</span>

            <span class="input-text-wrap">
                <input type="text" name="_web_link" value="<?php echo $location; ?>" class="widefat" />
            </span>
        </label>
    </div>
</fieldset>