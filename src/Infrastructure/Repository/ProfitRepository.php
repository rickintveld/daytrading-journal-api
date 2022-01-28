<?php

namespace App\Infrastructure\Repository;

use App\Domain\Contracts\Repository\ProfitRepository as ProfitRepositoryInterface;
use App\Infrastructure\Entity\Profit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profit[]    findAll()
 * @method Profit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfitRepository extends ServiceEntityRepository implements ProfitRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profit::class);
    }
}
