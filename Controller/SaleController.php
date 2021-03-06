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
        $saleAlias = "s";
        $accountAlias = "a";
        $qb = $em->getRepository('FlowerModelBundle:Sales\Sale')->createQueryBuilder($saleAlias);
        $qb->leftJoin("s.owner", "u");
        $qb->leftJoin("s.account", $accountAlias);
        $qb->leftJoin("s.status", "es");
        /* filter by org security groups */
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $secGroupSrv = $this->get('user.service.securitygroup');
            $qb = $secGroupSrv->addLowerSecurityGroupsFilter($qb, $this->getUser(), $accountAlias);
        }
        $translator = $this->get('translator');
        $dateformat = $translator->trans('fullDateTime');
        $filters = array('accountFilter' => "a.id",
            'ownerFilter' => 's.owner',
            'statusFilter' => 'es.id',
            'startDateFilter' => array("field" => "s.created", "type" => "date", "format" => $dateformat, "operation" => ">"),
            'endDateFilter' => array("field" => "s.created", "type" => "date", "format" => $dateformat, "operation" => "<="));

        if ($request->query->has('reset')) {
            $request->getSession()->set('filter.sale', null);
            $request->getSession()->set('sort.sale', null);
            return $this->redirectToRoute("sale");
        }

        $this->saveFilters($request, $filters, 'sale', 'sale');
        $qb->andWhere("es.saleDeleted = 0 ");
        $paginator = $this->filter($qb, 'sale', $request);

        $accounts = $this->get("client.service.account")->findAll();
        $status = $em->getRepository('FlowerModelBundle:Sales\SaleStatus')->findBy(array("saleDeleted" => false));
        $users = $em->getRepository('FlowerModelBundle:User\User')->findAll();
        $accountFilter = $request->query->get("accountFilter");
        $filters = $this->getFilters('sale');
        if (!$accountFilter && $filters['accountFilter'] && $filters['accountFilter']["value"]) {
            $accountFilter = $filters['accountFilter']["value"];
        }
        $ownerFilter = $request->query->get("ownerFilter");
        if (!$ownerFilter && $filters['ownerFilter'] && $filters['ownerFilter']["value"]) {
            $ownerFilter = $filters['ownerFilter']["value"];
        }
        $statusFilter = $request->query->get("statusFilter");
        if (!$statusFilter && $filters['statusFilter'] && $filters['statusFilter']["value"]) {
            $statusFilter = $filters['statusFilter']["value"];
        }
        return array(
            'startDateFilter' => isset($filters['startDateFilter']) ? $filters['startDateFilter']["value"] : null,
            'endDateFilter' => isset($filters['endDateFilter']) ? $filters['endDateFilter']["value"] : null,
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
     * @Route("/stadistics", name="sale_stadistics")
     * @Method("GET")
     * @Template()
     */
    public function stadisticsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('FlowerModelBundle:Sales\Sale');
        $topSalers = $repository->getTopSalersByMonthBetweenTime();
        $topProducts = $repository->getTopProductsByMonthByTime();
        $thisYear = date("Y");
        $lastYear = date("Y", strtotime("-1 year"));
        $salesThisYear = $repository->getSumSalesByYear(date("Y"));
        $salesLastYear = $repository->getSumSalesByYear(date("Y", strtotime("-1 year")));
        $precessedSalesThisYear = array();
        for ($i = 1; $i <= 12; $i++) {
            $data = array("month" => $i, "sales" => 0);
            foreach ($salesThisYear as $sale) {
                if ($sale["month"] == $i) {
                    $data = array("month" => $i, "sales" => $sale["sum"]);
                }
            }
            $precessedSalesThisYear[] = $data;
        }
        $precessedSalesLastYear = array();
        for ($i = 1; $i <= 12; $i++) {
            $data = array("month" => $i, "sales" => 0);
            foreach ($salesLastYear as $sale) {
                if ($sale["month"] == $i) {
                    $data = array("month" => $i, "sales" => $sale["sum"]);
                }
            }
            $precessedSalesLastYear[] = $data;
        }

        //top 10 vendedores del mes
        //comparativa ventas anual mes x mes.
        //top 10 productos mas vendidos
        return array(
            "topSalers" => $topSalers,
            "topProducts" => $topProducts,
            "salesLastYear" => $precessedSalesLastYear,
            "salesThisYear" => $precessedSalesThisYear,
            "thisYear" => $thisYear,
            "lastYear" => $lastYear,
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
        $qb->leftJoin("s.account", "a");

        $limit = 20;
        $currPage = $request->query->get('page');
        if ($currPage) {
            $sales = $this->filter($qb, 'sale', $request, $limit, $currPage);
        } else {
            $sales = $this->filter($qb, 'sale', $request, -1);
        }

        $data = $this->get("sales.service.sale")->saleDataExport($sales);
        $this->get("sales.service.excelexport")->exportData($data, "Ventas", "Exportación de ventas.");
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
     * Finds and displays a Sale entity.
     *
     * @Route("/{id}/display", name="sale_display", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template("FlowerSalesBundle:Sale:display.html.twig")
     */
    public function displayAction(Sale $sale)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentMethods = $em->getRepository('FlowerModelBundle:Sales\PaymentMethod')->findAll();
        return array(
            'sale' => $sale,
            'paymentMethods' => $paymentMethods,
        );
    }

    /**
     * Finds and displays a Sale entity.
     *
     * @Route("/{id}/change_status", name="sale_change_status", requirements={"id"="\d+"})
     * @Method("POST")
     * @Template("FlowerSalesBundle:Sale:display.html.twig")
     */
    public function changeStatusAction(Sale $sale, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $statusTo = $em->getRepository('FlowerModelBundle:Sales\SaleStatus')->find($request->get('status_to'));

        $sale->setStatus($statusTo);
        $em->flush();

        return $this->redirect($this->generateUrl('sale_display', array('id' => $sale->getId())));
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
