<?php

namespace App\Libraries\Helplib;
use Log;

class BaseService
{
    static $helpLib = null;
    static $init = null;
    // method
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_POSTFile = 'POST_FILE';
    // config
    protected $config = [];
    protected $uri = "";

    // instantiate
    private $ch = null;
    protected static $instantiate = null;

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $uir
     * @param array $params
     */
    public function parseRestfulUIR($uri, array $params)
    {
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key. '}', $value, $uri);
        }
        return $uri;
    }

    /**
     * @param $path
     * @param array $data
     * @param string $method
     * @param string $url
     * @return mixed
     */
    public function httpBuilder($path, $data = [], $method = 'GET', $url = '', $extData = [])
    {
        if (empty($url)) {
            $url = $this->uri.$path;
        }
		Log::info("访问路径".$url.";".json_encode($data));
        switch ($method) {
            case self::METHOD_GET:
                $result = self::$helpLib->get($url, $data);
                break;
            case self::METHOD_POST:
                $result = self::$helpLib->post($url, $data);
                break;
            case self::METHOD_PUT:
                $result = self::$helpLib->put($url, $data);
                break;
            case self::METHOD_DELETE:
                $result = self::$helpLib->delete($url, $data);
                break;
            case self::METHOD_POSTFile:
                $result = self::$helpLib->postFile($url, $data, $extData);
                break;
            default:
                $result = self::$helpLib->get($url, $data);
                break;
        }
        $encodeResult = json_decode($result, true);
        return $encodeResult ? $encodeResult : $result;
    }

    public static function init()
    {
        if (self::$instantiate === null)
            self::$instantiate = new BaseService();
        return self::$instantiate;
    }

    /**
     * @param $data
     * @return string
     */
    private function arrayToStr(&$data)
    {
        $string = '';
        foreach ($data as $key => $value) {
            $string .= $key.'='.$value.'&';
        }
        return rtrim($string, '&');
    }

    /**
     * @param $url
     * @return bool
     */
    private function checkHttps($url)
    {
        return stripos($url, 'https') === 0 ? true : false;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function get($url, array $data)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url.'?'.$this->arrayToStr($data));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, true);
        if ($this->checkHttps($url)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $result = curl_exec($this->ch) ;
        curl_close($this->ch);
        return $result;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function put($url, array $data)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url.'?'.$this->arrayToStr($data));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, true);
        if ($this->checkHttps($url)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt ($this->ch, CURLOPT_CUSTOMREQUEST, self::METHOD_PUT);
        $result = curl_exec($this->ch) ;
        curl_close($this->ch);
        return $result;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function delete($url, array $data)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url.'?'.$this->arrayToStr($data));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, true);
        if ($this->checkHttps($url)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt ($this->ch, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
        $result = curl_exec($this->ch) ;
        curl_close($this->ch);
        return $result;
    }

    public function postFile($url, $fileData, $extData = [])
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url) ;
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        $cfile = curl_file_create($fileData['tmp_name'], $fileData['type'], $fileData['name']);
        $data = array_merge(
            ['filedata' => $cfile],
            $extData
        );
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        if ($this->checkHttps($url)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;
    }

    /**
     * @param $url
     * @param array $data
     * @return string
     */
    public function post($url, $data)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url) ;
        curl_setopt($this->ch, CURLOPT_POST, count($data));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, true);

        if ($this->checkHttps($url)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }

        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;
    }
}
