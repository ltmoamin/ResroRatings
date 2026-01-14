<?php

namespace App\Controller;
use App\Entity\Plat;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Entity\Achat;
use App\Entity\User;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Security;



#[Route('/achat')]
class AchatController extends AbstractController
{  
    #[Route('/', name: 'app_achat_index', methods: ['GET'])]
    public function index(AchatRepository $achatRepository): Response
    {
        return $this->render('achat/index.html.twig', [
            'achats' => $achatRepository->findAll(),
        ]);
    }

    #[Route('/type', name: 'app_achat_index_type', methods: ['GET', 'POST'])]
    public function indexType(Request $request, AchatRepository $achatRepository): Response
    {
        $type = $request->get('type');
    
        if ($type) {
            $achats = $achatRepository->findByType($type);
        } else {
            $achats = $achatRepository->findAll();
        }
    
        return $this->render('achat/index.html.twig', [
            'achats' => $achats,
        ]);
    }
    
    #[Route('/top3', name: 'app_achat_top3', methods: ['GET'])]
    public function top3Plats(): Response
    {
        $top3Plats = $this->getDoctrine()->getRepository(Achat::class)->findTop3Plats();

        return $this->render('achat/top3.html.twig', [
            'top3Plats' => $top3Plats,
        ]);
    }

    
    #[Route('/stats/plats-par-categorie', name: 'app_stats_plats_par_categorie', methods: ['GET', 'POST'])]
    public function statsPlatsParCategorie(Request $request, AchatRepository $achatRepository): Response
    {
        $selectedDate = $request->request->get('selectedDate', date('Y-m-d'));

        $stats = $achatRepository->findPlatsCountByCategoryAndDate(new \DateTime($selectedDate));

        return $this->render('achat/stats_plats_par_categorie.html.twig', [
            'stats' => $stats,
            'selectedDate' => $selectedDate,
        ]);
    }

    
#[Route('/date', name: 'app_achat_index_date', methods: ['GET', 'POST'])]
public function indexDate(Request $request, AchatRepository $achatRepository): Response
{
    $date = $request->get('date');

    if ($date) {
        $date = new \DateTime($date);
        $achats = $achatRepository->findByDate($date);
    } else {
        $achats = $achatRepository->findAll();
    }

    return $this->render('achat/index.html.twig', [
        'achats' => $achats,
    ]);
}




/*#[Route('/new', name: 'app_achat_new', methods: ['POST'])]
public function new(Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager, PlatRepository $platRepository, SessionInterface $session): Response
{
    $platId = $request->request->get('idplat');
    $quantity = $request->request->get('quantite');
    $type = $request->request->get('type');

    $fakeUser = new User();
    $fakeUser->setUsername('med amine');
    $fakeUser->setEmail('medvmiine@gmail.com');
    $fakeUser->setPassword('password123');

    $achat = new Achat();

    if ($platId) {
        $plat = $platRepository->find($platId);

        if ($plat) {
            $achat->setPlat($plat);
        }
    }

    $achat->setQuantite((int)$quantity);
    $achat->setType((string)$type);
    $achat->setDate(new \DateTime());

    $achat->setUser($fakeUser);
    $achat->setMontanttotal((float)$achat->calculateTotalAmount());

    $entityManager->persist($achat);
    $entityManager->flush();

    $achatsSession = $session->get('achats', []);

    $achatsSession[] = $achat;

    $session->set('achats', $achatsSession);

    $i = $session->get('achat_counter', 0);
    $i++;

    $i = max(0, $i);

    $session->set('achat_counter', $i);

    $this->addFlash('success', 'Plat ajouté au panier!');

    return $this->redirectToRoute('app_plat_index', ['counter' => $i]);
}
*/
#[Route('/new', name: 'app_achat_new', methods: ['POST'])]
public function new(Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager, PlatRepository $platRepository, SessionInterface $session, Security $security): Response
{
    $platId = $request->request->get('idplat');
    $quantity = $request->request->get('quantite');
    $type = $request->request->get('type');

    $achat = new Achat();

    if ($platId) {
        $plat = $platRepository->find($platId);

        if ($plat) {
            $achat->setPlat($plat);
        }
    }

    $achat->setQuantite((int)$quantity);
    $achat->setType((string)$type);
    $achat->setDate(new \DateTime());

    $user = $security->getUser();

    if ($user) {
        $achat->setUser($user);
    }

    $achat->setMontanttotal((float)$achat->calculateTotalAmount());

    $entityManager->persist($achat);
    $entityManager->flush();

    $achatsSession = $session->get('achats', []);
    $achatsSession[] = $achat;

    $session->set('achats', $achatsSession);

    $i = $session->get('achat_counter', 0);
    $i++;

    $i = max(0, $i);

    $session->set('achat_counter', $i);

    $this->addFlash('success', 'Plat ajouté au panier!');

    return $this->redirectToRoute('app_plat_index', ['counter' => $i]);
}


#[Route('/panier', name: 'app_view_panier', methods: ['GET'])]
public function viewPanier(SessionInterface $session): Response
{
    $achatsSession = $session->get('achats', []);
    $i = $session->get('achat_counter', 0);

    return $this->render('panier/index.html.twig', [
        'achats' => $achatsSession,
        'counter' => $i,
    ]);
}

