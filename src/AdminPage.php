<?php

namespace DRPSermonManager;

use DRPSermonManager\Logging\Logger;

/**
 * Create admin settings page for plugin.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class AdminPage
{
    private $settings;
    public string $settingsName;
    private string $page;

    private string $slug;
    private string $optionGroup;
    private string $section;
    public array $fields;

    public function __construct()
    {
        $this->page = Helper::getKeyName('admin');
        $this->section = Helper::getKeyName('section');
        $this->settingsName = Helper::getKeyName('settings');
        $this->slug = Helper::getSlug();
        $this->optionGroup = Helper::getKeyName('option_group');

        $this->fields = [
            'client_id' => ['name' => 'client_id', 'label' => 'Client ID', 'type' => 'text'],
            'client_secret' => ['name' => 'client_secret', 'label' => 'Client Secret', 'type' => 'text'],
            'access_token' => ['name' => 'access_token', 'label' => 'Access Token', 'type' => 'text'],
            'match' => ['name' => 'match', 'label' => 'Match', 'type' => 'select', 'options' => ['date' => 'Date', 'name' => 'Name']],
        ];
    }

    public function init(): void
    {
        if (!is_admin()) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        try {
            return;
            $hook = Helper::getKeyName('ADMIN_PAGE_INIT');

            if (did_action($hook) && !defined('PHPUNIT_TESTING')) {
                // @codeCoverageIgnoreStart
                return;
                // @codeCoverageIgnoreEnd
            }
            add_action('admin_menu', [$this, 'addMenuPage']);
            add_action('admin_init', [$this, 'pageInit']);

            Logger::debug('ADMIN PAGE HOOKS INITIALIZED');
            do_action($hook);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Add menu page.
     * - Called from action admin_menu.
     */
    public function addMenuPage(): string|bool
    {
        return add_submenu_page(
            'edit.php?post_type=wpfc_sermon',
            __('Sermon Manager Settings', 'sermon-manager-for-wordpress'),
            'Vimeo',
            'manage_options',
            $this->slug,
            [$this, 'showAdminPage']
        );
    }

    /**
     * Display admin page.
     * - Called from add_submenu_page.
     *
     * @see https://developer.wordpress.org/reference/functions/get_option/
     */
    public function showAdminPage(): void
    {
        $this->settings = get_option($this->settingsName);

        ob_start();
        settings_fields($this->optionGroup);
        do_settings_sections($this->page);
        submit_button();
        $settings = ob_get_clean();

        ob_start();
        settings_errors();
        $errors = ob_get_clean();
        $name = NAME;

        $html = <<< EOF
        $errors
		<div class="wrap">
			<h2>$name</h2>
			<p>Vimeo API Settings</p>

			<form method="post" action="options.php">
                $settings
			</form>
		</div>
EOF;
        echo $html;
    }

    /**
     * Register page settings.
     *
     * - Called from admin_init
     *
     * @see https://developer.wordpress.org/reference/functions/register_setting/
     * @see https://developer.wordpress.org/reference/functions/add_settings_section/
     */
    public function pageInit(): void
    {
        try {
            register_setting(
                $this->optionGroup,     // option_group
                $this->settingsName,    // option_name
                [$this, 'sanitize']     // sanitize_callback
            );

            add_settings_section(
                $this->section,             // id
                'Settings',                 // title
                [$this, 'showSectionInfo'], // callback
                $this->page                 // page
            );

            foreach ($this->fields as $fieldInfo) {
                $type = $fieldInfo['type'];
                switch ($type) {
                    case 'text':
                        $this->addField($fieldInfo['name'], $fieldInfo['label'], 'showTextField');
                        break;
                    case 'select':
                        $this->addField($fieldInfo['name'], $fieldInfo['label'], 'showSelectField');
                        break;
                }
            }
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error($th->getMessage());
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Santize form input.
     * - Callback from regsiter_setting.
     */
    public function sanitize(array $input): array
    {
        $sanitary_values = [];

        foreach ($this->fields as $fieldInfo) {
            $name = $fieldInfo['name'];
            $type = $fieldInfo['type'];
            if (isset($input[$name])) {
                switch ($type) {
                    case 'text':
                        $sanitary_values[$name] = sanitize_text_field($input[$name]);
                        break;
                    case 'select':
                        $sanitary_values[$name] = $input[$name];
                        break;
                }
            }
        }

        return $sanitary_values;
    }

    public function showSectionInfo()
    {
    }

    /**
     * Display text field on form.
     * - Called from pageInit.
     */
    public function showTextField(string $field): void
    {
        printf(
            '<input class="regular-text" type="text" name="%s[%s]" id="%s" value="%s">',
            $this->settingsName,
            $field,
            $field,
            isset($this->settings[$field]) ? esc_attr($this->settings[$field]) : ''
        );
    }

    /**
     * Display select field on form.
     * - Called from pageInit.
     */
    public function showSelectField(string $field): void
    {
        $data = $this->fields[$field];
        $options = $data['options'];

        printf(
            '<select name="%s[%s]" id="%s">',
            $this->settingsName,
            $field,
            $field
        );

        foreach ($options as $key => $text) {
            $selected = '';

            if (isset($this->settings[$field])) {
                if ($key === $this->settings[$field]) {
                    $selected = ' selected';
                }
            }
            printf(
                '<option value="%s"%s>%s</option>',
                $key,
                $selected,
                $text
            );
        }
        echo "</select>\n";
    }

    /**
     * Register fields.
     */
    private function addField(string $field, string $label, string $cbName): void
    {
        add_settings_field(
            $field,             // id
            $label,             // title
            [$this, $cbName],   // callback
            $this->page,        // page
            $this->section,     // section
            $field
        );
    }
}
