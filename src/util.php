<?php

require GEO_BASE_DIR . 'vendor/autoload.php';

/**
 * Addon information class.
 *
 * @package S3ImageAddon
 */
class addon_s3image_util extends addon_s3image_info
{
    public $db = true;

    public function core_notify_image_insert ($image_info)
    {
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        /* Get image data. */
        $id         = image_info['id'];
        $full_path  = $image_info['file_path'] . $image_info['full_filename'];
        $thumb_path = $image_info['file_path'] . $image_info['thumb_filename'];

        /* Generate new S3 resource key. */
        $uuid4 = Ramsey\Uuid\Uuid::uuid4();
        $full_key = $uuid4 . '-full.jpg';
        $thumb_key = $uuid4 . '-thumb.jpg';
        $url = $settings['base_url'] . '/' . $key;

        /* Upload image to S3 */
        $credentials = new Aws\Credentials\Credentials(
            $settings['aws_key'],
            $settings['aws_secret']
        );
        $s3 = new Aws\S3\S3Client([
            'region'      => $settings['aws_region'],
            'version'     => '2006-03-01',
            'credentials' => $credentials
        ]);
        $s3->putObject([
            'Bucket' => $settings['s3_bucket'],
            'Key'    => $full_key,
            'Body'   => fopen($full, 'r'),
            'ACL'    => 'public-read',
        ]);
        $s3->putObject([
            'Bucket' => $settings['s3_bucket'],
            'Key'    => $key,
            'Body'   => fopen($thumb, 'r'),
            'ACL'    => 'public-read',
        ]);

        /* Update image URL in database. */
        $sql  = "UPDATE " . geoTables::images_urls_table;
        $sql .= " SET `image_url` = '$image_url', `thumb_url` = $thumb_url";
        $sql .= " WHERE `id` = '$id'";
        $this->db->Execute($sql);
    }
}
