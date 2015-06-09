<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 19:00
 */

namespace Htc\NowTaxiBundle\Response;

use Htc\NowTaxiBundle\Entity\Order;

/**
 * Class NowTaxiResponse
 * @package Htc\NowTaxiBundle\Response
 */
class NowTaxiResponse
{
    /** Статус успешного выполнения операции */
    const OK = 200;
    const BAD_REQUEST = 400;
    /** Неправильный apikey */
    const FORBIDDEN = 403;
    const NOT_FOUND= 404;

    /** Некорректная секция с клиентом в запросе */
    const ERROR_CODE_BROKEN_CLIENT = 'BROKEN_CLIENT';
    /** Некорректный телефон в запросе */
    const ERROR_CODE_BROKEN_CLIENT_PHONE = 'BROKEN_CLIENT_PHONE';
    /** Некорректное имя клиента в запросе */
    const ERROR_CODE_BROKEN_CLIENT_NAME = 'BROKEN_CLIENT_NAME';
    /** Некорректный адрес подачи */
    const ERROR_CODE_BROKEN_FROM_TO = 'BROKEN_FROM_TO';
    /** Некорректное время подачи */
    const ERROR_CODE_BROKEN_BOOKING_TIME = 'BROKEN_BOOKING_TIME';
    /** Некорректные требования к заказу */
    const ERROR_CODE_BROKEN_REQUIREMENTS = 'BROKEN_REQUIREMENTS';
    /** Некорректная секция messenger */
    const ERROR_CODE_BROKEN_MESSENGER = 'BROKEN_MESSENGER';
    /** Некоректный тариф */
    const ERROR_CODE_BROKEN_TARIFFS = 'BROKEN_TARIFFS';
    /** Некорректная категория */
    const ERROR_CODE_BROKEN_CATEGORIES = 'BROKEN_CATEGORIES';
    /** Заказ (уже) существует */
    const ERROR_CODE_ORDER_EXISTS = 'ORDER_EXISTS';
    /** Некорректный ID заказа */
    const ERROR_CODE_BROKEN_ID = 'BROKEN_ID';
    /** Некорректный коментарий */
    const ERROR_CODE_BROKEN_COMMENT = 'BROKEN_COMMENT';
    /** Заказ не найден */
    const ERROR_CODE_ORDER_NOT_FOUND = 'ORDER_NOT_FOUND';
    /** Внутренняя ошибка сервера (прочие ошибки) */
    const ERROR_CODE_INTERNAL_ERROR = 'INTERNAL_ERROR';
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $message;
    /**
     * @var Order
     */
    public $order;
    /**
     * Расширенное описание ошибки: ERROR_CODE_*
     * @var string
     */
    public $code;
    /**
     * @var string|mixed
     */
    private $rawResponse;

    /**
     * @return mixed|string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param $rawResponse
     */
    public function setRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }
}
