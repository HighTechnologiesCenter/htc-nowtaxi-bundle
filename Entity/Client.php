<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:34
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Данные о клиенте
 *
 * Class Client
 * @package Htc\NowTaxiBundle\Entity
 */
class Client
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $comment;

    /**
     * Поле произвольных данных (текстовая форма), привязанных к заказу.
     * На обратных запросах эти данные будут привязаны к заказу.
     *
     * @var string
     */
    public $data;
}
