<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\UserProfile;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\File;

class ProfileSettingController extends AbstractController
{

    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    //UserProfileSetting
    #[Route('/settings/profile', name: 'app_settings_profile', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(Request $request, UserRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, ManagerRegistry $doctrine): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            $getUserProfile = $user->getUserProfile() ?? new UserProfile();

            $data = $request->getContent();
            $userProfile = $serializer->deserialize($data, UserProfile::class, 'json');

            $getUserProfile->setName($userProfile->getName());
            $getUserProfile->setBio($userProfile->getBio());
            $getUserProfile->setWebsiteUrl($userProfile->getWebsiteUrl());
            $getUserProfile->setTwitterUsername($userProfile->getTwitterUsername());
            $getUserProfile->setCompany($userProfile->getCompany());
            $getUserProfile->setLocation($userProfile->getLocation());
            $getUserProfile->setDateOfBirth($userProfile->getDateOfBirth());

            $errors = $validator->validate($getUserProfile);

            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 422);
            }

            $user->setUserProfile($getUserProfile);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($getUserProfile);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your user profile settings were saved.'
            );

            return new JsonResponse(['message' => 'Your user profile settings were saved']);
        } catch (\Exception $e) {


            $this->logger->error('An error occurred: ' . $e->getMessage());

            return new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }



    //SettingProfile Image
    #[Route('/settings/profile-image', name: 'app_settings_profile_image', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profileImage(Request $request, SluggerInterface $slugger, UserRepository $repo, LoggerInterface $logger, ValidatorInterface $validator): JsonResponse
    {
        $profileImageFile = $request->files->get('Image');

        if (!$profileImageFile) {
            return new JsonResponse(['error' => 'No image uploaded.'], 400);
        }

        $constraints = [
            new File([
                'maxSize' => '1024k',
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'mimeTypesMessage' => 'Please upload a valid PNG/JPEG image',
            ]),
        ];

        $violations = $validator->validate($profileImageFile, $constraints);

        if (count($violations) > 0) {
            return new JsonResponse(['error' => $violations[0]->getMessage()], 400);
        }

        try {
            $originalFileName = pathinfo($profileImageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFileName);
            $newFileName = $safeFilename . '-' . uniqid() . '.' . $profileImageFile->guessExtension();

            $profileImageFile->move(
                $this->getParameter('profiles_directory'),
                $newFileName
            );

            /** @var User $user */
            $user = $this->getUser();

            $profile = $user->getUserProfile() ?? new UserProfile();
            $profile->setImage($newFileName);
            $user->setUserProfile($profile);

            $repo->save($user, true);

            $this->addFlash('success', 'Your profile image was updated');

            return new JsonResponse(['message' => 'Your profile image was updated']);
            //redirecting to profile image template
            // return $this->redirectToRoute('app_settings_profile_image');

        } catch (FileException $e) {
            $logger->error('Failed to upload profile image: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Failed to upload profile image.'], 500);
        }
    }
}
