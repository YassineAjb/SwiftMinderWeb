<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChnagePasswordType;
use App\Form\InscriptionType;
use App\Form\LoginType;
use App\Form\ResetPasswordCodeFormType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Endroid\QrCode\Builder\BuilderInterface; 
use Endroid\QrCode\Writer\Result\PngResult;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use function PHPUnit\Framework\equalTo;

#[Route('/user')]
class UserController extends AbstractController
{
    private $qrCodeBuilder;

    public function __construct(BuilderInterface $qrCodeBuilder)
    {
        $this->qrCodeBuilder = $qrCodeBuilder;
    }

   public static function  getUserData(SessionInterface $sessionInterface){
    $serializedUser = $sessionInterface->get('user');
    
    $user = unserialize($serializedUser);
    if(!$user){
        return null;
    }
   return $user;
   }
    public static function isAuthenticated(SessionInterface $sessionInterface){
      $user= UserController::getUserData($sessionInterface);
        return isset($user);

    }

    public static function hasPermissionRole(SessionInterface $sessionInterface,$role){
        if(!UserController::isAuthenticated($sessionInterface)) {
            return false;
        }
        $user = UserController::getUserData($sessionInterface);
       
        return strtoupper($user->getRole())==strtoupper($role);
    }   

    #[Route('/admin', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,SessionInterface $sessionInterface): Response
    {
        if(!$this->hasPermissionRole($sessionInterface,"admin")){
            return $this->redirectToRoute('app_user_login');
        }
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            if ($this->qrCodeBuilder !== null) {
                $qrCodeResult = $this->qrCodeBuilder
                    ->data($user->getEmail(),$user->getRole())
                    ->build();

                $qrCodeString = $this->convertQrCodeResultToString($qrCodeResult);

                $user->setQrCode($qrCodeString);
            }
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'loggedIn'=>true,
        ]);
    }
    private function convertQrCodeResultToString(PngResult $qrCodeResult): string
    {
        return 'data:image/png;base64,' . base64_encode($qrCodeResult->getString());
    }

    #[Route('/admin/new ', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $sessionInterface, SluggerInterface $slugger): Response

    {   
        if(!$this->hasPermissionRole($sessionInterface,"admin")){
            return $this->redirectToRoute('app_user_login');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
             $file = $form->get('image')->getData();
		if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   
                }

                $user->setImage($newFilename);
            }
            $email = $user->getEmail();
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $form->get('email')->addError(new FormError('L\'adresse e-mail n\'est pas valide.'));
            } else {
                $user->setMotdepasse(password_hash($user->getMotdepasse(), PASSWORD_BCRYPT, ["cost" => 12]));
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'loggedIn'=>true,

        ]);
    }
    #[Route('/admin/search', name: 'app_user_search', methods: ['GET'])]
    public function search(Request $request, UserRepository $userRepository,SessionInterface $sessionInterface): Response
    {
        if(!$this->hasPermissionRole($sessionInterface,"admin")){
            return $this->redirectToRoute('app_user_login');
        }
        $users = $userRepository->findAll();

        $searchTerm = trim($request->query->get('search'));
        $results = [];
        foreach ($users as $user) {
            if ($this->qrCodeBuilder !== null) {
                $qrCodeResult = $this->qrCodeBuilder
                    ->data($user->getEmail())
                    ->build();

                $qrCodeString = $this->convertQrCodeResultToString($qrCodeResult);

                $user->setQrCode($qrCodeString);
            }
        }
        if (empty($searchTerm)) {
            $results = $userRepository->findAll();
        } else {
            $results = $userRepository->searchByEmail($searchTerm);
        }
        
        return $this->render('user/index.html.twig', [
            'users' => $results,
            'loggedIn'=>true,

        ]);
    }
    #[Route('/admin/{iduser}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user,sessionInterface $sessionInterface): Response
    {  if(!$this->hasPermissionRole($sessionInterface,"admin")){
        return $this->redirectToRoute('app_user_login');
    }
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'loggedIn'=>true,

        ]);
    }

    #[Route('/admin/{iduser}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $sessionInterface,SluggerInterface $slugger): Response
    {
        $originalImage = $user->getImage();
        if(!$this->hasPermissionRole($sessionInterface,"admin")){
            return $this->redirectToRoute('app_user_login');
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                if ($originalImage) {
                  
                    $oldFilePath = $this->getParameter('images_directory').'/'.$originalImage;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }

                $user->setImage($newFilename);
            } elseif (!$file && $request->get('remove_image') === 'true') {
               
                if ($originalImage) {
                    $oldFilePath = $this->getParameter('images_directory').'/'.$originalImage;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                    $user->setImage("");
                }
            }
            $user->setMotdepasse(password_hash($user->getMotdepasse(), PASSWORD_BCRYPT, ["cost" => 12]));
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'loggedIn'=>true,

        ]);
    }
  
    #[Route('/admin/{iduser}', name: 'app_user_delete', methods: ['POST'])]
        public function delete(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $sessionInterface): Response
        {
            if(!$this->hasPermissionRole($sessionInterface,"admin")){
                return $this->redirectToRoute('app_user_login');
            }
            if ($this->isCsrfTokenValid('delete'.$user->getIduser(), $request->request->get('_token'))) {
                $entityManager->remove($user);
                $entityManager->flush();
            }
    
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        #[Route('/inscription', name: 'app_user_inscription', methods: ['GET', 'POST'])]
        public function inscription(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
        {
            $user = new User();
            $form = $this->createForm(InscriptionType::class, $user);
            $form->handleRequest($request);
            $email = $user->getEmail();
            $error="";

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('image')->getData();
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
                    try {
                        $file->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
    
                    $user->setImage($newFilename);
                }


           
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                   

                    $error='L\'adresse  n\'est pas valide.';

            } else {
                $userExiste= $entityManager->getRepository(User::class)->findOneBy(["email"=>$email]);
                if($userExiste){
                $error='L\'adresse e-mail existe déja.';
 
                }else{
                    $user->setRole("membre");
                    $user->setMotdepasse(password_hash($user->getMotdepasse(), PASSWORD_BCRYPT, ["cost" => 12]));
                    $entityManager->persist($user);
                    $entityManager->flush();
                
                    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
                    
                }
             }
            }
         
             return $this->renderForm('user/inscription.html.twig', [
                'user' => $user,
                'form' => $form,
                'error'=>$error
            ]);
        }
        #[Route('/login', name: 'app_user_login', methods: ['GET', 'POST'])]
        public function login(Request $request, EntityManagerInterface $entityManager,SessionInterface $sessionInterface): Response

        {
            
            if($this->isAuthenticated($sessionInterface)){
                if($this->hasPermissionRole($sessionInterface,"admin")){
                    return $this->redirectToRoute('app_user_index');
                }else{
                    return $this->redirectToRoute('app_user_profil');
 
                }
            }
            $userData = new User();
            $form = $this->createForm(LoginType::class, $userData);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            $email = $userData->getEmail();
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $form->get('email')->addError(new FormError('L\'adresse e-mail n\'est pas valide.'));
            } else {
                $userFromDatabase= $entityManager->getRepository(User::class)->findOneBy(["email"=>$email]);
                if($userFromDatabase==null){
                    $error= "Cet utilisateur n'existe pas";
                    return $this->renderForm('user/Login.html.twig', [
                        'user' => $userData,
                        'form' => $form,
                        'error'=>$error
                    ]); 
                }else{
                    if(!password_verify($userData->getMotdepasse(),$userFromDatabase->getMotdepasse())){
                        $error= "Cet utilisateur n'existe pas";
                        return $this->renderForm('user/login.html.twig', [
                            'user' => $userData,
                            'form' => $form,
                            'error'=>$error
                        ]); 
                    }else{
                        $serializedUser = serialize($userFromDatabase);
                        $sessionInterface->set('user', $serializedUser);
                        if($this->hasPermissionRole($sessionInterface,"admin")){
                            return $this->redirectToRoute('app_user_index');
                        }else{
                            return $this->redirectToRoute('app_user_profil');
         
                        }
                    }
                }
           
                }
            }
         
             return $this->renderForm('user/login.html.twig', [
                'user' => $userData,
                'form' => $form,
            ]);
        }
        #[Route('/resetpassword/request', name: 'app_user_request_reset', methods: ['GET', 'POST'])]
        public function request_reset_passwod(Request $request,MailerInterface $mailer,  EntityManagerInterface $entityManager,SessionInterface $sessionInterface): Response
        {
            if($this->isAuthenticated($sessionInterface)){
                return $this->redirectToRoute('app_user_login');
            }
            $submitEmailForm = $this->createForm(ResetPasswordType::class);
            $submitEmailForm->handleRequest($request);
            $resetPasswordCodeForm=$this->createForm(ResetPasswordCodeFormType::class);
            $resetPasswordCodeForm->handleRequest($request);
            ///
            if($sessionInterface->get('user_in_second_step') && $sessionInterface->get("randomCode")){
                $randomString=$sessionInterface->get("randomCode");
                if ($resetPasswordCodeForm->isSubmitted() && $resetPasswordCodeForm->isValid()) {
                     $formDataCode = $resetPasswordCodeForm->getData();
                      $codeSubmitted= $formDataCode["code"];
                //Verification du code
                      if($codeSubmitted==$randomString){
                        return $this->redirectToRoute("app_user_reset_password_validate");
                        }else{
                         return $this->renderForm('user/resetPassword.html.twig', [
                        'error'=>"Code invalid",
                          'resetPasswordCodeForm'=>$resetPasswordCodeForm
                     ]);  
                 }
            }
            $message="Veuillez vérifier votre boîte mail pour réinitialiser votre mot de passe.";

            return $this->renderForm('user/resetPassword.html.twig', [
                'message'=>$message,
                'resetPasswordCodeForm'=>$resetPasswordCodeForm
            ]); 
        
        }
            //
            if( ($submitEmailForm->isSubmitted() && $submitEmailForm->isValid())){
                $formDataEmail = $submitEmailForm->getData();
                $email = $formDataEmail['email'];
                $userFromDatabase= $entityManager->getRepository(User::class)->findOneBy(["email"=>$email]);
                if($userFromDatabase==null){
                    $error= "Cet utilisateur n'existe pas";
                    return $this->renderForm('user/resetPassword.html.twig', [
                        'submitEmailForm' => $submitEmailForm,
                        'error'=>$error,

                    ]); 
                }else{
                    $randomString = $this->generateRandomNumericString();
                    $emailMessage = (new Email())
                    ->from('benhmida.molka01@gmail.com')
                    ->to($email)
                    ->text($randomString." est votre code de reset password ! ");
                    $mailer->send($emailMessage);
                    $sessionInterface->set("user_in_second_step",true);
                    $sessionInterface->set("randomCode",$randomString);
                    $sessionInterface->set("email_to_resetPassword",$email);

                    return $this->redirectToRoute("app_user_request_reset");
                 
                }

            }
             return $this->renderForm('user/resetPassword.html.twig', [
                'submitEmailForm' => $submitEmailForm,
                'emailIsSubmitted'=>false

            ]); 


        
        }

        function generateRandomNumericString($length = 6) {
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
        
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }
        
            return $randomString;
        }
        
        #[Route('/resetpassword/validate', name: 'app_user_reset_password_validate', methods: ['GET', 'POST'])]
        public function resetPasswordValidate(Request $request,MailerInterface $mailer,  EntityManagerInterface $entityManager,SessionInterface $sessionInterface): Response
       {
        if ($sessionInterface->get('email_to_resetPassword') ) {
            $email=$sessionInterface->get('email_to_resetPassword');
            $form=$this->createForm(ChnagePasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $formData=$form->getData();

                $password=$formData['motdepasse'];
                $hashedpassword=password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
                $userFromDatabase= $entityManager->getRepository(User::class)->findOneBy(["email"=>$email]);
                $userFromDatabase->setMotdepasse($hashedpassword);
                $entityManager->flush();


                $sessionInterface->set("user_in_second_step", null);
                $sessionInterface->set("randomCode", null);
                $sessionInterface->set("email_to_resetPassword", null);
                return $this->redirectToRoute("app_user_login");
            
            
            }
            return $this->renderForm('user/changePassword.html.twig', [
                'form' => $form,
            ]); 
            



  

        } else {
            
         return $this->redirectToRoute("app_user_request_reset");

        }
       }

        /*
        #[Route('/login', name: 'app_user_login', methods: ['GET', 'POST'])]
public function login(Request $request, EntityManagerInterface $entityManager, SessionInterface $sessionInterface): Response
{
    if ($this->isAuthenticated($sessionInterface)) {
        if ($this->hasPermissionRole($sessionInterface, "admin")) {
            return $this->redirectToRoute('app_user_index');
        } else {
            return $this->redirectToRoute('app_user_profil');
        }
    }

    $userData = new User();
    $loginForm = $this->createForm(LoginType::class, $userData);
    $registrationForm = $this->createForm(InscriptionType::class, $userData);

    $loginForm->handleRequest($request);
    $registrationForm->handleRequest($request);

    if ($loginForm->isSubmitted() && $loginForm->isValid()) {
        // Handle login form submission
        $email = $userData->getEmail();
        $userFromDatabase = $entityManager->getRepository(User::class)->findOneBy(["email" => $email]);

        if ($userFromDatabase == null || !password_verify($userData->getMotdepasse(), $userFromDatabase->getMotdepasse())) {
            $error = "Identifiants invalides";
            return $this->renderForm('Userfront/login.html.twig', [
                'user' => $userData,
                'login' => $loginForm,
                'registration' => $registrationForm,
                'error' => $error
            ]);
        }

        $serializedUser = serialize($userFromDatabase);
        $sessionInterface->set('user', $serializedUser);

        if ($this->hasPermissionRole($sessionInterface, "admin")) {
            return $this->redirectToRoute('app_user_index');
        } else {
            return $this->redirectToRoute('app_user_profil');
        }
    }

    if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
        // Handle registration form submission
        $email = $userData->getEmail();
        $userExiste = $entityManager->getRepository(User::class)->findOneBy(["email" => $email]);

        if ($userExiste) {
            $registrationForm->get('email')->addError(new FormError('L\'adresse e-mail existe déjà.'));
        } else {
            $userData->setRole("membre");
            $userData->setMotdepasse(password_hash($userData->getMotdepasse(), PASSWORD_BCRYPT, ["cost" => 12]));
            $entityManager->persist($userData);
            $entityManager->flush();

            // Optionally, you can log in the user after registration
            $serializedUser = serialize($userData);
            $sessionInterface->set('user', $serializedUser);

            return $this->redirectToRoute('app_user_index');
        }
    }

    return $this->renderForm('Userfront/login.html.twig', [
        'user' => $userData,
        'login' => $loginForm,
        'registration' => $registrationForm,
    ]);
}*/

        #[Route('/profil', name: 'app_user_profil', methods: ['GET', 'POST'])]
        public function profil(SessionInterface $sessionInterface): Response
        {
            if(!$this->isAuthenticated($sessionInterface)){
                return $this->redirectToRoute('app_user_login') ;
            }
            $user=$this->getUserData($sessionInterface);
            return $this->renderForm('user/profil.html.twig', [
                'user' => $user,
                'loggedIn'=>true,

            ]);
        }
        #[Route('/profil/edit', name: 'app_user_profil_edit', methods: ['GET', 'POST'])]
        public function editProfil(User $user,SessionInterface $sessionInterface, SluggerInterface $slugger ): Response
        {
            $originalImage = $user->getImage();
            if(!$this->isAuthenticated($sessionInterface)){
                return $this->redirectToRoute('app_user_login') ;
            }
            
            $user=$this->getUserData($sessionInterface);
            return $this->renderForm('user/profil.html.twig', [
                'user' => $user,
            ]);
        }
        #[Route('/logout', name: 'app_user_logout', methods: ['GET', 'POST'])]
        public function logout(SessionInterface $sessionInterface): Response
        {
           
            $sessionInterface->set('user',null);
            return $this->redirectToRoute('app_user_login') ;

            
        }
    
}