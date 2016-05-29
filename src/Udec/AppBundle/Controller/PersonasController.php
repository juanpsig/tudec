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
    
    public function nuevoAction(){
        return new Response('ok');
    }
    
    public function buscarAction(){
        return new Response('ok');
    }
}
