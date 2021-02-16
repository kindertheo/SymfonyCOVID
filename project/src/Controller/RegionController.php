<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegionController extends AbstractController
{
    /**
     * @Route("/region/{region}", name="region")
     */
    public function index(string $region, CallApiService $callApiService): Response
    {
        return $this->render('region/index.html.twig', [
            'region' => $callApiService->getDataDepartement($region)
        ]);
    }
}
