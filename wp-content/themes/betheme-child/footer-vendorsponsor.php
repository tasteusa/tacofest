<?php
/**
 * The template for displaying the footer.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


$back_to_top_class = mfn_opts_get('back-top-top');

if( $back_to_top_class == 'hide' ){
    $back_to_top_position = false;
} elseif( strpos( $back_to_top_class, 'sticky' ) !== false ){
    $back_to_top_position = 'body';
} elseif( mfn_opts_get('footer-hide') == 1 ){
    $back_to_top_position = 'footer';
} else {
    $back_to_top_position = 'copyright';
}

?>

<?php do_action( 'mfn_hook_content_after' ); ?>

</div><!-- #Wrapper -->

<?php
// Responsive | Side Slide
if( mfn_opts_get( 'responsive-mobile-menu' ) ){
    get_template_part( 'includes/header', 'side-slide' );
}
?>

<?php
if( $back_to_top_position == 'body' ){
    echo '<a id="back_to_top" class="button button_js '. $back_to_top_class .'" href=""><i class="icon-up-open-big"></i></a>';
}
?>

<?php if( mfn_opts_get('popup-contact-form') ): ?>
    <div id="popup_contact">
        <a class="button button_js" href="#"><i class="<?php mfn_opts_show( 'popup-contact-form-icon', 'icon-mail-line' ); ?>"></i></a>
        <div class="popup_contact_wrapper">
            <?php echo do_shortcode( mfn_opts_get('popup-contact-form') ); ?>
            <span class="arrow"></span>
        </div>
    </div>
<?php endif; ?>

<?php do_action( 'mfn_hook_bottom' ); ?>

<!-- wp_footer() -->
<?php wp_footer(); ?>

<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script><script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us8.list-manage.com","uuid":"9f71368dc454f7ad28ffeb362","lid":"3b7ee7f5cd"}) })</script>
<script src="https://www.eventbrite.com/static/widgets/eb_widgets.js"></script>

<script type="text/javascript">
    ( function( $ ) {
        $(document).ready(function () {
            var exampleCallback = function() {
                console.log('Order complete!');
            };

            if (typeof(buttonInfo) != 'undefined') {

                window.EBWidgets.createWidget({
                    widgetType: 'checkout',
                    eventId: buttonInfo.id,
                    modal: true,
                    modalTriggerElementId: 'eventbrite-widget-modal-trigger-'+buttonInfo.id,
                    onOrderComplete: exampleCallback
                });

            }

        });
    })(jQuery);
</script>

</body>
</html>
