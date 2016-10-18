<?php

namespace Udec\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Udec\AppBundle\Entity\Clasificaciontg;
use Udec\AppBundle\Form\ClasificaciontgType;

/**
 * Clasificaciontg controller.
 *
 */
class ClasificaciontgController extends Controller
{
    /**
     * Lists all Clasificaciontg entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clasificaciontgs = $em->getRepository('UdecAppBundle:Clasificaciontg')->findAll();

        return $this->render('clasificaciontg/index.html.twig', array(
            'clasificaciontgs' => $clasificaciontgs,
        ));
    }

    /**
     * Creates a new Clasificaciontg entity.
     *
     */
    public function newAction(Request $request)
    {
        $clasificaciontg = new Clasificaciontg();
        $form = $this->createForm('Udec\AppBundle\Form\ClasificaciontgType', $clasificaciontg);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clasificaciontg);
            $em->flush();

            return $this->redirectToRoute('clasificaciontg_show', array('id' => $clasificaciontg->getId()));
        }

        return $this->render('clasificaciontg/new.html.twig', array(
            'clasificaciontg' => $clasificaciontg,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Clasificaciontg entity.
     *
     */
    public function showAction(Clasificaciontg $clasificaciontg)
    {
        $deleteForm = $this->createDeleteForm($clasificaciontg);

        return $this->render('clasificaciontg/show.html.twig', array(
            'clasificaciontg' => $clasificaciontg,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Clasificaciontg entity.
     *
     */
    public function editAction(Request $request, Clasificaciontg $clasificaciontg)
    {
        $deleteForm = $this->createDeleteForm($clasificaciontg);
        $editForm = $this->createForm('Udec\AppBundle\Form\ClasificaciontgType', $clasificaciontg);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clasificaciontg);
            $em->flush();

            return $this->redirectToRoute('clasificaciontg_edit', array('id' => $clasificaciontg->getId()));
        }

        return $this->render('clasificaciontg/edit.html.twig', array(
            'clasificaciontg' => $clasificaciontg,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Clasificaciontg entity.
     *
     */
    public function deleteAction(Request $request, Clasificaciontg $clasificaciontg)
    {
        $form = $this->createDeleteForm($clasificaciontg);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clasificaciontg);
            $em->flush();
        }

        return $this->redirectToRoute('clasificaciontg_index');
    }

    /**
     * Creates a form to delete a Clasificaciontg entity.
     *
     * @param Clasificaciontg $clasificaciontg The Clasificaciontg entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Clasificaciontg $clasificaciontg)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('clasificaciontg_delete', array('id' => $clasificaciontg->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
