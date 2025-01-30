/**
 * Admin jquery script.
 *
 * @package Proclaim Sermon Manager
 * @since 1.0.0
 *
 */

(function ($) {
	var drppsm_admin = {
		remove_url_parameter(url, parameter) {
			// Create a URL object
			var urlObj = new URL(url);

			// Get the search parameters
			var params = new URLSearchParams(urlObj.search);

			// Delete the specified parameter
			params.delete(parameter);

			// Reconstruct the URL
			urlObj.search = params.toString();

			// Return the updated URL
			return urlObj.toString();
		}
	};
	$(document).ready(
		function () {

			if ($('#drppsm-custom-filters').length > 0) {

				$('#search-submit').off('click');
				$('#search-submit').off('mousedown');
				$('#search-submit').click(
					function (e) {

						e.preventDefault();

						var url = '';
						var url_main = window.location.href;

						var val = $('#post-search-input').val();

						var pieces = ['drppsm_preacher', 'drppsm_series', 'drppsm_stype', 'drppsm_topics'];
						for (var i = 0; i < pieces.length; i++) {

							url = drppsm_admin.remove_url_parameter(url_main, pieces[i]);
							console.log(url);
						}

						if (url.indexOf('?') > -1) {
							window.location.href = url + '&s=' + val;
						} else {
							window.location.href = url + '?s=' + val;
						}

					}
				)
			}

			if ($('#drppsm-custom-filters').length > 0) {

				$('input[name="post_type"]').val('drppsm_sermon');

				var id = "#drppsm-filter"
				if (!$(id).hasClass('drppsm-dnone')) {
					$(id).addClass('drppsm-dnone');
				}
				$('#drppsm-custom-filters').off('click');
				$('#drppsm-custom-filters').click(
					function () {
						var id = "#drppsm-filter"
						if ($(id).hasClass('drppsm-dnone')) {
							$(id).removeClass('drppsm-dnone');
						} else {
							$(id).addClass('drppsm-dnone');
						}
					}
				);

				$('#drppsm-filter-reset').click(
					function () {
						let url = $(this).data('url');
						location.replace(url);

					}
				);

				$('#post-query-submit').click(
					function (e) {
						e.preventDefault();

						let ctrl = '#dropdown_drppsm_stype';
						if ($(ctrl).val() === '') {
							$(ctrl).prop('disabled', true);
						}

						ctrl = '#dropdown_drppsm_preacher';
						if ($(ctrl).val() === '') {
							$(ctrl).prop('disabled', true);
						}

						ctrl = '#dropdown_drppsm_series';
						if ($(ctrl).val() === '') {
							$(ctrl).prop('disabled', true);
						}

						if ($('input[name="post_status"]').val() === 'all') {
							$('input[name="post_status"]').prop('disabled', true);
						}

						$('#post-search-input').prop('disabled', true);
						$('#bulk-action-selector-top').prop('disabled', true);
						$('#bulk-action-selector-bottom').prop('disabled', true);
						$('#current-page-selector').prop('disabled', true);

						ctrl = '#filter-by-date';
						if ($(ctrl).val() === '0') {
							$(ctrl).prop('disabled', true);
						}

						$('#posts-filter').submit();
					}
				);
			}

		}
	);
})(jQuery);
