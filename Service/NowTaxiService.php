<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 07.03.15
 * Time: 19:44
 */

namespace Htc\NowTaxiBundle\Service;

use Htc\NowTaxiBundle\Converter\OrderConverterInterface;
use Htc\NowTaxiBundle\Entity\Order;
use Htc\NowTaxiBundle\Event\Events;
use Htc\NowTaxiBundle\Event\OrderEvent;
use Htc\NowTaxiBundle\Exception\AuthenticationException;
use Htc\NowTaxiBundle\Exception\ApiConnectionException;
use Htc\NowTaxiBundle\Exception\BadRequestException;
use Htc\NowTaxiBundle\Exception\NotFoundException;
use Htc\NowTaxiBundle\Response\NowTaxiResponse;
use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;
use Httpful\Response;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Сервис для работы с заказами через апи сервиса nowtaxi.ru
 * @link http://doc.nowtaxi.ru/api/exchange
 *
 * Class NowTaxiService
 * @package Htc\NowTaxiBundle\Service
 */
class NowTaxiService
{
    /**
     * Ключ к API
     *
     * @var string
     */
    private $apiKey;

    /**
     * API хост
     *
     * @var string
     */
    private $apiHost;

    /**
     * @var OrderConverterInterface
     */
    private $converter;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Если true, сервис будет выбрасывать исключения при работе
     *
     * @var bool
     */
    private $throwExceptions;

    /**
     * @param                          $apiKey
     * @param                          $apiHost
     * @param OrderConverterInterface  $converter
     * @param SerializerInterface      $serializer
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface          $logger
     * @param bool                     $throwExceptions
     */
    public function __construct(
        $apiKey,
        $apiHost,
        OrderConverterInterface $converter,
        SerializerInterface $serializer,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger = null,
        $throwExceptions = true
    ) {
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;
        $this->converter = $converter;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->throwExceptions = $throwExceptions;
    }

    /**
     * Возвращает список тарифов в виде массива
     * @link http://doc.nowtaxi.ru/api/exchange#apiapikeytarifflist
     *
     * @return array
     * @throws ApiConnectionException
     * @throws BadRequestException
     * @throws AuthenticationException
     * @throws NotFoundException
     */
    public function tariffListAsArray()
    {
        $url = $this->buildUrl('/tariff/list');

        $request =
            // Build a GET request...
            Request::get($url)
                ->expectsJson();

        $httpfulResponse = $this->sendRequest($request);
        $this->checkForErrors($httpfulResponse);

        return json_decode($httpfulResponse->raw_body, true);
    }

    /**
     * Создает заказ
     * @link http://doc.nowtaxi.ru/api/exchange#apiapikeyorderput
     *
     * @param mixed $orderData
     * @param mixed $eventData
     *
     * @return Order
     * @throws ApiConnectionException
     * @throws BadRequestException
     * @throws AuthenticationException
     * @throws NotFoundException
     */
    public function putOrder($orderData, $eventData = null)
    {
        if (!$orderData instanceof Order) {
            $order = $this->converter->convert($orderData);
        } else {
            $order = $orderData;
        }

        $orderJson = $this->serializer->serialize($order, 'json');

        $url = $this->buildUrl('/order/put');

        $request = $this->createPostJsonRequest($url, $orderJson);
        $this->debug('NowTaxi order creation', ['json' => $orderJson]);
        $httpfulResponse = $this->sendRequest($request);
        $this->checkForErrors($httpfulResponse);

        $response = $this->serializer->deserialize(
            $httpfulResponse->__toString(),
            'Htc\NowTaxiBundle\Response\NowTaxiResponse',
            'json'
        );

        if ($response instanceof NowTaxiResponse && $response->order instanceof Order) {
            $order->id = $response->order->id;
            $order->status = $response->order->status;
        }

        $this->eventDispatcher->dispatch(Events::ORDER_CREATED, new OrderEvent($order, $eventData));

        return $order;
    }

    /**
     * Отменяет заказ
     * @link http://doc.nowtaxi.ru/api/exchange#apiapikeyordercancel
     *
     * @param string $id
     * @param string $comment
     * @param null|mixed $eventData
     *
     * @return bool
     * @throws ApiConnectionException
     * @throws BadRequestException
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws \InvalidArgumentException
     */
    public function cancelOrder($id, $comment, $eventData = null)
    {
        $this->executeCheck(
            function () use ($id, $comment) {
                if (empty($id) || empty($comment)) {
                    throw new \InvalidArgumentException('Id and comment are mandatory parameters and can not be empty');
                }
            }
        );

        $data['id'] = $id;
        $data['comment'] = $comment;

        $url = $this->buildUrl('/order/cancel');

        $request = $this->createPostJsonRequest($url, json_encode($data));
        $httpfulResponse = $this->sendRequest($request);
        $this->checkForErrors($httpfulResponse, sprintf('Order can not be cancelled. It already done, already cancelled or not found. Id: %s', $id));

        $order = new Order();
        $order->id = $id;
        $order->comment = $comment;

        $this->eventDispatcher->dispatch(Events::ORDER_CANCELLED, new OrderEvent($order, $eventData));

        return true;
    }

