<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Twig\Environment;
use Swift_Mailer;
use Swift_Message;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Security\Core\Security;

#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository,Security $security, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('q');
        $query = $searchQuery ? $userRepository->advancedSearch($searchQuery) : $userRepository->findAll();
        if ($searchQuery) {
            // Use the advanced search method if a search query is provided
            $users = $userRepository->advancedSearch($searchQuery);
        } else {
            // Use findAll if no search query is provided
            $users = $userRepository->findAll();
        }
        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Get the current page from the request, default to 1
        10 // Number of items per page
    );
        $isAuthenticated = $security->isGranted('IS_AUTHENTICATED_REMEMBERED');
        // Count of users with the role 'ROLE_USER'
    
        return $this->render('user/index.html.twig', [
            'users' => $pagination,
            'isAuthenticated' => $isAuthenticated,
            
        ]);
    }
    #[Route('/user-chart', name: 'app_user_chart', methods: ['GET'])]
    public function userChart(UserRepository $userRepository): Response
    {
        return $this->render('user/user_chart.html.twig');
    }

    #[Route('/chart-data', name: 'app_user_chart_data', methods: ['GET'])]
    public function userChartData(UserRepository $userRepository): JsonResponse
    {
        $activeUserCount = $userRepository->countUsersByEtat('Actif');
        $blockedUserCount = $userRepository->countUsersByEtat('Bloqué');

        return new JsonResponse([
            'activeUserCount' => $activeUserCount,
            'blockedUserCount' => $blockedUserCount,
        ]);
    }

    #[Route('/search', name: 'ajax_search', methods: ['GET'])]
public function search(Request $request, UserRepository $userRepository): JsonResponse
{
    $searchString = $request->query->get('q');
    $users = $userRepository->findReclamationsByString($searchString);

    $reclamationDetails = [];
    foreach ($users as $user) {
        $reclamationDetails[] = [
            'iduser' => $user->getIduser(),
            'username' => $user->getUsername(),
            // Ajoutez d'autres détails de réclamation si nécessaires
        ];
    }

    return new JsonResponse(['users' => $reclamationDetails]);
}
#[Route('/details/{idr}', name: 'app_reclamations_details', methods: ['GET'])]
public function details(string $idr, UserRepository $UserRepository): Response
{
    $user = $UserRepository->find($idr);

    return $this->render('user/detail.html.twig', [
        'user' => $user,
    ]);
}
    #[Route('/yamen/search', name: 'app_reclamations_search')]
    public function searchPage(): Response
    {
        return $this->render('user/search.html.twig');
    }    
    #[Route('/user-count', name: 'app_user_count', methods: ['GET'])]
public function userCount(UserRepository $userRepository): Response
{
    $userCount = $userRepository->countUsersWithRoleUser();

    return $this->render('user/user_count.html.twig', [
        'userCount' => $userCount,
    ]);
}

//     #[Route('/generateExcel', name: 'excel')]
// public function generateUserExcel(UserRepository $userRepository): BinaryFileResponse
// {
//     $users = $userRepository->findAll();
//     $spreadsheet = new Spreadsheet();
//     $sheet = $spreadsheet->getActiveSheet();
//     $sheet->setCellValue('A1', 'Username');
//     $sheet->setCellValue('B1', 'Email');
//     $sheet->setCellValue('C1', 'Firstname');
//     $sheet->setCellValue('D1', 'Lastname');
//     $sheet->setCellValue('E1', 'Address'); // Added column for address
//     $sheet->setCellValue('F1', 'Role');    // Added column for role
//     $sheet->setCellValue('G1', 'Etat');    // Added column for etat


//     $sn = 1;
//     foreach ($users as $user) {
//         $sheet->setCellValue('A' . $sn, $user->getUsername());
//         $sheet->setCellValue('B' . $sn, $user->getEmail());
//         $sheet->setCellValue('C' . $sn, $user->getFirstname());
//         $sheet->setCellValue('D' . $sn, $user->getLastname());
//         $sheet->setCellValue('E' . $sn, $user->getAddress()); // Added address
//         $sheet->setCellValue('F' . $sn, implode(', ', $user->getRole())); // Added role
//         $sheet->setCellValue('G' . $sn, $user->getEtat()); // Added etat

        

//         $sn++;
//     }

//     $writer = new Xlsx($spreadsheet);

//     $fileName = 'users.xlsx';
//     $tempFile = tempnam(sys_get_temp_dir(), $fileName);

//     $writer->save($tempFile);

