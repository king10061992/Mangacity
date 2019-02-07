<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Partenaire
 *
 *
 * @ORM\Table(name="partenaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\partenaireRepository")
 * @UniqueEntity(fields="name", message="Un partenaire existe déjà avec ce noms.")
 */
class Partenaire
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
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\Length(
     *     min=2,
     *     max=75,
     *     minMessage="Le noms du magasin doit faire au moins {{ limit }} caracteres.",
     *     maxMessage="Le noms du magasin doit faire moins que {{ limit }} caracteres."
     * )
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Assert\Length(
     *     min=10,
     *     max=100,
     *     minMessage="Le noms de la rue doit faire au moins {{ limit }} caractères.",
     *     maxMessage="Le noms de la rue doit faire moins que {{ limit }} caractères."
     * )
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255,unique=true)
     */
    private $street;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer", length=255)
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    /**
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="La localite doit faire au moins {{ limit }} caractères.",
     *     maxMessage="La localite doit faire moins que {{ limit }} caractères."
     * )
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=255)
     */
    private $locality;

    /**
     * @Assert\Time
     *
     * @var \DateTime
     *
     * @ORM\Column(name="timeStart", type="time")
     */
    private $timeStart;

    /**
     * @Assert\DateTime
     * @var \DateTime
     *
     * @ORM\Column(name="timeEnd", type="time")
     */
    private $timeEnd;

    /**
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
     * @var string
     *
     * @ORM\Column(name="webSite", type="string", length=255)
     */
    private $webSite;

    /**
     *
     * @Assert\Length(
     *     min=20,
     *     minMessage="L URL doit faire au moins {{ limit }} caractères."
     * )
     * @Assert\Url(
     *     message = "L URL '{{ value }}' est invalide",
     *     protocols = {"http", "https","facebook"}
     * )
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255)
     */
    private $facebook;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="999999999",
     *     minMessage="Le numero de telephone est invalide car est trop petit",
     *     maxMessage="Le numero de telephone est invalide car est trop grand"
     * )
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=9)
     */
    private $mobile;

    /**
     * @Assert\Length(
     *     min=5,
     *     minMessage="L email doit faire au moins {{ limit }} caractères."
     * )
     * @Assert\Email(
     *     message = "L email {{ value }} est pas valide",
     *     checkMX = true
     * )
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @Assert\Length(
     *     min=5,
     *     max=75,
     *     minMessage="L exception doit faire au moins {{ limit }} caractères.",
     *     maxMessage="L exception doit faire moins que {{ limit }} caractères."
     * )
     * @var string
     *
     * @ORM\Column(name="exception", type="text")
     */
    private $exception;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     */
    private $published = true;

    /**
     *
     * @Assert\NotBlank()
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     *
     * @Assert\NotBlank()
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Logo", cascade={"persist", "remove"})
     */
    private $logo;

    /**
     *
     * @Assert\NotBlank()
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Flyers", cascade={"persist", "remove"})
     */
    private $flyers;

    /**
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", cascade={"persist"})
     * @ORM\JoinTable(name="app_partenaire_product")
     */
    private $product;

    public function __toString() {
        return $this->name;
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Partenaire
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Partenaire
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
     * @return Partenaire
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
     * @param integer $code
     *
     * @return Partenaire
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return int
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
     * @return Partenaire
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
     * Set timeStart
     *
     * @param \DateTime $timeStart
     *
     * @return Partenaire
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    /**
     * Get timeStart
     *
     * @return \DateTime
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * Set timeEnd
     *
     * @param \DateTime $timeEnd
     *
     * @return Partenaire
     */
    public function setTimeEnd($timeEnd)
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    /**
     * Get timeEnd
     *
     * @return \DateTime
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * Set webSite
     *
     * @param string $webSite
     *
     * @return Partenaire
     */
    public function setWebSite($webSite)
    {
        $this->webSite = $webSite;

        return $this;
    }

    /**
     * Get webSite
     *
     * @return string
     */
    public function getWebSite()
    {
        return $this->webSite;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Partenaire
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
     * Set exception
     *
     * @param string $exception
     *
     * @return Partenaire
     */
    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * Get exception
     *
     * @return string
     */
    public function getException()
    {
        return $this->exception;
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

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Partenaire
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

    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }


    public function setLogo(Logo $logo = null)
    {
        $this->logo = $logo;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setFlyers(Flyers $flyers = null)
    {
        $this->flyers = $flyers;
    }

    public function getFlyers()
    {
        return $this->flyers;
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


    public function getProduct()
    {
        return $this->product;
    }

    // Notez le singulier, on ajoute une seule catégorie à la fois
    public function addProduct(Product $product)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->product[] = $product;
    }

    public function removeProduct(Product $product)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->product->removeElement($product);
    }
}

