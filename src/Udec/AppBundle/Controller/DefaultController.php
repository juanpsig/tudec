<?php

namespace Udec\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UdecAppBundle:Default:index.html.twig');
    }
}
