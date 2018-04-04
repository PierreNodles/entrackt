<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MoviesRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $tmdb_id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */

    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=TRUE)
     */
    private $slug;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */

    private $synopsis;



    // FONCTION POUR L'ID
    public function getId()
    {
        return $this->id;
    }

    // FONCTION POUR LE NAME
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
      $this->name = $name;;
   }


   // FONCTION POUR LE SLUG
   public function getSlug()
   {
      return $this->slug;
   }
   public function setSlug($slug)
   {
     $this->slug = $slug;
  }

  // FONCTION POUR LA DESCRIPTION
  public function getSynopsis()
  {
     return $this->synopsis;
  }
  public function setSynopsis($synopsis)
  {
    $this->synopsis = $synopsis;
}
}
