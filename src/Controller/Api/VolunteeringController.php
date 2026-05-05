<?php

namespace App\Controller\Api;

use App\Repository\VolunteeringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class VolunteeringController extends AbstractController
{
    #[Route('/api/volunteering', name: 'app_api_volunteering', methods: ['GET'])]
    public function index(Request $request, VolunteeringRepository $repository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $volunteerings = $repository->findBy([], [], 10, ($page - 1) * 10);
        dump($volunteerings);

        return $this->json($volunteerings, context:[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn(object $o) => $o->getId(),
            AbstractNormalizer::GROUPS => ['volunteering:list'],
        ]);
    }
}
