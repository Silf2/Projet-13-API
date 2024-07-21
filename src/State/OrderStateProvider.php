<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Metadata\Operation;
use App\Entity\User;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderStateProvider implements ProviderInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private TokenStorageInterface $tokenStorage,
        #[Autowire(service: 'api_platform.doctrine.orm.query_extension.pagination')]
        private PaginationExtension $paginationExtension
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

        $queryNameGenerator = new QueryNameGenerator;

        $this->paginationExtension->applyToCollection(
            $queryBuilder, 
            $queryNameGenerator,
            Order::class,
            $operation, 
            $context
        );

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($queryBuilder);
        return $paginator;
    }
}