<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 09.03.15
 * Time: 20:36
 */

namespace Htc\NowTaxiBundle\Event;


use Htc\NowTaxiBundle\Entity\Order;
use Symfony\Component\EventDispatcher\Event;

/**
 * Событие заказа
 *
 * Class OrderStatusChangedEvent
 * @package Htc\NowTaxiBundle\Event
 */
class OrderEvent extends Event
{
    /**
     * @var Order
     */
    private $order;

    /**
     * Дополнительные данные
     *
     * @var mixed
     */
    private $data;

    /**
     * @param Order $order
     * @param null  $data
     */
    public function __construct(Order $order, $data = null)
    {
        $this->order = $order;
        $this->data = $data;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

}