<?php

namespace App\Repository;

use App\Entity\AssocProductOrder;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssocProductOrder>
 */
class AssocProductOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, AssocProductOrder::class);
    }

    public function createAssociation(AssocProductOrder $assocProductOrder, Product $product, int $quantity, Order $order){
        $assocProductOrder->setProduct($product);
        $assocProductOrder->setQuantity($quantity);
        $assocProductOrder->setCommande($order);

        $this->em->persist($assocProductOrder);
    }

//    /**
//     * @return AssocProductOrder[] Returns an array of AssocProductOrder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssocProductOrder
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
