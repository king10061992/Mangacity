<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Convention
 *
 * @ORM\Table(name="convention")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConventionRepository")
 * @UniqueEntity(fields="title", message="Une convention existe déjà avec ce noms.")
 */
class Convention
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=5,
     *     max=10000,
     *     minMessage="Le noms de la convention doit faire au moins {{ limit }} caractères.",
     *     maxMessage="Le noms de la convention doit faire moins que {{ limit }} caractères."
     *)
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=5,
     *     max=10000,
     *     minMessage="La description du magasin doit faire au moins {{ limit }} caractères.",
     *     maxMessage="La description du magasin doit faire moins que {{ limit }} caractères."
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=3,
     *     max=100,
     *     minMessage="Le noms de la rue doit faire au moins {{ limit }} caractères.",
     *     maxMessage="Le noms de la rue doit faire moins que {{ limit }} caractères."
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="integer", length=255)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="integer", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="La localite doit faire au moins {{ limit }} caractères.",
     *     maxMessage="La localite doit faire moins que {{ limit }} caractères."
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="locality", type="string", length=255)
     */
    private $locality;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="datetime")
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     *
     * @ORM\Column(name="dateEnd", type="datetime")
     */
    private $dateEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255)
     */
    private $website;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=20,
     *     minMessage="L URL doit faire au moins {{ limit }} caractères."
     * )
     * @Assert\Url(
     *     message = "L URL '{{ value }}' est invalide",
     *     protocols = {"http", "https"}
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="facebook", type="string", length=255)
     */
    private $facebook;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="mobile", type="string", length=9)
     */
    private $mobile;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=5,
     *     minMessage="L email doit faire au moins {{ limit }} caractères."
     * )
     * @Assert\Email(
     *     message = "L email {{ value }} est pas valide"
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     *
     * @Assert\NotBlank()
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     *
     * @Assert\NotBlank()
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Poster", cascade={"persist", "remove"})
     */
    private $poster;

    /**
     *
     * @Assert\NotBlank()
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Flyers", cascade={"persist", "remove"})
     *
     */
    private $flyers;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Partenaire", cascade={"persist"})
     * @ORM\JoinTable(name="app_convention_partenaire")
     */
    private $partenaire;

    /**
     * @var bool
     *
     */
    private $published = true;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Convention
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Convention
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Convention
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Convention
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Convention
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return Convention
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Convention
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Convention
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Convention
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Convention
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Convention
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Convention
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setFlyers(Flyers $flyers = null)
    {
        $this->flyers = $flyers;
    }

    public function getFlyers()
    {
        return $this->flyers;
    }

    public function setPoster(Poster $poster = null)
    {
        $this->poster = $poster;
    }

    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param bool $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function getPartenaire()
    {
        return $this->partenaire;
    }

    // Notez le singulier, on ajoute une seule catégorie à la fois
    public function addPartenaire(Partenaire $partenaire)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->partenaire[] = $partenaire;
    }

    public function removePartenaire(Partenaire $partenaire)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->partenaire->removeElement($partenaire);
    }

}

