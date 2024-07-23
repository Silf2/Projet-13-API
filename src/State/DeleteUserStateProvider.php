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
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeleteUserStateProvider implements ProviderInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        $token = $this->tokenStorage->getToken();
        /** @var User $user */
        $user = $token->getUser();
dd($user);
        return $user;
    }
}