<?php

require GEO_BASE_DIR . 'vendor/autoload.php';

/**
 * Addon utility class.
 *
 * @package S3ImageAddon
 */
class addon_s3image_util extends addon_s3image_info
{
    public function create_s3_client ()
    {
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        $credentials = new Aws\Credentials\Credentials(
            $settings['aws_key'],
            $settings['aws_secret']
        );

        $s3_scheme = $settings['s3_disable_ssl'] == 'on' ? 'http' : 'https';
        $s3 = new Aws\S3\S3Client([
            'region'      => $settings['aws_region'],
            'scheme'      => $s3_scheme,
            'version'     => '2006-03-01',
            'credentials' => $credentials
        ]);

        return $s3;
    }

    public function check_s3_connection ()
    {
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        $s3 = $this->create_s3_client();

        $s3_status = $s3->headBucket([
            'Bucket' => $settings['s3_bucket']
        ]);

        return $s3_status;
    }

    public function core_notify_image_insert ($image_info)
    {
        $db = true;
        include(GEO_BASE_DIR . 'get_common_vars.php');

        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        /* Get image data. */
        $id         = image_info['id'];
        $full_path  = $image_info['file_path'] . $image_info['full_filename'];
        $thumb_path = $image_info['file_path'] . $image_info['thumb_filename'];

        /* Generate new S3 resource key. */
        $uuid4 = Ramsey\Uuid\Uuid::uuid4();
        $key_prefix = (string) $settings['s3_key_prefix'];

        $key = $key_prefix . '/' . $uuid4;
        $full_key = $key . '-full.jpg';
        $thumb_key = $key . '-thumb.jpg';

        $base_url = (string) $settings['s3_base_url'];
        $image_url = $base_url . '/' . $full_key;
        $thumb_url = $base_url . '/' . $thumb_key;

        /* Upload image to S3 */
        $s3 = $this->create_s3_client();
        $s3->putObject([
            'Bucket' => $settings['s3_bucket'],
            'Key'    => $full_key,
            'Body'   => fopen($full_path, 'r'),
            'ACL'    => 'public-read',
        ]);
        $s3->putObject([
            'Bucket' => $settings['s3_bucket'],
            'Key'    => $thumb_key,
            'Body'   => fopen($thumb_path, 'r'),
            'ACL'    => 'public-read',
        ]);

        /* Update image URL in database. */
        $sql  = "UPDATE " . geoTables::images_urls_table;
        $sql .= " SET `image_url` = '$image_url', `thumb_url` = $thumb_url";
        $sql .= " WHERE `id` = '$id'";
        $db->Execute($sql);
    }
}
