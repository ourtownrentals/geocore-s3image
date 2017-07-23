<?php
/**
 * @package S3ImageAddon
 */
class addon_s3image_admin extends addon_s3image_info
{
    public function init_pages ($menuName)
    {
        menu_page::addonAddPage(
            'addon_s3image_settings',
            '',
            'Settings',
            $this->name
        );
    }

    public function display_addon_s3image_settings ()
    {
        $reg = geoAddon::getRegistry($this->name);

        // Load existing settings or submitted settings on error.
        if ($reg->settings_with_error) {
            $settings = $reg->settings_with_error;
        } elseif ($reg->settings) {
            $settings = $reg->settings;
        }

        // Unset settings_with_error to avoid loop.
        $reg->settings_with_error = null;
        $reg->save();

        $tpl_vars = array();
        $tpl_vars['admin_messages'] = geoAdmin::m();
        $tpl_vars['settings'] = $settings;

        geoView::getInstance()
            ->setBodyTpl('admin/settings.tpl', $this->name)
            ->setBodyVar($tpl_vars);
    }

    public function update_addon_s3image_settings ()
    {
        if (isset($_POST['settings'])) {
            $settings = $_POST['settings'];
        } else {
            return false;
        }

        $admin = geoAdmin::getInstance();
        $reg = geoAddon::getRegistry($this->name);

        $errors = $this->checkSettingsInput($settings);

        // If there was an error add each error to the list of admin messages.
        if ($errors) {
            foreach ($errors as $error) {
                $admin->userError($error);
            }

            $admin->userError('Settings not saved.');

            $reg->settings_with_error = $settings;
            $reg->save();
            return false;
        } else {
            $reg->settings = $settings;
            $reg->save();
            return true;
        }
    }

    private function checkSettingsInput ($input)
    {
        $errors = array();
        return $errors;
    }
}
