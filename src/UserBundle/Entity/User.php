<?php
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     *
     * @Assert\Length(
     *     min=2,
     *     max=75,
     *     minMessage="Le noms du magasin doit faire au moins {{ limit }} caracteres.",
     *     maxMessage="Le noms du magasin doit faire moins que {{ limit }} caracteres."
     * )
     * @Assert\NotBlank()
     */
    private $surname;

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
     *
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=255)
     */
    private $locality;

    /**
     * @Assert\Date
     * @var \Date
     *
     * @ORM\Column(name="born", type="time")
     */
    private $born;



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
     * @return User
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
     * Set name
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }


    /**
     * Set code
     *
     * @param integer $code
     *
     * @return User
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
     * @return User
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
     * Set born
     *
     * @param \Date $timeStart
     *
     * @return User
     */
    public function setBorn($born)
    {
        $this->born = $born;

        return $this;
    }

    /**
     * Get born
     *
     * @return \Date
     */
    public function getBorn()
    {
        return $this->born;
    }


}

