<?php

namespace Flower\SalesBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;

/**
 * SaleCategory controller.
 */
class SaleCategoryController extends FOSRestController
{
    public function getAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $saleCategorys = $em->getRepository('FlowerModelBundle:Sales\SaleCategory')->findAll();

        $view = FOSView::create($saleCategorys, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }

}
