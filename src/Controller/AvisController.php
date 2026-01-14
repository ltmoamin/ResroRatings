<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Service\CensorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Symfony\Component\Security\Core\Security;

use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Service\WhatsAppService;
use App\Service\EmailService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use UltraMsg\WhatsAppApi;
use CensorClean\CensorWords;
use Box\Spout\Writer\Common\Creator;


#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/top', name: 'app_top_three_views', methods: ['GET'])]
    public function topThreeMostViewedAvis(AvisRepository $avisRepository): Response
    {
        $topThreeAvis = $avisRepository->findTopThreeMostViewedAvis();
    
        return $this->render('Avis/MostThree.html.twig', [
            'topThreeAvis' => $topThreeAvis,
        ]);
    }
    
  
    #[Route('/send-email')]
    public function sendEmail(
        MailerInterface $mailer,
        $userName,
        $avisTitle,
        $avisViews
    ) {
        $htmlContent = '
        <style>
            /* Style CSS pour le lien */
            .button-link {
                display: inline-block;
                font-size: 14px;
                color: #ffffff;
                text-decoration: none;
                padding: 10px 20px;
                background-color: #0073ff;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }
            .button-link:hover {
                background-color: #0056b3;
            }
            /* Style pour la bordure */
            .container {
                border: 2px solid #0073ff;
                padding: 20px;
                border-radius: 15px;
            }
        </style>
        <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
            Félicitations ! Votre avis a reçu plus de 100 vues
        </h1>
        <div class="container" style="font-family: Arial, sans-serif;">
            <p>Cher(e) ' . $userName . ',</p>
            <p>Nous sommes ravis de vous informer que votre avis intitulé "' . $avisTitle . '" a été vu par plus de 100 personnes.</p>
            <p>Cet engouement pour votre avis est remarquable et reflète son importance pour notre communauté.</p>
            <p>Au total, votre avis a atteint ' . $avisViews . ' vues. Merci pour votre précieuse contribution !</p>
            <a href="https://127.0.0.1:8000/avis" class="button-link" target="_blank" style="margin-top: 20px; display: inline-block;">Cliquez ici pour en savoir plus</a>
            <p>Cordialement,</p>
        </div>
        <!-- Image Amazon -->
        <img src="https://img.freepik.com/premium-photo/customer-hand-review-feedback-five-star-rating-service-product-quality-positive-ranking-background-best-evaluation-user-experience-success-business-rate-businessman-satisfaction-5-score_79161-2307.jpg?w=360" alt="Image Amazon" style="max-width: 100%; border: 2px solid #0073ff; border-radius: 15px; margin-top: 20px;">
    ';
    
    
        $email = (new Email())
            ->from('aminfsm2001@gmail.com')
            ->to('mohamedamin.ltifi@esprit.tn')
            ->subject('Votre avis a atteint plus de 100 vues')
            ->html($htmlContent);
    
        try {
            $mailer->send($email);
            return new Response('Email envoyé avec succès!');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('rr/{id}', name: 'app_avis_show55', methods: ['GET'])]
    public function showf(Avis $avi, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $currentViews = $avi->getNbVue();
        $avi->setNbVue($currentViews + 1);
    
        if ($currentViews + 1==2) {
            // Appel de la fonction pour envoyer l'e-mail
            $this->sendEmail(
                $mailer,
                $avi->getUser()->getUsername(),
                $avi->getTitreavis(),
                $avi->getNbVue()
            );
            $this->addFlash('success', 'L\'email a été envoyé avec succès.');

        }
    
        $entityManager->persist($avi);
        $entityManager->flush();
    
        return $this->render('avis/show55.html.twig', [
            'avi' => $avi,
        ]);
    }
    
    
    #[Route('/', name: 'app_avis_index', methods: ['GET'])]
    public function index(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
{
    $restaurantName = $request->query->get('restaurant');
    $username = $request->query->get('username');
    $date = $request->query->get('date');

    $query = $avisRepository->findByCriteria($restaurantName, $username, $date);

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        7
    );

    return $this->render('avis/index.html.twig', [
        'av' => $pagination,
    ]);
}

    
    


    #[Route('/QR/{id}', name: 'Qrfunction')]
    public function generateQrCode($id, AvisRepository $repo): Response
    
    {
        $event = $repo->find($id);
    
        // Générer le contenu du QR Code (utilisez toutes les informations de l'événement)
        $qrContent = sprintf(
            "Nom de l'événement:  %s\nLieu: %s\nDescription: %s\nPrix: %s",
            $event->getDateavis() ,
            $event->getTitreavis(),
            $event->getPubavis(),
            $event->getId()
        );
    
        // Créer une instance de QrCode
        $qrCode = new QrCode($qrContent);
        
        // Création du writer pour générer l'image
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Encodage de l'image en base64
        $imageData = base64_encode($result->getString());
        $imageSrc = 'data:image/png;base64,' . $imageData;
        
        // Rendre le template Twig et passer les données encodées en base64
        return $this->render('avis/index.html.twig', [
            'imageQrCode' => $imageSrc,
        ]);
    
    }








    #[Route('/download/excel/avis', name: 'download_excel_avis')]
    public function downloadExcelFromAvis(EntityManagerInterface $entityManager, FlashBagInterface $flashBag): BinaryFileResponse
    {
        
        $avisRepository = $entityManager->getRepository(Avis::class);
        $avisData = $avisRepository->findAll(); // Vous pouvez adapter cette requête selon vos besoins
            
        
        $directoryPath = 'D:\pipipiweb\Piweb-ihebevent\Piweb-ihebevent\Piweb-ihebevent';
    
       
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true); 
        }
    
        $filePath = $directoryPath . '\fichier_excel_avis.xlsx';
    
        $writer = WriterEntityFactory::createXLSXWriter();
    
        if (!file_exists($filePath)) {
           
            $columnTitles = [
                ['ID', 'Username', 'Titre avis', 'Pub avis', 'Date avis', 'Pub avis', 'Nom restaurant'],
            ];
        
           
            $writer->openToFile($filePath);
            
            foreach ($columnTitles as $columnRow) {
               
                $titleRow = WriterEntityFactory::createRowFromArray($columnRow);
                $writer->addRow($titleRow); 
               
            }
            foreach ($avisData as $avis) {
                
                $row = [
                    $avis->getId(),
                    $avis->getUser()->getUsername(),
                    $avis->getTitreavis(),
                    $avis->getPubavis(),
                    $avis->getDateavis()->format('Y-m-d'),
                    $avis->getPubavis(), 
                    $avis->getRestaurant()->getNom(),
                ];
        
                $rowEntity = WriterEntityFactory::createRowFromArray($row);
                $writer->addRow($rowEntity);
            }
        
            $writer->close();
        }
        if (file_exists($filePath)) {
            $this->addFlash('success', 'Le fichier a été téléchargé avec succès.');

        }
    
        return $this->file($filePath, 'fichier_excel_avis.xlsx');
        
    }
   
    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($user) {
            $avi->setUser($user);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $avi-> setDateavis (new \DateTime());
            $entityManager->persist($avi);
            $entityManager->flush();
            $this->addFlash('success', 'L\'avis a été ajouté avec succès.');
            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/front', name: 'app_avis_indexFront', methods: ['GET'])]
    public function indexFront(AvisRepository $avisRepository): Response
    {
        return $this->render('avis/indexFront.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }
    // #[Route('/new55', name: 'app_avis_new55', methods: ['GET', 'POST'])]
    // public function new55(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $avi = new Avis();
    //     $form = $this->createForm(AvisType::class, $avi);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $avi-> setDateavis (new \DateTime());
    //         $entityManager->persist($avi);
    //         $entityManager->flush();
            
           
    //     }

    //     return $this->renderForm('avis/new55.html.twig', [
    //         'avi' => $avi,
    //         'form' => $form,
    //     ]);
    // }



    #[Route('/new55', name: 'app_avis_new55', methods: ['GET', 'POST'])]
public function new55(Request $request, EntityManagerInterface $entityManager, WhatsAppService $whatsAppService,Security $security): Response
{
    $avi = new Avis();
    $form = $this->createForm(AvisType::class, $avi);
    $form->handleRequest($request);
    $user = $security->getUser();

    if ($user) {
        $avi->setUser($user);
    }
    if ($form->isSubmitted() && $form->isValid()) {
        $avi->setDateavis(new \DateTime());
        $entityManager->persist($avi);
        $entityManager->flush();
        $this->addFlash('success', 'L\'avis a été ajouté avec succès.');
    }

    return $this->renderForm('avis/new55.html.twig', [
        'avi' => $avi,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager,Security $security): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L\'avis a été modifié avec succès.');

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editF', name: 'app_avis_editFront', methods: ['GET', 'POST'])]
    public function editf(Request $request, Avis $avi, EntityManagerInterface $entityManager,EmailService $emailService): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L\'avis a été modifié avec succès.');

            return $this->redirectToRoute('app_avis_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/editFront.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }
   
    

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $this->addFlash('success', 'L\'avis a été supprimée avec succès.');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('back/{id}', name: 'app_avis_deleteFront', methods: ['POST'])]
    public function deletef(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
            $this->addFlash('success', 'L\'avis a été supprimée avec succès.');

        }

        return $this->redirectToRoute('app_avis_indexFront', [], Response::HTTP_SEE_OTHER);
    }
   


    
}



