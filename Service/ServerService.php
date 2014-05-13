<?php

namespace Leobenoist\SocketBundle\Service;

use Symfony\Component\Process\Process;

/**
* Server connection handling
*
* @author    Leo Benoist <leo.benoist@gmail.com>
* @copyright 2014 Leo Benoist
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/
class ServerService
{

    /**
     * @var Logger
     */
    protected $logger;

    /*
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $hostname;

    /*
     * @var integer
     */
    protected $port;

    /**
     * @var array
     */
    private $urn;

    /**
     * @param Logger $logger
     * @param array  $config
     */
    public function __construct($logger, $config)
    {
        $this->logger = $logger;
        $this->config = $config;
        $this->hostname = $config['server']['hostname'];
        $this->port = $config['server']['port'];
        $this->urn = array(
            'registerUser' => '/safire/user/session/register',
            //'registerUserLabel' => '/safire/label/session/register',
            'grantUserLabel' => '/safire/label/session/grant',
            'sendUpdateLabel' => '/safire/label/update',
            'sendUpdateToUsers' => '/safire/send/update/users',
        );
    }

    /**
     * Send json by http request
     *
     * @param string $type This is to dertermine what type of send it is (Deletermine url)
     * @param string $json json formated string
     *
     * @return void
     */
    private function sendByHttpRequest($type, $json)
    {
        //Do Not Use, User async one
        if (array_key_exists($type, $this->urn)) {
            $uri = 'http://' . $this->hostname . ':' . $this->port . '' . $this->urn[$type];
        } else {
            $uri = 'http://' . $this->hostname . ':' . $this->port . $type;
        }
        $port = $this->port;
        $curlPost = curl_init();
        curl_setopt($curlPost, CURLOPT_URL, $uri);
        curl_setopt($curlPost, CURLOPT_PORT, $port);
        curl_setopt($curlPost, CURLOPT_POST, 1);
        curl_setopt($curlPost, CURLOPT_POSTFIELDS, $json);
        curl_setopt(
            $curlPost,
            CURLOPT_HTTPHEADER,
            array("Content-Type: application/json", "Content-length: " . strlen($json))
        );
        curl_exec($curlPost);
        curl_close($curlPost);
    }

    /**
     * Send json by http request in async way
     *
     * @param string $type This is to dertermine what type of send it is (Deletermine url)
     * @param string $json json formated string
     *
     * @return void
     */
    public function sendByHttpRequestAsync($type, $json)
    {
        error_reporting(0);
        if (array_key_exists($type, $this->urn)) {
            $uri = 'http://' . $this->hostname . ':' . $this->port . '' . $this->urn[$type];
        } else {
            $uri = 'http://' . $this->hostname . ':' . $this->port . $type;
        }

        $parts = parse_url($uri);

        //TODO: see stream_socket_client

        $fp = fsockopen(@$parts['host'], isset($parts['port']) ? $parts['port'] : $this->port, $errno, $errstr, 30);

        $out  = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/json\r\n";
        $out .= "Content-Length: " . strlen($json) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";

        $out .= $json;

        fwrite($fp, $out);
        fclose($fp);
    }

    /**
     * Send json by socket
     *
     * @param string $type This is to dertermine what type of send it is (Deletermine url)
     * @param string $json json formated string
     *
     * @return string
     */
    private function sendBySocket($type, $json)
    {
        return 'Not implemented yet !';
    }

    /**
     * Send json by stdin
     *
     * @param string $type This is to dertermine what type of send it is (Deletermine url)
     * @param string $json json formated string
     *
     * @return string
     */
    private function sendByStdIn($type, $json)
    {
        return 'Not implemented yet !';
    }

    /**
     * Send json to process
     *
     * @param string $type This is to dertermine what type of send it is (Deletermine url)
     * @param string $json json formated string
     */
    private function sendToProcess($type, $json)
    {
        //TODO: System to determine the best way socket stdin http request
        $this->sendByHttpRequestAsync($type, $json);
    }

    /**
     * Send data
     *
     * @param string $type This is to dertermine what type of send it is (Deletermine url)
     * @param string $data The label of the update wich is the string who user register
     *
     * @return void
     */
    public function send($type, $data)
    {
        if (is_string($data) && $this->isJson($data)) {
            $this->sendToProcess($type, $data);
        } else {
            $this->sendToProcess($type, json_encode($data));
        }
    }

    /**
     * Check if the data is json formated
     *
     * @param string $data
     *
     * @return boolean
     */
    public function isJson($data)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * get the service name
     *
     * @return string
     */
    public function getName()
    {
        return 'leobenoist_server.service';
    }

}
