<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 22/10/2017
 * Time: 19:49
 */

namespace Admin\EventSubscriber;

use Admin\AdminControllerInterface;
use AppBundle\Utils\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserData implements EventSubscriberInterface
{
    protected $container;

    protected $requestStack;

    protected $logger;

    /**
     * UserData constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Symfony\Component\HttpFoundation\RequestStack            $request
     * @param \Psr\Log\LoggerInterface                                  $logger
     */
    public function __construct(ContainerInterface $container, RequestStack $request, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->requestStack = $request;
        $this->logger = $logger;

    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return bool|void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }


        /** @var \AppBundle\Utils\Api\Redis $api */
        $api = $this->container->get('app.api.redis');

        $user = new User();
        $userData = $user->isLoggedIn($this->requestStack->getCurrentRequest(), $api, $this->logger);

        /** @var \Twig_Environment $twig */
        $twig = $this->container->get('twig');
        $twig->addGlobal('logged_in_data', $userData);
        $twig->addGlobal('logged_in_status', (isset($userData['result']) ? $userData['result'] : []));
        $twig->addGlobal('is_admin', (isset($userData['contents']) && $userData['contents']->admin));

        /**
         * Restrict access to admin area
         * If controller implements AdminController and user is not logged in
         * or is not admin, then throw exception
         */
        if ($event->getController()[0] instanceof AdminControllerInterface) {
            if (!
                (!empty($userData) &&
                $userData['result'] &&
                $userData['contents']->admin === true
                )
            ) {
                throw new AccessDeniedException('Getta outta here');
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
