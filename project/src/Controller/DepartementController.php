<?php

namespace App\Controller;

use App\Service\CacheService;
use App\Service\CallApiService;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepartementController extends AbstractController
{
    /**
     * @Route("/departement/{departement}", name="departement")
     */
    public function index(string $departement, CallApiService $callApiService, ChartBuilderInterface $chartBuilder, CacheService $cacheService): Response
    {
        $departs = $callApiService->getDataDepartement($departement);
        //dd($departs);
        foreach($departs as $depart){            
            if(
                isset($depart['date']) && 
                isset($depart['nouvellesHospitalisations']) &&  
                isset($depart['nouvellesReanimations'])
            ) 
            {
                $labelNvlHospitRea[] = $depart['date'];
                $nouvellesHospitalisations[] = $depart['nouvellesHospitalisations'];
                $nouvellesReanimations[] = $depart['nouvellesReanimations'];
            }
            if(
                isset($depart['deces']) &&
                isset($depart['gueris'])
            )
            {
                $labelDecesGueris[] = $depart['date'];
                $deces[] = $depart['deces'];
                $gueris[] = $depart['gueris'];
            }

            if(
                isset($depart['hospitalises']) &&
                isset($depart['reanimation'])
            ){
                $labelHospitRea[] = $depart['date'];
                $hospitalises[] = $depart['hospitalises'];
                $reanimation[] = $depart['reanimation'];
            }

        }
        
        $chartNvlHospitRea = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartNvlHospitRea->setData([
            'labels' => $labelNvlHospitRea,
            'datasets' => [
                [
                    'label' => 'Nouvelles Hospitalisations',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $nouvellesHospitalisations,
                ],
                [
                    'label' => 'Nouvelles entrées en Réa',
                    'borderColor' => 'rgb(46, 41, 78)',
                    'data' => $nouvellesReanimations,
                ],
            ],
        ]);

        $chartDecesGueris = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartDecesGueris->setData([
            'labels' => $labelDecesGueris,
            'datasets' => [
                [
                    'label' => 'Nombre de décès totaux',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $deces,
                ],
                [
                    'label' => 'Nombre de guérisons totaux',
                    'borderColor' => 'rgb(46, 41, 78)',
                    'data' => $gueris,
                ],
            ],
        ]);

        $chartHospitRea = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartHospitRea->setData([
            'labels' => $labelHospitRea,
            'datasets' => [
                [
                    'label' => 'Personnes en hospitalisation',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $hospitalises,
                ],
                [
                    'label' => 'Personnes en réanimations',
                    'borderColor' => 'rgb(46, 41, 78)',
                    'data' => $reanimation,
                ],
            ],
        ]);

        $response = $this->render('departement/index.html.twig', [
            'departement' => $departement,
            'chartNvlHospitRea' => $chartNvlHospitRea,
            'chartDecesGueris' => $chartDecesGueris,
            'chartHospitRea' => $chartHospitRea
        ]);

        return $cacheService->response($response);
    }
}
