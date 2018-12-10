<a id="<?php echo $tacoID; ?>" href="<?php echo $url ?>" target="_blank" class="btn btn-sm taco-btn"><?php echo $text ?></a>
<style>
    a#<?php echo $tacoID?>.taco-btn{
        <?php if($bgColor && trim($bgColor)): ?>
            background-color: <?php echo $bgColor; ?>;
        <?php endif; ?>

        <?php if($textColor && trim($textColor)): ?>
            color: <?php echo $textColor; ?>;
        <?php endif; ?>
    }
</style>
