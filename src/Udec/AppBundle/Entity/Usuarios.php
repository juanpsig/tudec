<?php

namespace Udec\AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_FDFB7F7E92FC23A8", columns={"username_canonical"}), @ORM\UniqueConstraint(name="UNIQ_FDFB7F7EA0D96FBF", columns={"email_canonical"})})
 * @ORM\Entity
 */
class Usuarios extends BaseUser {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    
}
