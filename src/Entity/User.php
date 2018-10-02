<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Table(name="app_users")
* @ORM\Entity(repositoryClass="App\Repository\UserRepository")
* @UniqueEntity("username")
*/
class User implements UserInterface, \Serializable
{
  /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
  private $id;

  /**
  * @ORM\Column(type="string", length=255)
  */
  private $username;

  /**
  * @ORM\Column(type="string", length=64)
  */
  private $password;

  /**
  * @ORM\Column(type="string", length=254)
  */
  private $email;

  /**
  * @ORM\Column(name="is_active", type="boolean")
  */
  private $isActive;

   /**
  * @ORM\Column(type="array")
  */

 private $roles;
  /**
   * @Assert\NotBlank()
   */
  private $plain_password;
  /**
   * @ORM\Column(type="string", length=255)
   */
  private $slug;
  /**
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $birthdate;
  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $gender;
  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $reset_token;
  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $reset_expire;
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
   */
  private $comments;
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Critique", mappedBy="user")
   */
  private $critiques;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\Movie", inversedBy="users")
   */
  private $favorite;

  public function __construct() {
    $this->roles = array('ROLE_USER');
    $this->isActive = true;
    $this->comments = new ArrayCollection();
    $this->critiques = new ArrayCollection();
    $this->favorite = new ArrayCollection();
    // may not be needed, see section on salt below
    // $this->salt = md5(uniqid('', true));
  }

  public function getRoles()
  {
    return $this->roles;
  }

  public function setRoles(array $roles): self
  {
      $this->roles = $roles;

      return $this;
  }

  public function serialize()
  {
    return serialize(array(
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      // $this->salt,
    ));
  }

  public function unserialize($serialized)
  {
    list (
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      // $this->salt
      ) = unserialize($serialized, ['allowed_classes' => false]);
  }
  public function eraseCredentials()
 {
 }
  public function getSalt()
   {
       // you *may* need a real salt depending on your encoder
       // see section on salt below
       return null;
   }

    public function getId()
    {
      return $this->id;
    }

    public function getUsername(): ?string
    {
      return $this->username;
    }

    public function setUsername(string $username): self
    {
      $this->username = $username;

      return $this;
    }

    public function getPassword(): ?string
    {
      return $this->password;
    }

    public function setPassword(string $password): self
    {
      $this->password = $password;

      return $this;
    }

    public function getEmail(): ?string
    {
      return $this->email;
    }

    public function setEmail(string $email): self
    {
      $this->email = $email;

      return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    public function setPlainPassword(string $plain_password): self
    {
        $this->plain_password = $plain_password;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getResetExpire(): ?string
    {
        return $this->reset_expire;
    }

    public function setResetExpire(?string $reset_expire): self
    {
        $this->reset_expire = $reset_expire;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Critique[]
     */
    public function getCritiques(): Collection
    {
        return $this->critiques;
    }

    public function addCritique(Critique $critique): self
    {
        if (!$this->critiques->contains($critique)) {
            $this->critiques[] = $critique;
            $critique->setUser($this);
        }

        return $this;
    }

    public function removeCritique(Critique $critique): self
    {
        if ($this->critiques->contains($critique)) {
            $this->critiques->removeElement($critique);
            // set the owning side to null (unless already changed)
            if ($critique->getUser() === $this) {
                $critique->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Movie $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Movie $favorite): self
    {
        if ($this->favorite->contains($favorite)) {
            $this->favorite->removeElement($favorite);
        }

        return $this;
    }

  }
