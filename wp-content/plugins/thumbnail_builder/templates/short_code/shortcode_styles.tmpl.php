<style>
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
    #<?php echo $shortcodeId; ?> .lgts_category_title{
        <?php if($cat_title_color): ?>
            color: <?php echo $cat_title_color ?>;
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
    #<?php echo $shortcodeId; ?> .lgts_thumb_title{
         <?php if($th_title_color): ?>
             color: <?php echo $th_title_color ?>;
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
</style>