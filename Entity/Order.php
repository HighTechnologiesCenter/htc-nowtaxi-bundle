<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:29
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Class Order
 * @package Htc\NowTaxiBundle\Entity
 */
class Order
{

    /**
     * Идентификатор заказа КЛИЕНТА
     *
     * @var string
     */
    public $id;

    /**
     * Адрес откуда ехать (подача)
     *
     * @var Place
     */
    public $from;

    /**
     * Адрес назначения (финиш), может не указываться целиком.
     * В противном случае должен быть корректно заполнен
     *
     * @var Place
     */
    public $to;

    /**
     * @var Client
     */
    public $client;

    /**
     * Время бронирования
     *
     * @var \DateTime
     */
    public $bookingTime;

    /**
     * Статус заказа (актуально только для обратных запросов)
     *
     * @var string
     */
    public $status;

    /**
     * Уточнения к тарифу и основной тариф
     *
     * @var OrderProperties
     */
    public $orderProperties;

    /**
     * Перечень требований к водителю/машине.
     * Может отсутствовать. Требования не обязательные передавать не нужно.
     *
     * @var Requirements
     */
    public $requirements;

    /**
     * Информация о плате за заказ
     *
     * @var Fee
     */
    public $fee;

    /**
     * Произвольное поле с данными привязанными к заказу
     *
     * @var string
     */
    public $data;

    /**
     * (актуально только при статусе ‘complete’)
     *
     * @var integer
     */
    public $payment;

    /**
     * Коментарий службы или водителя при отмене заказа (cancelled) или при срыве заказа (failed)
     *
     * @var string
     */
    public $comment;

    /**
     * Хеш описывающий водителя, назначенного на заказ. Может приходить в статусах
     * confirm и driving. Переназначение водителя всегда возвращает к статусу confirm или driving.
     *
     * @var Driver
     */
    public $driver;

    /**
     * Хеш с описанием службы выполняющей заказ. Появляется вместе с driver.
     *
     * @var Service
     */
    public $service;

    /**
     * Описание ошибки для статуса OrderStatus::ERROR
     *
     * @var string
     */
    public $message;

    /**
     * Список тэгов
     *
     * @var array
     */
    public $tags;

}