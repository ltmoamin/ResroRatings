<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BadgeRestaurant
 *
 * @ORM\Table(name="badge_restaurant", indexes={@ORM\Index(name="fkrest", columns={"id_restau"}), @ORM\Index(name="fkbadg", columns={"idbadge"})})
 * @ORM\Entity
 */
class BadgeRestaurant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Restaurant
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_restau", referencedColumnName="id_restau")
     * })
     */
    private $idRestau;

    /**
     * @var \Badge
     *
     * @ORM\ManyToOne(targetEntity="Badge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idbadge", referencedColumnName="id")
     * })
     */
    private $idbadge;


}
