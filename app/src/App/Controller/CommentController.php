<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Repository\AuthorRepository;
use Blog\Application\Command\Comment\CreateCommentCommand;
use Blog\Application\Command\Comment\RemoveCommentCommand;
use Blog\Application\Command\Comment\UpdateCommentCommand;
use Blog\Application\Query\CommentQuery;
use Blog\Domain\Model\Comment\Comment;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    private MessageBusInterface $messageBus;
    private CommentQuery $commentQuery;

    public function __construct(MessageBusInterface $messageBus, CommentQuery $commentQuery)
    {
        $this->messageBus = $messageBus;
        $this->commentQuery = $commentQuery;
    }

    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(DoctrineCommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/new/{messageId}', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, array $_route_params, AuthorRepository $authorRepository): Response
    {
        $postId = intval($_route_params["messageId"]);
        $author = $authorRepository->find(1);
        $comment = Comment::Create($postId, $author, "");
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new CreateCommentCommand(
                $postId,
                $author,
                $form->get("content")->getData()
            );
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('app_message_show', ["id" =>$postId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new UpdateCommentCommand(
                $comment->getId(),
                $form->get("content")->getData()
            );
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('app_message_show', ["id" =>$comment->getPostId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment): Response
    {
        $postId = $comment->getPostId();
        $command = new RemoveCommentCommand($comment);
        $this->messageBus->dispatch($command);
        return $this->redirectToRoute('app_message_show', ["id" =>$postId], Response::HTTP_SEE_OTHER);
    }
}
