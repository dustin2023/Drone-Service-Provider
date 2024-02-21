<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Controller;

use Maurerd\Webentwicklung\AuthenticationRequiredException;
use Maurerd\Webentwicklung\Http\Session;
use Maurerd\Webentwicklung\Model\Repositories\CategoryRepository;
use Maurerd\Webentwicklung\Model\Repositories\OrderRepository;
use Maurerd\Webentwicklung\View\HomePage as HomePageView;
use Maurerd\Webentwicklung\View\Package as PackagePriceView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;

class HomeController
{
    public function showHomepage(ServerRequestInterface $request): ResponseInterface
    {
        $session = new Session();
        $session->start();

        // get the current customer ID from the session
        if ($session->isLoggedIn()){
            $customerId = $session->getValue('customer_id');
        }
        $view = new HomePageView();
        $response = new Response();
        $response->getBody()->write($view->render());
        return $response;
    }

    public function showPackages(ServerRequestInterface $request): ResponseInterface
    {
        $session = new Session();
        $session->start();

        $categoryId = $session->getValue('category_id');
        $categoryRepository = new CategoryRepository();
        $targetOrder = $categoryRepository->findById($categoryId);

        $view = new PackagePriceView();
        $response = new Response();
        $response->getBody()->write($view->render( ['order' => $targetOrder]));
        return $response;
    }
}
