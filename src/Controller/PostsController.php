<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

class PostsController extends AbstractController {

    /**
     * @Route("/", name="posts.listar", methods="GET")
     */
    public function list(EntityManagerInterface $em) {
        $posts = $em->getRepository(Post::class)->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("show/{id}", name="posts.show", methods={"GET", "POST"}, requirements={"id":"\d+"}))
     * @param Post $post
     * @return Response
     */
    public function show(Post $post, Request $request, EntityManagerInterface $em): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute(
                'posts.show',
                ['id' => $post->getId()]
            );
        }
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("Likes", options={"expose"=true}, name="Likes")
     * @param Request $request
     */
    public function Like(Request $request) {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $commentId = $request->request->get('commentId');
            //VERIFICAR SI EL USUARIO ACTUAL TIENE NO ME GUSTA EN ALGUN LIKE
            $like = $em->getRepository(Like::class)->findOneBy(['comentario' => $commentId, 'user' => $user->getId()]);
            //$unlikeABorrar = $em->getRepository(Like::class)->getUnLikeByCommentAndUser($commentId, $user->getId());
            if($like) {
                // ACTUALIZAR UNLIKE
                if($like->isLike() == false) {
                    //$unlikeABorrar = $em->getRepository(Like::class)->find($unlikeABorrar[0]);
                    $like->setIsLike(true);
                    //$em->remove($unlike);
                    $em->flush();
                } else {
                    return new JsonResponse(['exito' => false]);
                }
            } else {
                $comment = $em->getRepository(Comment::class)->find($commentId);
                $like = new Like();
                $like->setUser($user);
                $like->setComentario($comment);
                $like->setIsLike(true);
                $em->persist($like);
            }

            try {
                $em->flush();
            }
            catch (UniqueConstraintViolationException $e) {
                return new JsonResponse(['exito' => false]);
            }
            // OBTENGO CANTIDAD DE LIKES ACTUALIZADO
            $commentLikes = $em->getRepository(Like::class)->getLikesByComment($commentId);
            $commentdisLikes = $em->getRepository(Like::class)->getDisLikesByComment($commentId);
            return new JsonResponse(['exito' => true, 'likes' => $commentLikes, 'dislikes' => $commentdisLikes]);
        } else {
            throw new \Exception('Error');
        }
    }

    /**
     * @Route("Dislike", options={"expose": true}, name="Dislike")
     * @param Request $request
     */
    public function Dislike(Request $request) {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $commentId = $request->request->get('commentId');
            //VERIFICAR SI EL USUARIO ACTUAL TIENE ME GUSTA EN ALGUN LIKE
            $like = $em->getRepository(Like::class)->findOneBy(['comentario' => $commentId, 'user' => $user->getId()]);
            //$likeABorrar = $em->getRepository(Like::class)->getLikeByCommentAndUser($commentId, $user->getId());
            if($like) {
                // ACTUALIZAR LIKE
                if($like->isLike() == true) {
                    //$likeABorrar = $em->getRepository(Like::class)->find($likeABorrar[0]);
                    $like->setIsLike(false);
                    //$em->remove($likeABorrar);
                    $em->flush();
                } else {
                    return new JsonResponse(['exito' => false]);
                }
            } else {
                $comment = $em->getRepository(Comment::class)->find($commentId);
                $like = new Like();
                $like->setUser($user);
                $like->setComentario($comment);
                $like->setIsLike(false);
                $em->persist($like);
                try {
                    $em->flush();
                }
                catch (UniqueConstraintViolationException $e) {
                    return new JsonResponse(['exito' => false]);
                }
            }

            // OBTENGO CANTIDAD DE DISLIKES ACTUALIZADO
            $commentdisLikes = $em->getRepository(Like::class)->getDisLikesByComment($commentId);
            $commentLikes = $em->getRepository(Like::class)->getLikesByComment($commentId);
            return new JsonResponse(['exito' => true, 'dislikes' => $commentdisLikes, 'likes' => $commentLikes]);
        } else {
            throw new \Exception('Error');
        }
    }
}