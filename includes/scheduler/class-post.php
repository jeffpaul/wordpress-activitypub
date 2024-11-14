<?php
/**
 * Post scheduler class file.
 *
 * @package Activitypub
 */

namespace Activitypub\Scheduler;

/**
 * Post scheduler class.
 */
class Post {
	/**
	 * Initialize the class, registering WordPress hooks.
	 */
	public static function init() {
		// Post transitions.
		\add_action( 'transition_post_status', array( self::class, 'schedule_post_activity' ), 33, 3 );
		\add_action(
			'edit_attachment',
			function ( $post_id ) {
				self::schedule_post_activity( 'publish', 'publish', $post_id );
			}
		);
		\add_action(
			'add_attachment',
			function ( $post_id ) {
				self::schedule_post_activity( 'publish', '', $post_id );
			}
		);
		\add_action(
			'delete_attachment',
			function ( $post_id ) {
				self::schedule_post_activity( 'trash', '', $post_id );
			}
		);
	}

	/**
	 * Schedule Activities.
	 *
	 * @param string   $new_status New post status.
	 * @param string   $old_status Old post status.
	 * @param \WP_Post $post       Post object.
	 */
	public static function schedule_post_activity( $new_status, $old_status, $post ) {
		$post = get_post( $post );

		if ( ! $post || is_post_disabled( $post ) ) {
			return;
		}

		if ( 'ap_extrafield' === $post->post_type ) {
			self::schedule_profile_update( $post->post_author );
			return;
		}

		if ( 'ap_extrafield_blog' === $post->post_type ) {
			self::schedule_profile_update( 0 );
			return;
		}

		// Do not send activities if post is password protected.
		if ( \post_password_required( $post ) ) {
			return;
		}

		// Check if post-type supports ActivityPub.
		$post_types = \get_post_types_by_support( 'activitypub' );
		if ( ! \in_array( $post->post_type, $post_types, true ) ) {
			return;
		}

		$type = false;

		if (
			'publish' === $new_status &&
			'publish' !== $old_status
		) {
			$type = 'Create';
		} elseif (
			'publish' === $new_status ||
			// We want to send updates for posts that are published and then moved to draft.
			( 'draft' === $new_status &&
			'publish' === $old_status )
		) {
			$type = 'Update';
		} elseif ( 'trash' === $new_status ) {
			$type = 'Delete';
		}

		if ( empty( $type ) ) {
			return;
		}

		$hook = 'activitypub_send_post';
		$args = array( $post->ID, $type );

		if ( false === wp_next_scheduled( $hook, $args ) ) {
			set_wp_object_state( $post, 'federate' );
			\wp_schedule_single_event( \time() + 10, $hook, $args );
		}
	}
}
