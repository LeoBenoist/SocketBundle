<?php

namespace Leobenoist\SocketBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

/**
 *
 * @author Léo Benoist leo@benoi.st
 *
 * This class create a twig extension to provide a socket unique id.
 * Right now php session id is use for that
 */
class SocketTwigService extends \Twig_Extension
{

    protected $session;
    protected $server;
    protected $environment;
    protected $config;

    function __construct(Session $session, SecurityContext $context, ServerService $server, array $config)
    {
        $this->session = $session;
        $this->context = $context;
        $this->server = $server;
        $this->config = $config;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        parent::initRuntime($environment);
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'leobenoistSocketConnect' => new \Twig_Function_Method($this, 'socketConnect'),
            'leobenoistSocketRegisterLabel' => new \Twig_Function_Method($this, 'registerLabel'),
        );
    }

    public function socketConnect()
    {
        $socketSessionId = uniqid($this->session->getId(), true);

        $this->server->send(
            'registerUser',
            array(
                'user' => $this->context->getToken()->getUser()->getId(),
                'session' => $socketSessionId,
            )
        );

        return $this->environment->render(
            'LeobenoistSocketBundle:Client:client.html.twig',
            array(
                'config' => $this->config,
                'session' => $socketSessionId,
            )
        );
    }

    public function registerLabel($label, $callback, $format = "html")
    {
        $this->server->send(
            'grantUserLabel',
            array(
                'user' => $this->context->getToken()->getUser()->getId(),
                'label' => $label,
            )
        );

        return $this->environment->render(
            'LeobenoistSocketBundle:Client:register.label.html.twig',
            array(
                'config' => $this->config,
                'label' => $label,
                'callback' => $callback,
                'format' => $format,
            )
        );
    }

    public function getName()
    {
        return 'SocketUserService';
    }

}

