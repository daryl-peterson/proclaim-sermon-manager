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

			console.log(drppsm_info);

			if (typeof drppsm_info !== 'undefined') {

				$('#drppsm_browse').on('change', function () {

					var slug = $(this).val();
					var path = '';
					switch (slug) {
						case 'drppsm_bible':
							path = drppsm_info.drppsm_bible;
							break;
						case 'drppsm_preacher':
							path = drppsm_info.drppsm_preacher;
							break;
						case 'drppsm_series':
							path = drppsm_info.drppsm_series;
							break;
						case 'drppsm_sermon':
							path = drppsm_info.drppsm_sermon;
							break;
						case 'drppsm_topics':
							path = drppsm_info.drppsm_topics;
							break;
					}
					if (slug !== '') {
						window.location.href = path
					}

				});

				$('#drppsm_series').on('change', function () {
					let path = drppsm_info.drppsm_series;
					let slug = $(this).val();
					if (slug !== '') {
						window.location.href = path + "/" + slug + "/";
					} else {
						window.location.href = path + "/"
					}

				});

				$('#drppsm_preacher').on('change', function () {
					let path = drppsm_info.drppsm_preacher;
					let slug = $(this).val();
					if (slug !== '') {
						window.location.href = path + "/" + slug + "/";
					} else {
						window.location.href = path + "/"
					}
				});

				$('#drppsm_bible').on('change', function () {
					let path = drppsm_info.drppsm_bible;
					let slug = $(this).val();
					if (slug !== '') {
						window.location.href = path + "/" + slug + "/";
					} else {
						window.location.href = path + "/"
					}
				});

				$('#drppsm_topics').on('change', function () {
					let path = drppsm_info.drppsm_topics;
					let slug = $(this).val();
					if (slug !== '') {
						window.location.href = path + "/" + slug + "/";
					} else {
						window.location.href = path + "/"
					}
				});

				$('#drppsm_stype').on('change', function () {
					let path = drppsm_info.drppsm_stype;
					let slug = $(this).val();
					if (slug !== '') {
						window.location.href = path + "/" + slug + "/";
					} else {
						window.location.href = path + "/"
					}
				});
			};

			$('.drppsm-play-video').click(
				function (e) {
					e.preventDefault();

					var id = $(this).data('id');

					var url = '';
					url = drppsm_fe.remove_url_parameter(window.location.href, 'play');
					url = drppsm_fe.remove_url_parameter(url, 'player');

					if (url.indexOf('?') > -1) {
						window.location.href = url + '&play=' + id + '&player=video';
					} else {
						window.location.href = url + '?play=' + id + '&player=video';
					}

				}
			)

			$('.drppsm-play-audio').click(
				function (e) {
					e.preventDefault();

					var id = $(this).data('id');
					var url = '';
					url = drppsm_fe.remove_url_parameter(window.location.href, 'play');
					url = drppsm_fe.remove_url_parameter(url, 'player');

					if (url.indexOf('?') > -1) {
						window.location.href = url + '&play=' + id + '&player=audio';
					} else {
						window.location.href = url + '?play=' + id + '&player=audio';
					}

				}
			)

		}

	);
})(jQuery);
