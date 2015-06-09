<?php
/**
 * Created by PhpStorm.
 * User: mr
 * Date: 08.06.2015
 * Time: 12:01
 */

namespace Htc\NowTaxiBundle\Tests\API;

use Htc\NowTaxiBundle\Constant\OrderStatus;
use Htc\NowTaxiBundle\Entity\Order;
use Htc\NowTaxiBundle\Service\NowTaxiService;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class IntegrationTest
 * @package Htc\NowTaxiBundle\Tests\API
 */
class IntegrationTest extends KernelTestCase
{

    /** @var  ContainerInterface */
    private $container;

    /** @var  NowTaxiService */
    private $nowTaxiService;

    /** @var  Serializer */
    private $serializer;

    /**
     *
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->nowTaxiService = $this->container->get('htc_now_taxi.order_management.service');
        $this->serializer = $this->container->get('jms_serializer');
    }

    /**
     * Тест создания заказа и получения информации о нем
     */
    public function testPutOrderAndGetOrder()
    {
        $order = $this->nowTaxiService->putOrder($this->createOrder());
        sleep(2);
        $order = $this->nowTaxiService->getOrder($order->id);
        $this->assertTrue($order instanceof Order);
        $this->assertTrue($order->status == OrderStatus::REQUEST);
    }

    /**
     * Тест отмены заказа и его оценки
     */
    public function testCancelOrderAndRateOrder()
    {
        $order = $this->nowTaxiService->putOrder($this->createOrder());
        sleep(3);
        $this->assertTrue($order instanceof Order);
        sleep(3);
        $this->assertTrue($this->nowTaxiService->cancelOrder($order->id, 'Client cancelled an order'));

        try {
            $this->nowTaxiService->rateOrder($order->id, 4, 'All is well');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Htc\NowTaxiBundle\Exception\NotFoundException', $e, 'Exception must be an instance of');
        }
    }

    /**
     * @return \Htc\NowTaxiBundle\Entity\Order
     */
    private function createOrder()
    {
        $date = (new \DateTime())->modify("+5.5 hours")->format('Y-m-d H:i:s O');
        $orderJson = '{
        "id": "3352",
        "from": {
        "address": "Россия, Московская область, аэропорт Шереметьево",
        "lon": 37.412438,
        "lat": 55.973367
        },
        "to": {
        "address": "Россия, Москва, улица 8 Марта, 9с3",
        "porch": 0,
        "lon": 37.548836,
        "lat": 55.802555
        },
        "client": {
        "name": "AerotaxiUser",
        "phone": "+79111111111",
        "comment": "Комментарий",
        "data": "Данные заказа"
        },
        "booking_time": "' . $date . '",
        "messenger": {
        "category": [
        "standard"
        ],
        "minprice": 800,
        "inctime": 180,
        "transfer": 1,
        "algorithm": "max",
        "time_free": 30,
        "time_cost_wait": 10
        },
        "requirement": {
        "child_chair": 0,
        "universal": 0
        },
        "fee": {
        "fix": 0,
        "relative": 0
        },
        "data": "Данные заказа"
        }';

        /** @var Order $order */
        $order = $this->serializer->deserialize(
            $orderJson,
            'Htc\NowTaxiBundle\Entity\Order',
            'json'
        );

        return $order;
    }

}