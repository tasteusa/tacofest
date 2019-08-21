<?php
/**
 * Template Name: Vendor-Sponsor
 *
 * @package Betheme
 * @author Muffin Group
 */

get_header('vendorsponsor');
?>


<!-- #Content -->
<div id="Content">
    <div class="content_wrapper clearfix">

        <!-- .sections_group -->
        <div class="sections_group">
            <div class="the_content_wrapper">
                <div class="vc_row wpb_row vc_row-fluid">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">

                            <?php
                            while ( have_posts() ){
                                the_post();
                                the_content();
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- .four-columns - sidebar -->
        <?php get_sidebar(); ?>

    </div>
</div>

<?php do_action( 'mfn_hook_content_after' ); ?>

<?php do_action( 'mfn_hook_bottom' ); ?>


<?php get_footer('vendorsponsor');?>
