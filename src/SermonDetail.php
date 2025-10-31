<?php
/**
 * Show sermon deail meta box.
 *
 * @package     DRPPSM\SermonDetail
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use CMB2;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Show sermon deail meta box.
 *
 * @package     DRPPSM\SermonDetail
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonDetail implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Post type.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $pt_sermon;

	/**
	 * CMB2 id.
	 *
	 * @var string
	 */
	private string $cmb_id;

	/**
	 * CMB2 object.
	 *
	 * @var CMB2
	 */
	private ?CMB2 $cmb;

	/**
	 * Service type taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_service_type;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt_sermon        = DRPPSM_PT_SERMON;
		$this->tax_service_type = DRPPSM_TAX_SERVICE_TYPE;
		$this->cmb_id           = 'drppsm_details';
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true hooks were registered.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( ! is_admin() || has_action( Action::SERMON_EDIT_FORM, array( $this, 'show' ) ) ) {
			return false;
		}
		// @codeCoverageIgnoreStart
		$pt = 'post';
		add_action( Action::SERMON_EDIT_FORM, array( $this, 'show' ) );
		add_action( "cmb2_save_{$pt}_fields_{$this->cmb_id}", array( $this, 'save' ), 10, 3 );
		return true;
	}

	/**
	 * Show sermon detail controls.
	 *
	 * @return bool Return true.
	 * @since 1.0.0
	 */
	public function show(): bool {
		if ( FatalError::exist() ) {
			return false;
		}

		/**
		 * Initiate the metabox.
		 */
		$cmb = \new_cmb2_box(
			array(
				'id'           => $this->cmb_id,
				'title'        => __( 'Sermon Details', 'drppsm' ),
				'object_types' => array( $this->pt_sermon ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$this->add_date_preached( $cmb );
		$this->add_service_type( $cmb );
		$this->add_bible_passage( $cmb );
		$this->add_description( $cmb );
		$this->cmb = $cmb;
		return true;
	}


	/**
	 * Fires after save. Used to seto object terms.
	 *
	 * @param int   $post_ID Post ID.
	 * @param array $updated List of fields that were updated.
	 * @param CMB2  $cmb CMB2 Object.
	 * @return bool
	 * @since 1.0.0
	 */
	public function save( int $post_ID, array $updated, CMB2 $cmb ): bool {
		if ( get_post_type() !== $this->pt_sermon ) {
			return false;
		}
		$this->save_service_type( $post_ID, $cmb->data_to_save );
		$this->save_date( $post_ID, $cmb->data_to_save );
		$this->save_books( $post_ID, $cmb->data_to_save );
		$this->save_series( $post_ID, $cmb->data_to_save );
		Logger::debug( $cmb->data_to_save );
		return true;
	}

	/**
	 * Save series.
	 *
	 * @param int   $post_ID Post ID.
	 * @param array $data Data array.
	 * @return bool Returns true if series was saved, otherwise false.
	 * @since 1.0.0
	 */
	private function save_series( int $post_ID, array $data ): bool {
		// Already set so bail.
		if ( isset( $data['tax_input']['drppsm_series'] ) && ! empty( $data['tax_input']['drppsm_series'] ) ) {
			return false;
		}
		$result = wp_set_post_terms( $post_ID, 'None', 'drppsm_series' );
		if ( is_wp_error( $result ) ) {

			return false;
		}

		return false;
	}

	/**
	 * Save bible books.
	 *
	 * - Uses Main bible passage.
	 * - If books are already set, doesn't update.
	 *
	 * @param int   $post_ID Post ID.
	 * @param array $data Data array.
	 * @return bool Returns true if books we saved.
	 * @since 1.0.0
	 */
	private function save_books( int $post_ID, array $data ): bool {

		// Not set so bail.
		if ( ! isset( $data[ SermonMeta::BIBLE_PASSAGE ] ) ) {
			return false;
		}

		// Already set so bail.
		if ( isset( $data['tax_input']['drppsm_bible'] ) && ! empty( $data['tax_input']['drppsm_bible'] ) ) {
			return false;
		}

		$result  = false;
		$passage = $data[ SermonMeta::BIBLE_PASSAGE ];

		$parts = explode( ',', $passage );
		foreach ( $parts as $value ) {
			$book = explode( ':', $value );
			$book = $book[0];
			$book = trim( substr( $book, 0, -1 ) );

			$term = get_term_by( 'name', $book, 'drppsm_bible' );
			if ( ! $term ) {
				continue;
			}

			$result = wp_set_post_terms( $post_ID, $book, 'drppsm_bible', true );

			if ( is_wp_error( $result ) ) {
				Logger::error( $result->get_error_message() );
				$result = false;
			} else {
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * Save date preached.
	 *
	 * @param int   $post_ID Post ID.
	 * @param array $data Data array.
	 * @return bool Returns true if date was saved, otherwise false.
	 * @since 1.0.0
	 */
	private function save_date( int $post_ID, array $data ): bool {

		// Already set so bail.
		if ( isset( $data[ SermonMeta::DATE ]['date'] ) && ! empty( $data[ SermonMeta::DATE ]['date'] ) ) {
			return false;
		}

		$check = array(
			'year'   => 'aa',
			'month'  => 'mm',
			'day'    => 'jj',
			'hour'   => 'hh',
			'minute' => 'mn',
		);

		// Make sure all keys exist.
		foreach ( $check as $check_key ) {
			if ( ! isset( $data[ $check_key ] ) || empty( $data[ $check_key ] ) ) {
				return false;
			}
		}

		$date_str  = $data[ $check['year'] ] . '-';
		$date_str .= $data[ $check['month'] ] . '-';
		$date_str .= $data[ $check['day'] ] . ' ';
		$date_str .= $data[ $check['hour'] ] . ':' . $data[ $check['minute'] ];

		$date  = date_i18n( 'Y-m-d H:i', strtotime( $date_str ) );
		$stamp = abs( date_round( $date, 'U', 15 ) );

		Logger::debug(
			array(
				'date_str' => $date_str,
				'date'     => $date,
				'rounded'  => $stamp,
			)
		);
		update_post_meta( $post_ID, SermonMeta::DATE, $stamp );

		return true;
	}

	/**
	 * Save service type.
	 *
	 * @param integer $post_ID Post ID.
	 * @param array   $data Data array.
	 * @return bool Returns true if service type was saved, otherwise false.
	 * @since 1.0.0
	 */
	private function save_service_type( int $post_ID, array $data ): bool {
		if ( ! isset( $data[ $this->tax_service_type ] ) ) {
			return false;
		}

		$term = get_term_by(
			'id',
			sanitize_text_field( $data[ $this->tax_service_type ] ),
			$this->tax_service_type
		);

		if ( $term ) {
			$service_type = $term->slug;
		}

		$result = wp_set_object_terms( $post_ID, empty( $service_type ) ? null : $service_type, $this->tax_service_type );
		if ( is_wp_error( $result ) ) {
			Logger::error( $result->get_error_message() );
			return false;
		}
		return true;
	}

	/**
	 * Add date preached field.
	 *
	 * @param CMB2 $cmb CMB object.
	 * @return void
	 * @since 1.0.0
	 *
	 * @todo fix $format.
	 */
	private function add_date_preached( CMB2 $cmb ): void {
		$format = Settings::get( Settings::DATE_FORMAT, Settings::get_default( Settings::DATE_FORMAT ) );

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Date Preached', 'drppsm' ),
				// translators: %1 Date preached.
				// 'desc'         => '<br>' . wp_sprintf( esc_html__( 'format: %s', 'drppsm' ), $format ), .
				'id'           => SermonMeta::DATE,
				'type'         => 'text_datetime_timestamp',
				'autocomplete' => 'off',
			)
		);
	}

	/**
	 * Add service type field.
	 *
	 * @param CMB2 $cmb CMB object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_service_type( CMB2 $cmb ): void {
		/**
		 * Service Type.
		 */
		$cmb->add_field(
			array(
				'name'             => TaxUtils::get_taxonomy_field( $this->tax_service_type, 'singular_name' ),
				'desc'             => wp_sprintf(
					// translators: %1$s The singular label. Default Service Type.
					// translators: %2$s The plural label. Default Service Types.
					// translators: %3$s <a href="edit-tags.php?taxonomy=drppsm_stype&post_type=drppsm" target="_blank">here</a>.
					esc_html__( 'Select the %1$s. Modify the %2$s %3$s.', 'drppsm' ),
					strtolower( TaxUtils::get_taxonomy_field( $this->tax_service_type, 'singular_name' ) ),
					strtolower( TaxUtils::get_taxonomy_field( $this->tax_service_type, 'label' ) ),
					'<a href="' . admin_url( 'edit-tags.php?taxonomy=drppsm_stype&post_type=drppsm' ) . '" target="_blank">here</a>'
				),
				'id'               => $this->tax_service_type,
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => TaxUtils::get_term_options( $this->tax_service_type ),
			)
		);
	}

	/**
	 * Add bible passage field.
	 *
	 * @param CMB2 $cmb CMB object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_bible_passage( CMB2 $cmb ): void {
		$meta = SermonMeta::BIBLE_PASSAGE;

		$desc = wp_sprintf(
			// translators: %1$s Bible book.
			// translators: %2$s The plural label. Bible books.
			esc_html__( 'Enter the Bible passage with the full book names, e.g. %1$s Or multiple books like %2$s', 'drppsm' ),
			'<code>' . __( 'John 3:16-18', 'drppsm' ) . '</code><br>',
			'<code>' .
				__( 'John 3:16-18, Luke 2:1-3', 'drppsm' ) .
			'</code>'
		);
		$cmb->add_field(
			array(
				'name' => __( 'Main Bible Passage', 'drppsm' ),
				'desc' => $desc,
				'id'   => $meta,
				'type' => 'text',
			)
		);
	}

	/**
	 * Add description field
	 *
	 * @param CMB2 $cmb CMB object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_description( CMB2 $cmb ): void {

		/**
		 * Description meta.
		 */
		$meta = SermonMeta::DESCRIPTION;
		$cmb->add_field(
			array(
				'id'      => $meta,
				'name'    => __( 'Description', 'drppsm' ),
				'desc'    => __(
					'Type a brief description about this sermon, an outline, or a full manuscript',
					'drppsm'
				),
				'type'    => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 7,
					'media_buttons' => true,
				),
			)
		);
	}
}
