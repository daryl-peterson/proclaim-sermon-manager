/**
 * Admin js / jquery.
 *
 * @package Sermon Manager
 * @since 1.0.0
 */

(function ($) {
	$( document ).ready(
		function () {
			console.log( 'HERE' );

			if ($( '#drppsm-custom-filters' ).length > 0) {

				$( 'input[name="post_type"]' ).val( 'drppsm_sermon' );

				var id = "#drppsm-filter"
				if ( ! $( id ).hasClass( 'drppsm-dnone' )) {
					$( id ).addClass( 'drppsm-dnone' );
				}
				$( '#drppsm-custom-filters' ).off( 'click' );
				$( '#drppsm-custom-filters' ).click(
					function () {
						var id = "#drppsm-filter"
						if ($( id ).hasClass( 'drppsm-dnone' )) {
							$( id ).removeClass( 'drppsm-dnone' );
						} else {
							$( id ).addClass( 'drppsm-dnone' );
						}
					}
				);

				$( '#drppsm-filter-reset' ).click(
					function () {
						let url = $( this ).data( 'url' );
						location.replace( url );

					}
				);

				$( '#post-query-submit' ).click(
					function (e) {
						e.preventDefault();

						let ctrl = '#dropdown_drppsm_stype';
						if ($( ctrl ).val() === '') {
							$( ctrl ).prop( 'disabled', true );
						}

						ctrl = '#dropdown_drppsm_preacher';
						if ($( ctrl ).val() === '') {
							$( ctrl ).prop( 'disabled', true );
						}

						ctrl = '#dropdown_drppsm_series';
						if ($( ctrl ).val() === '') {
							$( ctrl ).prop( 'disabled', true );
						}

						if ($( 'input[name="post_status"]' ).val() === 'all') {
							$( 'input[name="post_status"]' ).prop( 'disabled', true );
						}

						$( '#post-search-input' ).prop( 'disabled', true );
						$( '#bulk-action-selector-top' ).prop( 'disabled', true );
						$( '#bulk-action-selector-bottom' ).prop( 'disabled', true );
						$( '#current-page-selector' ).prop( 'disabled', true );

						ctrl = '#filter-by-date';
						if ($( ctrl ).val() === '0') {
							$( ctrl ).prop( 'disabled', true );
						}

						$( '#posts-filter' ).submit();
					}
				);
				// }
			}

		}
	);
})( jQuery );
