<?php

namespace Flower\SalesBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;

/**
 * SaleStatus controller.
 */
class SaleStatusController extends FOSRestController
{
    public function getAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $saleStatuss = $em->getRepository('FlowerModelBundle:Sales\SaleStatus')->findAll();

        $view = FOSView::create($saleStatuss, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }

}
