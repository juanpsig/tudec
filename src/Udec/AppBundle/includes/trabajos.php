<?php
namespace Udec\AppBundle\includes;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use Udec\AppBundle\includes\funciones;

use Udec\AppBundle\Entity\Trabgrado;
use Udec\AppBundle\Entity\Clasificaciontg;
use Udec\AppBundle\Entity\Programas;


/**
 * Maneja las actividades relacionadas con los trabajos
 *
 * @author Juan Pablo Rueda
 */
class trabajos {
    private $container;
    private $em;
    private $funciones;
    
    public function __construct(Container $container, EntityManager $em, funciones $funciones){
        $this->container = $container;
        $this->em = $em;
        $this->funciones = $funciones;
    }
    
    public function getInfoCarreras(){
        $query = "SELECT pg.id idCarrera,pg.nombre nombreCarrera,sd.id idSede,sd.nombre nombreSede
                    FROM programas pg
                    INNER JOIN sedes sd ON sd.id = pg.id_sede";
        $con = $this->em->getConnection()->prepare($query);
        $con->execute();
        return $con->fetchAll();
    }
    
    public function getInfoClasificacion(){
        $query = "SELECT id,nombre,electiva,descripcion
                    FROM clasificaciontg
                    WHERE estado='1'";
        $con = $this->em->getConnection()->prepare($query);
        $con->execute();
        return $con->fetchAll();
    }
    
    public function getInfoPersonas($info){
        $txt = str_replace(' ','%',$info);
        $query = "SELECT id,tipo_doc tipoDoc,numero_doc numeroDoc,primer_nombre nombre1,segundo_nombre nombre2,primer_apellido apellido1,segundo_apellido apellido2
                    FROM personas p
                    WHERE CONCAT(numero_doc,primer_nombre, IF(segundo_nombre,segundo_nombre,''),primer_apellido, IF(segundo_apellido,segundo_apellido,'')) LIKE'%$txt%'";
        $con = $this->em->getConnection()->prepare($query);
        $con->execute();
        return $con->fetchAll();
    }
    
    public function crearTrabajo($datos){
        $clasificacion = $this->getClasificacion($datos['clasificacion']);
        $programa = $this->getPrograma($datos['carrera']);
        $trabajo = new Trabgrado();
        $trabajo->setConcepto($datos['concepto']);
        $trabajo->setEstado('1');
        $trabajo->setFechaRg(new \DateTime(date("Y-m-d H:i:s")));
        $trabajo->setFechaGrado(new \DateTime($datos['fechaGrado']));
        $trabajo->setIdClasificacion($clasificacion);
        $trabajo->setIdPrograma($programa);
        $trabajo->setTitulo($datos['titulo']);
        $this->em->persist($trabajo);
        $this->em->flush();
    return $trabajo;
    }
    
    public function getClasificacion($id){
        return $this->em->getRepository('UdecAppBundle:Clasificaciontg')->find($id);
    }
    
    public function getPrograma($id){
        return $this->em->getRepository('UdecAppBundle:Programas')->find($id);
    }
       
    
}
