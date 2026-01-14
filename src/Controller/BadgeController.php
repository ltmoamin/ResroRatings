<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Service\TwilioService;
use App\Form\BadgeType;
use App\Repository\BadgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\EmailService;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Security;

use UltraMsg\WhatsAppApi;
use App\Entity\User;
use App\Service\WhatsAppService;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;




#[Route('/badge')]
class BadgeController extends AbstractController
{

    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function index_pdf(BadgeRepository $badgeRepository, Request $request): Response
    {
        // Récupération du type de badge sélectionné à partir de la requête
        $badgeType = $request->query->get('badgeType');
    
        // Vérification si le type de badge est sélectionné
        if ($badgeType !== null && $badgeType !== 'All') {
            // Récupération des badges selon le type sélectionné
            $badges = $badgeRepository->findBy(['typebadge' => $badgeType]);
            $dompdf = new Dompdf();
    
        // Chemin vers votre image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/assets/img/logo.png';
    
        // Encodez l'image en base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
    
        // Génération du HTML à partir du template Twig 'badge/Badge_pdf.html.twig' en passant la liste des badges et l'image
        $html = $this->renderView('badge/' . $badgeType . '_pdf.html.twig', [
            'badges' => $badges,
            'imagePath' => $imageSrc,
        ]);
    
        // Récupération des options de Dompdf et activation du chargement des ressources à distance
        $options = $dompdf->getOptions();
        $options->set([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,  // Activer le rendu PHP
        ]);
    
        $dompdf->setOptions($options);
    
        // Chargement du HTML généré dans Dompdf
        $dompdf->loadHtml($html);
    
        // Configuration du format de la page en A4 en mode portrait
        $dompdf->setPaper('A4', 'portrait');
    
        // Génération du PDF
        $dompdf->render();
    
        // Récupération du contenu du PDF généré
        $output = $dompdf->output();
    
        // Configuration des en-têtes pour le téléchargement du PDF
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="liste_badges.pdf"',
        ]);
    
