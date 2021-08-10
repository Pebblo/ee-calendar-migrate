<?php
/*
Plugin Name: Enable EE jQuery Migrate
Description: Load legacy jQuery on calendar pages.
Version: 1.0.0
Requires at least: 5.4
Tested up to: 5.5
Requires PHP: 5.6
*/

add_action( 'parse_query', 'tw_ee_calendar_migrate' );

function tw_ee_calendar_migrate() {
    if( is_page('espresso_calendar') ) {
    	add_action( 'wp_enqueue_scripts', 'tw_ee_replace_scripts', 9 );
    }
}

function tw_ee_set_script( $scripts, $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {
	$script = $scripts->query( $handle, 'registered' );

	if ( $script ) {
		// If already added
		$script->src  = $src;
		$script->deps = $deps;
		$script->ver  = $ver;
		$script->args = $in_footer;

		unset( $script->extra['group'] );

		if ( $in_footer ) {
			$script->add_data( 'group', 1 );
		}
	} else {
		// Add the script
		if ( $in_footer ) {
			$scripts->add( $handle, $src, $deps, $ver, 1 );
		} else {
			$scripts->add( $handle, $src, $deps, $ver );
		}
	}
}

function tw_ee_replace_scripts() {
		$scripts = wp_scripts();
		$assets_url = plugins_url( 'js/', __FILE__ );
	    tw_ee_set_script( $scripts, 'jquery-migrate', $assets_url . 'jquery-migrate/jquery-migrate-1.4.1-wp.js', array(), '1.4.1-wp' );
	    tw_ee_set_script( $scripts, 'jquery-core', $assets_url . 'jquery/jquery-1.12.4-wp.js', array(), '1.12.4-wp' );
	    tw_ee_set_script( $scripts, 'jquery', false, array( 'jquery-core', 'jquery-migrate' ), '1.12.4-wp' );
}
