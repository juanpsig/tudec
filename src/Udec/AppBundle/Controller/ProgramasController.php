<?php

namespace Udec\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Udec\AppBundle\Entity\Programas;
use Udec\AppBundle\Form\ProgramasType;

/**
 * Programas controller.
 *
 */
class ProgramasController extends Controller
{
    /**
     * Lists all Programas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programas = $em->getRepository('UdecAppBundle:Programas')->findAll();

        return $this->render('programas/index.html.twig', array(
            'programas' => $programas,
        ));
    }

    /**
     * Creates a new Programas entity.
     *
     */
    public function newAction(Request $request)
    {
        $programa = new Programas();
        $form = $this->createForm('Udec\AppBundle\Form\ProgramasType', $programa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($programa);
            $em->flush();

            return $this->redirectToRoute('programas_show', array('id' => $programa->getId()));
        }

        return $this->render('programas/new.html.twig', array(
            'programa' => $programa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Programas entity.
     *
     */
    public function showAction(Programas $programa)
    {
        $deleteForm = $this->createDeleteForm($programa);

        return $this->render('programas/show.html.twig', array(
            'programa' => $programa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Programas entity.
     *
     */
    public function editAction(Request $request, Programas $programa)
    {
        $deleteForm = $this->createDeleteForm($programa);
        $editForm = $this->createForm('Udec\AppBundle\Form\ProgramasType', $programa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($programa);
            $em->flush();

            return $this->redirectToRoute('programas_edit', array('id' => $programa->getId()));
        }

        return $this->render('programas/edit.html.twig', array(
            'programa' => $programa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Programas entity.
     *
     */
    public function deleteAction(Request $request, Programas $programa)
    {
        $form = $this->createDeleteForm($programa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($programa);
            $em->flush();
        }

        return $this->redirectToRoute('programas_index');
    }

    /**
     * Creates a form to delete a Programas entity.
     *
     * @param Programas $programa The Programas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Programas $programa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('programas_delete', array('id' => $programa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
