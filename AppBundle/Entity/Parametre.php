<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parametre
 *
 * @ORM\Table(name="parametre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParametreRepository")
 */
class Parametre
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
     * @var int
     *
     * @ORM\Column(name="lotActif", type="integer")
     */
    private $lotActif;


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
     * Set lot
     *
     * @param integer $lotActif
     *
     * @return int
     */
    public function setLotActif($lotActif)
    {
        $this->lotActif = $lotActif;

        return $this;
    }

    /**
     * Get lot
     *
     * @return int
     */
    public function getLotActif()
    {
        return $this->lotActif;
    }
}
