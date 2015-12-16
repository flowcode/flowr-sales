<?php

namespace Flower\SalesBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;

/**
 * PaymentMethod controller.
 */
class PaymentMethodController extends FOSRestController
{
    public function getAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentMethods = $em->getRepository('FlowerModelBundle:Sales\PaymentMethod')->findAll();

        $view = FOSView::create($paymentMethods, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }

    public function getByIdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentMethods = $em->getRepository('FlowerModelBundle:Sales\PaymentMethod')->find($id);

        $view = FOSView::create($paymentMethods, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }
}
