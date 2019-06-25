<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Conflit
 *
 * @ORM\Table(name="conflit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConflitRepository")
 *
 */
class Conflit
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date")
     */
    private $dateFin;


    /**
     *
     * @var string
     *
     * @ORM\ Column (name="abrevation" , type="string" , length=255)
     */
    private $abrevation;

    /**
     * Set nom
     *
     * @param string $abrevation
     *
     * @return Conflit
     */
    public function setAbrevation($abrevation)
    {
        $this->abrevation = $abrevation;

        return $this;
    }

    /**
     * Get abrevation
     *
     * @return string
     */
    public function getAbrevation()
    {
        return $this->abrevation;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Conflit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Conflit
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Conflit
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;


    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
