<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function newComment($id){

    }

    /**
     * Delete Each Comment
     *
     * @Route("/delete/{id}")
     */
    public function deleteComment($id){

    }
}
