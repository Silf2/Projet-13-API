<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Entity\User;
use ApiPlatform\State\ProviderInterface;
use App\Repository\OrderRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderStateProvider implements ProviderInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private TokenStorageInterface $tokenStorage
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

        return $this->orderRepository->findBy(['user' => $user]);
    }
}