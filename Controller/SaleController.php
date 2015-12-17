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
     * Displays a form to create a new Sale entity.
     *
     * @Route("/new", name="sale_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $sale = new Sale();
        return array(
            'sale' => $sale
        );
    }

    /**
     * Finds and displays a Sale entity.
     *
     * @Route("/{id}/show", name="sale_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template("FlowerSalesBundle:Sale:new.html.twig")
     */
    public function showAction(Sale $sale)
    {
        return array(
        'sale' => $sale,
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


}
