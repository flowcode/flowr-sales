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

        $filters = array('accountFilter' => "a.id", 'ownerFilter' => 's.owner');

        if($request->query->has('reset')) {
            $request->getSession()->set('filter.sale', null);
            return $this->redirectToRoute("sale");
        }

        $this->saveFilters($request, $filters, 'sale','sale');
        $paginator = $this->filter($qb,'sale',$request);
        $accounts = $em->getRepository('FlowerModelBundle:Clients\Account')->findAll();
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
        
        return array(
            'users' => $users,
            'ownerFilter' => $ownerFilter,
            'paginator' => $paginator,
            'accountFilter' => $accountFilter,
            'accounts' => $accounts,
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
}
