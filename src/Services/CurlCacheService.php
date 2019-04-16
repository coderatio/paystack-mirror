<?php


namespace Coderatio\PaystackMirror\Services;


use RuntimeException;

/**
 * This class is used by cURL class, use case:
 *
 * $c = new CurlService(array('cache'=>true), 'module_cache'=>'repository');
 * $ret = $c->get('http://www.google.com');
 *
 * @copyright  Coderatio
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class CurlCacheService
{
    /** @var string */
    public $dir = '';

    /**
     *
     * @param string @module which module is using curl_cache
     *
     */
    public function __construct()
    {

        $this->dir = '/tmp/';

        if (!file_exists($this->dir) && !mkdir($concurrentDirectory = $this->dir, 0700, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $this->ttl = 1200;
    }

    /**
     * Get cached value
     *
     * @param mixed $param
     * @return bool|string
     */
    public function get($param)
    {
        $this->cleanup($this->ttl);
        $filename = 'u_' . md5(serialize($param));
        if (file_exists($this->dir . $filename)) {
            $lasttime = filemtime($this->dir . $filename);

            if (time() - $lasttime > $this->ttl) {
                return false;
            }

            $fp = fopen($this->dir . $filename, 'rb');
            $size = filesize($this->dir . $filename);
            $content = fread($fp, $size);

            return unserialize($content);
        }

        return false;
    }

    /**
     * Set cache value
     *
     * @param mixed $param
     * @param mixed $val
     */
    public function set($param, $val): void
    {
        $filename = 'u_' . md5(serialize($param));
        $fp = fopen($this->dir . $filename, 'wb');
        fwrite($fp, serialize($val));
        fclose($fp);
    }

    /**
     * Remove cache files
     *
     * @param int $expire The number os seconds before expiry
     */
    public function cleanup($expire): void
    {
        if ($dir = opendir($this->dir)) {
            while (false !== ($file = readdir($dir))) {
                if ($file !== '.' && $file !== '..' && !is_dir($file)) {
                    $lasttime = @filemtime($this->dir . $file);
                    if (time() - $lasttime > $expire) {
                        @unlink($this->dir . $file);
                    }
                }
            }
        }
    }

    /**
     * delete current user's cache file
     *
     */
    public function refresh(): void
    {
        if ($dir = opendir($this->dir)) {
            while (false !== ($file = readdir($dir))) {
                if ($file !== '.' && $file !== '..' && !is_dir($file) && strpos($file, 'u_') !== false) {
                    @unlink($this->dir . $file);
                }
            }
        }
    }
}
