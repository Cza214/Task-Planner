<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;

/**
 * Class TaskController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 * @Route("tasks")
 */
class TaskController extends Controller
{
    /**
     * @Route("/", name="show_tasks")
     */
    public function showAction(){
        $current = new \DateTime('now');
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        $tasks = $this->numberOfTasksByMonths();
        dump($tasks);
        return $this->render('AppBundle:Task:months.html.twig',['months' => $months, 'current' => $current, 'count' => $tasks]);
    }

    /**
     * Get Number of Tasks by Month
     */
    protected function numberOfTasksByMonths(){

        $em = $this->getDoctrine()->getManager();
        $result = [];

        for($i = 1; $i <= 12; $i++) {
            $count = $em->getRepository('AppBundle:Task')->getTasksByMonth($i,"2018",$this->getUser());
            dump($count);
            $result[$i - 1] = count($count);
        }
        return $result;
    }

    /**
     * Get Number of Tasks by Day
     */
    protected function numberOfTasksByDay(){

    }
}
