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
class PersonasController extends Controller {
    
    public function getEm(){
        return $this->getDoctrine()->getManager();
    }

    public function nuevoAction(){
        return new Response('ok');
    }
    
    public function buscarAction(){
        $rqt = $this->get('request');
        if($rqt->get('texto')){
            $buscar = '+'.str_replace(' ','* +',$rqt->get('texto')).'*';
            $personas = $this->queryPersonas("WHERE MATCH(primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,codigo,email,telefono_fijo,movil,numero_doc) AGAINST('$buscar' IN BOOLEAN MODE)");
            return $this->render('UdecAppBundle:Personas:listaPersonas.html.twig',array('lista'=>$personas));
        }
        return new Response('',404);
    }
    
    public function queryPersonas($where){
        $em = $this->getEm();
        $query = "SELECT id,CONCAT(primer_nombre,' ',segundo_nombre) nombre,CONCAT_WS(' ',primer_apellido,segundo_apellido) apellidos,codigo,email,telefono_fijo telefono,movil,tipo_doc tipoDoc,numero_doc numeroDoc,if(estado = '1','Activo','Inactivo') estado
                    FROM personas
                    $where";
        //print_r($query);
        $con = $em->getConnection()->prepare($query);
        $con->execute();
        return $con->fetchAll();
    }
}
