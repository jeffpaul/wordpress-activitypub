<?php
/**
 * Outbox collection file.
 *
 * @package Activitypub
 */

namespace Activitypub\Collection;

use Activitypub\Transformer\Factory;

/**
 * ActivityPub Outbox Collection
 */
class Outbox {
	const POST_TYPE = 'ap_outbox';

	/**
	 * Add an Item to the outbox.
	 *
	 * @param string|array|Base_Object|WP_Post|WP_Comment $item The item to add.
	 * @param int                                         $user_id The user ID.
	 * @param string                                      $activity_type The activity <type class=""></type>
	 *
	 * @return mixed The added item or an error.
	 */
	public static function add_item( $item, $user_id, $activity_type = 'Create' ) {
		$transformer = Factory::get_transformer( $item );
		$object      = $transformer->transform();

		if ( ! $object || is_wp_error( $object ) ) {
			return $object;
		}

		$outbox_item = array(
			'post_type'    => self::POST_TYPE,
			'post_title'   => $object->get_id(),
			'post_content' => $object->to_json(),
			'post_author'  => $user_id,
			'post_status'  => 'draft',
		);

		$has_kses = false !== \has_filter( 'content_save_pre', 'wp_filter_post_kses' );
		if ( $has_kses ) {
			// Prevent KSES from corrupting JSON in post_content.
			\kses_remove_filters();
		}

		$result = \wp_insert_post( $outbox_item, true );

		if ( $has_kses ) {
			\kses_init_filters();
		}

		return $result;
	}
}
