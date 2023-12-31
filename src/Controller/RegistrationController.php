<?php

namespace App\Controller;

use SendGrid;
use App\Entity\User;
use SendGrid\Mail\Mail;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use SendGrid\Exception\SendGridException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserRepository $repo): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        if (empty($data['username']) || empty($data['password'])) {
            return new JsonResponse(['message' => 'Email and password are required.'], 400);
        }

        $email = $data['username'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'Invalid email format.'], 400);
        }

        $password = $data['password'];
        if (strlen($password) < 8) {
            return new JsonResponse(['message' => 'Password should be at least 8 characters long.'], 400);
        }

        $existingUser = $repo->findOneBy(['username' => $email]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Email is already registered.'], 400);
        }

        $user = new User();
        $user->setUsername($email);

        $confirmationToken = mt_rand(100000, 999999);
        $user->setConfirmationToken($confirmationToken);

        $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        try {
            $sendgridApiKey = $_ENV['SENDGRID_API_KEY'];
            $sendgrid = new SendGrid($sendgridApiKey);

            $email = new \SendGrid\Mail\Mail();


            $email->setFrom('hassan.oladipupo@buildafrica.co', 'Tech Sphere');

            $email->addTo($data['username']);

            $email->setSubject('Email Confirmation');
            $email->addContent("text/plain", "Your confirmation token is: " . $confirmationToken);

            $response = $sendgrid->send($email);


            return new JsonResponse(['message' => 'User Registered successfully.', 'user_id' => $user->getId()], 201);
        } catch (\Exception $e) {

            $this->logger->error('An error occurred: ' . $e->getMessage());

            return new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }



    #[Route('/api/confirm-email', name: 'api_confirm_email', methods: ['POST'])]
    public function confirmEmail(Request $request, UserRepository $repo, EntityManagerInterface $entityManager): JsonResponse
    {
        try {

            $requestData = json_decode($request->getContent(), true);


            if (empty($requestData) || !isset($requestData['confirmation_token'])) {
                return new JsonResponse(['message' => 'Invalid token.'], 400);
            }

            $token = $requestData['confirmation_token'];

            $user = $repo->findOneBy(['confirmationToken' => $token]);

            if (!$user) {
                return new JsonResponse(['message' => 'User not found.'], 404);
            }

            if ($user->isConfirmed()) {
                return new JsonResponse(['message' => 'Email is already confirmed.'], 400);
            }

            $user->setConfirmed(true);
            $user->setConfirmationToken(null);

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Email confirmed successfully.'], 200);
        } catch (\Exception $e) {

            $this->logger->error('An error occurred during email confirmation: ' . $e->getMessage());

            return new JsonResponse(['message' => 'An error occurred during email confirmation: ' . $e->getMessage()], 500);
        }
    }
}
