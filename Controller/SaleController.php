<?php

namespace Flower\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\ModelBundle\Entity\Sales\Sale;
use Flower\SalesBundle\Form\Type\SaleType;
use Doctrine\ORM\QueryBuilder;

/**
 * Sale controller.
 *
 * @Route("/sale")
 */
class SaleController extends Controller
{
    /**
     * Lists all Sale entities.
     *
     * @Route("/", name="sale")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Sales\Sale')->createQueryBuilder('s');
        $this->addQueryBuilderSort($qb, 'sale');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Sale entity.
     *
     * @Route("/{id}/show", name="sale_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Sale $sale)
    {
        $editForm = $this->createForm(new SaleType(), $sale, array(
            'action' => $this->generateUrl('sale_update', array('id' => $sale->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($sale->getId(), 'sale_delete');

        return array(

        'sale' => $sale,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new Sale entity.
     *
     * @Route("/new", name="sale_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $sale = new Sale();
        $form = $this->createForm(new SaleType(), $sale);

        return array(
            'sale' => $sale,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Sale entity.
     *
     * @Route("/create", name="sale_create")
     * @Method("POST")
     * @Template("FlowerSalesBundle:Sale:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $sale = new Sale();
        $form = $this->createForm(new SaleType(), $sale);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sale);
            $em->flush();

            return $this->redirect($this->generateUrl('sale_show', array('id' => $sale->getId())));
        }

        return array(
            'sale' => $sale,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sale entity.
     *
     * @Route("/{id}/edit", name="sale_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Sale $sale)
    {
        $editForm = $this->createForm(new SaleType(), $sale, array(
            'action' => $this->generateUrl('sale_update', array('id' => $sale->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($sale->getId(), 'sale_delete');

        return array(
            'sale' => $sale,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Sale entity.
     *
     * @Route("/{id}/update", name="sale_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerSalesBundle:Sale:edit.html.twig")
     */
    public function updateAction(Sale $sale, Request $request)
    {
        $editForm = $this->createForm(new SaleType(), $sale, array(
            'action' => $this->generateUrl('sale_update', array('id' => $sale->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('sale_show', array('id' => $sale->getId())));
        }
        $deleteForm = $this->createDeleteForm($sale->getId(), 'sale_delete');

        return array(
            'sale' => $sale,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="sale_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('sale', $field, $type);

        return $this->redirect($this->generateUrl('sale'));
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
     * Deletes a Sale entity.
     *
     * @Route("/{id}/delete", name="sale_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Sale $sale, Request $request)
    {
        $form = $this->createDeleteForm($sale->getId(), 'sale_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sale);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sale'));
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
