<?php
/**
 * Inspired by the PHP ActivityPub Library by @Landrok
 *
 * @link https://github.com/landrok/activitypub
 */

namespace Activitypub\Activity;

use WP_Error;

use function Activitypub\camel_to_snake_case;

/**
 * ObjectType is an implementation of one of the
 * Activity Streams Core Types.
 *
 * The Object is the primary base type for the Activity Streams
 * vocabulary.
 *
 * Note: Object is a reserved keyword in PHP. It has been suffixed with
 * 'Type' for this reason.
 *
 * @see https://www.w3.org/TR/activitystreams-core/#object
 */
class Activity_Object {
	/**
	 * The object's unique global identifier
	 *
	 * @see https://www.w3.org/TR/activitypub/#obj-id
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $type = 'Object';

	/**
	 * A resource attached or related to an object that potentially
	 * requires special handling.
	 * The intent is to provide a model that is at least semantically
	 * similar to attachments in email.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-attachment
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $attachment;

	/**
	 * One or more entities to which this object is attributed.
	 * The attributed entities might not be Actors. For instance, an
	 * object might be attributed to the completion of another activity.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-attributedto
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $attributed_to;

	/**
	 * One or more entities that represent the total population of
	 * entities for which the object can considered to be relevant.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-audience
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $audience;

	/**
	 * The content or textual representation of the Object encoded as a
	 * JSON string. By default, the value of content is HTML.
	 * The mediaType property can be used in the object to indicate a
	 * different content type.
	 *
	 * The content MAY be expressed using multiple language-tagged
	 * values.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-content
	 *
	 * @var string|null
	 */
	protected $content;

	/**
	 * The context within which the object exists or an activity was
	 * performed.
	 * The notion of "context" used is intentionally vague.
	 * The intended function is to serve as a means of grouping objects
	 * and activities that share a common originating context or
	 * purpose. An example could be all activities relating to a common
	 * project or event.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-context
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | null
	 */
	protected $context;

	/**
	 * The content MAY be expressed using multiple language-tagged
	 * values.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-content
	 *
	 * @var array|null
	 */
	protected $content_map;

	/**
	 * A simple, human-readable, plain-text name for the object.
	 * HTML markup MUST NOT be included.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-name
	 *
	 * @var string|null xsd:string
	 */
	protected $name;

	/**
	 * The name MAY be expressed using multiple language-tagged values.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-name
	 *
	 * @var array|null rdf:langString
	 */
	protected $name_map;

	/**
	 * The date and time describing the actual or expected ending time
	 * of the object.
	 * When used with an Activity object, for instance, the endTime
	 * property specifies the moment the activity concluded or
	 * is expected to conclude.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-endtime
	 *
	 * @var string|null
	 */
	protected $end_time;

	/**
	 * The entity (e.g. an application) that generated the object.
	 *
	 * @var string|null
	 */
	protected $generator;

	/**
	 * An entity that describes an icon for this object.
	 * The image should have an aspect ratio of one (horizontal)
	 * to one (vertical) and should be suitable for presentation
	 * at a small size.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-icon
	 *
	 * @var string
	 *    | Image
	 *    | Link
	 *    | array<Image>
	 *    | array<Link>
	 *    | null
	 */
	protected $icon;

	/**
	 * An entity that describes an image for this object.
	 * Unlike the icon property, there are no aspect ratio
	 * or display size limitations assumed.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-image-term
	 *
	 * @var string
	 *    | Image
	 *    | Link
	 *    | array<Image>
	 *    | array<Link>
	 *    | null
	 */
	protected $image;

	/**
	 * One or more entities for which this object is considered a
	 * response.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-inreplyto
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $in_reply_to;

	/**
	 * One or more physical or logical locations associated with the
	 * object.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-location
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $location;

	/**
	 * An entity that provides a preview of this object.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-preview
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | null
	 */
	protected $preview;

	/**
	 * The date and time at which the object was published
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-published
	 *
	 * @var string|null xsd:dateTime
	 */
	protected $published;

	/**
	 * A Collection containing objects considered to be responses to
	 * this object.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-replies
	 *
	 * @var string
	 *    | Collection
	 *    | Link
	 *    | null
	 */
	protected $replies;

	/**
	 * The date and time describing the actual or expected starting time
	 * of the object.
	 * When used with an Activity object, for instance, the startTime
	 * property specifies the moment the activity began
	 * or is scheduled to begin.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-starttime
	 *
	 * @var string|null xsd:dateTime
	 */
	protected $start_time;

	/**
	 * A natural language summarization of the object encoded as HTML.
	 * Multiple language tagged summaries MAY be provided.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-summary
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | null
	 */
	protected $summary;

	/**
	 * The content MAY be expressed using multiple language-tagged
	 * values.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-summary
	 *
	 * @var array<string>|null
	 */
	protected $summary_map;

