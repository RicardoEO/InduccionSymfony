<?php
namespace App\Entity;
use Doctrine\ORM\Mapping\Entity;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LikeRepository;
/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="likes",uniqueConstraints={
 *        @ORM\UniqueConstraint(name="assignment_unique", columns={"idUser", "idComment"})
 * })
 */
class Like {

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="likes")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Comment
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="likes")
     * @ORM\JoinColumn(name="idComment", referencedColumnName="id", nullable=false)
     */
    private $comentario;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isLike;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser(): \App\Entity\User
    {
        return $this->user;
    }

    /**
     * @param \App\Entity\User $user
     */
    public function setUser(\App\Entity\User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \App\Entity\Comment
     */
    public function getComentario(): \App\Entity\Comment
    {
        return $this->comentario;
    }

    /**
     * @param \App\Entity\Comment $comentario
     */
    public function setComentario(\App\Entity\Comment $comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return bool
     */
    public function isLike(): bool
    {
        return $this->isLike;
    }

    /**
     * @param bool $isLike
     */
    public function setIsLike(bool $isLike): void
    {
        $this->isLike = $isLike;
    }
}