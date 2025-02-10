<?php
/**
 * Template files.
 *
 * @package     DRPPSM\TemplateFiles
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Template files.
 *
 * @package     DRPPSM\TemplateFiles
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TemplateFiles implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Post type
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $pt;

	/**
	 * Path for plugin templates.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $path_plugin;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt          = DRPPSM_PT_SERMON;
		$this->path_plugin = array(
			DRPPSM_PATH . 'views/partials/',
			DRPPSM_PATH . 'views/template-parts/',
			DRPPSM_PATH . 'views/',
		);
	}

	/**
	 * Register hooks.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( wp_is_block_theme() ) {
			return false;
		}

		if ( has_filter( 'template_include', array( $this, 'template_include' ) ) ) {
			return false;
		}

		add_filter( 'template_include', array( $this, 'template_include' ), 10, 1 );
		return true;
	}

	/**
	 * Include template.
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

		Logger::debug(
			array(
				'TEMPLATE'     => $template,
				'DEFAULT FILE' => $default_file,
			)
		);

		if ( $default_file ) {

			// Search theme template directory.

			// @codeCoverageIgnoreStart
			if ( file_exists( get_stylesheet_directory() . '/' . $default_file ) ) {
				return get_stylesheet_directory() . '/' . $default_file;
			}
			// @codeCoverageIgnoreEnd

			return DRPPSM_PATH . 'views/' . $default_file;
		}

		// @codeCoverageIgnoreStart
		return $template;
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Get partial template.
	 * - Will render template output.
	 *
	 * @param string $name File name.
	 * @param array  $args Array of variables to pass to template.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_partial( string $name, array $args = array() ): void {

		$partial = $this->locate_partial( $name, $args );

		if ( $partial ) {
			load_template( $partial, false, $args );
		}
	}

	/**
	 * Locate the path to the template.
	 *
	 * @param string $name Template name.
	 * @param array  $args Arguments array used to pass to filter.
	 * @return null|string
	 * @since 1.0.0
	 */
	public function locate_partial( string $name, array $args = array() ): ?string {

		$name    = $this->fix_template_name( $name );
		$partial = $this->get_parial_filter( $name, $args );

		// @codeCoverageIgnoreStart
		if ( $partial ) {
			return $partial;
		}
		// @codeCoverageIgnoreEnd

		$partial = $this->get_partial_theme( $name );

		// @codeCoverageIgnoreStart
		if ( $partial ) {
			return $partial;
		}
		// @codeCoverageIgnoreEnd

		$partial = $this->get_partial_plugin( $name );

		return $partial;
	}

	/**
	 * Make sure template name ends with .php .
	 *
	 * @param string $name Template name.
	 * @return string
	 * @since 1.0.0
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
	 * @param string $name Template name.
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

			// @codeCoverageIgnoreStart
			if ( $partial ) {
				break;
			}
			// @codeCoverageIgnoreEnd
		}

		return $partial;
	}

	/**
	 * Get plugin partial template
	 *
	 * @param string $name Template name.
	 * @return null|string
	 * @since 1.0.0
	 */
	private function get_partial_plugin( string $name ): ?string {
		$partial = null;

		foreach ( $this->path_plugin as $path ) {

			if ( file_exists( $path . $name ) ) {
				$partial = $path . $name;
				break;
			}
		}
		return $partial;
	}


	/**
	 * Allows for changing the name of the template file.
	 *
	 * @param string $name Template name.
	 * @param mixed  $args Arguments to pass to template.
	 * @return mixed
	 * @since 1.0.0
	 */
	private function get_parial_filter( string $name, $args ) {
		// Save orginal name.
		$name_org = $name;

		/**
		 * Filter to allow changing the name of the template file.
		 *
		 * @param string $name File name.
		 * @param array  $args Array of variables to pass to template.
		 * @return string $name File name.
		 *
		 * @category filter
		 * @since 1.0.0
		 */
		$name = apply_filters( 'drppsm_tpl_partial', $name, $args );

		if ( $name !== $name_org ) {
			// @codeCoverageIgnoreStart
			if ( ! file_exists( $name ) ) {
				return $name;
			}
			// @codeCoverageIgnoreEnd
		}
		return null;
	}
}
