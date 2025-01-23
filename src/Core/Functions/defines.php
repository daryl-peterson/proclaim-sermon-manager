<?php
/**
 * Define statements here.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

if ( defined( 'DRPPSM_DEFINES' ) ) {
	return;
}

define( 'DRPPSM_DEFINES', true );

/**
 * UNIT TESTING
 */
define(
	'DRPPSM_TESTING',
	'PHPUNIT_TESTING'
);

/**
 * SERMON DEFINES
 */
define(
	'DRPPSM_SERMON_ORDER_BY',
	array(
		'date',
		'preached',
		'date_preached',
		'published',
		'date_published',
		'id',
		'none',
		'title',
		'name',
		'rand',
		'comment_count',
		'post_date',
	)
);


define( 'DRPPSM_PT_SERMON', 'drppsm_sermon' );



/*
|------------------------------------------------------------------------------
| TAXONOMY DEFINES
|------------------------------------------------------------------------------
| This sections begins the list of defined taxonomies.
*/

/**
 * Taxomomy Capabilities.
 *
 * #### Capabilities
 * - **manage_terms**
 * - **edit_terms**
 * - **delete_terms**
 * - **assign_terms**
 *
 * @since 1.0.0
 */
define(
	'DRPPSM_TAX_CAPS',
	array(
		'manage_terms' => 'manage_categories_drppsm_sermon',
		'edit_terms'   => 'manage_categories_drppsm_sermon',
		'delete_terms' => 'manage_categories_drppsm_sermon',
		'assign_terms' => 'manage_categories_drppsm_sermon',
	)
);

/**
 * Bible taxonomy.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_TAX_BOOK', 'drppsm_bible' );

/**
 * Preacher taxonomy.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_TAX_PREACHER', 'drppsm_preacher' );

/**
 * Service type taxonomy.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_TAX_SERVICE_TYPE', 'drppsm_stype' );

/**
 * Series taxonomy.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_TAX_SERIES', 'drppsm_series' );

/**
 * Topics taxonomy.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_TAX_TOPIC', 'drppsm_topics' );

/**
 * Taxomomy mapping friendly to unfriendly.
 *
 * @since 1.0.0
 */
define(
	'DRPPSM_TAX_MAP',
	array(
		'books'         => 'drppsm_bible',
		'preachers'     => 'drppsm_preacher',
		'series'        => 'drppsm_series',
		'service_types' => 'drppsm_stype',
		'topics'        => 'drppsm_topics',
	)
);

define(
	'DRPPSM_TAX_LIST',
	array(
		'drppsm_preacher',
		'drppsm_series',
		'drppsm_stype',
		'drppsm_bible',
		'drppsm_topics',
	)
);

define(
	'DRPPSM_TAX_VISIBILITY_MAP',
	array(
		'drppsm_bible'    => 'hide_books',
		'drppsm_dates'    => 'hide_dates',
		'drppsm_preacher' => 'hide_preachers',
		'drppsm_series'   => 'hide_series',
		'drppsm_stype'    => 'hide_service_types',
		'drppsm_topics'   => 'hide_topics',
	)
);

/*
|------------------------------------------------------------------------------
| SHORTCODES DEFINES
|------------------------------------------------------------------------------
| This sections begins the list of defined shortcodes.
*/

/**
 * Latest series shortcode.
 *
 * @since 1.0.0
 * #### Atts parameter
 * *defaults shown with ()*
 * - **filter_by** :Options "series", "preachers", "topics", "books", "service_type"
 * - **filter_value** : Use the "slug" related to the taxonomy field you want to filter by. ('')
 * - **image_size** : { proclaim_small, proclaim_medium, proclaim_wide, thumbnail, medium, large, full } ect.
 * - **per_page** : Number of sermons to display. (10)
 * - **order** : "DESC" for descending; "ASC" for ascending. (DESC)
 * - **orderby** : Options "date", "id", "none", "title", "name", "rand", "comment_count"
 */
define( 'DRPPSM_SC_SERIES_LATEST', 'drppsm_series_latest' );

/**
 * Latest sermon shortcode.
 *
 * @since 1.0.0
 * #### Atts parameter
 * *defaults shown with ()*
 * - **filter_by** :Options "series", "preachers", "topics", "books", "service_type"
 * - **filter_value** : Use the "slug" related to the taxonomy field you want to filter by. ('')
 * - **image_size** : { proclaim_small, proclaim_medium, proclaim_wide, thumbnail, medium, large, full } ect.
 * - **per_page** : Number of sermons to display. (10)
 * - **order** : "DESC" for descending; "ASC" for ascending. (DESC)
 * - **orderby** : Options "date", "id", "none", "title", "name", "rand", "comment_count"
*/
define( 'DRPPSM_SC_SERMON_LATEST', 'drppsm_sermon_latest' );

