<?php

namespace Flower\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\ModelBundle\Entity\Sales\SaleCategory;
use Flower\SalesBundle\Form\Type\SaleCategoryType;
use Doctrine\ORM\QueryBuilder;

/**
 * Sales\SaleCategory controller.
 *
 * @Route("/admin/salecategory")
 */
class SaleCategoryController extends Controller
{
    /**
     * Lists all SaleCategory entities.
     *
     * @Route("/", name="admin_salecategory")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Sales\SaleCategory')->createQueryBuilder('s');
        $this->addQueryBuilderSort($qb, 'salecategory');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a SaleCategory entity.
     *
     * @Route("/{id}/show", name="admin_salecategory_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(SaleCategory $salecategory)
    {
        $editForm = $this->createForm(new SaleCategoryType(), $salecategory, array(
            'action' => $this->generateUrl('admin_salecategory_update', array('id' => $salecategory->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($salecategory->getId(), 'admin_salecategory_delete');

        return array(

        'salecategory' => $salecategory,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new SaleCategory entity.
     *
     * @Route("/new", name="admin_salecategory_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $salecategory = new SaleCategory();
        $form = $this->createForm(new SaleCategoryType(), $salecategory);

        return array(
            'salecategory' => $salecategory,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new SaleCategory entity.
     *
     * @Route("/create", name="admin_salecategory_create")
     * @Method("POST")
     * @Template("FlowerCoreBundle:SaleCategory:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $salecategory = new SaleCategory();
        $form = $this->createForm(new SaleCategoryType(), $salecategory);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salecategory);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_salecategory_show', array('id' => $salecategory->getId())));
        }

        return array(
            'salecategory' => $salecategory,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing SaleCategory entity.
     *
     * @Route("/{id}/update", name="admin_salecategory_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerCoreBundle:SaleCategory:edit.html.twig")
     */
    public function updateAction(SaleCategory $salecategory, Request $request)
    {
        $editForm = $this->createForm(new SaleCategoryType(), $salecategory, array(
            'action' => $this->generateUrl('admin_salecategory_update', array('id' => $salecategory->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_salecategory_show', array('id' => $salecategory->getId())));
        }
        $deleteForm = $this->createDeleteForm($salecategory->getId(), 'admin_salecategory_delete');

        return array(
            'salecategory' => $salecategory,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_salecategory_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('salecategory', $field, $type);

        return $this->redirect($this->generateUrl('admin_salecategory'));
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
     * Deletes a SaleCategory entity.
     *
     * @Route("/{id}/delete", name="admin_salecategory_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(SaleCategory $salecategory, Request $request)
    {
        $form = $this->createDeleteForm($salecategory->getId(), 'admin_salecategory_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salecategory);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_salecategory'));
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
