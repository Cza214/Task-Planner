<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 * @Route("tasks")
 */
class TaskController extends Controller
{
    /**
     * Show count of task per month
     *
     * @Route("/", name="show")
     */
    public function showAction(){
        $current = new \DateTime('now');
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        $year = $current->format("Y");
        $tasks = $this->numberOfTasksByMonths($year);
        dump($tasks);
        return $this->render('AppBundle:Task:months.html.twig',['months' => $months, 'current' => $current, 'count' => $tasks]);
    }

    /**
     * Show Tasks by Day
     *
     * @Route("/{month}/{year}/{day}", name="show_task")
     *
     */
    public function showTask($month,$year,$day){

       $em = $this->getDoctrine()->getManager();
       $tasks = $em->getRepository('AppBundle:Task')->getTasksByDay($day,$month,$year,$this->getUser());
       dump($tasks);
       return $this->render('AppBundle:Task:task_content.html.twig',['tasks' => $tasks]);
    }

    /**
     * Show count of task per day
     *
     * @Route("/{month}/{year}", name="show_task_month")
     */
    public function showMonthAction($month,$year){

        $days = cal_days_in_month(CAL_GREGORIAN,$month,$year);

        $tasks = $this->numberOfTasksByDay($days,$month,$year);
        dump($tasks);
        return $this->render('AppBundle:Task:days.html.twig',['days' => $days, 'count' => $tasks, 'month' => $month, 'year' => $year]);
    }
    /**
     * New Task
     *
     * @Route("/new", name="new_task")
     * @Method("GET")
     */
    public function newAction(Request $req){

        $req_date = $req->query->get('date');

        if($req_date && $this->validateDate(explode('-',$req_date)))
        {
            $date = new \DateTime($req_date);
        } else {
            $date = new \DateTime("NOW");
        };

        $em = $this->getDoctrine()->getManager();
        $task = new Task();
        $task->setUser($this->getUser());

        $form = $this->GetFictionForm($task, $date);

        $form->handleRequest($req);
        if($form->isSubmitted()){
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            return $this->redirect("show");
        }
        return $this->render('AppBundle:Task:new.html.twig',['form' => $form->createView()]);
    }

    /**
     * Get Number of Tasks by Month
     */
    protected function numberOfTasksByMonths($year){

        $em = $this->getDoctrine()->getManager();
        $result = [];

        for($i = 1; $i <= 12; $i++) {
            $count = $em->getRepository('AppBundle:Task')->getTasksByMonth($i,$year,$this->getUser());
            $result[$i - 1] = count($count);
        }
        return $result;
    }

    /**
     * Get Number of Tasks by Day
     */
    protected function numberOfTasksByDay($days,$month,$year){

        $em = $this->getDoctrine()->getManager();
        $result = [];

        for($i = 1; $i <= $days; $i++) {
            $count = $em->getRepository('AppBundle:Task')->getTasksByDay($i,$month,$year,$this->getUser());
            $result[$i] = count($count);
        }
        return $result;
    }

    /**
     * Return FORM
     *
     * @param $task
     * @param $date
     * @return \Symfony\Component\Form\FormInterface
     *
     */
    protected function GetFictionForm($task,$date){

        $form = $this->createFormBuilder($task)
            ->setAction($this->generateUrl('new_task'))
            ->setMethod('GET')
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('date', DateType::class, [
                'data' => $date
            ])
            ->add('priority', NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Add Task'])
            ->getForm();
        return $form;
    }

    /**
     * Validate Date | Date must by an array of 3 elements(year,month,day).
     *  Return true or false.
     *
     * @param $date
     * @return bool
     *
     */
    protected function validateDate(array $date){
        if(count($date) != 3)
        {
            return false;
        } else {
            for($i = 0; $i < 3; $i ++){
                if(!is_numeric($date[$i])){
                    return false;
                }
            }
            return checkdate($date[1],$date[2],$date[0]);
        }
    }
}
