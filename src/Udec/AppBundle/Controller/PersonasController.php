<?php

namespace Udec\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Udec\AppBundle\Entity\Personas;
use Udec\AppBundle\Form\PersonasType;

/**
 * Personas controller.
 *
 */
class PersonasController extends Controller
{
    /**
     * Lists all Personas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $personas = $em->getRepository('UdecAppBundle:Personas')->findAll();

        return $this->render('personas/index.html.twig', array(
            'personas' => $personas,
        ));
    }

    /**
     * Creates a new Personas entity.
     *
     */
    public function newAction(Request $request)
    {
        $persona = new Personas();
        $form = $this->createForm('Udec\AppBundle\Form\PersonasType', $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($persona);
            $em->flush();

            return $this->redirectToRoute('personas_show', array('id' => $persona->getId()));
        }

        return $this->render('personas/new.html.twig', array(
            'persona' => $persona,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Personas entity.
     *
     */
    public function showAction(Personas $persona)
    {
        $deleteForm = $this->createDeleteForm($persona);

        return $this->render('personas/show.html.twig', array(
            'persona' => $persona,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Personas entity.
     *
     */
    public function editAction(Request $request, Personas $persona)
    {
        $deleteForm = $this->createDeleteForm($persona);
        $editForm = $this->createForm('Udec\AppBundle\Form\PersonasType', $persona);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($persona);
            $em->flush();

            return $this->redirectToRoute('personas_edit', array('id' => $persona->getId()));
        }

        return $this->render('personas/edit.html.twig', array(
            'persona' => $persona,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Personas entity.
     *
     */
    public function deleteAction(Request $request, Personas $persona)
    {
        $form = $this->createDeleteForm($persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($persona);
            $em->flush();
        }

        return $this->redirectToRoute('personas_index');
    }

    /**
     * Creates a form to delete a Personas entity.
     *
     * @param Personas $persona The Personas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Personas $persona)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('personas_delete', array('id' => $persona->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
