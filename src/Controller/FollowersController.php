<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class FollowersController extends AbstractController
{

    //Follow User
    #[Route('/Follow/{id}', name: 'app_Follow', methods: ['POST'])]
    public function follow(User $userToFollow, ManagerRegistry $doctrine, Request $request): jsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser || !$userToFollow) {
            return new JsonResponse(['error' => 'Invalid user or not authenticated'], 400);
        }

        if ($userToFollow->getId() !== $currentUser->getId()) {
            try {

                $currentUser->follow($userToFollow);
                $doctrine->getManager()->flush();
                //  return $this->redirect($request->headers->get('referer'));
                return new JsonResponse(['message' => 'You just followed a user']);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Failed to follow the user'], 500);
            }
        }

        return new JsonResponse(['error' => 'You cannot follow yourself'], 400);
    }



    //UnFollow User
    #[Route('/unFollow/{id}', name: 'app_unFollow', methods: ['POST'])]
    public function unFollow(User $userToFollow, ManagerRegistry $doctrine, Request $request): jsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser || !$userToFollow) {
            return new JsonResponse(['error' => 'Invalid user or not authenticated'], 400);
        }

        if ($userToFollow->getId() !== $currentUser->getId()) {
            try {

                $currentUser->unFollow($userToFollow);
                $doctrine->getManager()->flush();
                //  return $this->redirect($request->headers->get('referer'));
                return new JsonResponse(['message' => 'You just unFollowed a user']);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Failed to follow the user'], 500);
            }
        }

        return new JsonResponse(['error' => 'You cannot follow yourself'], 400);
    }
}
