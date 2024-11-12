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
class Json extends Activity_Object {

	/**
	 * JSON constructor.
	 *
	 * @param string|array $item The item that should be transformed.
	 */
	public function __construct( $item ) {
		$item = new Base_Object();

		if ( is_array( $this->item ) ) {
			$item = Base_Object::init_from_array( $this->item );
		} elseif ( is_string( $this->item ) ) {
			$item = Base_Object::init_from_json( $this->item );
		}

		parent::__construct( $item );
	}
}
