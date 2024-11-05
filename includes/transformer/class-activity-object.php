<?php
/**
 * Activity Object Transformer Class.
 *
 * @package Activitypub
 */

namespace Activitypub\Transformer;

/**
 * Activity Object Transformer Class.
 */
class Activity_Object extends Base {
	/**
	 * Transform the WordPress Object into an ActivityPub Object.
	 *
	 * @return Base_Object The ActivityPub Object.
	 */
	public function to_object() {
		return $this->item;
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
