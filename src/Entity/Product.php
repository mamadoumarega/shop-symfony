<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $moreInformation;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $price;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isBestSeller = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isNewArrival = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isFeatured = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isSpecialOffer = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $image;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="products")
     */
    private Collection $category;

    /**
     * @ORM\ManyToMany(targetEntity=TagsProduct::class, mappedBy="product")
     */
    private Collection $tagsProducts;

    /**
     * @ORM\OneToMany(targetEntity=RelatedProduct::class, mappedBy="product")
     */
    private Collection $relatedProducts;

    /**
     * @ORM\OneToMany(targetEntity=ReviewsProduct::class, mappedBy="product")
     */
    private Collection $reviewsProducts;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->tagsProducts = new ArrayCollection();
        $this->relatedProducts = new ArrayCollection();
        $this->reviewsProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMoreInformation(): ?string
    {
        return $this->moreInformation;
    }

    public function setMoreInformation(?string $moreInformation): self
    {
        $this->moreInformation = $moreInformation;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIsBestSeller(): ?bool
    {
        return $this->isBestSeller;
    }

    public function setIsBestSeller(?bool $isBestSeller): self
    {
        $this->isBestSeller = $isBestSeller;

        return $this;
    }

    public function getIsNewArrival(): ?bool
    {
        return $this->isNewArrival;
    }

    public function setIsNewArrival(?bool $isNewArrival): self
    {
        $this->isNewArrival = $isNewArrival;

        return $this;
    }

    public function getIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(?bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getIsSpecialOffer(): ?bool
    {
        return $this->isSpecialOffer;
    }

    public function setIsSpecialOffer(?bool $isSpecialOffer): self
    {
        $this->isSpecialOffer = $isSpecialOffer;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|TagsProduct[]
     */
    public function getTagsProducts(): Collection
    {
        return $this->tagsProducts;
    }

    public function addTagsProduct(TagsProduct $tagsProduct): self
    {
        if (!$this->tagsProducts->contains($tagsProduct)) {
            $this->tagsProducts[] = $tagsProduct;
            $tagsProduct->addProduct($this);
        }

        return $this;
    }

    public function removeTagsProduct(TagsProduct $tagsProduct): self
    {
        if ($this->tagsProducts->removeElement($tagsProduct)) {
            $tagsProduct->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|RelatedProduct[]
     */
    public function getRelatedProducts(): Collection
    {
        return $this->relatedProducts;
    }

    public function addRelatedProduct(RelatedProduct $relatedProduct): self
    {
        if (!$this->relatedProducts->contains($relatedProduct)) {
            $this->relatedProducts[] = $relatedProduct;
            $relatedProduct->setProduct($this);
        }

        return $this;
    }

    public function removeRelatedProduct(RelatedProduct $relatedProduct): self
    {
        if ($this->relatedProducts->removeElement($relatedProduct)) {
            // set the owning side to null (unless already changed)
            if ($relatedProduct->getProduct() === $this) {
                $relatedProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReviewsProduct[]
     */
    public function getReviewsProducts(): Collection
    {
        return $this->reviewsProducts;
    }

    public function addReviewsProduct(ReviewsProduct $reviewsProduct): self
    {
        if (!$this->reviewsProducts->contains($reviewsProduct)) {
            $this->reviewsProducts[] = $reviewsProduct;
            $reviewsProduct->setProduct($this);
        }

        return $this;
    }

    public function removeReviewsProduct(ReviewsProduct $reviewsProduct): self
    {
        if ($this->reviewsProducts->removeElement($reviewsProduct)) {
            // set the owning side to null (unless already changed)
            if ($reviewsProduct->getProduct() === $this) {
                $reviewsProduct->setProduct(null);
            }
        }

        return $this;
    }
}
