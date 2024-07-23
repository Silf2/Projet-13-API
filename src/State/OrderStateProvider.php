<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use App\Entity\User;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderStateProvider implements ProviderInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private TokenStorageInterface $tokenStorage,
        private PaginationExtension $collectionExtensions
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        $token = $this->tokenStorage->getToken();
        /** @var User $user */
        $user = $token->getUser();

        if (!$user) {
            throw new \Exception('L\utilisateur n\'est pas connecté');
        }

        
        $queryBuilder = $this->orderRepository->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user);

        $this->collectionExtensions->applyToCollection(
            queryBuilder: $queryBuilder,
            queryNameGenerator: new QueryNameGenerator(),
            resourceClass: Order::class,
            operation: $operation,
            context: $context,
        );

        return new Paginator(new DoctrinePaginator($queryBuilder));

        // [$page, , $limit] = $this->pagination->getPagination($operation, $context);

        // $queryBuilder
        //     ->setFirstResult(($page - 1) * $limit)
        //     ->setMaxResults($limit);

        // return new Paginator(new \Doctrine\ORM\Tools\Pagination\Paginator($queryBuilder));
    }
}