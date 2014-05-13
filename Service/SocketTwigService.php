<?php

namespace Leobenoist\SocketBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

/**
* Twig extension to inject socket.io in html
*
* @author    Leo Benoist <leo.benoist@gmail.com>
* @copyright 2014 Leo Benoist
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/
class SocketTwigService extends \Twig_Extension
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ServerService
     */
    protected $server;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     *@var array
     */
    protected $config;

    /**
     * @param Session         $session
     * @param SecurityContext $context
     * @param ServerService   $server
     * @param array           $config
     */
    public function __construct(Session $session, SecurityContext $context, ServerService $server, array $config)
    {
        $this->session = $session;
        $this->context = $context;
        $this->server = $server;
        $this->config = $config;
    }

    /**
     * Setup init runtime
     *
     * @param \Twig_Environment $environment
     *
     * @return void
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        parent::initRuntime($environment);
        $this->environment = $environment;
    }

    /**
     * Get twig functions
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'leobenoistSocketConnect'       => new \Twig_Function_Method($this, 'socketConnect'),
            'leobenoistSocketRegisterLabel' => new \Twig_Function_Method($this, 'registerLabel'),
        );
    }

    /**
     * Connection to the socket
     *
     * @return boolean
     */
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

    /**
     * Register the label
     *
     * @param string $label
     * @param string $callback
     * @param string $format
     *
     * @return boolean
     */
    public function registerLabel($label, $callback, $format = "html")
    {
        $this->server->send(
            'grantUserLabel',
            array(
                'user'  => $this->context->getToken()->getUser()->getId(),
                'label' => $label,
            )
        );

        return $this->environment->render(
            'LeobenoistSocketBundle:Client:register.label.html.twig',
            array(
                'config'   => $this->config,
                'label'    => $label,
                'callback' => $callback,
                'format'   => $format,
            )
        );
    }

    /**
     * Get the service name
     *
     * @return string
     */
    public function getName()
    {
        return 'SocketUserService';
    }

}
