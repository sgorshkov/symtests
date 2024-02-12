<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TestItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestItem[]    findAll()
 * @method TestItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestItem::class);
    }

    public function findLastWithQuestionsAndAnswers(): ?TestItem
    {
        return $this->createQueryBuilder('ti')
            ->leftJoin('ti.questions', 'tq')
            ->leftJoin('tq.answers', 'ta')
            ->orderBy('ti.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();
    }
}
