<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\EmailService;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Twilio\Rest\Client;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
#[Route('/reclamation')]
class ReclamationController extends AbstractController
{ 


   

    // Vos autres actions dans le contrôleur...

    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $pagination =  $paginator->paginate(  
        $reclamationRepository->paginationQuerry()
        ,
            $request->query->getInt('page', 1), // Récupère le numéro de page de la requête, 1 étant la page par défaut
            4 // Nombre d'éléments par page
        );
    
        return $this->render('reclamation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/mes-reclamations/{iduser}', name: 'mes_reclamations')]
        public function mesReclamations(string $iduser,PaginatorInterface $paginator, Request $request,UserRepository $userRepository): Response
    {
        $user = $userRepository->find($iduser);
    
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
    
        $reclamations = $user->getReclamations();
        $pagination =  $paginator->paginate(  
            $reclamations,
                $request->query->getInt('page', 1), // Récupère le numéro de page de la requête, 1 étant la page par défaut
                4 // Nombre d'éléments par page
            );
        return $this->render('reclamation/mes_reclamations.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    #[Route('/statistic', name:'statistic', methods: ['GET'])]
    public function getReclamationsCount(Request $request, ReclamationRepository $reclamationRepository, ReponseRepository $reponseRepository): Response
    {
        // Retrieve the Reclamation entity by ID using Doctrine entity manager
        $entityManager = $this->getDoctrine()->getManager();
        //$reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
    
        // Call the countReclamations method from the repository
        $counttt = $reclamationRepository->countReclamationsen_attente();
        $countttt = $reclamationRepository->countReclamationsresolue();
        $count = $reclamationRepository->countReclamations();
        $countt = $reponseRepository->countReponses();
    
        // Render the count in a response
        return $this->render('reclamation/statistic.html.twig', [
            'count' => $count,
            'countt' => $countt,
            'counttt' => $counttt,
            'countttt' => $countttt,
        ]);
    }
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forbiddenWords = ['gros_mot_1', 'gros_mot_2', 'gros_mot_3'];
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
  // #[Route('/new1', name: 'app_reclamation_new1', methods: ['GET', 'POST'])]
  //  public function new1(Request $request, EntityManagerInterface $entityManager): Response
  //  {
  //      $reclamation = new Reclamation();
  //  $form = $this->createForm(ReclamationType::class, $reclamation);
   // $form->handleRequest($request);

  //  if ($form->isSubmitted() && $form->isValid()) {
   //     // Vérifie si l'état de la réclamation est "en_attente"
    //    if ($reclamation->getEtatrec() === 'en_attente') {
     //       $entityManager->persist($reclamation);
      //      $entityManager->flush();
            // Redirection après l'ajout avec un message de succès
     //       $this->addFlash('success', 'Réclamation ajoutée avec succès.');
       //     return $this->redirectToRoute('app_reclamation_new1');
      //  } else {
            // Ajoute un message d'erreur
      //      $this->addFlash('error', 'L\'état de la réclamation doit être "en_attente".');
      //  }
   // }

  //  return $this->renderForm('reclamation/new1.html.twig', [
   //     'reclamation' => $reclamation,
    //    'form' => $form,
   // ]);
//}
#[Route('/new1', name: 'app_reclamation_new1', methods: ['GET', 'POST'])]
public function new1(Request $request, EntityManagerInterface $entityManager, Security $security, SessionInterface $session): Response
{
    $reclamation = new Reclamation();

    // Récupérer l'utilisateur actuellement authentifié
    $user = $security->getUser();

    if ($user) {
        // Associer l'utilisateur à la réclamation
        $reclamation->setUser($user);
    }

    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {if(($reclamation->getEtatrec() === 'en_attente')){
        // Votre logique pour la gestion de la réclamation
        $entityManager->persist($reclamation);
        $entityManager->flush();

        // Gérer des données spécifiques à la session
        $session->set('reclamation_added', true);

        // Redirection après l'ajout avec un message de succès
        $this->addFlash('success', 'Réclamation ajoutée avec succès.');
        return $this->redirectToRoute('app_reclamation_new1');
    }else{
        $this->addFlash('error','l\'etat doit etre en attente');
    }}

    return $this->render('reclamation/new1.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{idrec}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{idrec}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idrec}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdrec(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{idrec}/answer', name: 'app_reclamation_answer', methods: ['GET', 'POST'])]
public function answer(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager,\Swift_Mailer $mailer): Response
{
    $reponse = new Reponse();
    $form = $this->createForm(ReponseType::class, $reponse);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reponse->setReclamation($reclamation); // Associer la réponse à la réclamation
        // Peut-être d'autres logiques de traitement ici

        $entityManager->persist($reponse);
        $reclamation->setEtatrec('resolue');

        $entityManager->persist($reclamation);
        $entityManager->flush();
        $sid = 'AC8eaa46e8ebf7a3f9981d025f540fc4de';
        $token = 'c852629397a736df578a4f2ae8a7c645';
        $twilio = new Client($sid, $token);
        $user = $reclamation->getUser();
        $type = $reclamation->getTyperec();
        $date = $reclamation->getDate();
        $userfirstname = $user->getFirstName();
        $userlastname = $user->getLastName();
        $userEmail = $user->getEmail();
        $userTel = $user->getTel();
        $subject = 'Réponse à votre réclamation';
        $content = '<!DOCTYPE html>
        <html lang="fr">
        <head>
          <meta charset="UTF-8">
          <title>Lettre type de réponse à votre réclamation</title>
          <style>
          /* Cadre autour de la page */
          body {
            border: 10px solid #ccc; /* Bordure de 10px solide avec couleur grise */
            padding: 20px; /* Espacement intérieur pour le contenu */
          }
      
          /* Styles pour les différentes sections */
          .donnees-expediteur {
            /* Vos styles pour cette section */
          }
      
          .donnees-destinataire {
            /* Vos styles pour cette section */
            color: blue; /* Texte en bleu par exemple */
          }
      
          .corps {
            /* Vos styles pour cette section */
            background-color: #f7f7f7; /* Arrière-plan gris clair par exemple */
          }
      
          /* Autres styles que vous souhaitez appliquer */
        </style>
      
        </head>
        <body>
          <header>
            <h1>Lettre type de reponse à votre réclamation</h1>
          </header>
          <main>
            <div class="donnees-expediteur">
              <h2>INNOVATORS</h2>
              <p>MCHAIRIA YASSINE</p>
              <p>CIty el hidhab,Fouchena</p>
              <p>2028 - Ben A ROUS</p>
              <p>20229168</p>
              <p>INNOVATORS@gmail.com</p>
            </div>
            <div class="donnees-destinataire">
              <h2>Nom et Prénom du destinataire</h2>
              <p style="color: red;">' . $userfirstname . ' ' . $userlastname . '</p>
            </div>
            <div class="corps">
              <h2>Objet: réponse à la suite de la réclamation relative à nos services</h2>
              <p>
                 Monsieur
              </p>
              <p>
                Nous avons bien reçu votre reclamation   relative  aux nos <p style="color: blue;">' . $type .  '</p>
                 Nous regrettons cet incident indépendant de notre volonté.
              </p>
              <p>
                Nous vous proposons de remplacer la qualite de servce. Cependant, vous voudrez bien
                nous revisiter.
              </p>
              <p>
                Nous sommes à votre disposition pour toute demande de votre part.
              </p>
              <p>
                Veuillez agréer Monsieur, nos salutations distinguées.
              </p>
            </div>
           
            
            </main>
            </body>
            </html>';
         // Contenu du message

            // Utilisation du service emailService pour envoyer l'e-mail
         
            
        
        $message = (new \Swift_Message('Nouveau Contact'))
       ->setFrom('mohamedyemen.khefacha@esprit.tn')
       ->setTo($userEmail)
       ->setsubject( $subject )
       ->setBody($content,'text/html');

       $mailer->send($message);
      $userPhoneNumber = '+21620229168'; // Remplacez par le numéro de téléphone de l'utilisateur
        $twilioPhoneNumber = '+19285506150'; // Votre numéro Twilio
       
       $messageBody = 'Votre réclamation a été résolue. Merci pour votre compréhension.';
        $message = $twilio->messages->create(
            $userPhoneNumber,
            [
                'from' => $twilioPhoneNumber,
                'body' => $messageBody
            ]
        );
       
        // Redirection ou autre logique après avoir répondu à la réclamation
        $reponse = new Reponse(); // Créez une nouvelle instance de réponse
        $form = $this->createForm(ReponseType::class, $reponse);
    }

    return $this->renderForm('reponse/new.html.twig', [
        'reclamation' => $reclamation,
        'user' => $reclamation->getUser(),
        'form' => $form,
    ]);
}
    #[Route('/export-excel/{idrec}', name: 'app_reclamation_export_excel')]
    public function exportExcel(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la réclamation spécifique par son ID
        // $reclamation est déjà passé en paramètre grâce à l'annotation Route
        if (!$reclamation) {
            throw $this->createNotFoundException('La réclamation n\'a pas été trouvée');
        }
    
        // Création d'un objet Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // En-têtes du fichier Excel
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('C1', 'Type');
        $sheet->setCellValue('E1', 'Etat');
        // Ajoute d'autres en-têtes pour les attributs de Reclamation
    
        // Remplissage des données pour une seule réclamation
        $sheet->setCellValue('A2', $reclamation->getIdrec());
        $sheet->setCellValue('B2', $reclamation->getDate()->format('Y-m-d'));
        $sheet->setCellValue('C2', $reclamation->getDescription());
        $sheet->setCellValue('D2', $reclamation->getTyperec());
        $sheet->setCellValue('E2', $reclamation->getEtatrec());
        // Ajoute d'autres colonnes avec les attributs de Reclamation si nécessaire
    
        // Génération du fichier Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'reclamation_' . $reclamation->getIdrec() . '.xlsx'; // Nom de fichier unique pour chaque réclamation
        // $filePath = '"C:\Users\LENOVO\Downloads"' . $fileName; // Chemin de destination du fichier
        $directoryPath = 'D:\pipipiweb\Piweb-ihebevent\Piweb-ihebevent\Piweb-ihebevent';
    
       
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true); 
        }
        $filePath = $directoryPath . '\fichier_excel_avis.xlsx';
        $writer->save($filePath);
    
        // Création d'une réponse pour le téléchargement
        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($filePath);
        $response->setContentDisposition(
            \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );
    
        return $response;
    }
    

    
   
    
}
