<?php

namespace Flower\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\ModelBundle\Entity\Sales\SaleStatus;
use Flower\SalesBundle\Form\Type\SaleStatusType;
use Doctrine\ORM\QueryBuilder;

/**
 * SaleStatus controller.
 *
 * @Route("/admin/salesstatus")
 */
class SaleStatusController extends Controller
{
    /**
     * Lists all SaleStatus entities.
     *
     * @Route("/", name="admin_salesstatus")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Sales\SaleStatus')->createQueryBuilder('s');
        $this->addQueryBuilderSort($qb, 'salestatus');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a SaleStatus entity.
     *
     * @Route("/{id}/show", name="admin_salesstatus_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(SaleStatus $salestatus)
    {
        $editForm = $this->createForm(new SaleStatusType(), $salestatus, array(
            'action' => $this->generateUrl('admin_salesstatus_update', array('id' => $salestatus->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($salestatus->getId(), 'admin_salesstatus_delete');

        return array(

        'salestatus' => $salestatus,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new SaleStatus entity.
     *
     * @Route("/new", name="admin_salesstatus_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $salestatus = new SaleStatus();
        $form = $this->createForm(new SaleStatusType(), $salestatus);

        return array(
            'salestatus' => $salestatus,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new SaleStatus entity.
     *
     * @Route("/create", name="admin_salesstatus_create")
     * @Method("POST")
     * @Template("FlowerCoreBundle:SaleStatus:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $salestatus = new SaleStatus();
        $form = $this->createForm(new SaleStatusType(), $salestatus);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salestatus);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_salesstatus_show', array('id' => $salestatus->getId())));
        }

        return array(
            'salestatus' => $salestatus,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SaleStatus entity.
     *
     * @Route("/{id}/edit", name="admin_salesstatus_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(SaleStatus $salestatus)
    {
        $editForm = $this->createForm(new SaleStatusType(), $salestatus, array(
            'action' => $this->generateUrl('admin_salesstatus_update', array('id' => $salestatus->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($salestatus->getId(), 'admin_salesstatus_delete');

        return array(
            'salestatus' => $salestatus,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing SaleStatus entity.
     *
     * @Route("/{id}/update", name="admin_salesstatus_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerCoreBundle:SaleStatus:edit.html.twig")
     */
    public function updateAction(SaleStatus $salestatus, Request $request)
    {
        $editForm = $this->createForm(new SaleStatusType(), $salestatus, array(
            'action' => $this->generateUrl('admin_salesstatus_update', array('id' => $salestatus->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_salesstatus_show', array('id' => $salestatus->getId())));
        }
        $deleteForm = $this->createDeleteForm($salestatus->getId(), 'admin_salesstatus_delete');

        return array(
            'salestatus' => $salestatus,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_salesstatus_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('salestatus', $field, $type);

        return $this->redirect($this->generateUrl('admin_salesstatus'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a SaleStatus entity.
     *
     * @Route("/{id}/delete", name="admin_salesstatus_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(SaleStatus $salestatus, Request $request)
    {
        $form = $this->createDeleteForm($salestatus->getId(), 'admin_salesstatus_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salestatus);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_salesstatus'));
    }

    /**
     * Create Delete form
     *
     * @param integer                       $id
     * @param string                        $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
