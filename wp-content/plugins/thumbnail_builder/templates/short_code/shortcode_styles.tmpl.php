<style>
    /*<?php echo $cont_sep_last ?>*/
    <?php if($cont_sep_last && $cont_sep_last == 'no'): ?>
        #<?php echo $shortcodeId; ?> .category-container:last-child{
            border: none;
            padding-bottom:0;
            margin-bottom: -49px;
        }
    <?php endif; ?>

    #<?php echo $shortcodeId; ?> .category-container .thumbnails-cont{
        <?php if($thumbs_cont_max_w && $thumbs_cont_max_w != 'none'): ?>
            max-width: <?php echo $thumbs_cont_max_w ?>px;
        <?php endif; ?>
    }
    #<?php echo $shortcodeId; ?> .category-container{

         <?php if($cont_max_w && $cont_max_w != 'none'): ?>
             max-width: <?php echo $cont_max_w ?>px;
         <?php endif; ?>

        <?php if($cont_sep && $cont_sep == 'yes'): ?>
            border-bottom: <?php echo $cont_sep_th ?>px solid <?php echo $cont_sep_color ?>;

            <?php if($cont_sep_mt && $cont_sep_mt != 0): ?>
                padding-bottom: <?php echo $cont_sep_mt; ?>px;
            <?php endif; ?>

            <?php if($cont_sep_mb && $cont_sep_mb != 0): ?>
                margin-bottom: <?php echo $cont_sep_mb; ?>px;
            <?php endif; ?>
        <?php endif; ?>
    }
    #<?php echo $shortcodeId; ?> .ltgs_image_container a{
        width: 100%;
        overflow: hidden;
    }
    #<?php echo $shortcodeId; ?> .ltgs_image_container .ltgs_image{

        <?php if(!$th_image_sizing || $th_image_sizing == 'auto'): ?>
            width: auto;
            height: auto;
        <?php elseif ($th_image_sizing == 'full width'): ?>
            width: 100%;
            height: auto;
        <?php elseif ($th_image_sizing == 'full height'): ?>
            width: auto;
            height: <?php echo $th_image_size ?>px;
        <?php endif;?>

        <?php if($th_image_size): ?>
            max-height: <?php echo $th_image_size ?>px;
        <?php endif; ?>

        max-width: 100%;
    }
    #<?php echo $shortcodeId; ?> .ltgs_image_container{
        <?php if($th_image_size): ?>
            height: <?php echo $th_image_size ?>px;
        <?php endif; ?>

        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        align-items: center;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        width: 100%;
    }
    #<?php echo $shortcodeId; ?> h2.lgts_category_title{
        <?php if($cat_title_color): ?>
             color: <?php echo $cat_title_color ?>;
        <?php endif; ?>

        <?php if($cat_title_transform): ?>
             text-transform: <?php echo $cat_title_transform ?>;
        <?php endif; ?>

        <?php if($cat_title_size): ?>
            font-size: <?php echo $cat_title_size ?>px;
            line-height: initial;
        <?php endif; ?>

        <?php if($cat_title_font): ?>
            font-family: <?php echo $cat_title_font ?>;
        <?php endif; ?>

        <?php if($cat_title_pos && $cat_title_pos != 'default'): ?>
            text-align: <?php echo $cat_title_pos ?>;
        <?php endif; ?>

        <?php if($cat_title_weight && $cat_title_weight != 'default'): ?>
            font-weight: <?php echo $cat_title_weight ?>;
        <?php endif; ?>
    }
    #<?php echo $shortcodeId; ?> .lgts_thumb_title,
    #<?php echo $shortcodeId; ?> .lgts_thumb_title a{
         <?php if($th_title_color): ?>
             color: <?php echo $th_title_color ?>;
         <?php endif; ?>

         <?php if($th_title_transform): ?>
             text-transform: <?php echo $th_title_transform ?>;
         <?php endif; ?>

         <?php if($th_title_size): ?>
             font-size: <?php echo $th_title_size ?>px;
             line-height: initial;
         <?php endif; ?>

         <?php if($th_title_font): ?>
             font-family: <?php echo $th_title_font ?>;
         <?php endif; ?>

         <?php if($th_title_pos && $th_title_pos != 'default'): ?>
             text-align: <?php echo $th_title_pos ?>;
         <?php endif; ?>

         <?php if($th_title_weight && $th_title_weight != 'default'): ?>
             font-weight: <?php echo $th_title_weight ?>;
         <?php endif; ?>
    }

    #<?php echo $shortcodeId; ?> .ltg_item .caption {
        height: 83px;
    }
    #<?php echo $shortcodeId; ?> .ltg_item .caption p {
        max-height: 60px;
         overflow: hidden;
     }
    #<?php echo $shortcodeId; ?> .ltg_item p {
         <?php if($description_font_size): ?>
            font-size:<?php echo $description_font_size; ?>px;
         <?php endif; ?>
         <?php if($description_font_weight): ?>
            font-weight:<?php echo $description_font_weight; ?>;
         <?php endif; ?>
         <?php if($description_text_align): ?>
            text-align:<?php echo $description_text_align; ?>;
         <?php endif; ?>
         <?php if($description_text_color): ?>
             color:<?php echo $description_text_color; ?>;
         <?php endif; ?>
    }
</style>