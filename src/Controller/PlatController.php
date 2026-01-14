<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/plat')]
class PlatController extends AbstractController
{
    #[Route('/', name: 'app_plat_index', methods: ['GET'])]
    public function index(PlatRepository $platRepository, SessionInterface $session): Response
    {
        $counter = $session->get('achat_counter', 0);

        return $this->render('plat/plats_list.html.twig', [
            'plats' => $platRepository->findAll(),
            'counter' => $counter,
        ]);
    }

    
    
        #[Route('/platback', name: 'app_plat_index_back', methods: ['GET'])]
        public function indexback(PlatRepository $platRepository, SessionInterface $session): Response
        {
            $counter = $session->get('achat_counter', 0);
    
            return $this->render('plat/index.html.twig', [
                'plats' => $platRepository->findAll(),
                'counter' => $counter,
            ]);
        }
        #[Route('/new', name: 'app_plat_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $plat = new Plat();
            $form = $this->createForm(PlatType::class, $plat);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $form->get('image')->getData();
        
                if ($file) {
                    $filename = uniqid() . '.' . $file->guessExtension();
        
                    $file->move(
                        'platimage',
                        $filename
                    );
        
                    $plat->setImage($filename);
                }
                $entityManager->persist($plat);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_plat_index_back', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('plat/new.html.twig', [
                'plat' => $plat,
                'form' => $form,
            ]);
        }

    #[Route('/ajouter-au-favoris/{platId}', name: 'ajouter_au_favoris', methods: ['GET'])]
public function ajouterAuFavoris($platId, SessionInterface $session): Response
{
    $favoris = $session->get('favoris', []);

    $plat = $this->getDoctrine()->getRepository(Plat::class)->find($platId);

    if (!$plat) {
        throw $this->createNotFoundException('Plat non trouvé pour l\'ID ' . $platId);
    }

    $favoris[] = $plat;

    $session->set('favoris', $favoris);

    $this->addFlash('success', 'Le plat a été ajouté aux favoris avec succès.');

    return $this->redirectToRoute('app_plat_index');
}
    

    #[Route('/liste-des-favoris', name: 'liste_des_favoris', methods: ['GET'])]
    public function listeDesFavoris(SessionInterface $session): Response
    {
        $favoris = $session->get('favoris', []);

        return $this->render('plat/liste_des_favoris.html.twig', [
            'favoris' => $favoris,
        ]);
    }
    #[Route('/supprimer-favoris/{platId}', name: 'supprimer_favoris', methods: ['GET', 'POST'])]
    public function supprimerFavoris($platId, SessionInterface $session): Response
    { 
        $favoris = $session->get('favoris', []);
        $key = array_search($platId, array_column($favoris, 'idplat'));
    
            unset($favoris[$key]);
            $favoris = array_values($favoris); 
            $session->set('favoris', $favoris);
            
        
        return $this->redirectToRoute('liste_des_favoris');
    }
    


#[Route('/recherche', name: 'ajax_search_product', methods: ['GET'])]
     public function searchAction(Request $request, PlatRepository $repo)
     {
         $requestString = $request->get('q');
         $plats =  $repo->findEntitiesByString($requestString);
         if(!$plats) {
             $result['plats']['error'] = "  Aucun Plat n'a été trouvé! Veuillez saisir une autre chose! ";
         } else {
             $result['plats'] = $this->getRealEntities($plats);
         }
         return new Response(json_encode($result));
     }
     public function getRealEntities($plats){
         foreach ($plats as $plats){
             $realEntities[$plats->getIdplat()] = [$plats->getNom(),$plats->getPrix(),$plats->getCategorie(),$plats->getImage(),$plats->getDescription(),$plats->getIdplat()];
         }
         return $realEntities;
     }




    #[Route('/catg', name: 'app_plat_index_catg', methods: ['GET', 'POST'])]
public function indexCatg(Request $request, PlatRepository $platRepository): Response
{
    $category = $request->get('category');

    if ($category) {
        $plats = $platRepository->findByCategory($category);
    } else {
        $plats = $platRepository->findAll();
    }

    return $this->render('plat/plats_list.html.twig', [
        'plats' => $plats,
    ]);
}


#[Route('/{idplat}', name: 'app_plat_delete', methods: ['POST'])]
    public function delete(Request $request, Plat $plat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getIdplat(), $request->request->get('_token'))) {
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plat_index_back', [], Response::HTTP_SEE_OTHER);
    }
  


#[Route('/prix', name: 'app_plat_index_prix', methods: ['GET', 'POST'])]
public function indexPrix(Request $request, PlatRepository $platRepository): Response
{
    $minPrice = $request->get('minPrice');
    $maxPrice = $request->get('maxPrice');

    if ($minPrice && $maxPrice) {
        $plats = $platRepository->findByPriceRange($minPrice, $maxPrice);
    } else {
        $plats = $platRepository->findAll();
    }

    return $this->render('plat/plats_list.html.twig', [
        'plats' => $plats,
    ]);
}
#[Route('/nom', name: 'app_plat_index_nom', methods: ['GET', 'POST'])]
public function indexNom(Request $request, PlatRepository $platRepository): Response
{
    $searchTerm = $request->get('searchTerm');

    if ($searchTerm) {
        $plats = $platRepository->findByNom($searchTerm);
    } else {
        $plats = $platRepository->findAll();
    }

    return $this->render('plat/plats_list.html.twig', [
        'plats' => $plats,
    ]);
}


   

    #[Route('/{idplat}', name: 'app_plat_show', methods: ['GET'])]
    public function show(Plat $plat): Response
    {
        return $this->render('plat/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/{idplat}/edit', name: 'app_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plat $plat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plat_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plat/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    

   
}







