<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiRepository")
 */
class Api
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
    private $key_api;

    /**
     * @ORM\Column(type="integer")
     */
    private $movie_number;



    public function getId()
    {
        return $this->id;
    }

    public function getKeyApi(): ?string
    {
        return $this->key_api;
    }

    public function setKeyApi(string $key_api): self
    {
        $this->key_api = $key_api;

        return $this;
    }

    public function getMovieNumber(): ?int
    {
        return $this->movie_number;
    }

    public function setMovieNumber(int $movie_number): self
    {
        $this->movie_number = $movie_number;

        return $this;
    }
}
