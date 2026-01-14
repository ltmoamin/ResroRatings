<?php

namespace App\Controller;

use App\Entity\Evennement;
use App\Form\EvennementType;
use App\Repository\EvennementRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/evennement')]
class EvennementController extends AbstractController
{
    #[Route('/', name: 'app_evennement_index', methods: ['GET'])]
public function index(Request $request, EvennementRepository $evennementRepository): Response
{
    
    $searchQuery = $request->query->get('q');

    
    if ($searchQuery) {
     
        $evennements = $evennementRepository->advancedSearch($searchQuery);
    } else {
        
        $evennements = $evennementRepository->findAll();
    }

    
    return $this->render('evennement/index.html.twig', [
        'evennements' => $evennements,
    ]);
}



    #[Route('/new', name: 'app_evennement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evennement = new Evennement();
        $form = $this->createForm(EvennementType::class, $evennement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('img')->getData();
    
           
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
    
     
                $file->move(
                    'eventimages',
                    $filename
                );
    
               
                $evennement->setImg($filename);
            }
    
            $entityManager->persist($evennement);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('evennement/new.html.twig', [
            'evennement' => $evennement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevent}', name: 'app_evennement_show', methods: ['GET'])]
    public function show(Evennement $evennement, ParticipantRepository $rep, int $idevent): Response
    {
        $listParticipants = $rep->findParticipantsDetailsByEvent2($idevent);
        $countParticipants = $rep->countParticipantsByEvennement($idevent);

        $count = is_array($countParticipants) && isset($countParticipants[0]['count_participants']) ? $countParticipants[0]['count_participants'] : 0;
    
        return $this->render('evennement/show.html.twig', [
            'evennement' => $evennement,
            'countParticipants' => $count,
            'Listparticipants' => $listParticipants, 
        ]);
    }

    #[Route('/{idevent}/edit', name: 'app_evennement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evennement $evennement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvennementType::class, $evennement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evennement/edit.html.twig', [
            'evennement' => $evennement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevent}', name: 'app_evennement_delete', methods: ['POST'])]
    public function delete(Request $request, Evennement $evennement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evennement->getIdevent(), $request->request->get('_token'))) {
            $entityManager->remove($evennement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/searchEvennementAjax', name: 'app_evennement_search_ajax', methods: ['GET'])]
    
    public function searchEvennementAjax(Request $request, EvennementRepository $evennementRepository): Response
    {
        
        $searchQuery = $request->query->get('q');

        if (!$searchQuery) {
            return $this->render('evennement/index.html.twig', ['evennements' => []]);
        }

        $evennements = $evennementRepository->advancedSearch($searchQuery);
        $html = $this->render('evennement/index.html.twig', ['evennements' => $evennements])->getContent();

        return new Response($html);
    }
    
}
      
    

