<?php
namespace Udec\AppBundle\includes;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;

/**
 * Maneja las actividades relacionadas con los trabajos
 *
 * @author Juan Pablo Rueda
 */
class funciones {
    private $container;
    private $em;
    
    public function __construct(Container $container, EntityManager $em){
        $this->container = $container;
        $this->em = $em;
    }
    
}
