<?php

namespace Leobenoist\SocketBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Leobenoist\SocketBundle\Service\ServerService;

/**
 *
 * @author Léo Benoist <leo.benoist@gmail.com>
 */
class SocketService
{

    protected $server;

    function __construct(ServerService $server)
    {
        $this->server = $server;
    }

    //Start Label
    public function sendResponseForLabel($label, Response $response)
    {
        $this->sendLabel($label, $response->getContent());
    }

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

    //Stop Label
    //Start User
    public function sendResponseToUser($user, Response $response)
    {
        $this->sendToUser($user, $response->getContent());
    }

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

    //Stop User
    //Start Users
    public function sendResponseToUsers($users, Response $response)
    {
        $this->sendToUser($users, $response->getContent());
    }

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

    //Stop Users

    public function getName()
    {
        return 'leobenoist_socket.service';
    }

}

