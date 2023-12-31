<?php

namespace App\Controller;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    //UserProfile
    #[Route('/profile/{id}', name: 'app_profile', methods: ['GET'])]
    public function show(User $user, BlogPostRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        try {
            $blogPosts = $repo->findAllByAuthor($user);

            $json = $serializer->serialize($blogPosts, 'json', ['groups' => 'blogpost']);

            return new JsonResponse($json, 200, [], true);
        } catch (\Exception $e) {
            $this->logger->error('An error occurred: ' . $e->getMessage());

            return new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    //Retrieve all following
    #[Route('/profile/{id}/following', name: 'app_profile_follwingr', methods: ['GET'])]
    public function following(User $user, UserRepository $repo): JsonResponse
    {
        $currentUser = $this->getUser();


        if ($currentUser === null || !$currentUser instanceof User) {
            return new JsonResponse(['message' => 'User not found or not logged in'], 404);
        }


        /** @var array $userFollowing */
        $userFollowing = $repo->findFollowingUsernames($currentUser->getId());

        if (empty($userFollowing)) {
            return new JsonResponse(['message' => 'The list is currently empty'], 200);
        }

        return new JsonResponse($userFollowing);
    }

    //Retrieve all followers
    #[Route('/profile/{id}/followers', name: 'app_profile_followers', methods: ['GET'])]
    public function followers(User $user, UserRepository $repo): JsonResponse
    {
        $currentUser = $this->getUser();


        if ($currentUser === null || !$currentUser instanceof User) {
            return new JsonResponse(['message' => 'User not found or not logged in'], 404);
        }


        /** @var array $userFollow */
        $userFollow = $repo->findFollowerUsernames($currentUser->getId());

        if (empty($userFollow)) {
            return new JsonResponse(['message' => 'The list is currently empty'], 200);
        }

        return new JsonResponse($userFollow);
    }
}
