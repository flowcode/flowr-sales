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
use Ps\PdfBundle\Annotation\Pdf;
/**
 * Sale controller.
 *
 * @Route("/sale")
 */
class SaleController extends BaseController
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
        $qb->leftJoin("s.account","a");
        $qb->leftJoin("s.status","es");
        $translator = $this->get('translator');
        $dateformat = $translator->trans('fullDateTime');
        $filters = array('accountFilter' => "a.id",
                         'ownerFilter' => 's.owner',
                         'statusFilter' => 'es.id',
                         'startDateFilter' => array("field"=> "s.created", "type" => "date", "format" => $dateformat, "operation" => ">"),
                         'endDateFilter' => array("field"=> "s.created", "type" => "date","format" => $dateformat , "operation" => "<="));

        if($request->query->has('reset')) {
            $request->getSession()->set('filter.sale', null);
            return $this->redirectToRoute("sale");
        }

        $this->saveFilters($request, $filters, 'sale','sale');
        $paginator = $this->filter($qb,'sale',$request);
        $accounts = $em->getRepository('FlowerModelBundle:Clients\Account')->findAll();
        $status = $em->getRepository('FlowerModelBundle:Sales\SaleStatus')->findAll();
        $users = $em->getRepository('FlowerModelBundle:User\User')->findAll();
        $accountFilter = $request->query->get("accountFilter");
        $filters = $this->getFilters('sale');
        if(!$accountFilter && $filters['accountFilter'] && $filters['accountFilter']["value"]){
            $accountFilter = $filters['accountFilter']["value"];
        }
        $ownerFilter = $request->query->get("ownerFilter");
        if(!$ownerFilter && $filters['ownerFilter'] && $filters['ownerFilter']["value"]){
            $ownerFilter = $filters['ownerFilter']["value"];
        }
        $statusFilter = $request->query->get("statusFilter");
        if(!$statusFilter && $filters['statusFilter'] && $filters['statusFilter']["value"]){
            $statusFilter = $filters['statusFilter']["value"];
        }    
        return array(
            'startDateFilter' => isset($filters['startDateFilter'])?$filters['startDateFilter']["value"] : null,
            'endDateFilter' => isset($filters['endDateFilter'])?$filters['endDateFilter']["value"] : null,
            'users' => $users,
            'statuses' => $status,
            'ownerFilter' => $ownerFilter,
            'statusFilter' => $statusFilter,
            'paginator' => $paginator,
            'accountFilter' => $accountFilter,
            'accounts' => $accounts,
        );
    }

        /**
     *
     * @Route("/export", name="sale_export")
     * @Method("GET")
     */
    public function exportViewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Sales\Sale')->createQueryBuilder('s');
        $qb->leftJoin("s.account","a");

        $limit = 20;
        $currPage = $request->query->get('page');
        if($currPage){
            $sales = $this->filter($qb,'sale',$request, $limit, $currPage);
        } else {
            $sales = $this->filter($qb,'sale',$request, -1);
        }
        
        $data = $this->get("sales.service.sale")->saleDataExport($sales);
        $this->get("sales.service.excelexport")->exportData($data,"Ventas","Exportación de ventas.");
        die();
        return $this->redirectToRoute("sale");
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
    * @Pdf()
    * @Route("/print/{id}/pdf", name="sale_pdf_export", requirements={"id"="\d+"},  defaults={ "_format"="pdf" })
    * @Template()
    */
        public function printPDFAction(Sale $sale)
        {
            $em = $this->getDoctrine()->getManager();
            $paymentMethods = $em->getRepository('FlowerModelBundle:Sales\PaymentMethod')->findAll();
            return array(
            'sale' => $sale,
            'paymentMethods' => $paymentMethods,
            );
        }
}
