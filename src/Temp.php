<?php

add_shortcode(
	'taxography',
	function ( $atts ) {
		$attributes = shortcode_atts(
			array(
				'terms_per_page' => 12,
				'taxonomy'       => 'books',
			// Add any attribute which you seem fit
			),
			$atts,
			'taxography'
		);

		// Sanitize and validate our inputs and set variables
		$tpp      = filter_var( $attributes['terms_per_page'], FILTER_VALIDATE_INT );
		$taxonomy = filter_var( $attributes['taxonomy'], FILTER_SANITIZE_STRING );

		// Make sure our taxonomy exists to avoid unnecessary work
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}

		// Our taxonomy exists, lets continue
		// Get the term count to calculate pagination.
		$term_count = get_terms( $taxonomy, array( 'fields' => 'count' ) );

		// Check if we have terms to avoid bugs
		if ( ! $term_count ) {
			return false;
		}

		// We have terms, now calculate pagination
		$max_num_pages = ceil( $term_count / $tpp );
		// Get current page number. Take static front pages into account as well
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		// Calculate term offset
		$offset = ( ( $paged - 1 ) * $tpp );

		// We can now get our terms and paginate it
		$args = array(
			'number' => $tpp, // Amount of terms to return
			'offset' => $offset, // The amount to offset the list by for pagination
		);
		// Set our variable to hold our string
		$output  = '';
		$wpbtags = get_terms( $taxonomy, $args );
		$output .= '<div class="grid"><div class="taxography-grid"><ul>';
		foreach ( $wpbtags as $tag ) {
			$output .= '<li class="item"><a href="' . get_term_link( $tag, $taxonomy ) . '" style="background-image: url(\'http://localhost/wordpress/wp-content/uploads/books/' . $tag->slug . '.png\')"><span class="count">' . $tag->count . '</span><span class="taxography-name">' . $tag->name . '</span></a></li>';
		}
		$output .= '</ul></div></div>';

		// Add our pagination links, I have used the default 'get_*_posts_link()'. Adjust accordingly
		$output .= get_next_posts_link( 'Next Terms', $max_num_pages ) . '</br>';
		$output .= get_previous_posts_link( 'Previous Terms' ) . '</br>';

		return $output;
	}
);
