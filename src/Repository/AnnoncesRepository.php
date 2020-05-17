<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    public function searchAnnonces($resultSearchData)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        if ($resultSearchData['mots_cles']) {
            $queryBuilder
            ->andWhere('a.ann_titre like :annTitre')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('annTitre', '%'.$resultSearchData['mots_cles'].'%')
            ;
        }
        if ($resultSearchData['prix_min']) {
            $queryBuilder
            ->andWhere('a.ann_prix >= :annPrixMin')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('annPrixMin', $resultSearchData['prix_min'])
            ;
        } 
        if ($resultSearchData['prix_max']) {
            $queryBuilder
            ->andWhere('a.ann_prix <= :annPrixMax')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('annPrixMax', $resultSearchData['prix_max']) 
            ;
        }
        if ($resultSearchData['km_min']) {
            $queryBuilder
            ->andWhere('a.kilometre >= :kilometreMin')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('kilometreMin', $resultSearchData['km_min'])
            ;
        }
        if ($resultSearchData['km_max']) {
            $queryBuilder
            ->andWhere('a.kilometre <= :kilometreMax')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('kilometreMax', $resultSearchData['km_max'])
            ;
        }
        if ($resultSearchData['annee']) {
            $queryBuilder
            ->andWhere('a.annee_modele like :anneeModele')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('anneeModele', $resultSearchData['annee'])
            ;
        }
        if ($resultSearchData['boite_de_vitesse']) {
            $queryBuilder
            ->andWhere('a.boite_de_vitesse like :boiteDeVitesse')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('boiteDeVitesse', $resultSearchData['boite_de_vitesse'])
            ;
        }
        if ($resultSearchData['carburant']) {
            $queryBuilder
            ->andWhere('a.carburant like :carburant')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('carburant', $resultSearchData['carburant'])
            ;
        }
        if ($resultSearchData['region']) {
            $queryBuilder
            ->andWhere('a.region like :region')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('region', $resultSearchData['region'])
            ;
        }
        if ($resultSearchData['departement']) {
            $queryBuilder
            ->andWhere('a.departement like :departement')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('departement', $resultSearchData['departement'])
            ;
        }
        if ($resultSearchData['ville']) {
            $queryBuilder
            ->andWhere('a.ville like :ville')
            ->andWhere('a.ann_a_valider = 0 and a.ann_signaler = 0 and a.ann_active = 1')
            ->setParameter('ville', $resultSearchData['ville'])
            ;
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function countAnnoncesNumber()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->getQuery()
        ->getSingleScalarResult();
        ;  
    }

    public function countAnnoncesActives()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->where('a.ann_active = 1')
        ->getQuery()
        ->getSingleScalarResult();
        ;  
    }

    public function countAnnoncesDesactivees()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->where('a.ann_active = 0')
        ->getQuery()
        ->getSingleScalarResult();
        ;  
    }

    public function countAnnoncesAValider()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->where('a.ann_a_valider = 1')
        ->getQuery()
        ->getSingleScalarResult();
        ;  
    }

    public function countAnnoncesAModerer()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->where('a.ann_signaler = 1')
        ->getQuery()
        ->getSingleScalarResult();
        ;  
    }

    public function countAnnoncesModerees()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->where('a.ann_moderee = 1')
        ->getQuery()
        ->getSingleScalarResult();
        ;  
    }

    // /**
    //  * @return Annonces[] Returns an array of Annonces objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonces
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
