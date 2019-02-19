<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_book = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Book", mappedBy="user")
     */
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getFirstname() : ? string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname) : self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname() : ? string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname) : self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getIdentifiant() : ? string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant) : self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getNbBook() : ? int
    {
        return $this->nb_book;
    }

    public function setNbBook(int $nb_book) : self
    {
        $this->nb_book = $nb_book;

        return $this;
    }

    public function borrowBook()
    {
        $this->nb_book++;

        return $this;
    }

    public function returnBook()
    {
        $this->nb_book--;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks() : Collection
    {
        return $this->books;
    }

    public function addBook(Book $book) : self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setUser($this);
        }

        return $this;
    }

    public function removeBook(Book $book) : self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
            // set the owning side to null (unless already changed)
            if ($book->getUser() === $this) {
                $book->setUser(null);
            }
        }

        return $this;
    }
}
