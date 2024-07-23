<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteUserAction
{
    public function __construct(private readonly Security $security, private ManagerRegistry $registry)
    {}

    public function __invoke(): void
    {
        $this->registry->getManager()->remove($this->security->getUser());
        $this->registry->getManager()->flush();
    }
}