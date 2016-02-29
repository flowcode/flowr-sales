<?php

namespace Flower\SalesBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPExcel_Style_Fill;

/**
 * Description of AccountService
 *
 * @author Francisco Memoli <fmemoli@flowcode.com.ar>
 */
class SaleService
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(ContainerInterface $container = NULL)
    {
        $this->container = $container;
        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    /**
     * SaleDataExport() genera el contenido a ser exportado segun vista.
     *
     */
    public function saleDataExport($sales)
    {
        $data = array();
        $data["header"] =
            array("values" =>
                array(
                    "Numero de Venta",
                    "Cuenta",
                    "Creado Por",
                    "Total",
                    "IVA",
                    "Total con IVA",
                    'Forma de Pago',
                    'Calle',
                    'Número',
                    'Depto.',
                    'Partido/Barrio',
                    'Código Postal',
                    'Ciudad',
                    'Email',
                    'Teléfono',
                    'Nombre',
                    'Observaciones',
                    'Creado',
                    'Actualizado',),
                "styles" => array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'd22729')
                    )
                ));
        $subHeader = array("values" =>
            array(
                " ",
                "Producto",
                "Unidades",
                "Precio x Unidad",
                "Total"));
        $index = 1;
        foreach ($sales as $sale) {
            $id = $sale->getId() ?: " ";
            $account = $sale->getAccount() ?: " ";
            $owner = $sale->getOwner() ? $sale->getOwner()->getHappyName() : " ";
            $total = $sale->getTotal() ?: " ";
            $tax = $sale->getTax() ?: " ";
            $totalWithTax = $sale->getTotalWithTax() ?: " ";
            $paymentmethod = $sale->getPaymentMethod() ?: " ";
            $street = $sale->getStreet() ?: " ";
            $streetNumber = $sale->getStreetNumber() ?: " ";
            $department = $sale->getDepartment() ?: " ";
            $locality = $sale->getLocality() ?: " ";
            $zipCode = $sale->getZipCode() ?: " ";
            $city = $sale->getCity() ?: " ";


            $email = ($sale->getContact() && $sale->getContact()->getEmail()) ? $sale->getContact()->getEmail() : " ";
            $phone = ($sale->getContact() && $sale->getContact()->getPhone()) ? $sale->getContact()->getPhone() : " ";
            $nombre = ($sale->getContact() && $sale->getContact()->getHappyName()) ? $sale->getContact()->getHappyName() : " ";
            $observations = $sale->getObservations() ?: " ";
            $created = $sale->getCreated() ? $sale->getCreated()->format("d/m/y H:i") : " ";
            $updated = $sale->getUpdated() ? $sale->getUpdated()->format("d/m/y H:i") : " ";

            $data[$index++] =
                array("values" =>
                    array(
                        $id,
                        $account,
                        $owner,
                        $total,
                        $tax,
                        $totalWithTax,
                        $paymentmethod,
                        $street,
                        $streetNumber,
                        $department,
                        $locality,
                        $zipCode,
                        $city,
                        $email,
                        $phone,
                        $nombre,
                        $observations,
                        $created,
                        $updated
                    ),
                    "styles" => array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '929292')
                        )
                    ));
            $data[$index++] = $subHeader;
            foreach ($sale->getSaleItems() as $item) {

                $units = $item->getUnits() ?: " ";
                $unitPrice = $item->getUnitPrice() ?: " ";
                $product = $item->getProduct() ?: " ";
                $total = $item->getTotal() ?: " ";
                $data[$index++] =
                    array("values" =>
                        array(
                            " ",
                            $product,
                            $units,
                            $unitPrice,
                            $total
                        ));
            }

        }
        return $data;
    }
}