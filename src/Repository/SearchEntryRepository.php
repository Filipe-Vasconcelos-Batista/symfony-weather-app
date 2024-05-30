<?php

namespace App\Repository;

use App\Entity\SearchEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchEntry>
 * @method SearchEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchEntry[]    findAll()
 * @method SearchEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchEntry::class);
    }

    public function getTopSearches(int $limit = 10): array
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select("s.city_name, Count(s.city_name)as frequency")
            ->from("App\Entity\SearchEntry", "s")
            ->groupBy("s.city_name")
            ->orderBy("frequency", "DESC")
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
