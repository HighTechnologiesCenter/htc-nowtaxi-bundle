<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 09.03.15
 * Time: 20:53
 */

namespace Htc\NowTaxiBundle\Converter;


use Htc\NowTaxiBundle\Entity\Order;

/**
 * Class DefaultOrderConverter
 * @package Htc\NowTaxiBundle\Converter
 */
class DefaultOrderConverter extends AbstractOrderConverter
{

    /**
     * Конвертирует данные заказа в заказ NowTaxi
     *
     * @param mixed $data
     * @return Order
     */
    public function convert($data)
    {
        trigger_error("Direct data converting is not implemented in default order converter service!", E_WARNING);

        return new Order();
    }

}