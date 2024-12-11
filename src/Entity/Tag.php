<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, PostTag>
     */
    #[ORM\OneToMany(targetEntity: PostTag::class, mappedBy: 'tags')]
    private Collection $postTags;

    public function __construct()
    {
        $this->postTags = new ArrayCollection();
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

    /**
     * @return Collection<int, PostTag>
     */
    public function getPostTags(): Collection
    {
        return $this->postTags;
    }

    public function addPostTag(PostTag $postTag): static
    {
        if (!$this->postTags->contains($postTag)) {
            $this->postTags->add($postTag);
            $postTag->setTags($this);
        }

        return $this;
    }

    public function removePostTag(PostTag $postTag): static
    {
        if ($this->postTags->removeElement($postTag)) {
            // set the owning side to null (unless already changed)
            if ($postTag->getTags() === $this) {
                $postTag->setTags(null);
            }
        }

        return $this;
    }
}
