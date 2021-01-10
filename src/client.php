<?php
namespace NubiCodes\Flussonic;

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

/**
 * Flussonic HTTP API Client
 * @version 1.0.0
 * @author Billy Riantono <billy@nubicodes.com>
 * @copyright 2020 NubiCodes.com
 */
class FlussonicClient
{
    //flussonic username
    private $username;
    //flussonic password
    private $password;
    //flussonic media server url (ex : http://ip or https://domainmediaserver.com)
    private $flussonicMediaServerUrl;
    //guzzle http client
    private $httpClient;

    public function __construct($username, $password, $flussonicUrl)
    {
        $this->username = $username;
        $this->password = $password;
        $this->flussonicMediaServerUrl = $flussonicUrl;
        $this->httpClient = $this->_buildHttpClient();
    }

    private function _buildHttpClient()
    {
        return new Client(
        [
            'base_uri' => $this->flussonicMediaServerUrl . '/flussonic/api/',
            'timeout' => 0,
            'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
            'allow_redirects' => false,
            'auth' => [$this->username, $this->password]
        ]);
    }


    private function _request($url, $method = "GET", $data = [], $headers = null)
    {
        if ($this->httpClient == null) {
            $this->httpClient = $this->_buildHttpClient();
        }

        $res = $this->httpClient->request($method, $url, $data);
        return $res;
    }

    /**
     * Get Flussonic Media Server Information
     * @param null
     * @return mixed
     */
    public function getServerInfo()
    {
        return $this->_request('server')->getBody();
    }

    /**
     * Get List All Stream in the Media Server
     * @param null
     * @return mixed
     * @since 1.0.0
     */
    public function getListAllStreams()
    {
        return $this->_request('media')->getBody();
    }

    /**
     * Get Stream Details Information in the Media Server
     * @param String streamName
     * @return mixed
     * @since 1.0.0
     */
    public function getStreamDetail($streamName)
    {
        return $this->_request('media', ['name' => $streamName])->getBody();
    }

    /**
     * Get Stream Media Info like track, codec, height and width
     * @param mixed $streamName
     * @return 
     * @since 1.0.0
     */
    public function getStreamMediaInfo($streamName)
    {
        return $this->_request("media_info/$streamName")->getBody();
    }

    /**
     * Get Source / Input Stream Media Info 
     * @param mixed $streamName
     * @return mixed
     * @since 1.0.0
     */
    public function getSourceStreamInfo($streamName)
    {
        return $this->_request("input_media_info/$streamName")->getBody();
    }

    /**
     * Get Stream Health Information
     * @param mixed $streamName
     * @return 
     * @since 1.0.0
     */
    public function getStreamHealth($streamName)
    {
        $req = $this->_request("stream_health/$streamName");
        return json_encode(['status' => $req->getStatusCode() == 200]);
    }

    /**
     * Get All Opened Session
     * @param null
     * @return mixed
     */
    public function getAllOpenedSession()
    {
        return $this->_request("sessions")->getBody();
    }
}
