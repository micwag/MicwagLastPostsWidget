<?php
/***
 * Plugin Name: WP Last Posts Widget
 * Description: Displays the a list of the last posts. Markup and displayed content can be fully modified.
 * Author: Michael Wagner
 * Author URI: http://micwag.de
 */

// Block direct calls
defined( 'ABSPATH' ) or die( 'This page must not be called directly!' );

// Load widget class
require_once "lib/WPLastPostsWidget.php";

// Load widget

/**
 * Registers the widget class as widget
 */
function WPLastPostsWidgetLoad() {
	register_widget( 'WPLastPostsWidget' );
}

add_action( 'widgets_init', 'WPLastPostsWidgetLoad' );
