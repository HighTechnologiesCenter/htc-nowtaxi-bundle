<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 09.03.15
 * Time: 20:38
 */

namespace Htc\NowTaxiBundle\Event;

/**
 * Class Events
 * @package Htc\NowTaxiBundle\Event
 */
final class Events {

    /** Поджигается после создания заказа в NowTaxi */
    const ORDER_CREATED = 'htc_now_taxi.order_created';
    /** Поджигается при изменении статуса заказа */
    const ORDER_CHANGED = 'htc_now_taxi.order_changed';
    /** Поджигается после отмены заказа в NowTaxi */
    const ORDER_CANCELLED = 'htc_now_taxi.order_cancelled';
    /** Поджигается при изменении местоположения водителей */
    const DRIVERS_POSITION_CHANGED = 'htc_now_taxi.drivers_position_changed';

    public function __construct(){}
}
