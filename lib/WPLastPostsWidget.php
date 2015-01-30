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
		if ( isset( $instance['numPosts'] ) ) {
			$numPosts = $instance['numPosts'];
		} else {
			$numPosts = 3;
		}

		if ( isset( $instance['widgetContent'] ) ) {
			$widgetContent = $instance['widgetContent'];
		} else {
			$widgetContent = '%posts%';
		}

		if ( isset( $instance['postContent'] ) ) {
			$postContent = $instance['postContent'];
		} else {
			$postContent = '<article><h1><a href="%post_permalink%">%post_title%</a></h1><p>%post_content%</p></article>';
		}

		$posts = "";
		$query = new WP_Query( array( 'posts_per_page' => $numPosts ) );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$singlePostContent = $postContent;
				$singlePostContent = str_replace( '%post_content%', get_the_content(), $singlePostContent );
				$singlePostContent = str_replace( '%post_date%', get_the_date(), $singlePostContent );
				$singlePostContent = str_replace( '%post_excerpt%', get_the_excerpt(), $singlePostContent );
				$singlePostContent = str_replace( '%post_permalink%', get_the_permalink(), $singlePostContent );
				$singlePostContent = str_replace( '%post_title%', get_the_title(), $singlePostContent );

				if ( current_theme_supports( 'post-thumbnails' ) ) {
					if ( has_post_thumbnail() ) {
						$thumbnailId       = get_post_thumbnail_id();
						$thumbnailLink     = wp_get_attachment_thumb_url( $thumbnailId );
						$singlePostContent = str_replace( '%post_thumbnail%', $thumbnailLink, $singlePostContent );
					} else {
						$singlePostContent = str_replace( '%post_thumbnail%', '', $singlePostContent );
					}
				} else {
					$singlePostContent = str_replace( '%post_thumbnail%', '', $singlePostContent );
				}

				$posts .= $singlePostContent;
			}
		}

		$widgetContent = str_replace( '%posts%', $posts, $widgetContent );

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}
		echo $widgetContent;
		echo $args['after_widget'];
	}

	/**
	 *  Displays the widgets admin form
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {
		echo '<p>';

		// Title
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New Title', 'wp-last-posts-widget' );
		}

		echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title', 'wp-last-posts-widget' )
		     . ': </label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'title' )
		     . '" name="' . $this->get_field_name( 'title' )
		     . '" type="text" value="' . esc_attr( $title ) . '" /></p>';

		// Number of displayed posts
		if ( isset( $instance['numPosts'] ) ) {
			$num_posts = $instance['numPosts'];
		} else {
			$num_posts = __( '3', 'wp-last-posts-widget' );
		}

		echo '<p><label for="' . $this->get_field_id( 'numPosts' ) . '">'
		     . __( 'Number of displayed posts', 'wp-last-posts-widget' )
		     . ': </label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'numPosts' )
		     . '" name="' . $this->get_field_name( 'numPosts' )
		     . '" type="number" min="0" max="100" value="' . esc_attr( $num_posts ) . '" /></p>';

		// Widget Content
		if ( isset( $instance['widgetContent'] ) ) {
			$widgetContent = $instance['widgetContent'];
		} else {
			$widgetContent = '%posts%';
		}

		echo '<p><label for="' . $this->get_field_id( 'widgetContent' ) . '">' . __( 'Widget content',
				'wp-last-posts-widget' ) . ': </label>';
		echo '<textarea class="widefat" id="' . $this->get_field_id( 'widgetContent' ) . '" name="'
		     . $this->get_field_name( 'widgetContent' ) . '">' . esc_attr( $widgetContent ) . '</textarea></p>';


		// Widget Content
		if ( isset( $instance['postContent'] ) ) {
			$postContent = $instance['postContent'];
		} else {
			$postContent = '<article><h1><a href="%post_permalink%">%post_title%</a></h1><p>%post_content%</p></article>';
		}

		echo '<p><label for="' . $this->get_field_id( 'postContent' ) . '">' . __( 'Post content',
				'wp-last-posts-widget' ) . ': </label>';
		echo '<textarea class="widefat" id="' . $this->get_field_id( 'postContent' ) . '" name="'
		     . $this->get_field_name( 'postContent' ) . '">' . esc_attr( $postContent ) . '</textarea></p>';


		echo '</p>';
	}

	/**
	 * Updates widget replacing old instance with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array instance with up-to-date parameters
	 */
	public function update( $new_instance, $old_instance ) {
		if ( is_array( $old_instance ) ) {
			$instance = $old_instance;
		} else {
			$instance = array();
		}

		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['widgetContent'] = $new_instance['widgetContent'];
		$instance['postContent']   = $new_instance['postContent'];

		if ( is_numeric( $new_instance['numPosts'] ) ) {
			$instance['numPosts'] = strip_tags( $new_instance['numPosts'] );
		} else {
			add_action( 'admin_notices', function () {
				echo '<div class="error">' . __( 'Number of posts must be an integer value.' ) . '</div>';
			} );
		}

		return $instance;
	}
}