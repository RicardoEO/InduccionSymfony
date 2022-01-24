<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use App\Entity\User;
use App\Entity\Like;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comments")
 */
class Comment {
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Like[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Like", mappedBy="comentario", cascade={"remove"})
     */
    private $likes;

    /**
     * @return Like[]|ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param Like[]|ArrayCollection $likes
     */
    public function setLikes($likes): void
    {
        $this->likes = $likes;
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser(): ?\App\Entity\User
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
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="idPost", referencedColumnName="id", nullable=false)
     */
    private $post;

    /**
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

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
     * @return string
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    /**
     * @param string $comentario
     */
    public function setComentario(string $comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return string
     */
    public function getWeb(): ?string
    {
        return $this->web;
    }

    /**
     * @param string $web
     */
    public function setWeb(string $web): void
    {
        $this->web = $web;
    }

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $comentario;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $web;

    public function __toString() {
        return $this->nombre;
    }
}