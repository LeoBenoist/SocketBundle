<?php

namespace Leobenoist\SocketBundle\Service;

use Symfony\Component\HttpFoundation\Response;

/**
* Handle connections with socket.io
*
* @author    Leo Benoist <leo.benoist@gmail.com>
* @copyright 2014 Leo Benoist
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/
class SocketService
{
    /**
     * @var ServerService
     */
    protected $server;

    /**
     * @param ServerService $server
     */
    public function __construct(ServerService $server)
    {
        $this->server = $server;
    }

    /**
     * Send a response for a given label
     *
     * @param string   $label
     * @param Response $response
     */
    public function sendResponseForLabel($label, Response $response)
    {
        $this->sendLabel($label, $response->getContent());
    }

    /**
     * Send a label
     *
     * @param string $label
     * @param string $data
     */
    public function sendLabel($label, $data)
    {
        $this->server->send(
            'sendUpdateLabel',
            array(
                'label' => $label,
                'data' => $data,
            )
        );
    }

    /**
     * Send a response for a given user
     *
     * @param string   $user
     * @param Response $response
     */
    public function sendResponseToUser($user, Response $response)
    {
        $this->sendToUser($user, $response->getContent());
    }

    /**
     * Send an update for a given user
     *
     * @param string $user
     * @param string $data
     */
    public function sendToUser($user, $data)
    {
        $this->server->send(
            'sendUpdateToUser',
            array(
                'user' => $user->getId(),
                'data' => $data,
            )
        );
    }

    /**
     * Send a response for a group of users
     *
     * @param string   $users
     * @param Response $response
     */
    public function sendResponseToUsers($users, Response $response)
    {
        $this->sendToUser($users, $response->getContent());
    }

    /**
     * Send an update for a group of users
     *
     * @param string $users
     * @param string $data
     */
    public function sendToUsers($users, $data)
    {
        $userArray = array();
        foreach ($users as $user) {
            array_push($userArray, $user->getId());
        }

        $this->server->send(
            'sendUpdateToUsers',
            array(
                'users' => $users,
                'data' => $data,
            )
        );
    }

    /**
     * Get the name of the bundle
     *
     * @return string
     */
    public function getName()
    {
        return 'leobenoist_socket.service';
    }

}
