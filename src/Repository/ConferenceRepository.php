<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    /**
     * @return Conference[]
     */
    public function findConferencesBetweenDates(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        if (null === $start && null === $end) {
            throw new \InvalidArgumentException('At least one date is required to operate this method.');
        }

        $qb = $this->createQueryBuilder('c');

        if ($start instanceof \DateTimeImmutable) {
            $qb->andWhere('c.startAt >= :start')
                ->setParameter('start', $start);
        }

        if ($end instanceof \DateTimeImmutable) {
            $qb->andWhere('c.endAt <= :end')
                ->setParameter('end', $end);
        }

        return $qb->getQuery()->getResult();
    }

    public function findLikeName(string $name): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->andWhere($qb->expr()->like('c.name', ':name'))
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Conference[] Returns an array of Conference objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conference
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
