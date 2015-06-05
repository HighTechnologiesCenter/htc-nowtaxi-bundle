<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 18:27
 */

namespace Htc\NowTaxiBundle\Converter;

use Htc\NowTaxiBundle\Entity\Order;

/**
 * Интерфейс конвертера заказов
 *
 * Interface OrderConverterInterface
 * @package Htc\NowTaxiBundle\Converter
 */
interface OrderConverterInterface
{
    /**
     * Конвертирует данные заказа в заказ NowTaxi
     *
     * @param mixed $data
     * @return Order
     */
    public function convert($data);

}