<?php
/**
 * Sermon object.
 *
 * @package     DRPPSM\Sermon
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon object.
 *
 * @package     DRPPSM\Sermon
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Sermon {

	/**
	 * Post object.
	 *
	 * @var WP_Post
	 * @since 1.0.0
	 */
	public WP_Post $post;

	/**
	 * Sermon link.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $link;

	/**
	 * Sermon meta object.
	 *
	 * @var SermonMeta
	 * @since 1.0.0
	 */
	public SermonMeta $meta;

	/**
	 * Books terms object.
	 *
	 * @var TaxBooks
	 * @since 1.0.0
	 */
	public TaxBooks $books;

	/**
	 * Preacher terms object.
	 *
	 * @var TaxPreacher
	 * @since 1.0.0
	 */
	public TaxPreacher $preacher;

	/**
	 * Series terms object.
	 *
	 * @var TaxSeries
	 * @since 1.0.0
	 */
	public TaxSeries $series;

	/**
	 * Topics terms object.
	 *
	 * @var TaxTopics
	 * @since 1.0.0
	 */
	public TaxTopics $topics;

	/**
	 * Sermon constructor.
	 *
	 * @param int|WP_Post $post
	 * @since 1.0.0
	 */
	public function __construct( int|WP_Post $post ) {

		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$this->post     = $post;
		$this->meta     = new SermonMeta( $post );
		$this->books    = new TaxBooks( $post );
		$this->preacher = new TaxPreacher( $post );
		$this->series   = new TaxSeries( $post );
		$this->topics   = new TaxTopics( $post );

		$link = get_permalink( $post );
		if ( ! $link ) {
			$link = '';
		}
		$this->link = $link;

		Logger::debug( $this );
	}

	/**
	 * Serialize sermon object.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'post'     => $this->post,
			'link'     => $this->link,
			'meta'     => $this->meta,
			'books'    => $this->books,
			'preacher' => $this->preacher,
			'series'   => $this->series,
			'topics'   => $this->topics,
		);
	}

	/**
	 * Unserialize sermon object.
	 *
	 * @param array $data
	 * @since 1.0.0
	 */
	public function __unserialize( array $data ): void {
		$this->post = $data['post'];

		$this->meta     = $data['meta'];
		$this->books    = $data['books'];
		$this->preacher = $data['preacher'];
		$this->series   = $data['series'];
		$this->topics   = $data['topics'];

		if ( key_exists( 'link', $data ) ) {
			$this->link = (string) $data['link'];
		} else {
			$this->link = '';
		}
	}
}
