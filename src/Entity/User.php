<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
   * @ORM\Column(type="string", length=255)
   */
  private $plain_password;

  public function __construct() {
    $this->roles = array('ROLE_ADMIN');
    $this->isActive = true;
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

  }
