<?php
namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks()
 */
class Post {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $titulo;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=1000)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="json")
     */
    private $tags;

    /**
     * @return Comment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment[]|ArrayCollection $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Comment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"remove"})
     */
    private $comments;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return array
     */
    public function getTags(): ?array
    {
        return $this->tags;

    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function __toString() {
        return $this->titulo;
    }

    const SERVER_PATH_TO_IMAGE_FOLDER = 'uploads/images';

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;


    /**
     * @param string|null|UploadedFile $file
     *
     * @return self
     */
    public function setFile($file = null): void
    {
        $this->file = $file;
    }

    /**
     * @return string|null|UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($object): void
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        //$originalFilename = pathinfo($this->getFile()->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = uniqid().'.'.$this->getFile()->getClientOriginalName();
        $this->getFile()->move(
            self::SERVER_PATH_TO_IMAGE_FOLDER,
            $newFilename
        );

//        $this->getFile()->move(
//            self::SERVER_PATH_TO_IMAGE_FOLDER,
//            $this->getFile()->getClientOriginalName()
//        );

        // set the path property to the filename where you've saved the file
        $this->filename = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->setFile($newFilename);
    }

    /**
     * Lifecycle callback to upload the file to the server.
     */
    public function lifecycleFileUpload($object): void
    {
        $this->upload($object);
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire.
     */
//    public function refreshUpdated(): void
//    {
//        $this->setUpdated(new \DateTime());
//    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(object $object)
    {
        $this->lifecycleFileUpload($object);
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(object $object)
    {
        $this->lifecycleFileUpload($object);
    }

}