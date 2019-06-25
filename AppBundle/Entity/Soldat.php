<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Soldat
 *
 * @ORM\Table(name="soldat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SoldatRepository")
 * @Vich\Uploadable
 */
class Soldat
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
     * @ORM\Column(name="identification", type="string", length=255, nullable=true)
     */
    private $identification;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=255, nullable=true)
     */
    private $matricule;

    /**
     * @var string
     *
     * @ORM\Column(name="corps", type="string", length=255, nullable=true)
     */
    private $corps;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDeces", type="date", nullable=true)
     */
    private $dateDeces;

    /**
     * @var string
     *
     * @ORM\Column(name="departementNaissance", type="string", length=255, nullable=true)
     */
    private $departementNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="CommuneNaissance", type="string", length=255, nullable=true)
     */
    private $communeNaissance;


    /**
     * @var string
     *
     * @ORM\Column(name="departementDeces", type="string", length=255, nullable=true)
     */
    private $departementDeces;

    /**
     * @var string
     *
     * @ORM\Column(name="communeDeces", type="string", length=255, nullable=true)
     */
    private $communeDeces;

    /**
     * @var string
     *
     * @ORM\Column(name="paysDeces", type="string", length=255, nullable=true)
     */
    private $paysDeces;

    /**
     * @var string
     *
     * @ORM\Column(name="conditionDeces", type="string", length=255, nullable=true)
     */
    private $conditionDeces;

    /**
     * @var string
     *
     * @ORM\Column(name="complementDeces", type="string", length=255, nullable=true)
     */
    private $complementDeces;

    /**
     * @var string
     *
     * @ORM\Column(name="derniereResidence", type="string", length=255, nullable=true)
     */
    private $derniereResidence;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="portrait", type="string", length=255, nullable=true)
     */
    private $portrait;

    /**
     *
     * @Assert\File(
     *    maxSize = "1024k"
     *
     *)
     *
     */
    private $csv;


    public function getCsv()
    {
        return $this->csv;
    }

    public function setCsv($csv)
    {
        $this->csv = $csv;

        return $this;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="complementInfo", type="string", length=255, nullable=true)
     */

    private $complementInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="complementImg1", type="string", length=255, nullable=true)
     */
    private $complementImg1;

    /**
     * @var string
     *
     * @ORM\Column(name="complementImg2", type="string", length=255, nullable=true)
     */
    private $complementImg2;

    /**
     * @var string
     *
     * @ORM\Column(name="complementImg3", type="string", length=255, nullable=true)
     */
    private $complementImg3;

    /**
     * @ORM\Column(name="idConflit", type="string")
     */
    private $idConflit;

    /**
     * @var int
     *
     * @ORM\Column(name="lot", type="integer", nullable=true)
     */
    private $lot;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getIdentification()
    {
        return $this->identification;
    }

    public function setIdentification($identification)
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Soldat
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return bool
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Soldat
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Soldat
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return Soldat
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set corps
     *
     * @param string $corps
     *
     * @return Soldat
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return string
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Soldat
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set dateDeces
     *
     * @param \DateTime $dateDeces
     *
     * @return Soldat
     */
    public function setDateDeces($dateDeces)
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    /**
     * Get dateDeces
     *
     * @return \DateTime
     */
    public function getDateDeces()
    {
        return $this->dateDeces;
    }

    /**
     * Set departementNaissance
     *
     * @param string $departementNaissance
     *
     * @return Soldat
     */
    public function setDepartementNaissance($departementNaissance)
    {
        $this->departementNaissance = $departementNaissance;

        return $this;
    }

    /**
     * Get departementNaissance
     *
     * @return string
     */
    public function getDepartementNaissance()
    {
        return $this->departementNaissance;
    }

    /**
     * Set communeNaissance
     *
     * @param string $communeNaissance
     *
     * @return Soldat
     */
    public function setCommuneNaissance($communeNaissance)
    {
        $this->communeNaissance = $communeNaissance;

        return $this;
    }

    /**
     * Get communeNaissance
     *
     * @return string
     */
    public function getCommuneNaissance()
    {
        return $this->communeNaissance;
    }

    /**
     * Set departementDeces
     *
     * @param string $departementDeces
     *
     * @return Soldat
     */
    public function setDepartementDeces($departementDeces)
    {
        $this->departementDeces = $departementDeces;

        return $this;
    }

    /**
     * Get departementDeces
     *
     * @return string
     */
    public function getDepartementDeces()
    {
        return $this->departementDeces;
    }

    /**
     * Set communeDeces
     *
     * @param string $communeDeces
     *
     * @return Soldat
     */
    public function setCommuneDeces($communeDeces)
    {
        $this->communeDeces = $communeDeces;

        return $this;
    }

    /**
     * Get communeDeces
     *
     * @return string
     */
    public function getCommuneDeces()
    {
        return $this->communeDeces;
    }

    /**
     * Set paysDeces
     *
     * @param string $paysDeces
     *
     * @return Soldat
     */
    public function setPaysDeces($paysDeces)
    {
        $this->paysDeces = $paysDeces;

        return $this;
    }

    /**
     * Get paysDeces
     *
     * @return string
     */
    public function getPaysDeces()
    {
        return $this->paysDeces;
    }

    /**
     * Set complementDeces
     *
     * @param string $complementDeces
     *
     * @return Soldat
     */
    public function setComplementDeces($complementDeces)
    {
        $this->complementDeces = $complementDeces;

        return $this;
    }

    /**
     * Get complementDeces
     *
     * @return string
     */
    public function getComplementDeces()
    {
        return $this->complementDeces;
    }

    /**
     * Set conditionDeces
     *
     * @param string $conditionDeces
     *
     * @return Soldat
     */
    public function setConditionDeces($conditionDeces)
    {
        $this->conditionDeces = $conditionDeces;

        return $this;
    }

    /**
     * Get conditionDeces
     *
     * @return string
     */
    public function getConditionDeces()
    {
        return $this->conditionDeces;
    }

    /**
     * Set derniereResidence
     *
     * @param string $derniereResidence
     *
     * @return Soldat
     */
    public function setDerniereResidence($derniereResidence)
    {
        $this->derniereResidence = $derniereResidence;

        return $this;
    }

    /**
     * Get derniereResidence
     *
     * @return string
     */
    public function getDerniereResidence()
    {
        return $this->derniereResidence;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Soldat
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set portrait
     *
     * @param string $portrait
     *
     * @return Soldat
     */
    public function setPortrait($portrait)
    {
        $this->portrait = $portrait;

        return $this;
    }

    /**
     * Get portrait
     *
     * @return string
     */
    public function getPortrait()
    {
        return $this->portrait;
    }


    /**
     * Set complementInfo
     *
     * @param string $complementInfo
     *
     * @return Soldat
     */
    public function setComplementInfo($complementInfo)
    {
        $this->complementInfo = $complementInfo;

        return $this;
    }

    /**
     * Get complementInfo
     *
     * @return string
     */
    public function getComplementInfo()
    {
        return $this->complementInfo;
    }

    /**
     * Set complementImg1
     *
     * @param string $complementImg1
     *
     * @return Soldat
     */
    public function setComplementImg1($complementImg1)
    {
        $this->complementImg1 = $complementImg1;

        return $this;
    }

    /**
     * Get complementImg1
     *
     * @return string
     */
    public function getComplementImg1()
    {
        return $this->complementImg1;
    }

    /**
     * Set complementImg2
     *
     * @param string $complementImg2
     *
     * @return Soldat
     */
    public function setComplementImg2($complementImg2)
    {
        $this->complementImg2 = $complementImg2;

        return $this;
    }

    /**
     * Get complementImg2
     *
     * @return string
     */
    public function getComplementImg2()
    {
        return $this->complementImg2;
    }

    /**
     * Set complementImg3
     *
     * @param string $complementImg3
     *
     * @return Soldat
     */
    public function setComplementImg3($complementImg3)
    {
        $this->complementImg3 = $complementImg3;

        return $this;
    }

    /**
     * Get complementImg3
     *
     * @return string
     */
    public function getComplementImg3()
    {
        return $this->complementImg3;
    }


    public function setIdConflit(Conflit $idConflit)
    {
        $this->idConflit = $idConflit;
        return $this;
    }

    /**
     * Get idConflit
     *
     * @return string
     */
    public function getIdConflit()
    {
        return $this->idConflit;
    }

    /**
     * Get lot
     *
     * @return int
     */
    public function getLot()
    {
        return $this->lot;
    }

    public function setLot($lot)
    {
        $this->lot = $lot;
        return $this;
    }

    /**
     * Set matricule.
     *
     * @param string|null $matricule
     *
     * @return Soldat
     */
    public function setMatricule($matricule = null)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule.
     *
     * @return string|null
     */
    public function getMatricule()
    {
        return $this->matricule;
    }
}
