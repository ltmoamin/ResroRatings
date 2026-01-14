<?php

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BadgeRepository;

class ChartController extends AbstractController
{
    private $badgeRepository;

    // Injection de dépendances dans le constructeur
    public function __construct(BadgeRepository $badgeRepository)
    {
        $this->badgeRepository = $badgeRepository;
    }

    #[Route('/chart', name: 'app_chart')]
    public function index(): Response
    {
        // Utilisation du BadgeRepository injecté
        $nb_diamant = $this->badgeRepository->getNbDiamant();
        $nb_silver = $this->badgeRepository->getNbSilver();
        $nb_vip = $this->badgeRepository->getNbVIP();

        // Create a PieChart instance
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable([
            ['Badge', 'Count'],
            ['Diamant', $nb_diamant],
            ['Silver', $nb_silver],
            ['VIP', $nb_vip],
        ]);
        $pieChart->getOptions()->setTitle('Badge Distribution');

        // Render the chart in your template
        return $this->render('chart/index.html.twig', [
            'chart' => $pieChart,
        ]);
    }
}
