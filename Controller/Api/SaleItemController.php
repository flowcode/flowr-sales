<?php

namespace Flower\SalesBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;

/**
 * SaleItem controller.
 */
class SaleItemController extends FOSRestController
{
    public function getAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $saleItems = $em->getRepository('FlowerModelBundle:Sales\SaleItem')->findAll();

        $view = FOSView::create($saleItems, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }

    public function getByIdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $saleItems = $em->getRepository('FlowerModelBundle:Sales\SaleItem')->find($id);

        $view = FOSView::create($saleItems, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }
}
