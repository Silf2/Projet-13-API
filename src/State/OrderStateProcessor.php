<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Entity\AssocProductOrder;
use App\Entity\Order;
use App\Entity\User;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\AssocProductOrderRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderStateProcessor implements ProcessorInterface
{
    public function __construct(
        // private EntityManagerInterface $em,
        // private ProductRepository $productRepository,
        // private AssocProductOrderRepository $assocProductOrderRepository,
        // private OrderRepository $orderRepository,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private TokenStorageInterface $tokenStorage
    )
    {}

    /**
     * @param Order $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data || !$data->getAssocProductOrders()) {
            throw new BadRequestHttpException('Le panier n\'est pas valide.');
        }
        
        foreach ($data->getAssocProductOrders() as $product) {
            if (!$product->getProduct() || !$product->getQuantity()) {
                throw new BadRequestHttpException('Le panier n\'est pas valide.');
            }
        }

        $token = $this->tokenStorage->getToken();
        /** @var User $user */
        $user = $token->getUser();

        if (!$user) {
            throw new \Exception('L\utilisateur n\'est pas connecté');
        }

        $totalPrice = 0.0;

        /** @var AssocProductOrder $item */
        foreach($data->getAssocProductOrders() as $item) {
            $totalPrice += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        $data->setOrderNumber(uniqid());
        $data->setOrderDate(new \DateTime());
        $data->setUser($user);
        $data->setPrice($totalPrice);

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}