<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function findLatest()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupérer les suggestions d'articles
     * @param $idAnnonce
     * @param $idCategorie
     * @return mixed
     */
    public function findAnnoncesSuggestions($idAnnonce, $idCategorie)
    {
        return $this->createQueryBuilder('a')

            # Tous les articles d'une catégorie ($idCategorie)
            ->where('a.categorie = :categorie_id')
            ->setParameter('categorie_id', $idCategorie)

            # Sauf, un Article ($idArticle)
            ->andWhere('a.id != :annonce_id')
            ->setParameter('annonce_id', $idAnnonce)

            # 3 articles MAX
            ->setMaxResults(3)
            # par ordre décroissant
            ->orderBy('a.id', 'DESC')

            ->getQuery()
            ->getResult()
            ;
    }
}
