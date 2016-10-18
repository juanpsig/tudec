<?php

namespace Udec\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Udec\AppBundle\Entity\Sedes;
use Udec\AppBundle\Form\SedesType;

/**
 * Sedes controller.
 *
 */
class SedesController extends Controller
{
    /**
     * Lists all Sedes entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sedes = $em->getRepository('UdecAppBundle:Sedes')->findAll();

        return $this->render('sedes/index.html.twig', array(
            'sedes' => $sedes,
        ));
    }

    /**
     * Creates a new Sedes entity.
     *
     */
    public function newAction(Request $request)
    {
        $sede = new Sedes();
        $form = $this->createForm('Udec\AppBundle\Form\SedesType', $sede);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sede);
            $em->flush();

            return $this->redirectToRoute('sedes_show', array('id' => $sede->getId()));
        }

        return $this->render('sedes/new.html.twig', array(
            'sede' => $sede,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sedes entity.
     *
     */
    public function showAction(Sedes $sede)
    {
        $deleteForm = $this->createDeleteForm($sede);

        return $this->render('sedes/show.html.twig', array(
            'sede' => $sede,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sedes entity.
     *
     */
    public function editAction(Request $request, Sedes $sede)
    {
        $deleteForm = $this->createDeleteForm($sede);
        $editForm = $this->createForm('Udec\AppBundle\Form\SedesType', $sede);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sede);
            $em->flush();

            return $this->redirectToRoute('sedes_edit', array('id' => $sede->getId()));
        }

        return $this->render('sedes/edit.html.twig', array(
            'sede' => $sede,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Sedes entity.
     *
     */
    public function deleteAction(Request $request, Sedes $sede)
    {
        $form = $this->createDeleteForm($sede);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sede);
            $em->flush();
        }

        return $this->redirectToRoute('sedes_index');
    }

    /**
     * Creates a form to delete a Sedes entity.
     *
     * @param Sedes $sede The Sedes entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sedes $sede)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sedes_delete', array('id' => $sede->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
