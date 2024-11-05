<?php
/**
 * String Transformer Class file.
 *
 * @package Activitypub
 */

namespace Activitypub\Transformer;

use Activitypub\Activity\Base_Object;

/**
 * String Transformer Class file.
 */
class Json extends Base {
	/**
	 * Transform the WordPress Object into an ActivityPub Object.
	 *
	 * @return Base_Object The ActivityPub Object.
	 */
	public function to_object() {
		$activitypub_object = null;

		if ( is_array( $this->item ) ) {
			$activitypub_object = Base_Object::init_from_array( $this->item );
		} else {
			$activitypub_object = Base_Object::init_from_json( $this->item );
		}

		return $activitypub_object;
	}

	/**
	 * Get the ID of the WordPress Object.
	 *
	 * @return string The ID of the WordPress Object.
	 */
	protected function get_id() {
		return '';
	}
}
