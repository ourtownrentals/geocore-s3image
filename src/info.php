<?php
/**
 * Addon information class.
 *
 * @package S3ImageAddon
 */
class addon_s3image_info
{
    public $name = 's3image';
    public $version = '1.0.0';
    public $core_version_minimum = '17.0.0';
    public $title = 'S3 Image Addon';
    public $author = 'OurTownRentals.com';
    public $description = '';
    public $auth_tag = 'otr_addons';
    public $author_url = 'https://evansosenko.com';
    public $info_url = 'https://github.com/ourtownrentals/geocore-s3image';

    public $core_events = array (
        'notify_image_insert'
    );
}
