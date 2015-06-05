<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 09.03.15
 * Time: 22:23
 */

namespace Htc\NowTaxiBundle\Constant;

/**
 * Список индексов данных при передаче ответа позиции водителя
 *
 * Class DriverPositionIndexes
 * @package Htc\NowTaxiBundle\Constants
 */
final class DriverPositionIndex
{
    /** Идентификатор заказа */
    const ORDER_ID = 0;
    /** Долгота водителя, исполняющего заказ */
    const LONGITUDE = 1;
    /** Широта водителя, исполняющего заказ */
    const LATITUDE = 2;
    /** Полная дата/время точки */
    const POINT_TIME_STRING = 3;
    /** order.data - данные, привязанные при постинге заказа к заказу */
    const ORDER_DATA = 4;
    /** order.client.data - данные привязанные при постинге заказа к клиенту */
    const ORDER_CLIENT_DATA = 5;
}