//     return new BinaryFileResponse($tempFile, 200, [
//         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//         'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
//     ]);
// }
#[Route('/generateExcel', name: 'excel')]
public function generateUserExcel(UserRepository $userRepository): BinaryFileResponse
{
    $users = $userRepository->findAll();
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Define column names
    $columnNames = ['Username', 'Email', 'Firstname', 'Lastname', 'Address', 'Role', 'Etat'];

    // Set the entire first row at once and make it bold
    $sheet->fromArray([$columnNames], null, 'A1');
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);

    $sn = 2; // Start from the second row
    foreach ($users as $user) {
        $data = [
            $user->getUsername(),
            $user->getEmail(),
            $user->getFirstname(),
            $user->getLastname(),
            $user->getAddress(),
            implode(', ', $user->getRole()),
            $user->getEtat(),
        ];

        // Set data starting from the second row
        $sheet->fromArray([$data], null, 'A' . $sn);

        $sn++;
    }
    $sheet->getStyle('A1:D1')->applyFromArray([
        'font' => [
            'bold' => true,
        ],
    ]);
    $writer = new Xlsx($spreadsheet);

    $fileName = 'users.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);

    $writer->save($tempFile);

    return new BinaryFileResponse($tempFile, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
    ]);
}


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setRole(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/new44', name: 'app_user_new44', methods: ['GET', 'POST'])]
    public function new44(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Encode the new user's password
        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setRole(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

           
        }

        return $this->renderForm('user/new44.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/load-user-content/{iduser}', name: 'load_user_content', methods: ['GET'])]
public function loadUserContent(UserRepository $userRepository, $iduser): Response
{   
    $writer = new PngWriter();

    $user = $userRepository->find($iduser);
    // Concaténer tous les champs de l'entité User pour générer le contenu du code QR
    $userData = $user->getUserDataForQrCode();
    $qrCode = new QrCode($userData);

    $pngResult = $writer->write($qrCode);
    $qrCodeImage = base64_encode($pngResult->getString());

    return $this->render('user/qr.html.twig', [  
        'user'        => $user,
        'qrCodeImage' => $qrCodeImage,
    ]);
}
    #[Route('/{iduser}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            
        ]);
    }

    #[Route('/{iduser}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
        // Encode the user's password if it has been changed
       
        
            
    
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{iduser}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIduser(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    

    #[Route('/{iduser}/edit44', name: 'app_user_edit44', methods: ['GET', 'POST'])]
     public function edit44(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $form = $this->createForm(UserTypeEdit::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        


        $entityManager->flush();

        return $this->redirectToRoute('home_Front', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/edit44.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}


#[Route('/tri/{criteria}', name: 'app_user_tri', methods: ['GET'])]
public function tri(UserRepository $userRepository, string $criteria, PaginatorInterface $paginator, Request $request): Response
{
    // Vérifier si le critère de tri est valide
    $validCriteria = ['iduser', 'username', 'email', 'firstname', 'lastname', 'tel', 'address', 'role'];
    
    if (!in_array($criteria, $validCriteria)) {
        throw $this->createNotFoundException('Invalid sorting criteria.');
    }

    // Appeler la méthode de tri dans le repository
    $users = $userRepository->findAllSortedBy($criteria);

    // Paginate the sorted users
    $pagination = $paginator->paginate(
        $users,
        $request->query->getInt('page', 1),
        15 // Number of items per page
    );

    return $this->render('user/index.html.twig', [
        'users' => $pagination,
        'currentCriteria' => $criteria,
    ]);
}

#[Route('/searchUserAjax', name: 'app_user_search_ajax', methods: ['GET'])]
public function searchUserAjax(Request $request, UserRepository $userRepository): Response
{
    $searchQuery = $request->query->get('q');

    if (!$searchQuery) {
        return $this->render('user/index.html.twig', ['users' => []]);
    }

    $users = $userRepository->advancedSearch($searchQuery);
    $html = $this->render('user/index.html.twig', ['users' => $users])->getContent();

    return new Response($html);
}
#[Route('/block/{iduser}', name: 'app_user_block', methods: ['GET'])]
public function block(User $user, EntityManagerInterface $entityManager): Response
{
    $user->setIsBlocked(true);
    $user->setEtat("Bloqué");
    $entityManager->flush();

    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
}

#[Route('/unblock/{iduser}', name: 'app_user_unblock', methods: ['GET'])]
public function unblock(User $user, EntityManagerInterface $entityManager): Response
{
    $user->setIsBlocked(false);
    $user->setEtat("Actif");
    $entityManager->flush();

    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
}
#[Route('/{id}/change-status', name: 'app_user_change_status')]
public function changeStatusUser(User $user, UserRepository $userRepository, Swift_Mailer $mailer): Response
{
    if ($user->isIsBlocked()) {
        $user->setIsBlocked(false);
        $status = 'We are glad to inform you that your account has been activated again. You can now access our app and benefit from our services.';
    } else {
        $user->setIsBlocked(true);
        $status = 'We are sorry to inform you that your account has been blocked. You will no longer be able to benefit from our services until later notice.';
    }

    // Send Email
    $message = (new Swift_Message($user->isIsBlocked() ? 'Account Blocked' : 'Account Re-activated'))
       ->setFrom('mohamedyemen.khefacha@esprit.tn')
        ->setTo($user->getEmail())
        ->setBody(
            $this->renderView(
                'user/status_email.html.twig',
                ['status' => $status]
            ),
            'text/html'
        );

    $mailer->send($message);

    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
}

}