    /**
     * Возвращает заказ по его идентификатору
     * @link http://doc.nowtaxi.ru/api/exchange#apiapikeyorderget
     *
     * @param $id
     *
     * @return Order
     * @throws ApiConnectionException
     */
    public function getOrder($id)
    {
        $this->executeCheck(
            function () use ($id) {
                if (empty($id)) {
                    throw new \InvalidArgumentException('Id is mandatory parameter and can not be empty');
                }
            }
        );

        $data['id'] = $id;

        $url = $this->buildUrl('/order/get');

        $request = $this->createPostJsonRequest($url, json_encode($data));
        $httpfulResponse = $this->sendRequest($request);
        $this->checkForErrors($httpfulResponse, sprintf('Order not found. Id: %s', $id));

        $response = $this->serializer->deserialize(
            $httpfulResponse->__toString(),
            'Htc\NowTaxiBundle\Response\NowTaxiResponse',
            'json'
        );

        $order = $response->order;

        return $order;
    }

    /**
     * Оценивает заказ
     * @link http://doc.nowtaxi.ru/api/exchange#apiapikeyorderrate
     *
     * @param      $id
     * @param      $rating
     * @param null $comment
     *
     * @return bool
     * @throws ApiConnectionException
     * @throws BadRequestException
     * @throws AuthenticationException
     * @throws NotFoundException
     */
    public function rateOrder($id, $rating, $comment = null)
    {
        $this->executeCheck(
            function () use ($id, $rating) {
                if (empty($id) || is_int($rating)) {
                    throw new \InvalidArgumentException('Id and rating are mandatory parameters and can not be empty. Rating must be an integer.');
                }
            }
        );

        $data['id'] = $id;
        $data['rating'] = $rating;

        if (is_string($comment)){
            $data['comment'] = $comment;
        }

        $url = $this->buildUrl('/order/rate');

        $request = $this->createPostJsonRequest($url, json_encode($data));
        $httpfulResponse = $this->sendRequest($request);
        $this->checkForErrors($httpfulResponse, sprintf('Order not found or order status is not "complete", "cancelled" or "failed". Id: %s', $id));

        return true;
    }

    /**
     * @return string
     */
    private function getApiBasePath()
    {
        return $this->apiHost . '/api/' . $this->apiKey;
    }

    /**
     * @param $path
     * @return string
     */
    private function buildUrl($path)
    {
        return $this->getApiBasePath() . $path;
    }

    /**
     * Выполняет отправку запроса
     *
     * @param Request $request
     *
     * @return \Httpful\Response|null
     * @throws ApiConnectionException
     */
    private function sendRequest(Request $request)
    {
        $response = null;
        try {
            // and finally, fire that thing off!
            $response = $request->send();
        } catch(ConnectionErrorException $e){
            $this->handleException($e, 'Error while sending request');
        }

        return $response;
    }

    /**
     * Проверяет наличие ошибок
     *
     * @param Response $httpfulResponse
     * @param string   $notFoundInfo
     *
     * @throws BadRequestException
     * @throws AuthenticationException
     * @throws NotFoundException
     */
    private function checkForErrors(Response $httpfulResponse, $notFoundInfo = '')
    {
        try {
            switch ($httpfulResponse->code) {
                case 400:
                    throw new BadRequestException($httpfulResponse);
                    break;
                case 403:
                    throw new AuthenticationException('Invalid api key: %s', $this->apiKey);
                    break;
                case 404:
                    throw new NotFoundException($notFoundInfo);
                    break;
            }
        } catch (\Exception $e) {
            $this->handleException($e, 'Error in response detected!');
        }
    }

    /**
     * @param $url
     * @param $json
     *
     * @return Request
     */
    private function createPostJsonRequest($url, $json)
    {
        $this->debug('NowTaxi POST request to URL: ' . $url, json_decode($json, true));
        // Build a POST request...
        return Request::post($url)
            // tell it we're sending (Content-Type) JSON...
            ->sendsJson()
            // attach a body/payload...
            ->body($json)
            ->expectsJson();
    }

    /**
     * Logs an exception.
     *
     * @param \Exception $e The original \Exception instance
     * @param string     $message   The error message to log
     * @param bool       $original  False when the handling of the exception thrown another exception
     */
    protected function logException(\Exception $e, $message, $original = true)
    {
        $isCritical = !$e instanceof HttpExceptionInterface || $e->getStatusCode() >= 500;
        $context = array('exception' => $e);
        if (null !== $this->logger) {
            if ($isCritical) {
                $this->logger->critical($message, $context);
            } else {
                $this->logger->error($message, $context);
            }
        } elseif (!$original || $isCritical) {
            error_log($message);
        }
    }

    /**
     * Обрабатывает исключение
     *
     * @param \Exception $e
     * @param string     $message
     *
     * @throws \Exception
     */
    private function handleException(\Exception $e, $message = '')
    {
        if ($this->throwExceptions) {
            throw $e;
        } else {
            $this->logException($e, $message);
        }
    }

    /**
     * @param callable $checkFn
     */
    private function executeCheck(\Closure $checkFn)
    {
        try{
            $checkFn();
        }catch(\Exception $e){
            $this->handleException($e);
        }
    }

    /**
     * @param       $message
     * @param array $context
     */
    private function debug($message, array $context = [])
    {
        if (null !== $this->logger) {
            $this->logger->debug($message, $context);
        }
    }
}