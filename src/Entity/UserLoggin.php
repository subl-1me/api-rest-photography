<?php

namespace App\Entity;

use App\Repository\UserLogginRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserLogginRepository::class)
 */
class UserLoggin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    private $username;

    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(){
        return $this->$username;
    }

    public function getPassword(){
        return $this->$password;
    }
}
