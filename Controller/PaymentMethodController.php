<?php

namespace Flower\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\ModelBundle\Entity\Sales\PaymentMethod;
use Flower\SalesBundle\Form\Type\PaymentMethodType;
use Doctrine\ORM\QueryBuilder;

/**
 * PaymentMethod controller.
 *
 * @Route("/paymentmethod")
 */
class PaymentMethodController extends Controller
{
    /**
     * Lists all PaymentMethod entities.
     *
     * @Route("/", name="paymentmethod")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Sales\PaymentMethod')->createQueryBuilder('s');
        $this->addQueryBuilderSort($qb, 'paymentmethod');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a PaymentMethod entity.
     *
     * @Route("/{id}/show", name="paymentmethod_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(PaymentMethod $paymentmethod)
    {
        $editForm = $this->createForm(new PaymentMethodType(), $paymentmethod, array(
            'action' => $this->generateUrl('paymentmethod_update', array('id' => $paymentmethod->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($paymentmethod->getId(), 'paymentmethod_delete');

        return array(

        'paymentmethod' => $paymentmethod,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new PaymentMethod entity.
     *
     * @Route("/new", name="paymentmethod_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $paymentmethod = new PaymentMethod();
        $form = $this->createForm(new PaymentMethodType(), $paymentmethod);

        return array(
            'paymentmethod' => $paymentmethod,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new PaymentMethod entity.
     *
     * @Route("/create", name="paymentmethod_create")
     * @Method("POST")
     * @Template("FlowerCoreBundle:PaymentMethod:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $paymentmethod = new PaymentMethod();
        $form = $this->createForm(new PaymentMethodType(), $paymentmethod);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paymentmethod);
            $em->flush();

            return $this->redirect($this->generateUrl('paymentmethod_show', array('id' => $paymentmethod->getId())));
        }

        return array(
            'paymentmethod' => $paymentmethod,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PaymentMethod entity.
     *
     * @Route("/{id}/edit", name="paymentmethod_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(PaymentMethod $paymentmethod)
    {
        $editForm = $this->createForm(new PaymentMethodType(), $paymentmethod, array(
            'action' => $this->generateUrl('paymentmethod_update', array('id' => $paymentmethod->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($paymentmethod->getId(), 'paymentmethod_delete');

        return array(
            'paymentmethod' => $paymentmethod,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing PaymentMethod entity.
     *
     * @Route("/{id}/update", name="paymentmethod_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerCoreBundle:PaymentMethod:edit.html.twig")
     */
    public function updateAction(PaymentMethod $paymentmethod, Request $request)
    {
        $editForm = $this->createForm(new PaymentMethodType(), $paymentmethod, array(
            'action' => $this->generateUrl('paymentmethod_update', array('id' => $paymentmethod->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('paymentmethod_show', array('id' => $paymentmethod->getId())));
        }
        $deleteForm = $this->createDeleteForm($paymentmethod->getId(), 'paymentmethod_delete');

        return array(
            'paymentmethod' => $paymentmethod,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="paymentmethod_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('paymentmethod', $field, $type);

        return $this->redirect($this->generateUrl('paymentmethod'));
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
     * Deletes a PaymentMethod entity.
     *
     * @Route("/{id}/delete", name="paymentmethod_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(PaymentMethod $paymentmethod, Request $request)
    {
        $form = $this->createDeleteForm($paymentmethod->getId(), 'paymentmethod_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paymentmethod);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('paymentmethod'));
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
