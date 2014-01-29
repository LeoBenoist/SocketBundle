<?php

namespace Leobenoist\SocketBundle\Service;

use Symfony\Component\Process\Process;

/**
 *
 * @author Léo Benoist <jobs.leo@benoi.st>
 */
class ServerService
{

    protected $logger;
    protected $config;
    protected $hostname;
    protected $port;
    private $urn;

    function __construct($logger, $config)
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

//    protected function startServer() {
//        $nohupPath = shell_exec('which nohup');
//        $nodePath = '/usr/local/bin/node';
//        $serverPath = '/Users/leobenoist/www/leobenoist.local/Leobenoist/src/Leobenoist/SocketBundle/server/server.js'; // __DIR__ . '/../server/server.js'
//        $logPath = '/dev/null';
//        //OK$this->process = new Process('nohup /usr/local/bin/node /Users/leobenoist/www/leobenoist.local/Leobenoist/src/Leobenoist/SocketBundle/server/server.js > /dev/null', null, array('/usr/local/bin/'));
//        $this->process = new Process($nohupPath . ' ' . $nodePath . ' ' . $serverPath . '  > ' . $logPath, null, array('/usr/local/bin/'));
//
//        $this->process->start(
//                function ($type, $buffer) {
//                    if ('err' === $type) {
//                        $this->logger->err('SocketServer : ' . $buffer);
//                    } else {
//                        $this->logger->info('SocketServer : ' . $buffer);
//                    }
//                });
//    }
//
//    protected function getProcess() {
//        if (!$this->process || !$this->process->isRunning() || $this->process->hasBeenStopped()) {
//            $this->startServer();
//        }
//        return $this->process;
//    }
//
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

    function sendByHttpRequestAsync($type, $json)
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


        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/json\r\n";
        $out .= "Content-Length: " . strlen($json) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";

        $out .= $json;

        fwrite($fp, $out);
        fclose($fp);
    }

    private function sendBySocket($type, $json)
    {
        return 'Not implemented yet !';
    }

    private function sendByStdIn($type, $json)
    {
        return 'Not implemented yet !';
    }

    private function sendToProcess($type, $json)
    {
        //TODO: System to determine the best way socket stdin http request
        $this->sendByHttpRequestAsync($type, $json);
    }

    /**
     *
     * @param String $type This is to dertermine what type of send it is (Deletermine url)
     * @param String $label The label of the update wich is the string who user register
     */
    public function send($type, $data)
    {
        if (is_string($data) && $this->isJson($data)) {
            $this->sendToProcess($type, $data);
        } else {
            $this->sendToProcess($type, json_encode($data));
        }
    }

    function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function getName()
    {
        return 'leobenoist_server.service';
    }

}

