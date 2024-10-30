<?php
/*
Plugin Name: BPStatBar Buddypress statistic bar
Plugin URI: http://www.bgextensions.bgvhod.com
Description: Display buddypress statistic
Version: 1.0.0
Author: BgExtensions
Author URI: http://www.bgextensions.bgvhod.com
License: GPL2
*/

/*  Copyright 2012  BgExtensions  (email : bgextensions@bgvhod.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function bpstatbar_init() {
    require( dirname( __FILE__ ) . '/bp-stat-bar.php' );
}
add_action( 'bp_include', 'bpstatbar_init' );

function bpstatbar_locale_init () {
	$plugin_dir = basename(dirname(__FILE__));
	$locale = get_locale();
	$mofile = WP_PLUGIN_DIR . "/bp-statistic-bar/languages/bpstatbar-$locale.mo";

      if ( file_exists( $mofile ) )
      		load_textdomain( 'bpstatbar', $mofile );
}
add_action ('plugins_loaded', 'bpstatbar_locale_init');

function add_bpstatbar_stylesheet() {
	    $myStyleUrl = WP_PLUGIN_URL . '/bp-statistic-bar/css/bpstatbar.css';
        wp_register_style( 'mybpstatbarSheets', $myStyleUrl );
        wp_enqueue_style( 'mybpstatbarSheets' );
}
add_action('wp_print_styles', 'add_bpstatbar_stylesheet');
?>