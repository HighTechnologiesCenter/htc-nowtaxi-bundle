<?php

namespace Htc\NowTaxiBundle\Controller;

use Htc\NowTaxiBundle\Entity\Order;
use Htc\NowTaxiBundle\Event\DriversPositionEvent;
use Htc\NowTaxiBundle\Event\Events;
use Htc\NowTaxiBundle\Event\OrderEvent;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderModificationController
 * @package Htc\NowTaxiBundle\Controller
 */
class OrderModificationController extends Controller
{
    /**
     * Изменяет статус заказа
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeOrderStatusAction(Request $request)
    {
        $this->debug($request);
        /** @var SerializerInterface $serializer */
        $serializer = $this->get('jms_serializer');
        /** @var Order $order */
        $order = $serializer->deserialize($request->getContent(), 'Htc\NowTaxiBundle\Entity\Order', 'json');

        $this->getEventDispatcherService()->dispatch(Events::ORDER_CHANGED, new OrderEvent($order));

        return new Response();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    private function debug(Request $request)
    {
        $logger = $this->getLogger();

        if ($logger instanceof LoggerInterface) {
            $logger->debug('Raw request content: ' . $request->getContent());
        }
    }

    /**
     * @return LoggerInterface|null
     */
    private function getLogger()
    {
        return $this->container->get('monolog.logger.htc_now_taxi', ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher
     */
    private function getEventDispatcherService()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Изменяет местоположение водителей на заказах
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeDriversPositionAction(Request $request)
    {
        $this->debug($request);
        $positions = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new Response('', 400);
        }

        $this->getEventDispatcherService()->dispatch(Events::DRIVERS_POSITION_CHANGED, new DriversPositionEvent($positions));

        return new Response();
    }
}
