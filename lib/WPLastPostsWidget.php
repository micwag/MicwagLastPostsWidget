<?php

// Block direct calls
defined( 'ABSPATH' ) or die( 'This page must not be called directly!' );

/**
 * Class WPLastPostsWidget
 */
class WPLastPostsWidget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'wp-last-posts-widget',
			__( 'WP Last Posts Widget', 'wp-last-posts-widget' ),
			[
				'description' => __( 'Displays the a list of the last posts. Markup and displayed content can be fully modified.' )
			]
		);
	}

	/**
	 * Displays the widgets frontend
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

	}

	/**
	 *  Displays the widgets admin form
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {

	}

	/**
	 * Updates widget replacing old instance with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

	}
}