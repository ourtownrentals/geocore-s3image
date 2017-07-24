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
        $admin = geoAdmin::getInstance();
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

        $s3_status = 'Unknown';
        try {
            $credentials = new Aws\Credentials\Credentials(
                $settings['aws_key'],
                $settings['aws_secret']
            );
            $s3 = new Aws\S3\S3Client([
                'region'      => $settings['aws_region'],
                'version'     => '2006-03-01',
                'credentials' => $credentials
            ]);
            $s3_status = $s3->headBucket([
                'Bucket' => $settings['s3_bucket'],
            ]);
            $s3_status = 'OK';
        } catch (Exception $e) {
            $admin->userError($e->getMessage());
            $s3_status = 'Error';
        }

        $tpl_vars = array();
        $tpl_vars['admin_messages'] = geoAdmin::m();
        $tpl_vars['settings'] = $settings;
        $tpl_vars['s3_status'] = $s3_status;

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
