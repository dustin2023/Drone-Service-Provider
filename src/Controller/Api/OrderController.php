<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Controller\Api;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Maurerd\Webentwicklung\Model\Entities\Order;
use Maurerd\Webentwicklung\Model\Repositories\OrderRepository;
use Maurerd\Webentwicklung\View\Json;

/**
 *
 */
class OrderController
{
    /**
     * @param ServerRequestInterface $request
     * @param array $arguments
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function show(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $orderRepository = new OrderRepository();

        try {
            $targetOrder = $orderRepository->findByUrlKey($arguments['titleUrlKey']);
        } catch (\Exception $e) {
            $targetOrder = new Order();
        }
        $response = new Response();
        $view = new Json();
        $response->getBody()->write(
            $view->render($targetOrder)
        );
        return $response;
    }

    /**
     * Renders a 404 error page
     *
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function notFound(): ResponseInterface
    {
        $response = new Response();
        $view = new Json();
        $response = $response->withStatus(404);
        $response->getBody()->write(
            $view->render(['error' => 'Not found'])
        );
        return $response;
    }

}