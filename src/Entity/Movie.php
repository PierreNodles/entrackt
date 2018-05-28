<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $tmdb_id;

    public function setTmdb_id($tmdb_id) {
        $this->tmdb_id = $tmdb_id;
        return $this;
    }

    public function getTmdb_id() {
      return $this->tmdb_id;
    }

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $name;

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
      $this->name = $name;
      return $this;
   }


    /**
     * @ORM\Column(type="string", length=100, nullable=TRUE)
     */

    private $slug;

    public function getSlug()
    {
       return $this->slug;
    }
    public function setSlug($slug)
    {
      $this->slug = $slug;
   }

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $synopsis;

    public function getSynopsis()
    {
       return $this->synopsis;
    }
    public function setSynopsis($synopsis)
    {
      $this->synopsis = $synopsis;
  }



    // FONCTION POUR L'ID



/**
 * @ORM\Column(type="string", length=255, nullable=true)
 */
private $original_title;

public function setOriginal_Title(string $original_title): self
{
    $this->original_title = $original_title;
    return $this;
}
  public function getOriginal_Title(): ?string
  {
      return $this->original_title;
  }


  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $director;

  public function setDirector(string $director): self
  {
      $this->director = $director;
      return $this;
  }
  public function getDirector(): ?string
  {
      return $this->director;
  }
  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $producer;

  public function setProducer(?string $producer): self
  {
      $this->producer = $producer;
      return $this;
  }
  public function getProducer(): ?string
  {
      return $this->producer;
  }


  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $type;

  public function setType(string $type): self
  {
      $this->type = $type;
      return $this;
  }
  public function getType(): ?string
  {
      return $this->type;
  }


  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $release_date;


  public function getrelease_date(): ?string
  {
      return $this->release_date;
  }

  public function setRelease_Date( $release_date): self
  {
      $this->release_date = $release_date;
      return $this;
  }

  /**
   * @ORM\Column(type="float", nullable=true)
   */
  private $rating_score;


  public function getRating_Score(): ?float
  {
      return $this->rating_score;
  }


  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $tagline;
  public function setRating_Score(?float $rating_score): self
  {
      $this->rating_score = $rating_score;
      return $this;
  }

  public function getTagline(): ?string
  {
      return $this->tagline;
  }


  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $picture;
  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $actors;
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="movie")
   */
  private $comments;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favorite")
   */
  private $users;

  public function __construct()
  {
      $this->users = new ArrayCollection();
  }

  public function setTagline(?string $tagline): self
  {
      $this->tagline = $tagline;
      return $this;
  }

  public function getPicture(): ?string
  {
      return $this->picture;
  }

  public function setPicture(?string $picture): self
  {
      $this->picture = $picture;
      return $this;
  }

  public function getActors(): ?string
  {
      return $this->actors;
  }

  public function setActors(?string $actors): self
  {
      $this->actors = $actors;
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
          $comment->setMovie($this);
      }
      return $this;
  }

  public function removeComment(Comment $comment): self
  {
      if ($this->comments->contains($comment)) {
          $this->comments->removeElement($comment);
          // set the owning side to null (unless already changed)
          if ($comment->getMovie() === $this) {
              $comment->setMovie(null);
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
          $critique->setMovie($this);
      }
      return $this;
  }

  public function removeCritique(Critique $critique): self
  {
      if ($this->critiques->contains($critique)) {
          $this->critiques->removeElement($critique);
          // set the owning side to null (unless already changed)
          if ($critique->getMovie() === $this) {
              $critique->setMovie(null);
          }
      }
      return $this;
  }

  /**
   * @return Collection|User[]
   */
  public function getUsers(): Collection
  {
      return $this->users;
  }
  public function addUser(User $user): self
  {
      if (!$this->users->contains($user)) {
          $this->users[] = $user;
          $user->addFavorite($this);
      }
      return $this;
  }

  public function removeUser(User $user): self
  {
      if ($this->users->contains($user)) {
          $this->users->removeElement($user);
          $user->removeFavorite($this);
      }
      return $this;
  }

}
