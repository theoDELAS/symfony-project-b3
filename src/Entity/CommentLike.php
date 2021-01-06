<?php

namespace App\Entity;

use App\Repository\CommentLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentLikeRepository::class)
 * @ORM\Table(name="comment_likes")
 * @ORM\HasLifecycleCallbacks()
 */
class CommentLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $likedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLikedAt(): ?\DateTimeInterface
    {
        return $this->likedAt;
    }

    public function setLikedAt(\DateTimeInterface $likedAt): self
    {
        $this->likedAt = $likedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void
    {
        $this->setLikedAt(new \DateTime('now'));
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
