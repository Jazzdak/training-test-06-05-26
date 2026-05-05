<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

final class MaintenanceListener
{
    public function __construct(
        private readonly Environment $twig,
        #[Autowire(env: 'APP_MAINTENANCE')]
        private readonly bool $isMaintenance,
    ) {}

    #[AsEventListener(priority: 9999)]
    public function onRequestEvent(RequestEvent $event): void
    {
        if ($this->isMaintenance) {
            $response = new Response();

            if ($event->isMainRequest()) {
                $response->setContent($this->twig->render('maintenance.html.twig'));
            }

            $event->setResponse($response);
        }
    }
}
