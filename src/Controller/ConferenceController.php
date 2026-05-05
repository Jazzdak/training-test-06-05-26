<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Form\Handler\ConferenceFormHandler;
use App\Search\Database\DatabaseConferenceSearch;
use App\Search\Interface\ConferenceSearchInterface;
use App\Search\Provider\ConferenceProvider;
use App\Security\Voter\Attributes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ConferenceController extends AbstractController
{
    //#[IsGranted(new Expression("is_granted('ROLE_ORGANIZER') or is_granted('ROLE_WEBSITE')"))]
    #[Route('/conference/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    #[Route('/conference/{id}/edit', name: 'app_conference_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function newConference(?Conference $conference, Request $request, ConferenceFormHandler $handler): Response
    {
        $conference ??= new Conference();
        if (null === $conference->getId()) {
            $this->denyAccessUnlessGranted(new Expression("is_granted('ROLE_ORGANIZER') or is_granted('ROLE_WEBSITE')"));
        } else {
            $this->denyAccessUnlessGranted(Attributes::EDIT_CONFERENCE, $conference);
        }

        return $handler->handle($request, $conference);
    }

    #[Route('/conference', name: 'app_conference_list', methods: ['GET'])]
    public function list(Request $request, ConferenceProvider $provider): Response
    {
        $name = $request->query->get('name');

        return $this->render('conference/list.html.twig', [
            'conferences' => $provider->get([$name]),
        ]);
    }

    #[Route('/conference/{id}', name: 'app_conference_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }
}
