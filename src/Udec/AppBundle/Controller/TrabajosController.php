<?php
namespace Udec\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/*
 * Porque de tal manera amó Dios al mundo, que ha dado a su Hijo unigénito, 
 * para que todo aquel que en él cree, no se pierda, mas tenga vida eterna.
 * Juan 3:16
 */

/**
 * Administra las peticiones de los trabajos
 *
 * @author Juan Rueda
 */
class TrabajosController extends Controller {
    
    public function nuevoAction(){
        $info['carreras'] = $this->get('trabajos')->getInfoCarreras();
        $info['clasificacion'] = $this->get('trabajos')->getInfoClasificacion();
        return $this->render('UdecAppBundle:Trabajos:nuevo.html.twig',$info);
    }
    
    public function buscarAction(){
        $rqt = $this->get("request");
        if($rqt->get("acc")){
            $text = str_replace(' ','%',$rqt->get('txtkey'));
            $info = $this->queryTrabajos("WHERE tb.titulo like'%$text%'");
            return $this->render('UdecAppBundle:Trabajos:listBuscar.html.twig',array('info'=>$info));
        }else{
            $info = $this->queryTrabajos('');
            return $this->render('UdecAppBundle:Trabajos:buscar.html.twig',array('info'=>$info));
        }
    }
    
    public function crearAction(){
        $rqt = $this->get("request");
        $trabajo = $this->get('trabajos')->crearTrabajo($rqt->get('datos'));
        if($trabajo){
            $this->get("trabajos")->agregarAutores($trabajo,$rqt->get('datos'));
            $this->get("trabajos")->agregarAsesores($trabajo,$rqt->get('datos'));
            return new Response(json_encode(array('estado'=>true,'idTrabajo'=>$trabajo->getId())));
        }
        return new Response('',404);
    }
    
    public function queryTrabajos($where){
        $em = $this->getDoctrine()->getManager();
        $query = "SELECT tb.id idTrabajo,tb.fecha_rg fechaTrabajo,tb.titulo tituloTrabajo,tb.concepto conceptoTrabajo,tb.fecha_grado fechaGradoTRabajo
                    ,GROUP_CONCAT(DISTINCT(ps.id)) idsPersonas,GROUP_CONCAT(DISTINCT(ps.numero_doc)) docsPersonas
                    FROM autores au
                    INNER JOIN trabgrado tb ON tb.id = au.id_trabajo
                    INNER JOIN personas ps ON ps.id = au.id_persona
                    $where
                    GROUP BY au.id_trabajo
                    ORDER BY tb.id DESC LIMIT 50";
        $con = $em->getConnection()->executeQuery($query);
        $con->execute();
        return $con->fetchAll();
    }
    
    public function verAction(){
        $rqt = $this->get("request");
        $infoTrabajo = $this->get("trabajos")->getInfoTrabajo($rqt->get('idTrabajo'));
        if($infoTrabajo){
            return $this->render('UdecAppBundle:Trabajos:infoTrabajo.html.twig',$infoTrabajo);
        }
        return new Response('');
    }

    public function adjuntarAction($idTrabajo){
        $infoTrabajo = $this->get("trabajos")->getInfoTrabajo($idTrabajo);
        $info = array('idTrabajo'=>$idTrabajo,'trabajo'=>$infoTrabajo);
        return $this->render('UdecAppBundle:Trabajos:adjuntar.html.twig',$info);
    }
    
    public function adjuntarDocsAction($idTrabajo){
        $rqt = $this->get('request');
        $path = 'adjuntos/';
        $trabajo = $this->get("trabajos")->getTrabajo($idTrabajo);
        if($trabajo){
            $archivo = $rqt->files->get('resumen');
            $docs['resumen'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('abstrac');
            $docs['abstrac'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('articulo');
            $docs['articulo'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('documento');
            $docs['documento'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('soft');
            $docs['soft'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('codigof');
            $docs['codigof'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('manualt');
            $docs['manualt'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            $archivo = $rqt->files->get('manualu');
            $docs['manualu'] = $this->get("trabajos")->uploadArchivo($archivo,$path);
            
            $this->get("trabajos")->guardarDocumentos($trabajo,$docs);
        }
        return new Response('ok');
    }
}
