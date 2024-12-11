<?php

namespace App\Entity;

use App\Repository\PostTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostTagRepository::class)]
class PostTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'postTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tag $tags = null;

    #[ORM\ManyToOne(inversedBy: 'postTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $posts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTags(): ?tag
    {
        return $this->tags;
    }

    public function setTags(?tag $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getPosts(): ?Post
    {
        return $this->posts;
    }

    public function setPosts(?Post $posts): static
    {
        $this->posts = $posts;

        return $this;
    }
}
