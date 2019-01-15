<script id="thumbWinnerTemplate" type="text/x-jquery-tmpl">
    <div class="col-sm-6 col-md-4 col-lg-3 single-linked-thumb single-winner-thumb">
        <div class="thumbnail">
            <div class="image-wrapper-block" style="background-image:url(${img});">
                <img class='thumb-img' src="${img}" style="visibility:hidden"/>
                <div class="winner-image" {%if congrats_image_id %}style="${congrats_image_position}"{%/if%}><span class="clear"></span></div>
                <div class="winner-text" {%if congrats_text %}style="${congrats_text_position}"{%/if%}><p>${congrats_text}</p></div>
            </div>
            <div class="caption">
                <form>
                    <div class="form-group winner-select">
                        <p>
                            <label>
                                <input type="checkbox" value="1"
                                name="winner"
                                {%if is_winner == 'true' %}checked{%/if%}
                                class="is-winner form-control"> Mark as winner</label>
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
                    <div class="form-group">
                        <label>Congratulation Text</label>
                        <input type="text" value="${congrats_text}" name="congrats-text"
                        class="form-control form-control-sm congrats-text-field" placeholder="Congratulation Text">
                    </div>
                    <div class="form-group">
                        <label>Congratulation Text Align</label>
                        <div class="form-inline">
                            <div class="form-group">
                                <p>
                                    <label>Left <input type="radio"
                                    value="left"
                                    {%if congrats_text_align=="left" %}checked{%/if%}
                                    name="congrats-text-align"
                                    class="form-control form-control-sm congrats-text-align-field"></label>
                                </p>
                            </div>
                            <div class="form-group">
                                <p>
                                    <label>Center <input type="radio"
                                    value="center" checked
                                    {%if congrats_text_align=="center" %}checked{%/if%}
                                    name="congrats-text-align"
                                    class="form-control form-control-sm congrats-text-align-field"></label>
                                </p>
                            </div>
                            <div class="form-group">
                                <p>
                                    <label>Right <input type="radio"
                                    value="right"
                                    {%if congrats_text_align=="right" %}checked{%/if%}
                                    name="congrats-text-align"
                                    class="form-control form-control-sm congrats-text-align-field"></label>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Font size</label>
                        <div class="slider">
                            <div class="ui-slider-handle custom-handle"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Congratulation Text Color</label>
                        <input type="color" value="${congrats_text_color}"
                        name="congrats-text-color"
                        class="form-control form-control-sm congrats-text-color-field">
                    </div>
                    <div class="form-group">
                        <p>
                            <label><input type="checkbox" value="700"
                            name="congrats-font-weight"
                            {%if congrats_font_weight=="true" %}checked{%/if%}
                            class="form-control form-control-sm congrats-font-weight-field"> Font Weight Bold</label>
                        </p>
                    </div>
                    <div class="form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Congratulation Text Background Color</legend>
                            <div class="control-group">
                                <p>
                                    <label class="control-label input-label">
                                    <input type="checkbox" name="use-transparent-bg"
                                    {%if congrats_use_transparent_bg=="true" %}checked{%/if%}
                                    value="transparent" class="form-control use-transparent-bg"> Transparent Background</label>
                                </p>
                            </div>
                            <div class="control-group background-color-wrapper {%if congrats_use_transparent_bg=='true' %}hidden{%/if%}">
                                <label class="control-label input-label">Background Color</label>
                                <div class="controls">
                                    <input type="color" value="{%if congrats_text_background_color %}${congrats_text_background_color}{%else%}#e11f59{%/if%}"
                                    name="congrats-text-background-color"
                                    class="form-control form-control-sm congrats-text-background-color-field">
                                </div>
                            </div>
                        </fieldset>

                    </div>
                    <div class="form-group">
                        <a class="btn btn-primary select-img-btn">Select Congratulation Images</a>
                    </div>
                    <input type="hidden" class="congrats-hiddden-img-id" value="${congrats_image_id}"/>
                    <input type="hidden" class="congrats-hiddden-img-position" value="${congrats_image_position}"/>
                    <input type="hidden" class="congrats-hiddden-text-position" value="${congrats_text_position}"/>
                    <input type="hidden" class="hiddden-thumb-id" value="${id}"/>
                </form>
                <div>
                    <a class="btn btn-danger pull-left delete-winner-thumb">Remove</a>
                    <a class="btn btn-success pull-right save-thumb">Save</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</script>