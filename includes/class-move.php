<?php
/**
 * Move class file.
 *
 * @package Activitypub
 */

namespace Activitypub;

/**
 * ActivityPub (Account) Move Class
 *
 * @author Matthias Pfefferle
 */
class Move {
	/**
	 * Initialize the class, registering WordPress hooks
	 */
	public static function init() {
		\add_filter( 'activitypub_activity_user_object_array', array( self::class, 'extend_actor_profiles' ), 10, 3 );
	}

	/**
	 * Extend the actor profiles and add the "movedTo" and "alsoKnownAs" properties.
	 *
	 * @param array $actor The Actor-Profile.
	 * @param int   $id    The Activity-ID.
	 * @param mixed $user  The WordPress-User.
	 *
	 * @return array the extended actor profile
	 */
	public static function extend_actor_profiles( $actor, $id, $user ) {
		// Check if the user is a valid user object.
		if ( ! $user instanceof \Activitypub\Model\User ) {
			return $actor;
		}

		$move_to = \get_user_option( 'activitypub_move_to', $user->get__id() );

		if ( $move_to ) {
			$actor['movedTo'] = $move_to;
		}

		$also_known_as = \get_user_option( 'activitypub_also_known_as', $user->get__id() );

		if ( $also_known_as ) {
			$actor['alsoKnownAs'] = (array) $also_known_as;
		}

		return $actor;
	}

	/**
	 * Add settings to the admin interface
	 *
	 * @return void
	 */
	public static function add_settings() {
	}

	/**
	 * Normalize the host
	 *
	 * Returns the host if it is a valid URL, otherwise it tries to replace
	 * the host of the Actor-ID with the new host
	 *
	 * @param string $host_or_url the host or the url.
	 * @param string $id          the Actor-ID (URL).
	 *
	 * @return string the normalized host
	 */
	public static function normalize_host( $host_or_url, $id ) {
		// If it is a valid URL use it.
		if ( filter_var( $host_or_url, FILTER_VALIDATE_URL ) ) {
			return $host_or_url;
		}

		// Otherwise try to replace the host of the Actor-ID with the new host.
		$id = str_replace( wp_parse_url( get_home_url(), PHP_URL_HOST ), $host_or_url, $id );

		return $id;
	}

	/**
	 * Normalize the hosts
	 *
	 * Returns an array of normalized hosts
	 *
	 * @param string $hosts_or_urls the host or the url.
	 * @param string $id            the Actor-ID (URL).
	 *
	 * @return array the normalized hosts
	 */
	public static function normalize_hosts( $hosts_or_urls, $id ) {
		$normalized_hosts = array();

		foreach ( $hosts_or_urls as $host_or_url ) {
			$normalized_hosts[] = self::normalize_host( $host_or_url, $id );
		}

		return $normalized_hosts;
	}
}
