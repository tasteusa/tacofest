<?php

/* ---------------------------------------------------------------------------
 * Split Menu
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfnch_wp_split_menu' ) )
{
	function mfnch_wp_split_menu($side = 'left')
	{
	    if($side=='left'){
            echo '<nav id="" class="mfnch-menu-split mfnch-menu-split-left">';

            // Main Menu Left ----------------------------
            $args = array(
                'container' 		=> false,
                'menu_id'         	=> false,
                'menu_class'		=> 'menu pull-right',
                'fallback_cb'		=> false,
                'theme_location'	=> 'main-menu',
                'depth' 			=> 5,
                'link_before'     	=> '<span>',
                'link_after'      	=> '</span>',
            );

            // custom walker for mega menu
            $theme_disable = mfn_opts_get( 'theme-disable' );
            if( ! isset( $theme_disable['mega-menu'] ) ){
                $args['walker']		= new Walker_Nav_Menu_Mfn;
            }

            wp_nav_menu( $args );
            echo '</nav>';
        }else{
            echo '<nav id="" class="mfnch-menu-split mfnch-menu-split-right">';
            // Main Menu Right ----------------------------
            $args = array(
                'container' 		=> false,
                'menu_id'         	=> false,
                'menu_class'		=> 'menu pull-left',
                'fallback_cb'		=> false,
                'theme_location'	=> 'secondary-menu',
                'depth' 			=> 5,
                'link_before'     	=> '<span>',
                'link_after'      	=> '</span>',
            );

            // custom walker for mega menu
            $theme_disable = mfn_opts_get( 'theme-disable' );
            if( ! isset( $theme_disable['mega-menu'] ) ){
                $args['walker']		= new Walker_Nav_Menu_Mfn;
            }

            wp_nav_menu( $args );

            echo '</nav>';
        }
	}
}