<?php
/***
 * Plugin Name: Micwag Last Posts Widget
 * Description: Displays the a list of the last posts. Markup and displayed content can be fully modified.
 * Author: Michael Wagner
 * Author URI: http://micwag.de
 */

// Block direct calls
defined( 'ABSPATH' ) or die( 'This page must not be called directly!' );

// Load widget class
require_once "lib/MicwagLastPostsWidget.php";

// Load widget

/**
 * Registers the widget class as widget
 */
function MicwagLastPostsWidgetLoad() {
	register_widget( 'MicwagLastPostsWidget' );
}

add_action( 'widgets_init', 'MicwagLastPostsWidgetLoad' );
