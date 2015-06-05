<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:53
 */

namespace Htc\NowTaxiBundle\Constant;

/**
 * Список статусов заказов
 *
 * Class OrderStatus
 * @package Htc\NowTaxiBundle\Constants
 */
final class OrderStatus
{
    /**
     * Заказ не может быть обработан по техническим причинам (например невозможно
     * вычислить координаты переданного адреса, либо не удалось найти подходящих водителей итп).
     * В этом случае будет передано дополнительное поле message в котором будет
     * содержаться описание причины ошибки.
     */
    const ERROR = 'error';
    /** Заказ прошел стадию геокодирования и выполняется подбор водителей */
    const REQUEST = 'request';
    /** Водитель или служба назначены на заказ */
    const CONFIRM = 'confirm';
    /** Водитель выехал на заказ */
    const DRIVING = 'driving';
    /** Водитель ожидает клиента */
    const WAITING = 'waiting';
    /** Водитель везет клиента */
    const TRANSPORTING = 'transporting';
    /** Клиент доставлен */
    const COMPLETE = 'complete';
    /** Отменен водителем */
    const CANCELLED = 'cancelled';
    /** Клиент отменил заказ */
    const CANCELLED_USER = "cancelled_user";
    /** Водитель сообщил о невозможности выполнить заказ (срыв) */
    const FAILED = 'failed';
    /** Никто не взял заказ в заданный диапазон времени */
    const TIMEOUT = 'timeout';
    /** Идет определение тарифов по категориям */
    const CATEGORIES = 'categories';
}