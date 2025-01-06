<?php

/**
 * Template class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Exception;

/**
 * Template class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Templates implements Executable, Registrable {

	/**
	 * Post type
	 *
	 * @var string
	 */
	private string $pt;

	/**
	 * Bible taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_bible;

	/**
	 * Preacher taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_preacher;

	/**
	 * Series taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_series;


	/**
	 * Service type taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_service_type;


	/**
	 * Topics taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_topics;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt               = DRPPSM_PT_SERMON;
		$this->tax_bible        = DRPPSM_TAX_BIBLE;
		$this->tax_preacher     = DRPPSM_TAX_PREACHER;
		$this->tax_series       = DRPPSM_TAX_SERIES;
		$this->tax_service_type = DRPPSM_TAX_SERVICE_TYPE;
		$this->tax_topics       = DRPPSM_TAX_TOPICS;
	}

	/**
	 * Initialize object and register hooks.
	 *
	 * @return Templates
	 * @since 1.0.0
	 */
	public static function exec(): Templates {

		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return bool Returns true if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( has_filter( 'template_include', array( $this, 'template_include' ) ) ) {
			return false;
		}
		add_filter( 'template_include', array( $this, 'template_include' ), 10, 1 );
		return true;
	}

	/**
	 * Include template
	 *
	 * @param string $template Template name.
	 * @return string
	 * @since 1.0.0
	 */
	public function template_include( string $template ): string {

		$default_file = '';

		if ( is_singular( $this->pt ) ) {
			$default_file = $this->get_single_template();
		} elseif ( is_tax( get_object_taxonomies( $this->pt ) ) ) {
			$default_file = $this->get_tax_template();
		} elseif ( is_post_type_archive( $this->pt ) ) {
			$default_file = $this->get_archive_template();
		}

		if ( $default_file ) {

			// Search theme
			if ( file_exists( get_stylesheet_directory() . '/' . $default_file ) ) {
				return get_stylesheet_directory() . '/' . $default_file;
			}

			return DRPPSM_PATH . 'views/' . $default_file;
		}

		return $template;
	}

	/**
	 * Get partial template.
	 * - Will render template output.
	 *
	 * @param string $name File name.
	 * @param array  $args Array of variables to pass to template.
	 * @return void
	 * @since 1.0.0
	 */
	public function get_partial( string $name, array $args = array() ): void {

		// Save orginal name.
		$name_org = $name;

		/**
		 * Allows for filtering the name of the template with path.
		 * - Filters are prefixed with drppsmf_
		 *
		 * @param string $name File name.
		 * @param array  $args Array of variables to pass to template.
		 * @return string $name File name.
		 * @category filter
		 * @since 1.0.0
		 */
		$name = apply_filters( 'drppsmf_tpl_partial', $name, $args );
		if ( $name !== $name_org ) {
			if ( file_exists( $name ) ) {
				load_template( $name, false, $args );
				return;
			}
			$name = $name_org;
		}

		$name    = $this->fix_template_name( $name );
		$partial = $this->get_partial_theme( $name );

		if ( ! $partial ) {
			$partial = $this->get_partial_plugin( $name );
		}

		if ( $partial ) {
			load_template( $partial, false, $args );
		} else {
			$this->template_error( $name );
			Logger::error(
				array(
					'NAME' => $name,
					'ARGS' => $args,
				)
			);
		}
	}

	/**
	 * Make sure template name ends with .php .
	 *
	 * @param string $name
	 * @return string
	 */
	private function fix_template_name( string $name ): string {
		if ( false === strpos( $name, '.php' ) ) {
			$name .= '.php';
		}
		return $name;
	}

	/**
	 * Get archive template.
	 *
	 * @return null|string Return file name if true,otherwise null.
	 * @since 1.0.0
	 */
	private function get_archive_template(): ?string {
		if ( ! is_post_type_archive( $this->pt ) ) {
			Logger::debug( 'NOT A ARCHIVE TEMPLATE' );
			return null;
		}
		Logger::debug( "IT'S A ARCHIVE TEMPLATE" );
		return "archive-{$this->pt}.php";
	}

	/**
	 * Get single template.
	 *
	 * @return null|string Return file name if true,otherwise null.
	 * @since 1.0.0
	 */
	private function get_single_template(): ?string {
		if ( ! is_singular( $this->pt ) ) {
			Logger::debug( 'NOT SINGLE TEMPLATE' );
			return null;
		}
		Logger::debug( "IT'S A SINGLE TEMPLATE" );
		return "single-{$this->pt}.php";
	}

	/**
	 * Get taxonomy template.
	 *
	 * @return null|string Return file name if true,otherwise null.
	 * @since 1.0.0
	 */
	private function get_tax_template(): ?string {
		$term = get_queried_object();
		return "taxonomy-{$term->taxonomy}.php";
	}

	/**
	 * Get partial from theme.
	 *
	 * @param string $name
	 * @return null|string
	 * @since 1.0.0
	 */
	private function get_partial_theme( string $name ): ?string {
		$partial = null;

		$paths = array(
			'partials/',
			'template-parts/',
			'',
		);

		foreach ( $paths as $path ) {
			$search  = $path . $name;
			$partial = locate_template( $search );

			if ( $partial ) {
				break;
			}
		}

		return $partial;
	}

	/**
	 * Get plugin partial template
	 *
	 * @param string $name
	 * @return null|string
	 * @since 1.0.0
	 */
	private function get_partial_plugin( string $name ): ?string {
		$partial = null;

		$search = array(
			DRPPSM_PATH . 'views/partials/',
			DRPPSM_PATH . 'views/template-parts/',
		);
		foreach ( $search as $path ) {
			if ( file_exists( $path . $name ) ) {
				$partial = $path . $name;
				break;
			}
		}
		return $partial;
	}

	/**
	 * Display template error
	 *
	 * @param string $name
	 * @return void
	 * @since 1.0.0
	 */
	private function template_error( string $name ): void {

		$title = DRPPSM_TITLE;
		$error = DRPPSM_MSG_FAILED_PARTIAL . " $name . " . DRPPSM_MSG_FILE_NOT_EXIST;

		$html = '';
		if ( did_action( DRPPSMA_TPL_ERROR ) ) {
			return;
		}

		$html .= <<<HTML

				<div class="drppsm-error-wrap">
					<article class="drppsm-template-error">
						<b>$title</b>:<i>$error</i>
					</article>
				</div>
		HTML;
		echo $html;
		do_action( DRPPSMA_TPL_ERROR );
	}
}
