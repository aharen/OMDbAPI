<?php

namespace aharen;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class OMDbAPI
{

    /**
     * Set the OMDbAPI.com api uri
     *
     * @var string
     */
    protected $host = 'http://www.omdbapi.com';

    /**
     * Guzzle Client instance
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * OMDbAPI Poster API Host
     *
     * @var string
     */
    protected $img_host = 'http://img.omdbapi.com';

    /**
     * OMDbAPI Poster API Key
     *
     * @var string
     */
    protected $api_key = '';

    /**
     * Create a new OMDbAPI instance
     *
     * @param string $api_key
     * @return void
     */
    public function __construct($api_key = null)
    {
        $api_host     = (is_null($api_key)) ? $this->host : $this->img_host;
        $this->client = new Client([
            'base_uri' => $api_host,
        ]);

        $this->api_key = $api_key;
    }

    /**
     * Search Movie/TV Show from OMDbApi
     *
     * @param string $keyword
     * @param string $type
     * @param string $year
     * @return array
     */
    public function search($keyword, $type = null, $year = null)
    {

        $api_uri = '?s=' . urlencode($keyword);
        if ($type !== null) {
            $api_uri .= '&type=' . urlencode($type);
        }

        if ($year !== null) {
            $api_uri .= '&y=' . urlencode($year);
        }

        return $this->get($api_uri);
    }

    /**
     * Fetch Movie/TV Show from OMDbApi
     *
     * @param string $field
     * @param string $keyword
     * @return array
     */
    public function fetch($field, $keyword)
    {
        if ((!isset($field) && empty($field)) || (!isset($keyword) && empty($keyword))) {
            return $this->output('400', 'Missing required fields');
        }

        if ($field != 'i' && $field != 't') {
            return $this->output('400', 'Search field should be i or t');
        }

        if ($field == 'i' && $this->validateIMDBid($keyword) == false) {
            return $this->output('400', 'Invalid IMDB ID provided');
        }

        $api_uri = '?' . $field . '=' . urlencode($keyword);
        return $this->get($api_uri);
    }

    /**
     * Get poster from OMDb Poster API
     *
     * @param string $imdbid
     * @param integer $height
     * @return string
     */
    public function poster($imdbid, $height = 300)
    {
        if (is_null($this->api_key)) {
            return $this->output('400', 'No API Key Found');
        }

        if ($this->validateIMDBid($imdbid) == false) {
            return $this->output('400', 'Invalid IMDB ID provided');
        }

        if (!is_numeric($height)) {
            return $this->output('400', 'Height should be numeric');
        }

        if ($height < 1 && $height > 1000) {
            return $this->output('400', 'Height should be between 1-1000');
        }

        $api_uri = '?i=' . $imdbid . '&h=' . $height . '&apikey=' . $this->api_key;
        $output  = $this->get($api_uri);

        if ($output->code === 200) {
            header("Content-Type: image/jpeg");
            return $output->data;
        }

        return $output;
    }

    /**
     * Make the call using Guzzle Client
     *
     * @param string $api_uri
     * @return array
     */
    protected function get($api_uri)
    {
        try {
            $response = $this->client->get($api_uri);

            $code    = $response->getStatusCode();
            $message = $response->getReasonPhrase();

            $body = $response->getBody();
            $data = json_decode($body->getContents());

        } catch (RequestException $e) {
            $code    = $e->getStatusCode();
            $message = $e->getReasonPhrase();
            $data    = $e->getRequest();

        } catch (ClientException $e) {
            $code    = $e->getStatusCode();
            $message = $e->getReasonPhrase();
            $data    = $e->getRequest();
        }

        return $this->output($code, $message, $data);
    }

    /**
     * Format the output data
     *
     * @param string $code
     * @param string $message
     * @param array $data
     * @return array
     */
    protected function output($code, $message, $data = null)
    {
        return (object) [
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ];
    }

    /**
     * Validate IMDB id
     *
     * @param string $imdbid
     * @return bool
     */
    public function validateIMDBid($imdbid)
    {
        return preg_match("/tt\\d{7}/", $imdbid);
    }
}
