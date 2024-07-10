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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderStateProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private AssocProductOrderRepository $assocProductOrderRepository,
        private OrderRepository $orderRepository,
        private TokenStorageInterface $tokenStorage
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        dump($data);
        if(!is_array($data) || !isset($data[0]['productId'], $data[0]['quantity'])) {
            throw new BadRequestHttpException('Votre commande n\'est pas valide');
        }

        $token = $this->tokenStorage->getToken();
        /** @var User $user */
        $user = $token->getUser();

        if (!$user) {
            throw new \Exception('L\utilisateur n\'est pas connecté');
        }

        $order = new Order();
        $this->orderRepository->createOrder($order, $user);

        $totalPrice = 0.0;
        foreach($data as $item) {
            $product = $this->productRepository->findOneBy($item['productId']);
            
            if (!$product) {
                throw new BadRequestHttpException('L\'id du produit ' . $item['articleId'] . 'n\'existe pas');
            }

            $assocProductOrder = new AssocProductOrder();
            $this->assocProductOrderRepository->createAssociation($assocProductOrder, $product, $item['quantity'], $order);

            $totalPrice += $product->getPrice() * $item['quantity'];
        }

        $order->setPrice($totalPrice);
        $this->em->persist($order);
        
        $this->em->flush();

        return $order;
    }
}