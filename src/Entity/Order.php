<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\OrderRepository;
use App\State\OrderStateProcessor;
use App\State\OrderStateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [self::READ_GROUP]],
            provider: OrderStateProvider::class
        ),
        new Post(
            denormalizationContext: ['groups' => [self::CREATE_GROUP]],
            normalizationContext: ['groups' => [self::READ_GROUP]],
            processor: OrderStateProcessor::class,
        )
    ]
)]
class Order implements GroupsInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::READ_GROUP])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ_GROUP])]
    private ?string $orderNumber = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups([self::READ_GROUP])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[Groups([self::READ_GROUP])]
    #[ORM\Column]
    private ?float $price = null;

    /**
     * @var Collection<int, AssocProductOrder>
     */
    #[ORM\OneToMany(targetEntity: AssocProductOrder::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
    private Collection $assocProductOrders;

    public function __construct()
    {
        $this->assocProductOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, AssocProductOrder>
     */
    public function getAssocProductOrders(): Collection
    {
        return $this->assocProductOrders;
    }

    public function addAssocProductOrder(AssocProductOrder $assocProductOrder): static
    {
        if (!$this->assocProductOrders->contains($assocProductOrder)) {
            $this->assocProductOrders->add($assocProductOrder);
            $assocProductOrder->setCommande($this);
        }

        return $this;
    }

    public function removeAssocProductOrder(AssocProductOrder $assocProductOrder): static
    {
        if ($this->assocProductOrders->removeElement($assocProductOrder)) {
            // set the owning side to null (unless already changed)
            if ($assocProductOrder->getCommande() === $this) {
                $assocProductOrder->setCommande(null);
            }
        }

        return $this;
    }
}
