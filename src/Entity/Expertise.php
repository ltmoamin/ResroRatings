<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expertise
 *
 * @ORM\Table(name="expertise")
 * @ORM\Entity
 */
class Expertise
{
    /**
     * @var int
     *
     * @ORM\Column(name="idIdee", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ididee;

    /**
     * @var int
     *
     * @ORM\Column(name="reponseAvis", type="integer", nullable=false)
     */
    private $reponseavis;

    /**
     * @var int
     *
     * @ORM\Column(name="dateIdee", type="integer", nullable=false)
     */
    private $dateidee;

    /**
     * @var int
     *
     * @ORM\Column(name="titreIdee", type="integer", nullable=false)
     */
    private $titreidee;

    /**
     * @var string
     *
     * @ORM\Column(name="pubIdee", type="string", length=2000, nullable=false)
     */
    private $pubidee;


}
