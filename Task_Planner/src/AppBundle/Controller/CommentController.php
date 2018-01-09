<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 * @Route("comment")
 */
class CommentController extends Controller
{
    /**
     * Show All Comments
     *
     * @Route("/show/{id}")
     */
    public function showComments($id){
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository("AppBundle:Task")->find($id);
        $comments = $em->getRepository("AppBundle:Comment")->findBy(['tasks' => $task]);

        return $this->render('AppBundle:comment:comment.html.twig',['comments' => $comments]);
    }

    /**
     * Add New Comment
     *
     * @Route("/new/{id}")
     */
    public function newComment(Request $req, $id){
        $em = $this->getDoctrine()->getManager();
        $referer = $req->headers->get('referer');

        $task = $em->getRepository('AppBundle:Task')->find($id);
        $text = $req->request->get('text');

        $comment = new Comment();
        $comment->setDate(new \DateTime("now"));
        $comment->setText($text);
        $comment->setTasks($task);
        $em->persist($comment);
        $em->flush();

        return $this->redirect($referer);
    }

    /**
     * Delete Each Comment
     *
     * @Route("/delete/{id}")
     */
    public function deleteComment($id){

    }
}
