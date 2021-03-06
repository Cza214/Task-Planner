<?php

namespace AppBundle\Repository;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $month
     * @param $year
     * @param $user
     * @return array
     */
    public function getTasksByMonth($month, $year, $user){
        $em = $this->getEntityManager();
        $count = $em->createQuery(
            'SELECT r FROM AppBundle:Task r 
                 WHERE MONTH(r.date) = :mon 
                 AND YEAR(r.date) = :yr
                 AND r.user = :currentUser')
            ->setParameter("mon", $month)
            ->setParameter("yr", $year)
            ->setParameter("currentUser", $user)
            ->getResult();
        return $count;
    }

    /**
     * @param $day
     * @param $month
     * @param $year
     * @param $user
     * @return array
     */
    public function getTasksByDay($day, $month, $year, $user){
        $em = $this->getEntityManager();
        $count = $em->createQuery(
            'SELECT r FROM AppBundle:Task r 
                 WHERE MONTH(r.date) = :mon 
                 AND YEAR(r.date) = :yr
                 AND DAY(r.date) = :dy
                 AND r.user = :currentUser')
            ->setParameter("mon", $month)
            ->setParameter("yr", $year)
            ->setParameter("dy", $day)
            ->setParameter("currentUser", $user)
            ->getResult();
        return $count;
    }
}
