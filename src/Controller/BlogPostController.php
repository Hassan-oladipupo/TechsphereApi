<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\BlogPost;
use Psr\Log\LoggerInterface;
use App\Repository\CommentRepository;
use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BlogPostController extends AbstractController
{

    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    //getting all post with comments
    #[Route('/blog/post', name: 'app_blog_post', methods: ['GET'])]
    public function index(BlogPostRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        try {

            $blogPosts = $repo->findAllWithComments();

            $json = $serializer->serialize($blogPosts, 'json', ['groups' => 'blogpost']);

            return new JsonResponse($json, 200, [], true);
        } catch (\Exception $e) {

            $this->logger->error('An error occurred: ' . $e->getMessage());


            return new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    //Top liked post, starting post with top likes 
    #[Route('/blog-post/top-liked', name: 'app_blog_topliked', methods: ['GET'])]
    public function topLiked(BlogPostRepository $repo, SerializerInterface $serializer): jsonResponse
    {

        try {

            $topLike = $repo->findAllWithMinLikes(2);

            $json = $serializer->serialize($topLike, 'json', ['groups' => 'blogpost']);

            return new JsonResponse($json, 200, [], true);
        } catch (\Exception $e) {

            $this->logger->error('An error occurred: ' . $e->getMessage());

            return new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    //getting the post  of  the user that the current user follows
    #[Route('/blog-post/follows', name: 'app_blog_post_follows', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function followPosts(BlogPostRepository $repo, SerializerInterface $serializer): jsonResponse
    {

        try {
            /** @var User $currentUser */

            $currentUser = $this->getUser();

            $followPosts = $repo->findAllByAuthors($currentUser->getFollow());

            if (empty($followPosts)) {

                return new JsonResponse(['message' => ' No post yet'], 200);
            }

            $json = $serializer->serialize($followPosts, 'json', ['groups' => 'blogpost']);

            return new JsonResponse($json, 200, [], true);
        } catch (\Exception $e) {

            $this->logger->error('An error occurred: ' . $e->getMessage());

            return new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    //getting a  BlogPost 
    #[Route('/blog-post/{blog}', name: 'app_blog_post_show', methods: ['GET'])]
    #[IsGranted(BlogPost::VIEW, 'blog')]
    public function showOne(BlogPost $blog, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($blog, 'json', ['groups' => 'blogpost']);
        return new JsonResponse($data, 200, [], true);
    }



    //Adding BlogPost

    #[Route('/blog-post/add', name: 'app_blog_posts_add', priority: 2, methods: ['POST'])]
    public function addBlog(Request $request, BlogPostRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, LoggerInterface $logger): jsonResponse
    {
        try {
            $data = $request->getContent();


            $blogPost = $serializer->deserialize($data, BlogPost::class, 'json', ['groups' => 'blogpost']);


            $errors = $validator->validate($blogPost);

            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 422);
            }


            $blogPost->setAuthor($this->getUser());

            $repo->save($blogPost, true);


            $jsonResponse = $this->json($blogPost, 201, [], ['groups' => 'blogpost']);


            $this->addFlash('success', 'Your Post has been added');

            return $jsonResponse;

            // Redirecting to all posts
            // return $this->redirectToRoute('app_blog_post');
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            $this->logger->error('An error occurred: ' . $e->getMessage());

            // Return the exception message in the response
            return $this->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }






    //Function for Edit forms for the blogPost
    #[Route('/blog-post/{blog}/edit', name: 'app_blog_post_edit', methods: ['PUT'])]
    #[IsGranted(BlogPost::EDIT, 'blog')]
    public function editBlog(BlogPost $blog, Request $request, BlogPostRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, LoggerInterface $logger, Security $security): JsonResponse
    {


        $data = $request->getContent();
        try {
            $editBlog = $serializer->deserialize($data, BlogPost::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $blog
            ]);

            $errors = $validator->validate($editBlog);

            if (count($errors) > 0) {
                $errorMessages = [];
                /** @var \Symfony\Component\Validator\ConstraintViolation $error */
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }

                return $this->json(['errors' => $errorMessages], 422);
            }



            $repo->save($editBlog, true);
            $jsonResponse = $this->json($editBlog, 201, [], ['groups' => 'blogpost']);
            $this->addFlash('success', 'Your Blog has been updated');
            // Redirecting to all posts
            // return $this->redirectToRoute('app_blog_post');
            return $jsonResponse;
        } catch (\Exception $e) {
            $logger->error('An error occurred: ' . $e->getMessage());
            return $this->json(['message' => 'An error occurred' . $e->getMessage()], 500);
        }
    }

    //Function for adding comment forms for the blogPost
    #[Route('/blog-post/{blog}/comment', name: 'app_blog_post_comment', methods: ['POST'])]
    public function addComment(BlogPost $blog, Request $request, CommentRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = $request->getContent();

            $blogComment = $serializer->deserialize($data, Comment::class, 'json', ['groups' => 'comment']);

            $errors = $validator->validate($blogComment);

            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 422);
            }

            $blogComment->setBlog($blog);
            $blogComment->setAuthor($this->getUser());
            $repo->save($blogComment, true);

            $this->addFlash('success', 'Your Comment has been added');

            $data = $serializer->serialize($blogComment, 'json', ['groups' => 'comment']);
            return new JsonResponse($data, 201, [], true);
            //redirecting to the post
            //return $this->redirectToRoute('app_blog_post_show', ['blog' => $blog->getId()]);
        } catch (\Exception $e) {

            $this->logger->error('An error occurred: ' . $e->getMessage());


            return $this->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
