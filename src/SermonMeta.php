<?php
/**
 * Sermon meta class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Post;

/**
 * Sermon meta class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonMeta {

	/**
	 * Audo file name with path.
	 *
	 * @since 1.0.0
	 */
	public const AUDIO = 'drppsm_audio';

	/**
	 * Audio attachment id.
	 *
	 * @since 1.0.0
	 */
	public const AUDIO_ID = 'drppsm_audio_id';

	/**
	 * Bible passage.
	 *
	 * @since 1.0.0
	 */
	public const BIBLE_PASSAGE = 'drppsm_bible_passage';

	/**
	 * Church bulletin meta key.
	 *
	 * @since 1.0.0
	 */
	public const BULLETIN = 'drppsm_bulletin';

	/**
	 * Sermon date meta key.
	 *
	 * @since 1.0.0
	 */
	public const DATE = 'drppsm_date';

	/**
	 * Sermon notes meta key.
	 *
	 * @since 1.0.0
	 */
	public const NOTES = 'drppsm_notes';

	/**
	 * Sermon description meta key.
	 *
	 * @since 1.0.0
	 */
	public const DESCRIPTION = 'drppsm_description';

	/**
	 * Sermon duration meta key.
	 *
	 * @since 1.0.0
	 */
	public const DURATION = 'drppsm_duration';

	/**
	 * Sermon service type meta key.
	 *
	 * @since 1.0.0
	 */
	public const SERVICE_TYPE = DRPPSM_TAX_SERVICE_TYPE;

	/**
	 * Sermon video embed code meta key.
	 *
	 * @since 1.0.0
	 */
	public const VIDEO = 'drppsm_video';

	/**
	 * Sermon video link meta key.
	 *
	 * @since 1.0.0
	 */
	public const VIDEO_LINK = 'drppsm_video_link';

	/**
	 * List of meta keys with friendly names.
	 *
	 * @since 1.0.0
	 */
	public const META_LIST = array(
		self::AUDIO         => 'audio',
		self::AUDIO_ID      => 'audio_id',
		self::BIBLE_PASSAGE => 'bible_passage',
		self::BULLETIN      => 'bulletin',
		self::DATE          => 'date',
		self::DESCRIPTION   => 'description',
		self::DURATION      => 'duration',
		self::NOTES         => 'notes',
		self::SERVICE_TYPE  => 'service_type',
		self::VIDEO         => 'video',
		self::VIDEO_LINK    => 'video_link',
	);

	/**
	 * Post ID.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $post_id;

	/**
	 * Post date.
	 *
	 * @var mixed
	 * @since 1.0.0
	 */
	private mixed $post_date;

	/**
	 * Audio file name with path.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public ?string $audio;

	/**
	 * Audio attachment id.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public ?int $audio_id;

	/**
	 * Bible passage.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public ?string $bible_passage;

	/**
	 * Church bulletin attachment array.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public ?array $bulletin;

	/**
	 * Sermon date.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public ?int $date;

	/**
	 * Sermon description.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public ?string $description;

	/**
	 * Sermon duration.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public ?string $duration;

	/**
	 * Notes attachment array.
	 *
	 * @var null|array
	 * @since 1.0.0
	 */
	public ?array $notes;

	/**
	 * Video embed code.
	 *
	 * @var null|string
	 */
	public ?string $video;

	/**
	 * Video link string
	 *
	 * @var null|string
	 */
	public ?string $video_link;


	/**
	 * Initialize object.
	 *
	 * @param WP_Post $post Post object.
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct( WP_Post $post ) {
		$this->post_id   = $post->ID;
		$this->post_date = $post->post_date;

		$meta = self::get_meta( $post->ID );

		foreach ( $meta as $key => $value ) {
			if ( property_exists( $this, $key ) ) {
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Serialize sermon meta.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'post_id'       => $this->post_id,
			'audio'         => $this->audio,
			'audio_id'      => $this->audio_id,
			'bible_passage' => $this->bible_passage,
			'bulletin'      => $this->bulletin,
			'date'          => $this->date,
			'description'   => $this->description,
			'duration'      => $this->duration,
			'notes'         => $this->notes,
			'video'         => $this->video,
			'video_link'    => $this->video_link,
		);
	}

	/**
	 * Unserialize sermon meta.
	 *
	 * @param array $data Data to unserialize.
	 * @return void
	 * @since 1.0.0
	 */
	public function __unserialize( array $data ): void {
		$this->post_id       = $data['post_id'];
		$this->audio         = $data['audio'];
		$this->audio_id      = $data['audio_id'];
		$this->bible_passage = $data['bible_passage'];
		$this->bulletin      = $data['bulletin'];
		$this->date          = $data['date'];
		$this->description   = $data['description'];
		$this->duration      = $data['duration'];
		$this->notes         = $data['notes'];
		$this->video         = $data['video'];
		$this->video_link    = $data['video_link'];
	}

	/**
	 * Check if sermon has audio.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_audio(): bool {
		if ( ! isset( $this->audio ) || empty( $this->audio ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if sermon has video.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_video(): bool {
		$video_embed = false;
		if ( isset( $this->video ) || ! empty( $this->video ) ) {
			$video_embed = true;
		}

		$video_link = false;
		if ( isset( $this->video_link ) || ! empty( $this->video_link ) ) {
			$video_link = true;
		}
		if ( $video_embed || $video_link ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if sermon has bulletin.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_bulletin(): bool {
		if ( ! isset( $this->bulletin ) || empty( $this->bulletin ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if sermon has notes.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_notes(): bool {
		if ( ! isset( $this->notes ) || empty( $this->notes ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get video.
	 *
	 * @return string|null
	 * @since 1.0.0
	 */
	public function get_video(): ?string {
		if ( isset( $this->video_link ) && ! empty( $this->video_link ) ) {
			return $this->video_link;
		}

		if ( isset( $this->video ) && ! empty( $this->video ) ) {
			return $this->video;
		}
		return null;
	}

	/**
	 * Get audio.
	 *
	 * @return string|null
	 * @since 1.0.0
	 */
	public function get_audio(): ?string {
		return $this->audio;
	}

	/**
	 * Get post date.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function date(): string {
		if ( isset( $this->date ) && ! empty( $this->date ) ) {
			return format_date( absint( $this->date ) );
		}
		if ( isset( $this->post_date ) && ! empty( $this->post_date ) ) {
			return $this->post_date;
		}
		return '';
	}

	/**
	 * Get sermon duration.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_bible_passage(): string {
		if ( isset( $this->bible_passage ) && ! empty( $this->bible_passage ) ) {
			return $this->bible_passage;
		}
		return '';
	}

	/**
	 * Get meta.
	 *
	 * @param int  $post_id Post ID.
	 * @param bool $empty Return empty values.
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_meta( int $post_id, bool $empty = true ): array {
		$meta = array();
		foreach ( self::META_LIST as $meta_key => $meta_name ) {
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			if ( $empty && empty( $meta_value ) ) {
				$meta[ $meta_name ] = null;
				continue;
			}

			if ( ! empty( $meta_value ) ) {
				$meta[ $meta_name ] = $meta_value;
			}
		}
		return $meta;
	}
}
