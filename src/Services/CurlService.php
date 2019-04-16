<?php


namespace Coderatio\PaystackMirror\Services;

use Coderatio\PaystackMirror\Exceptions\PaystackMirrorException;
use CURLFile;
use Exception;

/**
 * RESTful cURL class
 *
 * A wrapper class for curl extension.
 * An edited version of https://github.com/dcai/curl.
 *
 * @copyright  Coderatio
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
            . ($postname ? '' : basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }
}

class CurlService
{
    public const VERSION = '0.1.0';
    public const DEFAULT_USERPWD = 'anonymous: anonymous@domain.com';
    /** @var bool */
    public $proxy = false;
    /** @var object */
    public $cacheInstance;
    /** @var array */
    public $responseHeaders = array();
    public $requestHeaders = array();
    /** @var string */
    public $info;
    public $error;

    protected $response;
    /** @var array */
    private $curlOptions;
    //private $curlInstance;
    /** @var bool */
    private $debug = false;
    /** @var string */
    private $cookiePath;

    /**
     * @param array $options
     * @throws Exception
     */
    public function __construct($options = array())
    {
        if (!function_exists('curl_init')) {
            throw new PaystackMirrorException('cURL module must be enabled!');
        }
        // the options of curl should be init here.
        $this->initializeCurlOptions();
        if (!empty($options['debug'])) {
            $this->debug = true;
        }
        if (!empty($options['cookie'])) {
            if ($options['cookie'] === true) {
                $this->cookiePath = 'curl_cookie.txt';
            } else {
                $this->cookiePath = $options['cookie'];
            }
        }
        if (!empty($options['cache']) && class_exists('CurlCacheService')) {
            $this->cacheInstance = new CurlCacheService();
        }
    }

    /**
     * HTTP GET method
     *
     * @param string $url
     * @param array $params
     * @param array $curlOptions
     * @return object
     * @throws Exception
     */
    public function get($url, $params = [], $curlOptions = [])
    {
        $curlOptions['CURLOPT_HTTPGET'] = 1;

        if (!empty($params)) {
            $url .= (strpos($url, '?') !== false) ? '&' : '?';
            $url .= http_build_query($params, '', '&');
        }

        return $this->request($url, $curlOptions);
    }

    /**
     * HTTP POST method
     *
     * @param string $url
     * @param array|string $params
     * @param array $curlOptions
     * @return object
     * @throws Exception
     */
    public function post($url, $params = '', $curlOptions = array())
    {
        if (is_array($params)) {
            $params = $this->makePostFields($params);
        }

        $curlOptions['CURLOPT_POST'] = 1;
        $curlOptions['CURLOPT_POSTFIELDS'] = $params;

        return $this->request($url, $curlOptions);
    }

    /**
     * HTTP PUT method
     *
     * @param string $url
     * @param array $params
     * @param array $curlOptions
     * @return object
     * @throws Exception
     */
    public function put($url, $params = [], $curlOptions = [])
    {
        if (is_array($params)) {
            $params = $this->makePostFields($params);
        }

        $curlOptions['CURLOPT_RETURNTRANSFER'] = true;
        $curlOptions['CURLOPT_CUSTOMREQUEST'] = 'PUT';
        $curlOptions['CURLOPT_POSTFIELDS'] = $params;

        return $this->request($url, $curlOptions);
    }

    /**
     * HTTP DELETE method
     *
     * @param string $url
     * @param array $params
     * @param array $curlOptions
     * @return object
     * @throws PaystackMirrorException
     */
    public function delete($url, $params = [], $curlOptions = [])
    {
        $curlOptions['CURLOPT_CUSTOMREQUEST'] = 'DELETE';
        $params = $this->makePostFields($params);
        $curlOptions['CURLOPT_POSTFIELDS'] = $params;

        if (!isset($curlOptions['CURLOPT_USERPWD'])) {
            $curlOptions['CURLOPT_USERPWD'] = self::DEFAULT_USERPWD;
        }

        return $this->request($url, $curlOptions);
    }

    /**
     * HTTP TRACE method
     *
     * @param string $url
     * @param array $curlOptions
     * @return object
     * @throws Exception
     */
    public function trace($url, $curlOptions = array())
    {
        $curlOptions['CURLOPT_CUSTOMREQUEST'] = 'TRACE';

        return $this->request($url, $curlOptions);
    }

    /**
     * HTTP OPTIONS method
     *
     * @param string $url
     * @param array $curlOptions
     * @return object
     * @throws Exception
     */
    public function options($url, $curlOptions = array())
    {
        $curlOptions['CURLOPT_CUSTOMREQUEST'] = 'OPTIONS';

        return $this->request($url, $curlOptions);
    }

    /**
     * HTTP HEAD method
     *
     * @param string $url
     * @param array $curlOptions
     * @return object
     * @throws PaystackMirrorException
     * @see request()
     */
    public function head($url, $curlOptions = [])
    {
        $curlOptions['CURLOPT_HTTPGET'] = 0;
        $curlOptions['CURLOPT_HEADER'] = 1;
        $curlOptions['CURLOPT_NOBODY'] = 1;

        return $this->request($url, $curlOptions);
    }

    /**
     * Download multiple files in parallel
     *
     * Calls {@link multi()} with specific download headers
     *
     * <code>
     * $c = new CurlService();
     * $c->download(array(
     *                  array('url'=>'http://localhost/', 'file'=>fopen('a', 'wb')),
     *              array('url'=>'http://localhost/20/', 'file'=>fopen('b', 'wb'))
     *              ));
     * </code>
     *
     * @param array $requests An array of files to request
     * @param array $options An array of options to set
     * @return array An array of results
     */
    public function download($requests, $options = array()): array
    {
        $options['CURLOPT_BINARYTRANSFER'] = 1;
        $options['RETURNTRANSFER'] = false;

        return $this->multi($requests, $options);
    }

    /**
     * Reset Cookie
     */
    public function purgeCookies(): void
    {
        if (!empty($this->cookiePath) && is_file($this->cookiePath)) {
            $fp = fopen($this->cookiePath, 'wb');
            if (!empty($fp)) {
                fwrite($fp, '');
                fclose($fp);
            }
        }
    }

    /**
     * Set curl option
     *
     * @param string $name
     * @param string $value
     */
    public function addCurlOption($name, $value): void
    {
        if (stripos($name, 'CURLOPT_') === false) {
            $name = strtoupper('CURLOPT_' . $name);
        }

        $this->curlOptions[$name] = $value;
    }

    /**
     * Set curl options
     *
     * @param array $curlOptions If array is null, this function will
     *                       reset the options to default value.
     */
    public function addCurlOptions($curlOptions = array()): void
    {
        if (is_array($curlOptions)) {
            foreach ($curlOptions as $name => $val) {
                $this->addCurlOption($name, $val);
            }
        }
    }

    /**
     * Reset http method
     *
     */
    public function resetCurlOptions(): void
    {
        unset(
            $this->curlOptions['CURLOPT_HTTPGET'],
            $this->curlOptions['CURLOPT_POST'],
            $this->curlOptions['CURLOPT_POSTFIELDS'],
            $this->curlOptions['CURLOPT_PUT'],
            $this->curlOptions['CURLOPT_INFILE'],
            $this->curlOptions['CURLOPT_INFILESIZE'],
            $this->curlOptions['CURLOPT_CUSTOMREQUEST']
        );
    }

    /**
     * Append to existing headers.
     *
     * @param $key
     * @param $value
     */
    public function appendRequestHeader($key, $value): void
    {
        $this->requestHeaders[] = [(string)$key, (string)$value];
    }

    /**
     * Set HTTP Request Header
     *
     * @param array $headers
     */
    public function appendRequestHeaders(array $headers): void
    {
        foreach ($headers as $header) {
            $this->appendRequestHeader($header[0], $header[1]);
        }
    }

    /**
     * Send request headers.
     *
     * @param array $headers
     */
    public function setRequestHeaders(array $headers): void
    {
        $this->requestHeaders = $headers;
    }

    /**
     * Set HTTP Response Header
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * Get all request headers.
     *
     * @return array
     */
    public function getRequestHeaders(): array
    {
        return $this->requestHeaders;
    }

    /*
     * Multiple HTTP Requests
     * This function could run multi-requests in parallel.
     *
     * @param array $requests An array of files to request
     * @param array $options An array of options to set
     * @return array An array of results
     */
    protected function multi($requests, $options = array()): array
    {
        $count = count($requests);
        $handles = array();
        $results = array();
        $main = curl_multi_init();

        for ($i = 0; $i < $count; $i++) {
            $url = $requests[$i];
            foreach ($url as $n => $v) {
                $options[$n] = $url[$n];
            }
            $handles[$i] = curl_init($url['url']);
            // Clean up
            $this->resetCurlOptions();
            $this->prepareRequest($handles[$i], $options);
            curl_multi_add_handle($main, $handles[$i]);
        }

        $running = 0;

        do {
            curl_multi_exec($main, $running);
        } while ($running > 0);

        foreach ($handles as $handle) {
            if (!empty($options['CURLOPT_RETURNTRANSFER'])) {
                $results[] = true;
            } else {
                $results[] = curl_multi_getcontent($handle);
            }

            curl_multi_remove_handle($main, $handle);
        }

        curl_multi_close($main);

        return $results;
    }

    /**
     * Single HTTP Request
     *
     * @param string $url The URL to request
     * @param array $curlOptions
     * @return object
     * @throws PaystackMirrorException
     */
    protected function request($url, $curlOptions = array())
    {
        // create curl instance
        $curl = curl_init($url);
        $curlOptions['url'] = $url;
        $this->resetCurlOptions();
        $this->prepareRequest($curl, $curlOptions);

        if ($this->cacheInstance && $httpbody = $this->cacheInstance->get($this->curlOptions)) {
            return $httpbody;
        }

        $httpbody = curl_exec($curl);

        if ($this->cacheInstance) {
            $this->cacheInstance->set($this->curlOptions, $httpbody);
        }

        $this->info = curl_getinfo($curl);
        $this->error = curl_error($curl);

        if ($this->debug) {
            paystack_mirror_dump($this->info);
            paystack_mirror_dump($this->error);
        }

        curl_close($curl);

        $response = new CurlHttpResponseService($this->info['http_code'], $this->responseHeaders, $httpbody);

        $this->response = $response;

        if (!empty($this->error)) {
            throw new PaystackMirrorException($this->error);
        }

        return $response;
    }

    /**
     * Transform a PHP array into POST parameter
     *
     * @param array $postdata
     * @return array containing all POST parameters  (1 row = 1 POST parameter)
     */
    public function makePostFields($postdata): array
    {
        if (!is_array($postdata) && !self::isCurlFile($postdata)) {
            $postdata = (array)$postdata;
        }

        $postFields = [];

        foreach ($postdata as $name => $value) {
            $name = urlencode($name);
            if (is_object($value) && !self::isCurlFile($value)) {
                $value = (array)$value;
            }
            if (is_array($value) && !self::isCurlFile($value)) {
                $postFields = $this->makeArrayField($name, $value, $postFields);
            } else {
                $postFields[$name] = $value;
            }
        }

        return $postFields;
    }

    /**
     * Get cURL info
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Prepare a file to upload via cURL
     *
     * @param $filepath
     * @param string $filename
     * @param string $mimetype
     * @return string
     */
    public static function makeUploadFile($filepath, $filename = '', $mimetype = ''): string
    {
        return curl_file_create($filepath, $filename, $mimetype);
    }

    /**
     * Get cURL response
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Resets the CURL options that have already been set
     */
    private function initializeCurlOptions(): void
    {
        $this->curlOptions = [
            'CURLOPT_USERAGENT' => 'cURL',
            // True to include the header in the output
            'CURLOPT_HEADER' => 0,
            // True to Exclude the body from the output
            'CURLOPT_NOBODY' => 0,
            // TRUE to follow any "Location: " header that the server
            // sends as part of the HTTP header (note this is recursive,
            // PHP will follow as many "Location: " headers that it is sent,
            // unless CURLOPT_MAXREDIRS is set).
            //$this->curlOptions['CURLOPT_FOLLOWLOCATION'] = 1;
            'CURLOPT_MAXREDIRS' => 10,
            'CURLOPT_ENCODING' => '',
            // TRUE to return the transfer as a string of the return
            // value of curl_exec() instead of outputting it out directly.
            'CURLOPT_RETURNTRANSFER' => 1,
            'CURLOPT_BINARYTRANSFER' => 0,
            'CURLOPT_SSL_VERIFYPEER' => 0,
            'CURLOPT_SSL_VERIFYHOST' => 2,
            'CURLOPT_CONNECTTIMEOUT' => 30,
        ];
    }

    /**
     * Recursive function formating an array in POST parameter
     *
     * @param $fieldname
     * @param $arrayData
     * @param $postFields
     * @return array
     */
    private function makeArrayField($fieldname, $arrayData, $postFields): array
    {
        foreach ($arrayData as $key => $value) {
            $key = urlencode($key);
            if (is_object($value)) {
                $value = (array)$value;
            }
            if (is_array($value)) { //the value is an array, call the function recursively
                $newfieldname = $fieldname . "[$key]";
                $postFields = $this->makeArrayField($newfieldname, $value, $postFields);
            } else {
                $postFields[] = $fieldname . "[$key]=" . urlencode($value);
            }
        }

        return $postFields;
    }

    /**
     * private callback function
     * Formatting HTTP Response Header
     *
     * @param $curl resource
     * @param string $header
     * @return int The strlen of the header
     */
    private function handleResponseHeaders($curl, $header): int
    {
        if ($curl && strlen((string)$header) > 2) {
            [$key, $value] = explode(' ', rtrim($header, "\r\n"), 2);
            $key = rtrim($key, ':');
            if (!empty($this->responseHeaders[$key])) {
                if (is_array($this->responseHeaders[$key])) {
                    $this->responseHeaders[$key][] = $value;
                } else {
                    $tmp = $this->responseHeaders[$key];
                    $this->responseHeaders[$key] = array();
                    $this->responseHeaders[$key][] = $tmp;
                    $this->responseHeaders[$key][] = $value;

                }
            } else {
                $this->responseHeaders[$key] = $value;
            }
        }

        return strlen($header);
    }

    /**
     * Set options for individual curl instance
     *
     * @param $curl resource
     * @param $curlOptions
     * @return resource The curl handle
     */
    private function prepareRequest($curl, $curlOptions)
    {
        // set cookie
        if (!empty($this->cookiePath) || !empty($curlOptions['cookie'])) {
            $this->addCurlOption('cookiejar', $this->cookiePath);
            $this->addCurlOption('cookiefile', $this->cookiePath);
        }

        // set proxy
        if (!empty($this->proxy) || !empty($curlOptions['proxy'])) {
            $this->addCurlOptions($this->proxy);
        }

        $this->addCurlOptions($curlOptions);
        // set headers
        if (empty($this->requestHeaders)) {
            $this->appendRequestHeaders(array(
                ['User-Agent', $this->curlOptions['CURLOPT_USERAGENT']],
                ['Accept-Charset', 'UTF-8']
            ));
        }

        self::applyCurlOption($curl, $this->curlOptions);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, [&$this, 'handleResponseHeaders']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::prepareRequestHeaders($this->requestHeaders));

        if ($this->debug) {
            paystack_mirror_dump($this->curlOptions);
            paystack_mirror_dump($this->requestHeaders);
        }
        return $curl;
    }

    private static function applyCurlOption($curl, $curlOptions): void
    {
        // Apply curl options
        foreach ($curlOptions as $name => $value) {
            if (is_string($name)) {
                curl_setopt($curl, constant(strtoupper($name)), $value);
            }
        }
    }

    private static function prepareRequestHeaders($headers): array
    {
        $processedHeaders = [];

        foreach ($headers as $header) {
            $processedHeaders[] = $header[0] . ': ' . $header[1];
        }

        return $processedHeaders;
    }

    private static function isCurlFile($field): bool
    {
        return is_object($field) ? $field instanceof CURLFile : false;
    }

}


