<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $authorComment;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commentArticle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAuthorComment(): ?User
    {
        return $this->authorComment;
    }

    public function setAuthorComment(?User $authorComment): self
    {
        $this->authorComment = $authorComment;

        return $this;
    }

    public function getCommentArticle(): ?Article
    {
        return $this->commentArticle;
    }

    public function setCommentArticle(?Article $commentArticle): self
    {
        $this->commentArticle = $commentArticle;

        return $this;
    }
}