	/**
	 * One or more "tags" that have been associated with an objects.
	 * A tag can be any kind of Object.
	 * The key difference between attachment and tag is that the former
	 * implies association by inclusion, while the latter implies
	 * associated by reference.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-tag
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $tag;

	/**
	 * The date and time at which the object was updated
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-updated
	 *
	 * @var string|null xsd:dateTime
	 */
	protected $updated;

	/**
	 * One or more links to representations of the object.
	 *
	 * @var string
	 *    | array<string>
	 *    | Link
	 *    | array<Link>
	 *    | null
	 */
	protected $url;

	/**
	 * An entity considered to be part of the public primary audience
	 * of an Object
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-to
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $to;

	/**
	 * An Object that is part of the private primary audience of this
	 * Object.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-bto
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $bto;

	/**
	 * An Object that is part of the public secondary audience of this
	 * Object.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-cc
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $cc;

	/**
	 * One or more Objects that are part of the private secondary
	 * audience of this Object.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-bcc
	 *
	 * @var string
	 *    | ObjectType
	 *    | Link
	 *    | array<ObjectType>
	 *    | array<Link>
	 *    | null
	 */
	protected $bcc;

	/**
	 * The MIME media type of the value of the content property.
	 * If not specified, the content property is assumed to contain
	 * text/html content.
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-mediatype
	 *
	 * @var string|null
	 */
	protected $media_type;

	/**
	 * When the object describes a time-bound resource, such as an audio
	 * or video, a meeting, etc, the duration property indicates the
	 * object's approximate duration.
	 * The value MUST be expressed as an xsd:duration as defined by
	 * xmlschema11-2, section 3.3.6 (e.g. a period of 5 seconds is
	 * represented as "PT5S").
	 *
	 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-duration
	 *
	 * @var string|null
	 */
	protected $duration;

	/**
	 * Intended to convey some sort of source from which the content
	 * markup was derived, as a form of provenance, or to support
	 * future editing by clients.
	 *
	 * @see https://www.w3.org/TR/activitypub/#source-property
	 *
	 * @var ObjectType
	 */
	protected $source;

	/**
	 * Magic function to implement getter and setter
	 *
	 * @param string $method The method name.
	 * @param string $params The method params.
	 *
	 * @return void
	 */
	public function __call( $method, $params ) {
		$var = \strtolower( \substr( $method, 4 ) );

		if ( \strncasecmp( $method, 'get', 3 ) === 0 ) {
			if ( $this->has( $var ) ) {
				return $this->get( $var );
			}
			return $this->$var;
		}

		if ( \strncasecmp( $method, 'set', 3 ) === 0 ) {
			$this->set( $var, $params[0] );
		}

		if ( \strncasecmp( $method, 'add', 3 ) === 0 ) {
			$this->add( $var, $params[0] );
		}
	}

	/**
	 * Generic getter.
	 *
	 * @param string $key The key to get.
	 *
	 * @return mixed The value.
	 */
	public function get( $key ) {
		if ( ! $this->has( $key ) ) {
			return new WP_Error( 'invalid_key', 'Invalid key' );

		}

		return $this->$key;
	}

	/**
	 * Check if the object has a key
	 *
	 * @param string $key The key to check.
	 *
	 * @return boolean True if the object has the key.
	 */
	public function has( $key ) {
		return property_exists( $this, $key );
	}

	/**
	 * Generic setter.
	 *
	 * @param string $key   The key to set.
	 * @param string $value The value to set.
	 *
	 * @return mixed The value.
	 */
	public function set( $key, $value ) {
		if ( ! $this->has( $key ) ) {
			return new WP_Error( 'invalid_key', 'Invalid key' );
		}

		$this->$key = $value;

		return $this->$key;
	}

	/**
	 * Generic adder.
	 *
	 * @param string $key   The key to set.
	 * @param mixed  $value The value to add.
	 *
	 * @return mixed The value.
	 */
	public function add( $key, $value ) {
		if ( ! $this->has( $key ) ) {
			return new WP_Error( 'invalid_key', 'Invalid key' );
		}

		if ( ! isset( $this->$key ) ) {
			$this->$key = array();
		}

		$this->$key[] = $value;

		return $this->$key;
	}

	/**
	 * Convert JSON input to an array.
	 *
	 * @return string The JSON string.
	 *
	 * @return array An Object built from the JSON string.
	 */
	public static function from_json( $json ) {
		$array = wp_json_decode( $json, true );

		return self::from_array( $array );
	}

	/**
	 * Convert JSON input to an array.
	 *
	 * @return string The object array.
	 *
	 * @return array An Object built from the JSON string.
	 */
	public static function from_array( $array ) {
		$object = new static();

		foreach ( $array as $key => $value ) {
			$key = camel_to_snake_case( $key );
			$object->set( $key, $value );
		}

		return $object;
	}
}
