<?php

namespace Flower\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SaleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SaleRepository extends EntityRepository
{
	public function getTopSalersByMonthBetweenTime($dateFrom = null, $dateTo = null)
	{
		$sql = 'SELECT count(sa.id) as count, sum(sa.total) as sum, us.username as username, us.id as userId FROM sale as sa'
					. ' INNER JOIN sale_status as ss ON sa.status = ss.id'
					. ' INNER JOIN users as us ON sa.user_id = us.id'
                    . ' WHERE ss.saleDeleted = 0 ';
        $data = array();
        $nextWhere = " and ";
        if($dateFrom){
            $data[] = $dateFrom->format('Y-m-d H:i:s');
            $sql .= $nextWhere.' sa.created > ? ';
            $nextWhere = " and ";
        }
        if($dateTo){
            $sql .= $nextWhere.' sa.created <= ? ';
            $data[] = $dateTo->format('Y-m-d H:i:s');
            $nextWhere = " and ";
        }            
        $sql .= ' GROUP BY us.id';
        $sql .= ' ORDER BY count DESC, sum DESC';
        $sql .= ' limit 10';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
	}

	public function getTopProductsByMonthByTime($dateFrom = null, $dateTo = null)
	{
		$sql = 'SELECT count(p.id) as count, sum(p.price) as sum, p.name as name, p.id as productId FROM sale as sa'
					. ' INNER JOIN sale_status as ss ON sa.status = ss.id'
					. ' INNER JOIN sale_item as si ON sa.id = si.sale'
					. ' INNER JOIN product as p ON p.id = si.product'
                    . ' WHERE ss.saleDeleted = 0 ';
        $data = array();
        $nextWhere = " and ";
        if($dateFrom){
            $data[] = $dateFrom->format('Y-m-d H:i:s');
            $sql .= $nextWhere.' sa.created > ? ';
            $nextWhere = " and ";
        }
        if($dateTo){
            $sql .= $nextWhere.' sa.created <= ? ';
            $data[] = $dateTo->format('Y-m-d H:i:s');
            $nextWhere = " and ";
        }            
        $sql .= ' GROUP BY MONTH(sa.created), p.id';
        $sql .= ' ORDER BY count DESC, sum DESC';
        $sql .= ' limit 10';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
	}
	public function getSumSalesByYear($year){
		$sql = 'SELECT sum(sa.total) as sum, MONTH(sa.created) as month FROM sale as sa'
					. ' INNER JOIN sale_status as ss ON sa.status = ss.id'
                    . ' WHERE ss.saleDeleted = 0 ';
        $data = array();

        $data[] = $year;
        $sql .= ' and YEAR(sa.created) = ? ';

        $sql .= ' GROUP BY MONTH(sa.created)';
        $sql .= ' ORDER BY MONTH(sa.created)';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
	}
}
