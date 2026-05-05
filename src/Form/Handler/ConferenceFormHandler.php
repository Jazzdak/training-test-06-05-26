<?php

namespace App\Form\Handler;

use App\Entity\Conference;
use App\Form\ConferenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerHelper;
use Symfony\Component\DependencyInjection\Attribute\AutowireMethodOf;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ConferenceFormHandler
{
    public function __construct(
        private EntityManagerInterface $manager,
        #[AutowireMethodOf(ControllerHelper::class)]
        private \Closure $createForm,
        #[AutowireMethodOf(ControllerHelper::class)]
        private \Closure $getUser,
        #[AutowireMethodOf(ControllerHelper::class)]
        private \Closure $redirectToRoute,
        #[AutowireMethodOf(ControllerHelper::class)]
        private \Closure $render,
    ) {}

    public function handle(Request $request, Conference $conference): Response
    {
        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $conference->getId()) {
                $conference->setCreatedBy($this->getUser());
                $this->manager->persist($conference);
            }

            $this->manager->flush();

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
            'conference' => $conference,
        ]);
    }

    public function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return ($this->createForm)($type, $data, $options);
    }

    public function getUser(): UserInterface
    {
        return ($this->getUser)();
    }

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): Response
    {
        return ($this->redirectToRoute)($route, $parameters, $status);
    }

    public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        return ($this->render)($view, $parameters, $response);
    }

}
