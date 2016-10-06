<?php
namespace Udec\AppBundle\includes;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use Udec\AppBundle\includes\funciones;

use Udec\AppBundle\Entity\Trabgrado;
use Udec\AppBundle\Entity\Archivostg;
use Udec\AppBundle\Entity\Autores;
use Udec\AppBundle\Entity\Asesores;
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
    
    public function getTrabajo($id){
        return $this->em->getRepository('UdecAppBundle:Trabgrado')->find($id);
    }
    
    public function getTrabajoBy($by){
        return $this->em->getRepository('UdecAppBundle:Trabgrado')->findBy($by);
    }
    
    public function getTrabajoOneBy($by){
        return $this->em->getRepository('UdecAppBundle:Trabgrado')->findOneBy($by);
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
    public function getInfoTrabajo($idTrabajo){
        $query = "SELECT tg.id idTrabajo,tg.titulo tituloTrabajo,tg.concepto conceptoTrabajo,tg.fecha_grado fechaGradoTrabajo
                    ,au.id idAutor,ae.id idAsesor,pg.id idPrograma,pg.nombre nombrePrograma,cl.id idClasificacion,cl.nombre nombreClasificacion
                    ,pau.id idPersonaAutor, GROUP_CONCAT(DISTINCT(CONCAT_WS(' ',pau.primer_nombre,pau.segundo_nombre,pau.primer_apellido,pau.segundo_apellido))) nombrePersonaAutor  
                    ,pae.id idPersonaAsesor,GROUP_CONCAT(DISTINCT(CONCAT_WS(' ',pae.primer_nombre,pae.segundo_nombre,pae.primer_apellido,pae.segundo_apellido))) nombrePersonaAsesor  
                    FROM trabgrado tg
                    INNER JOIN autores au ON au.id_trabajo = tg.id
                    INNER JOIN asesores ae ON ae.id_trabajo = tg.id
                    INNER JOIN programas pg ON pg.id = tg.id_programa
                    INNER JOIN clasificaciontg cl ON cl.id = tg.id_clasificacion
                    INNER JOIN personas pau ON pau.id = au.id_persona
                    INNER JOIN personas pae ON pae.id = ae.id_persona
                    WHERE tg.id='$idTrabajo'";
        $con = $this->em->getConnection()->prepare($query);
        $con->execute();
        $trabajos = $con->fetchAll();
        if($trabajos){
            return $trabajos[0];
        }
        return false;
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
    
    public function agregarAutores($trabajo,$info){
        foreach($info['autores'] as $item){
            $persona = $this->container->get('personas')->getPersona($item['id']);
            if($persona){
                $autor = new Autores();
                $autor->setEstado('1');
                $autor->setIdPersona($persona);
                $autor->setIdTrabajo($trabajo);
                $this->em->persist($autor);
            }
        }
        $this->em->flush();
        return true;
    }
    
    public function agregarAsesores($trabajo,$info){
        foreach($info['asesores'] as $item){
            $persona = $this->container->get('personas')->getPersona($item['id']);
            if($persona){
                $asesor = new Asesores();
                $asesor->setEstado('1');
                $asesor->setIdPersona($persona);
                $asesor->setIdTrabajo($trabajo);
                $this->em->persist($asesor);
            }
        }
        $this->em->flush();
        return true;
    }
    
    public function uploadArchivo(UploadedFile $file,$path){
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($path,$fileName);
        return $fileName;
    }
    
    public function guardarDocumentos($trabajo,$docs){
        $arc = $this->getAdjuntosOneBy(array('idTrabajo'=>$trabajo));
        if($arc){
            // penidente eliminar unlink
        }else{
            $arc = new Archivostg();
            $arc->setEstado('1');
        }
        $arc->setIdTrabajo($trabajo);
        $arc->setArticulo($docs['articulo']);
        $arc->setAbstrc($docs['abstrac']);
        $arc->setResumen($docs['resumen']);
        //$arc->setManualUsr($docs['']);
        //$arc->setManualTecn($docs['']);
        //$arc->setSoftware($docs['']);
        $this->em->persist($arc);
        $this->em->flush();
        return $arc;
    }
    
}
