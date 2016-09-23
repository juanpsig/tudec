<?php
namespace Udec\AppBundle\includes;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use Udec\AppBundle\includes\funciones;


/**
 * Maneja las actividades relacionadas con los trabajos
 *
 * @author Juan Pablo Rueda
 */
class personas {
    private $container;
    private $em;
    private $funciones;
    
    public function __construct(Container $container, EntityManager $em, funciones $funciones){
        $this->container = $container;
        $this->em = $em;
        $this->funciones = $funciones;
    }
}
