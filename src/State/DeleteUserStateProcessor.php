<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeleteUserStateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
        private ProcessorInterface $processor,
        private TokenStorageInterface $tokenStorage
    )
    {}

    public function process(mixed $data, \ApiPlatform\Metadata\Operation $operation, array $uriVariables = [], array $context = [])
    {
        $token = $this->tokenStorage->getToken();
        /** @var User $user */
        $user = $token->getUser();
        dd($user);

        if ($user instanceof User) {
            // Appeler le processeur pour la suppression
            return $this->processor->process($user, $operation, $uriVariables, $context);
        }

        throw new AccessDeniedHttpException('Action non autorisée.');    
    }
}