        return $response;
        } else {
            // Si "All" est sélectionné ou aucun type n'est spécifié, récupérer tous les badges
            $badges = $badgeRepository->findAll();
            $dompdf = new Dompdf();
    
        // Chemin vers votre image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/assets/img/logo.png';
    
        // Encodez l'image en base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
    
        // Génération du HTML à partir du template Twig 'badge/Badge_pdf.html.twig' en passant la liste des badges et l'image
        $html = $this->renderView('badge/Badge_pdf.html.twig', [
            'badges' => $badges,
            'imagePath' => $imageSrc,
        ]);
    
        // Récupération des options de Dompdf et activation du chargement des ressources à distance
        $options = $dompdf->getOptions();
        $options->set([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,  // Activer le rendu PHP
        ]);
    
        $dompdf->setOptions($options);
    
        // Chargement du HTML généré dans Dompdf
        $dompdf->loadHtml($html);
    
        // Configuration du format de la page en A4 en mode portrait
        $dompdf->setPaper('A4', 'portrait');
    
        // Génération du PDF
        $dompdf->render();
    
        // Récupération du contenu du PDF généré
        $output = $dompdf->output();
    
        // Configuration des en-têtes pour le téléchargement du PDF
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="liste_badges.pdf"',
        ]);
    
        return $response;
        }
    
        // Création d'une nouvelle instance de la classe Dompdf
        
    }

    #[Route('/stats', name: 'badge_stats')]
    public function badgeStats(BadgeRepository $badgeRepository): Response
    {
        $badgeStats = $badgeRepository->countBadgesByType();

        // Convertir les statistiques pour le rendu JSON
        $data = [];
        foreach ($badgeStats as $stat) {
            $data[] = [
                'type' => $stat['typebadge'],
                'count' => $stat['count'],
            ];
        }

        // Rendu de la vue
        return $this->render('badge/stats.html.twig', [
            'badgeStats' => json_encode($data), // Convertir en JSON pour le JavaScript
        ]);
    }


    // #[Route('/stat', name: 'app_badge_statistics')]
    // public function badgeStatistics(BadgeRepository $badgeRepository): JsonResponse
    // {
    //     $badgeStats = $badgeRepository->countBadgesByType();

    //     $stats = [];
    //     foreach ($badgeStats as $stat) {
    //         $stats[] = ['type' => $stat['type'], 'count' => $stat['count']];
    //     }

    //     return new JsonResponse($stats);
    // }


    #[Route('/what', name: 'whatsapp')]
    public function envoyerMessageWhatsApp($user, $restaurant, $badgeType, $date): Response
    {
        require_once __DIR__ . '/../../vendor/autoload.php'; // Make sure the path is correct
        $ultramsg_token = "tjo2dwlz03d1uqwi"; // Your Ultramsg token
        $instance_id = "instance70642"; // Your Ultramsg instance ID
    
        $client = new WhatsAppApi($ultramsg_token, $instance_id);
    
        $to = "+21640994876"; // Recipient's phone number
        $body = "Bonjour,\n\nNous vous informons qu'un nouveau badge a été signalé dans notre système. Voici les détails :\n\nUtilisateur : $user\nRestaurant : $restaurant\nType de badge : $badgeType\nDate de publication : $date\n\nVeuillez prendre les mesures nécessaires pour examen et suivi.\n\nCordialement.";
    
        // Send a text message
        $api = $client->sendChatMessage($to, $body);
    
        // Send an image message
        $image = "https://st5.depositphotos.com/72897924/61720/i/450/depositphotos_617209708-stock-photo-error-404-grey-wall.jpg";
        $caption = "Image Caption";
        $priority = 10;
        $referenceId = "SDK";
        $nocache = false;
        $imageApi = $client->sendImageMessage($to, $image, $caption, $priority, $referenceId, $nocache);
    
        print_r($api); // Handle the response as needed for the text message
        print_r($imageApi); // Handle the response for the image message
    
        // You can manage the responses as desired, for example, display them
        return new Response('WhatsApp messages sent successfully!');
    }
    

 #[Route('/badge/{id}/dislike', name: 'app_badge_dislike')]
    public function dislikeBadge(Badge $badge): Response
    {
        $badge->incrementDislikes();

        if ($badge->checkAndDeleteIfRequired()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($badge);
            $entityManager->flush();

            $user = $badge->getUser()->getUsername(); // Remplacez par la méthode réelle pour obtenir le nom de l'utilisateur
    $restaurant = $badge->getRestaurant()->getNom(); // Remplacez par la méthode réelle pour obtenir le nom du restaurant
    $badgeType = $badge->getTypebadge();
    $date = $badge->getDatebadge()->format('Y-m-d'); // Formatage de la date, remplacez selon votre format

    // Appel à la fonction pour envoyer le message WhatsApp avec les données
    $this->envoyerMessageWhatsApp($user, $restaurant, $badgeType, $date);
            $this->addFlash('warning', 'Le badge est signalé.');
            return $this->redirectToRoute('app_badge_indexFront');
        } else {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_badge_indexFront');
        }
    }



    #[Route('/front', name: 'app_badge_indexFront', methods: ['GET'])]
    public function indexf(BadgeRepository $badgeRepository): Response
    {
        return $this->render('badge/indexFront.html.twig', [
            'badges' => $badgeRepository->findAll(),
        ]);
    }

 

  



 

    #[Route('/search', name: 'app_badge_search', methods: ['GET'])]
    public function search(Request $request, BadgeRepository $badgeRepository): JsonResponse
    {
        $query = $request->query->get('query');
    
        $results = $badgeRepository->searchBadges($query);
    
        return new JsonResponse(['badges' => $results]);
    }

    #[Route('/generate-qr/{id}', name: 'app_badge_generate_qr', methods: ['GET'])]
    public function generateQrCodeForBadge($id, BadgeRepository $badgeRepository): Response
    {
        $badge = $badgeRepository->find($id);

        // Générer le contenu du QR Code (utilisez toutes les informations du client)
        $qrContent = sprintf(
            "Commentaire: %s\nDate du badge: %s\nType de badge: %s\nUtilisateur: %s\nRestaurant: %s",
    $badge->getCommantaire(),
    $badge->getDatebadge()->format('Y-m-d'), 
    $badge->getTypebadge(),
    $badge->getUser()->getUsername(),
    $badge->getRestaurant()->getNom()
        );

        // Créer une instance de QrCode
        $qrCode = new QrCode($qrContent);

        // Créer une instance de PngWriter pour générer le résultat sous forme d'image PNG
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Créer une réponse avec le résultat du QR Code
        $response = new Response($result->getString(), Response::HTTP_OK, [
            'Content-Type' => $result->getMimeType(),
        ]);

        return $response;
    }

    #[Route('/', name: 'app_badge_index', methods: ['GET'])]
    public function index(Request $request, BadgeRepository $badgeRepository, PaginatorInterface $paginator): Response
    {
        $query = $badgeRepository->createQueryBuilder('b')
            ->orderBy('b.datebadge', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            7
        );

        return $this->render('badge/index.html.twig', [
            'badges' => $pagination,
        ]);
    }

    
    // #[Route('/pdf/{id}', name:'pdf', methods: ['GET'])]
    // public function generatePDF(BadgeRepository $badgeRepository): Response
    // {
    //     // Récupérer les données de l'offrez

    //     // Rendre le modèle Twig avec les données de l'offre
    //     $html = $this->renderView('badge/badge_pdf.html.twig', [
    //         'badgeRepository' => $badgeRepository,
    //     ]);

    //     // Configuration de Dompdf
    //     $options = new Options();
    //     $options->set('isHtml5ParserEnabled', true);

    //     // Initialisation de Dompdf
    //     $dompdf = new Dompdf($options);
    //     $dompdf->loadHtml($html);

    //     // Réglage des options de rendu (facultatif)
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Générer le PDF
    //     $dompdf->render();

    //     // Envoyer le fichier PDF au navigateur
    //     return new Response($dompdf->output(), 200, [
    //         'Content-Type' => 'application/pdf',
    //     ]);
    // }
    
    
  

   
    #[Route('/new', name: 'app_badge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($user) {
            $badge->setUser($user);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $badge->setDatebadge(new \DateTime());
            $entityManager->persist($badge);
            $entityManager->flush();
        
            
            return $this->redirectToRoute('app_badge_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('badge/new.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }

   // public function br(Request $request, PaginatorInterface $paginator): Response
    // {
    //     $entityManager = $this->getDoctrine()->getManager();
    
    //     $query = $entityManager->getRepository(Badge::class)->createQueryBuilder('b')
    //         ->orderBy('b.datebadge', 'DESC')
    //         ->getQuery();
    
    //     $pagination = $paginator->paginate(
    //         $query,
    //         $request->query->getInt('page', 1),
    //         10
    //     );
    
    //     return $this->render('badge/index.html.twig', [
    //         'rap' => $pagination, // Utilisation de 'ppp' au lieu de 'badges'
    //     ]);
    // }
    
    
    #[Route('/new66', name: 'app_badge_new66', methods: ['GET', 'POST'])]
    public function new66(Request $request, EntityManagerInterface $entityManager, TwilioService $twilioService,Security $security): Response
    {
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($user) {
            $badge->setUser($user);
        }
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            $restaurantName = $badge->getRestaurant()->getNom();
    
            $existingBadge = $entityManager->getRepository(Badge::class)->findOneBy(['restaurant' => $restaurantName]);
    
            if (!$existingBadge) {
    
                $badge->setDatebadge(new \DateTime());
                $entityManager->persist($badge);
                $entityManager->flush();
                $to = '+216 20706900'; 
            
            $message = 'Le Badge est ajouté avec succès'; 
            $twilioService->sendSMS($to, $message);
        
                $this->addFlash('success', 'Le badge a été ajouté avec succès.');
                return $this->redirectToRoute('app_badge_indexFront');
            } else {
               
                $this->addFlash('error', 'Un badge existe déjà pour ce restaurant ! Veuillez en choisir un autre.');
            }
        }
    
        return $this->renderForm('badge/new66.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }
    
    
    #[Route('/{id}', name: 'app_badge_show', methods: ['GET'])]
    public function show(Badge $badge): Response
    {
        return $this->render('badge/show.html.twig', [
            'badge' => $badge,
        ]);
    }
    #[Route('sh/{id}', name: 'app_badge_show66', methods: ['GET'])]
    public function showFr(Badge $badge): Response
    {
        return $this->render('badge/show66.html.twig', [
            'badge' => $badge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_badge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Badge $badge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_badge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/edit.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editFront', name: 'app_badge_editFront', methods: ['GET', 'POST'])]
    public function editFront(Request $request, Badge $badge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_badge_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/editFront.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_badge_delete', methods: ['POST'])]
    public function delete(Request $request, Badge $badge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$badge->getId(), $request->request->get('_token'))) {
            $entityManager->remove($badge);
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_badge_index', [], Response::HTTP_SEE_OTHER);
    }
   
    #[Route('badgef/{id}', name: 'app_badge_deleteFront', methods: ['POST'])]
    public function deleteFront(Request $request, Badge $badge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$badge->getId(), $request->request->get('_token'))) {
            $entityManager->remove($badge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_badge_indexFront', [], Response::HTTP_SEE_OTHER);
    }
     
    #[Route('/badge/{id}/like', name: 'app_badge_like')]
public function likeBadge(Badge $badge): Response
{
    $badge->incrementLikes();
    // Enregistrez les modifications dans la base de données
    $this->getDoctrine()->getManager()->flush();

    return $this->redirectToRoute('app_badge_indexFront');
}
 // Assurez-vous d'importer la classe User de votre application


 

}