/**
 * Podcast list shortcode.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_SC_LIST_PODCAST', 'drppsm_list_podcasts' );

/**
 * Terms list shortcode.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_SC_TERMS', 'drppsm_terms' );

/**
 * Sermons shortcode.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_SC_SERMONS', 'drppsm_sermons' );

/**
 * Sermon images shortcode.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_SC_SERMON_IMAGES', 'drppsm_sermon_images' );

/**
 * Sermon sorting shortcode.
 *
 * @since 1.0.0
 */
define( 'DRPPSM_SC_SERMON_SORTING', 'drppsm_sermon_sorting' );






/*
|------------------------------------------------------------------------------
| MESSAGES
|------------------------------------------------------------------------------
| This sections begins the list of defined messages.
*/

/**
 * File does not exist message.
 *
 * @since 1.0.0
 */
define(
	'DRPPSM_MSG_FILE_NOT_EXIST',
	__( 'File does not exist.', 'drppsm' )
);

/**
 * Failed loading partial file.
 *
 * @since 1.0.0
 */
define(
	'DRPPSM_MSG_FAILED_PARTIAL',
	__( 'Failed loading partial file.', 'drppsm' )
);


define(
	'DRPPSM_MSG_LABEL_SINGLE',
	__( 'The label should be in the singular form.', 'drppsm' )
);

define(
	'DRPPSM_MSG_LABEL_PLURAL',
	__( 'The label should be in the plural form.', 'drppsm' )
);

define(
	'DRPPSM_MSG_SLUG_NOTE',
	__( 'Note: This also changes the slugs.', 'drppsm' )
);



/*
|------------------------------------------------------------------------------
| Filters
|------------------------------------------------------------------------------
| This sections begins the list of defined filters.
*/

define( 'DRPPSMF_RENDER_SORTING2', 'drppsmf_render_sorting' );


/**
 * Allows for filtering admin sermon inputs.
 * - Filters are prefixed with drppsmf_
 *
 * @param array $output HTML Inputs for admin sermons page.
 * @since 1.0.0
 */
define( 'DRPPSMF_ADMIN_SERMON', 'drppsmf_admin_sermons' );


/**
 * Get pagination links
 * - Filters are prefixed with drppsmf_
 *
 * @param integer $items Total records.
 * @param integer $limit Per page.
 * @param integer $page Page number.
 * @return string
 * @since 1.0.0
 */
define( 'DRPPSMF_PAGINATION_GET', 'drppsmf_pagination_get' );



/**
 * Filters the date a post was preached
 *
 * @param string $date                  Modified and sanitized date
 * @param string $orig_date             Original date from the database
 * @param string $format                Date format
 * @param bool   $force_unix_sanitation If the sanitation is forced
 * @since 1.0.0
 */
define( 'DRPPSMF_SERMON_DATES', 'drppsmf_sermon_dates' );

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
| This sections begins the list of defined actions.
*/


/**
 * Action after template error occurs.
 * - Actions are prefixed with drppsma_
 *
 * @return void
 * @since 1.0.0
 */
define( 'DRPPSMA_TPL_ERROR', 'drppsma_tpl_error' );

/**
 * Action to flush rewrite rules.
 * - Actions are prefixed with drppsma_
 *
 * @return void
 * @since 1.0.0
 */
define( 'DRPPSMA_FLUSH_REWRITE', 'drppsma_flush_rewrite' );

/**
 * Action after schedule is registered.
 * - Actions are prefixed with drppsma_
 *
 * @return void
 * @since 1.0.0
 */
define( 'DRPPSMA_SCHEDULE_REGISTERED', 'drppsma_schedule_registered' );

/**
 * Action that runs per schedule.
 *
 * @return void
 * @since 1.0.0
 */
define( 'DRPPSMA_JOB', 'drppsma_job' );

/**
 * Action that runs per schedule.
 *
 * @return void
 * @since 1.0.0
 */
define( 'DRPPSMA_SCHEDULER', 'drppsma_scheduler' );

















define(
	'DRPPSM_SETTINGS_COMMENTS_NAME',
	__( 'Allow Comments', 'drppsm' )
);


define(
	'DRPPSM_SETTINGS_PLAYER_NAME',
	__( 'Audio & Video Player', 'drppsm' )
);

define(
	'DRPPSM_SETTINGS_MENU_ICON_NAME',
	__( 'Menu Icon', 'drppsm' )
);

define(
	'DRPPSM_SETTINGS_DATE_FORMAT_NAME',
	__( 'Sermon Date Format', 'drppsm' )
);

define(
	'DRPPSM_SETTINGS_SERMON_COUNT_NAME',
	__( 'Sermons Per Page', 'drppsm' )
);

define(
	'DRPPSM_SETTING_PREACHER_LABEL',
	__( 'Preacher', 'drppsm' )
);
