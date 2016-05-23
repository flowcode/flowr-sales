<?php

namespace Flower\SalesBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;

use Flower\ModelBundle\Entity\Sales\Sale;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\Form\Form;

/**
 * Sale controller.
 *
 */
class SaleController extends FOSRestController
{

    public function updateAction(Request $request, Sale $sale)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm($this->get("form.api.sale"), $sale, array(
            'method' => 'PUT',
        ));
        $this->removeExtraFields($request, $form);
        $form->submit($request);
        if ($form->isValid()) {

            foreach ($sale->getSaleItems() as $item) {
                if ((!$item->getProduct() && !$item->getService()) || $item->getUnits() == 0) {
                    $sale->removeSaleItem($item);
                    $item->setSale(null);
                    $em->remove($item);
                } else {

                    /* Stock management */
                    if ($sale->getStatus()->isStockModifier()) {

                        if ($item->getProduct()) {
                            $stockService = $this->get('flower.stock.service.stock');
                            $stockService->decreaseProduct($item->getProduct(), $item->getUnits(), $sale);
                        }
                    }

                    $item->setSale($sale);
                }
            }
            $em->flush();


            $response = array("success" => true, "message" => "Sale created", "entity" => $sale);
            return $this->handleView(FOSView::create($response, Codes::HTTP_OK)->setFormat("json"));
        }
        $response = array('success' => false, 'errors' => $form->getErrors());
        return $this->handleView(FOSView::create($response, Codes::HTTP_NOT_FOUND)->setFormat("json"));
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sale = new Sale();
        $form = $this->createForm($this->get("form.api.sale"), $sale);
        $this->removeExtraFields($request, $form);
        $form->submit($request);
        if ($form->isValid()) {
            $sale->setDate(new \DateTime());
            $sale->setOwner($this->getUser());
            foreach ($sale->getSaleItems() as $item) {
                $item->setSale($sale);
            }
            $em->persist($sale);
            $em->flush();
            $response = array("success" => true, "message" => "Sale created", "entity" => $sale);
            return $this->handleView(FOSView::create($response, Codes::HTTP_OK)->setFormat("json"));
        }
        $response = array('success' => false, 'errors' => $form->getErrors());
        return $this->handleView(FOSView::create($response, Codes::HTTP_NOT_FOUND)->setFormat("json"));
    }

    private function removeExtraFields(Request $request, Form $form)
    {
        $data = $request->request->all();
        $children = $form->all();
        $data = array_intersect_key($data, $children);
        $request->request->replace($data);
    }

    public function getAction(Request $request, Sale $sale)
    {
        $view = FOSView::create($sale, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }
}
