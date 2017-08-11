<?php


namespace Nassau\CartoonBattle\EventListener;


use Nassau\CartoonBattle\Services\Authentication\AuthorizedUserRetriever;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class OnRequestUserInjectorListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizedUserRetriever
     */
    private $userRetriever;

    public function __construct(AuthorizedUserRetriever $userRetriever)
    {
        $this->userRetriever = $userRetriever;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => '__invoke'
        ];
    }

    public function __invoke(GetResponseEvent $event)
    {
        try {
            $event->getRequest()->attributes->set('user', $this->userRetriever->getAuthorizedUser());
        } catch (AccessDeniedHttpException $e) {
            // noop
        }
    }
}