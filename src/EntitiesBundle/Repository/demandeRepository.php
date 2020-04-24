<?php

namespace EntitiesBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * DemandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DemandeRepository extends EntityRepository
{
    public function findAvisDQL($idCurrentUser)
    {
        $dqlresult = $this->getEntityManager()
            ->createQuery("SELECT a
                               FROM 
                                    EntitiesBundle:DemandeConge a
                               WHERE
                                    a.user = '$idCurrentUser'
                                    
                              ");
        return $dqlresult->getResult();
    }

    public function getnom_user($nom)
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.ide_user' , 'u')
            ->addSelect('u')
            ->where('u.Nom=:query')
            ->setParameter('query', $nom);
        return $qb->getQuery()
            ->getResult();
    }

    public function findMulti($find)
    {
        $q=$this->createQueryBuilder('m')
            ->where('m.note LIKE :find')->orWhere('m.type LIKE :find')
            ->setParameter(':find',"%$find%");
        return $q->getQuery()->getResult();
    }
    public function CountE($id)
    {
        $x = $this->getEntityManager()
            ->createQuery("SELECT COUNT (e) as nb 
                            FROM savBundle:avis a 
                            WHERE a.produit=:n")
            ->setParameter('n',$id);

        return $x->getResult();
    }
    public function finduid()
    {

        $qb = $this->getEntityManager()
            ->createQuery("select c from EntitiesBundle:User c where c.UID=:num or c.UID=:ui")
            ->setParameters(array('num' => 1 , 'ui'=> 2));
        return $query = $qb->getResult();

    }
}