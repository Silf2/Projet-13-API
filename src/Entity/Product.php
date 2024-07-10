<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['product:read']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['product:read']]
        )
    ]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $shortDescription = null;

    #[ORM\Column(length: 1020)]
    private ?string $fullDescription = null;

    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?float $price = null;

    #[ORM\Column(length: 510)]
    private ?string $picture = null;

    /**
     * @var Collection<int, AssocProductOrder>
     */
    #[ORM\OneToMany(targetEntity: AssocProductOrder::class, mappedBy: 'product')]
    private Collection $assocProductOrders;

    public function __construct()
    {
        $this->assocProductOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getFullDescription(): ?string
    {
        return $this->fullDescription;
    }

    public function setFullDescription(string $fullDescription): static
    {
        $this->fullDescription = $fullDescription;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

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
            $assocProductOrder->setProduct($this);
        }

        return $this;
    }

    public function removeAssocProductOrder(AssocProductOrder $assocProductOrder): static
    {
        if ($this->assocProductOrders->removeElement($assocProductOrder)) {
            // set the owning side to null (unless already changed)
            if ($assocProductOrder->getProduct() === $this) {
                $assocProductOrder->setProduct(null);
            }
        }

        return $this;
    }
}
