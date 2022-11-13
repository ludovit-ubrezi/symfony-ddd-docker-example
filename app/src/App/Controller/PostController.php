<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Repository\AuthorRepository;
use Blog\Application\Command\Post\CreatePostCommand;
use Blog\Application\Command\Post\RemovePostCommand;
use Blog\Application\Command\Post\UpdatePostCommand;
use Blog\Application\Query\CommentQuery;
use Blog\Application\Query\PostQuery;
use Blog\Domain\Model\Post\Post;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    const PAGING_LIMIT = 3;
    const PAGING_DEFAULT_OFFSET = 0;

    private MessageBusInterface $messageBus;
    private PostQuery $postQuery;
    private CommentQuery $commentQuery;

    public function __construct(MessageBusInterface $messageBus, PostQuery $postQuery, CommentQuery $commentQuery)
    {
        $this->messageBus = $messageBus;
        $this->postQuery = $postQuery;
        $this->commentQuery = $commentQuery;
    }

    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $limit = $request->query->get("limit") ?? PostController::PAGING_LIMIT;
        $offset = $request->query->get("offset") ?? PostController::PAGING_DEFAULT_OFFSET;
        $resultPosts = $this->postQuery->getAll($limit, $offset);

        return $this->render('message/index.html.twig', [
            'messages' => $resultPosts,
            'paging' => $this->createPostPaging($request)
        ]);
    }

    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->find(1);
        $message = Post::create("", "", 0, $author); // authorId == logged user
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new CreatePostCommand(
                $form->get("title")->getData(),
                $form->get("description")->getData(),
                $author
            );
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Request $request, Post $post, array $_route_params): Response
    {
        $postId = intval($_route_params["id"]);
        $limit = $request->query->get("limit") ?? PostController::PAGING_LIMIT;
        $offset = $request->query->get("offset") ?? PostController::PAGING_DEFAULT_OFFSET;

        $comments = $this->commentQuery->findByPostId($postId, intval($limit), intval($offset));

        return $this->render('message/show.html.twig', [
            'message' => $post,
            'comments' => $comments,
            'paging' => $this->createCommentPaging($request, $postId)
        ]);
    }

    private function createCommentPaging(Request $request, $postId) {
        $limit = $request->query->get("limit") ?? PostController::PAGING_LIMIT;
        $offset = $request->query->get("offset") ?? PostController::PAGING_DEFAULT_OFFSET;
        $next = $this->generateUrl('app_message_show', ['id'=> $postId, 'limit' => $limit,
            'offset' => $offset + PostController::PAGING_LIMIT]);
        $previous = $this->generateUrl('app_message_show', ['id'=> $postId, 'limit' => $limit,
            'offset' => ($offset - PostController::PAGING_LIMIT) > PostController::PAGING_DEFAULT_OFFSET ? ($offset - PostController::PAGING_LIMIT) : PostController::PAGING_DEFAULT_OFFSET ]);
        return [
            'next' => $next,
            'previous' => $previous
        ];
    }

    private function createPostPaging(Request $request, ) {
        $limit = $request->query->get("limit") ?? PostController::PAGING_LIMIT;
        $offset = $request->query->get("offset") ?? PostController::PAGING_DEFAULT_OFFSET;
        $next = $this->generateUrl('app_message_index', ['limit' => $limit,
            'offset' => $offset + PostController::PAGING_LIMIT]);
        $previous = $this->generateUrl('app_message_index', ['limit' => $limit,
            'offset' => ($offset - PostController::PAGING_LIMIT) > PostController::PAGING_DEFAULT_OFFSET ? ($offset - PostController::PAGING_LIMIT) : PostController::PAGING_DEFAULT_OFFSET ]);
        return [
            'next' => $next,
            'previous' => $previous
        ];
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(MessageType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new UpdatePostCommand(
                $post->getId(),
                $form->get("title")->getData(),
                $form->get("description")->getData(),
            );
            $this->messageBus->dispatch($command);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post): Response
    {
        $command = new RemovePostCommand($post);
        $this->messageBus->dispatch($command);

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
