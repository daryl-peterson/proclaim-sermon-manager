<?php
/**
 * Template blocks class.
 *
 * - Used for block based themes.
 *
 * @package     DRPPSM\TemplateBlocks
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Template blocks class.
 *
 * - Used for block based themes.
 *
 * @package     DRPPSM\TemplateBlocks
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TemplateBlocks implements Executable, Registrable {

	use ExecutableTrait;

	protected string $pt;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt = 'drppsm_sermon';
	}

	/**
	 * Register hooks.
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( ! wp_is_block_theme() ) {
			return false;
		}

		if ( has_filter( 'get_block_templates', array( $this, 'manage_block_templates' ) ) ) {
			return false;
		}

		add_filter( 'get_block_templates', array( $this, 'manage_block_templates' ), 10, 3 );

		$template_types = array(
			'archive'  => 'add_custom_archive_template',
			'index'    => 'add_custom_index_template',
			'single'   => 'add_custom_single_template',
			'taxonomy' => 'add_custom_taxonomy_template',
		);

		foreach ( $template_types as $type => $callback ) {
			add_filter( "{$type}_template_hierarchy", array( $this, $callback ) );
		}

		return true;
	}

	/**
	 * Manage block templates for the wz_knowledgebase custom post type.
	 *
	 * @param array  $query_result   Array of found block templates.
	 * @param array  $query          Arguments to retrieve templates.
	 * @param string $template_type  $template_type wp_template or wp_template_part.
	 * @return array Updated array of found block templates.
	 * @since 1.0.0
	 */
	public function manage_block_templates( $query_result, $query, $template_type ) {

		Logger::debug(
			array(
				'TEMPLATE QUERY' => $query,
				'TEMPLATE TYPE'  => $template_type,
			)
		);

		if ( 'wp_template' !== $template_type ) {
			return $query_result;
		}

		global $post;
		if ( ( empty( $post ) && ! is_admin() ) || ( ! empty( $post ) && $this->pt !== $post->post_type ) ) {
			Logger::debug( 'NOT OURS' );
			return $query_result;
		}

		$theme        = wp_get_theme();
		$block_source = 'plugin';

		$template_name = null;

		if ( is_singular( $this->pt ) ) {
			$template_name = "single-{$this->pt}";
		} elseif ( is_post_type_archive( $this->pt ) ) {
			$template_name = "archive-{$this->pt}";
		} elseif ( is_tax( get_object_taxonomies( $this->pt ) ) ) {
			// $template_name = str_replace( '.php', '', $this->get_tax_template() );
		}

		if ( ! $template_name ) {
			return $query_result;
		}

		$template_file_path = $theme->get_template_directory() . '/templates/' . $template_name . '.html';
		if ( file_exists( $template_file_path ) ) {
			$block_source = 'theme';
		} else {
			$template_file_path = DRPPSM_PATH . 'views/' . $template_name . '.html';
		}

		$template_contents = self::get_template_content( $template_file_path );
		$template_contents = self::replace_placeholders_with_shortcodes( $template_contents );

		$new_block                 = new \WP_Block_Template();
		$new_block->type           = 'wp_template';
		$new_block->theme          = $theme->stylesheet;
		$new_block->slug           = $template_name;
		$new_block->id             = 'drppsm//' . $template_name;
		$new_block->title          = 'Proclaim Sermon Manager - ' . $template_name;
		$new_block->description    = '';
		$new_block->source         = $block_source;
		$new_block->status         = 'publish';
		$new_block->has_theme_file = true;
		$new_block->is_custom      = true;
		$new_block->content        = $template_contents;
		$new_block->post_types     = array( $this->pt );

		$query_result[] = $new_block;

		Logger::debug(
			array(
				'query_result'  => $query_result,
				'query'         => $query,
				'template_type' => $template_type,
			)
		);

		return $query_result;
	}


	/**
	 * Add custom archive template for custom post type.
	 *
	 * @param array $templates Array of found templates.
	 * @return array Updated array of found templates.
	 * @since 1.0.0
	 */
	public function add_custom_archive_template( $templates ) {

		$tax = get_object_taxonomies( $this->pt );

		if ( is_tax( get_object_taxonomies( $this->pt ) ) ) {
			foreach ( $tax as $taxonomy ) {
				$templates = $this->add_custom_template( $templates, 'archive', $taxonomy, "taxonomy-$taxonomy" );
			}
			Logger::debug( 'TAXONOMY TEMPLATE' );
			return $templates;
		}

		if ( is_singular( $this->pt ) ) {
			Logger::debug( 'SINGLE TEMPLATE' );
			return $this->add_custom_template( $templates, 'single', $this->pt, "single-{$this->pt}" );
		}
		Logger::debug( 'ARCHIVE TEMPLATE' );
		return $this->add_custom_template( $templates, 'archive', $this->pt, "archive-{$this->pt}" );
	}

	/**
	 * Add custom archive template custom post type.
	 *
	 * @param array $templates Array of found templates.
	 * @return array Updated array of found templates.
	 */
	public function add_custom_index_template( $templates ) {
		return $this->add_custom_archive_template( $templates );
	}

	/**
	 * Add custom single template custom post type.
	 *
	 * @param array $templates Array of found templates.
	 * @return array Updated array of found templates.
	 */
	public function add_custom_single_template( $templates ) {
		return $this->add_custom_template( $templates, 'single', $this->pt, "single-{$this->pt}" );
	}

	/**
	 * Add custom taxonomy template for the wzkb_category taxonomy.
	 *
	 * @param array $templates Array of found templates.
	 * @return array Updated array of found templates.
	 * @since 1.0.0
	 */
	public function add_custom_taxonomy_template( $templates ) {

		$tax = get_object_taxonomies( $this->pt );
		foreach ( $tax as $taxonomy ) {
			$templates = $this->add_custom_template( $templates, 'archive', $taxonomy, "taxonomy-$taxonomy" );
		}
		Logger::debug( array( 'TEMPLATES' => $templates ) );

		return $templates;
	}

	/**
	 * Add custom template for the wz_knowledgebase custom post type and wzkb_category taxonomy.
	 *
	 * @param array  $templates Array of found templates.
	 * @param string $type Type of template (archive, single, taxonomy).
	 * @param string $post_type Post type or taxonomy name.
	 * @param string $template_name Template name to add.
	 * @return array Updated array of found templates.
	 * @since 1.0.0
	 */
	private function add_custom_template( $templates, $type, $post_type, $template_name ) {

		$msg = array(
			'TEMPLATES'     => $templates,
			'TYPE'          => $type,
			'POST TYPE'     => $post_type,
			'TEMPLATE NAME' => $template_name,
		);

		if ( in_array( $template_name, $templates, true ) ) {
			Logger::debug( $msg );
			return $templates;
		}

		/*
		if ( in_array( $template_name, $templates, true ) ||
			( in_array( $template_name . '.php', $templates, true ) ) ) {
			Logger::debug( $msg );
			return $templates;
		}
		*/

		if ( in_array( $type, array( 'archive', 'index', 'search' ), true ) ) {
			array_unshift( $templates, $template_name );
			$msg['TEMPLATES'] = $templates;
			Logger::debug( $msg );
			return $templates;
		}

		if ( 'single' === $type && is_singular( $post_type ) ) {
			array_unshift( $templates, $template_name );

			$msg['TEMPLATES'] = $templates;
			Logger::debug( $msg );
			return $templates;
		}
		return $templates;
	}

	/**
	 * Get the content of a template file.
	 *
	 * @param string $template The template file to include.
	 * @return string The content of the template file.
	 * @since 1.0.0
	 */
	public static function get_template_content( $template ) {
		ob_start();
		include $template;
		return ob_get_clean();
	}

	/**
	 * Replaces placeholders with corresponding shortcode output.
	 *
	 * @param string $template_contents The template content containing placeholders.
	 * @return string The updated template with shortcodes replaced by their output.
	 * @since 1.0.0
	 */
	public static function replace_placeholders_with_shortcodes( $template_contents ) {
		// Regular expression to match placeholders like {{shortcode param="value"}}.
		$pattern = '/\{\{([a-zA-Z_]+)(.*?)\}\}/';

		// Callback function to process each match.
		$callback = function ( $matches ) {
			$shortcode = trim( $matches[1] ); // Extract the shortcode name.
			$params    = trim( $matches[2] ); // Extract any parameters.

			// Construct the shortcode with the parameters.
			if ( ! empty( $params ) ) {
				$shortcode_output = '[' . $shortcode . ' ' . $params . ']';
			} else {
				$shortcode_output = '[' . $shortcode . ']';
			}

			// Run the shortcode and return the output.
			return do_shortcode( $shortcode_output );
		};

		// Run the preg_replace_callback to find and replace all placeholders.
		$result = preg_replace_callback( $pattern, $callback, $template_contents );
		return $result;
	}
}
