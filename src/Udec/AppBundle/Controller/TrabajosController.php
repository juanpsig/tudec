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
        return $this->render('UdecAppBundle:Trabajos:nuevo.html.twig');
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
}