    #[Route('/{idachat}', name: 'app_achat_show', methods: ['GET'])]
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    #[Route('/{idachat}/edit', name: 'app_achat_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Achat $achat, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AchatType::class, $achat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Achat mis à jour avec succès.');

        return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('achat/edit.html.twig', [
        'achat' => $achat,
        'form' => $form,
    ]);
}
   
#[Route('/panier/pdf', name: 'app_generate_panier_pdf')]
public function generatePanierPDF(\Swift_Mailer $mailer,SessionInterface $session): Response
{
    $achatsSession = $session->get('achats', []);

    $username = $achatsSession[0]->getUser()->getUsername();
    $email = $achatsSession[0]->getUser()->getEmail();
    $type = $achatsSession[0]->getType();
    $montantTotal = $achatsSession[0]->getMontanttotal();

    $totalMontant = 0;
    foreach ($achatsSession as $achat) {
        $totalMontant += $achat->getMontanttotal();
    }

    $codeBarre = $this->generateBarcode($totalMontant, $username);

    $html = $this->renderView('panier/panier_pdf.html.twig', [
        'achats' => $achatsSession,
        'codeBarre' => $codeBarre,
        'username' => $username,
        'type' => $type,
        'montantTotal' => $montantTotal,
    ]);

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    $message = (new \Swift_Message('Nouveau Contact'))
    ->setFrom('mohamedamine.brahmi@esprit.tn')
    ->setTo($email)
    ->setsubject('Votre Facture !!');
    $attachement = new \Swift_Attachment($output, "Facture.pdf", 'application/pdf' );
    $message->attach($attachement);

        $mailer->send($message); 
    $uniqueId = time();

    $pdfPath = 'D:\pipipiweb\Piweb-ihebevent\Piweb-ihebevent\Piweb-ihebevent\public\PDF/panier_' . $username . '_' . $uniqueId . '.pdf';
    file_put_contents($pdfPath, $dompdf->output());

    $response = new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename=panier_' . $username . '_' . $uniqueId . '.pdf',
    ]);

    return $response;
}
private function generateBarcode( float $totalMontant, string $username): string
{
    $generator = new BarcodeGeneratorHTML();
    
    $barcodeContent = "{$username}-Total-{$totalMontant}DT";
    
    $codeBarre = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_128);

    return $codeBarre;
}  
  
#[Route('/update-quantite/{achatId}', name: 'app_update_quantite', methods: ['POST'])]
public function updateQuantite(Request $request, $achatId, SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    $achatsSession = $session->get('achats', []);

    foreach ($achatsSession as $key => $achat) {
        if ($achat->getIdachat() == $achatId) {
            $newQuantite = $request->request->get('newQuantite');

            $achatEntity = $entityManager->getRepository(Achat::class)->find($achatId);

            if ($achatEntity) {
                $achatEntity->setQuantite($newQuantite);

                $nouveauMontantTotal = $achatEntity->calculateTotalAmount();

                $achatEntity->setMontanttotal($nouveauMontantTotal);

                $entityManager->flush();
            }

            $achatsSession[$key]->setQuantite($newQuantite);
            $achatsSession[$key]->setMontanttotal($nouveauMontantTotal);
            $session->set('achats', $achatsSession);

            $this->addFlash('success', 'Quantité modifiée avec succès.');

            break;
        }
    }

    return $this->redirectToRoute('app_view_panier');
}







    #[Route('/{idachat}', name: 'app_achat_delete', methods: ['POST'])]
    public function delete(Request $request, Achat $achat, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$achat->getIdachat(), $request->request->get('_token'))) {
        $entityManager->remove($achat);
        $entityManager->flush();

        $this->addFlash('success', 'Achat supprimé avec succès.');
    }

    return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
}

 #[Route('/remove-from-panier/{achatId}', name: 'app_remove_from_panier', methods: ['GET'])]
public function removeFromPanier(SessionInterface $session, $achatId, EntityManagerInterface $entityManager, FlashBagInterface $flashBag): Response
{
    $achatsSession = $session->get('achats', []);

    foreach ($achatsSession as $key => $achat) {
        if ($achat->getIdachat() == $achatId) {
            unset($achatsSession[$key]);

            $achatEntity = $entityManager->getRepository(Achat::class)->find($achatId);
            if ($achatEntity) {
                $entityManager->remove($achatEntity);
                $entityManager->flush();
            }

            $flashBag->add('success', 'Suppression réussie.');

            break;
        }
    }

    $session->set('achats', $achatsSession);

    $i = $session->get('achat_counter', 0);
    $i--;

    $i = max(0, $i);

    $session->set('achat_counter', $i);

    return $this->redirectToRoute('app_view_panier', ['counter' => $i]);
}





}



















    

    


