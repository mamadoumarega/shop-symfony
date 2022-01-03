<?php

namespace App\Entity;

use App\Repository\CartDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartDetailsRepository::class)
 */
class CartDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $productName;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $productPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $subTotalHT;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $taxe;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $subTotalTTC;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Cart $carts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubTotalHT(): ?float
    {
        return $this->subTotalHT;
    }

    public function setSubTotalHT(float $subTotalHT): self
    {
        $this->subTotalHT = $subTotalHT;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getSubTotalTTC(): ?float
    {
        return $this->subTotalTTC;
    }

    public function setSubTotalTTC(float $subTotalTTC): self
    {
        $this->subTotalTTC = $subTotalTTC;

        return $this;
    }

    public function getCarts(): ?Cart
    {
        return $this->carts;
    }

    public function setCarts(?Cart $carts): self
    {
        $this->carts = $carts;

        return $this;
    }
}
