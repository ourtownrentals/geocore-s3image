<?php

require GEO_BASE_DIR . 'vendor/autoload.php';

/**
 * Addon utility class.
 *
 * @package S3ImageAddon
 */
class addon_s3image_util extends addon_s3image_info
{
    private $db = false;
    private $s3 = false;

    private function get_db ()
    {
        if (!$this->db) {
            $db = true;
            include(GEO_BASE_DIR . 'get_common_vars.php');
            $this->db = $db;
        }
        return $this->db;
    }

    private function get_s3 ()
    {
        if (!$this->s3) {
            $this->s3 = $this->create_s3_client();
        }
        return $this->s3;
    }

    private function get_s3_prefix ()
    {
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        return trim((string) $settings['s3_key_prefix'], '/');
    }

    private function create_uuid ()
    {
        $uuid = Ramsey\Uuid\Uuid::uuid4();
        return str_replace('-', '', $uuid);
    }

    private function create_s3_key ($name)
    {
        $key = $this->get_s3_prefix() . '/' . $name;
        return $key;
    }

    private function create_s3_client ()
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

    private function put_s3_file ($key, $path, $mime_type)
    {
        $s3 = $this->get_s3();
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        $s3->putObject([
            'Bucket'      => $settings['s3_bucket'],
            'Key'         => $key,
            'Body'        => fopen($path, 'r'),
            'ContentType' => $mime_type,
            'ACL'         => 'public-read'
        ]);
    }

    private function set_image_data (
        $id,
        $full_filename,
        $thumb_filename,
        $full_key,
        $thumb_key
    )
    {
        $db = $this->get_db();
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        $base_url  = trim((string) $settings['s3_base_url'], '/');
        $image_url = $base_url . '/' . $full_key;
        $thumb_url = $base_url . '/' . $thumb_key;

        // TODO: Use query building methods.
        $sql = "UPDATE " . geoTables::images_urls_table;
        $sql .= " SET `full_filename` = ?, `thumb_filename` = ?, `image_url` = ?, `thumb_url` = ?";
        $sql .= " WHERE image_id = ?;";
        $db->Execute($sql, [$full_filename, $thumb_filename, $image_url, $thumb_url, $id]);
    }

    public function check_s3_connection ()
    {
        $s3 = $this->get_s3();
        $reg = geoAddon::getRegistry($this->name);
        $settings = $reg->settings;

        $s3_status = $s3->headBucket([
            'Bucket' => $settings['s3_bucket']
        ]);

        return $s3_status;
    }

    public function core_notify_image_insert ($image_info)
    {
        $id         = $image_info['id'];
        $mime_type  = $image_info['mime_type'];
        $full_path  = $image_info['file_path'] . $image_info['full_filename'];
        $thumb_path = $image_info['file_path'] . $image_info['thumb_filename'];
        $extension  = pathinfo($full_path)['extension'];

        $uuid       = $this->create_uuid();
        $full_name  = $uuid . '-full.' . $extension;
        $thumb_name = $uuid . '-thumb.' . $extension;
        $full_key   = $this->create_s3_key($full_name);
        $thumb_key  = $this->create_s3_key($thumb_name);

        $this->put_s3_file($full_key, $full_path, $mime_type);
        $this->put_s3_file($thumb_key, $thumb_path, $mime_type);
        $this->set_image_data($id, $full_name, $thumb_name, $full_key, $thumb_key);
    }
}
