/**
 * Fontend jquery script.
 *
 * @package Proclaim Sermon Manager
 * @since 1.0.0
 */

(function ($) {
	var drppsm_fe = {
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

			$('.drppsm-play-video').click(
				function (e) {
					e.preventDefault();

					var id = $(this).data('id');
					var url = drppsm_fe.remove_url_parameter(window.location.href, 'play');

					if (url.indexOf('?') > -1) {
						window.location.href = url + '&play=' + id;
					} else {
						window.location.href = url + '?play=' + id;
					}

				}
			)

		}

	);
})(jQuery);
