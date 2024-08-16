/**
 * Admin js / jquery.
 *
 * @package Sermon Manager
 *
 * @since 1.0.0
 *
 */

(function ($) {
	$(document).ready(function () {

		if ($('input[name="post_type"]').length > 0) {
			let pt = $('input[name="post_type"]').val();

			if (pt === 'drppsm_sermon') {

				var id = "#drppsm-filter"
				if (!$(id).hasClass('display-none')) {
					$(id).addClass('display-none');
				}

				$('#drppsm-custom-filters').click(function () {
					var id = "#drppsm-filter"
					if ($(id).hasClass('display-none')) {
						$(id).removeClass('display-none');
					} else {
						$(id).addClass('display-none');
					}
				});

				$('#drppsm-filter-reset').click(function () {
					let url = $(this).data('url');
					location.replace(url);

				});

				$('#post-query-submit').click(function (e) {
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

					// current-page-selector
					$('#post-search-input').prop('disabled', true);
					$('#bulk-action-selector-top').prop('disabled', true);
					$('#bulk-action-selector-bottom').prop('disabled', true);
					$('#current-page-selector').prop('disabled', true);

					ctrl = '#filter-by-date';
					if ($(ctrl).val() === '0') {
						$(ctrl).prop('disabled', true);
					}

					$('#posts-filter').submit();
				});
			}
		}

	});
})(jQuery);
