<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Controller;

use Cocur\Slugify\Slugify;
use Exception;
use JMS\Serializer\SerializerBuilder;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Maurerd\Webentwicklung\AuthenticationRequiredException;
use Maurerd\Webentwicklung\Model\Repositories\CategoryRepository;
use Maurerd\Webentwicklung\Model\Repositories\CustomerRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Maurerd\Webentwicklung\Http\Session;
use Maurerd\Webentwicklung\Model\Entities\Order;
use Maurerd\Webentwicklung\Model\Repositories\OrderRepository;
use Maurerd\Webentwicklung\View\BlogPost\Show as OrderView;
use Maurerd\Webentwicklung\View\BlogPost\Add as OrderAddView;

use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;


class OrderController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AuthenticationRequiredException
     */
    public function addForm(ServerRequestInterface $request): ResponseInterface
    {
        // check for a logged-in user and exit with an exception if none found
        $session = new Session();
        $session->start();
        if (!$session->isLoggedIn()) {
            throw new AuthenticationRequiredException();
        }
        $categoryType = $session->getValue('category_type');
        $packageType = $session->getValue('package');

        // check if the user is already on the form page and return the form without redirection
        if ($this->isOnFormPage($request, $categoryType, $packageType)) {
            $view = new OrderAddView();
            $response = new Response();
            $response->getBody()->write($view->render(['order' => $packageType]));
            return $response;
        }
        // redirect the user to the form page
        $formUrl = '/Auftrag/' . $categoryType->getCategoryType() . '/' . $packageType;
        $view = new OrderView();
        $response = new Response(status: 303, headers: ['Location' => $formUrl]);
        $response->getBody()->write($view->render(['order' => $packageType]));
        return $response;
    }

    /**
     * Check if the user is already on the form page.
     *
     * @param ServerRequestInterface $request
     * @param mixed $categoryType
     * @param mixed $packageType
     * @return bool
     */
    private function isOnFormPage(ServerRequestInterface $request, $categoryType, $packageType): bool
    {
        $currentUrl = $request->getUri()->getPath();
        $formUrl = '/Auftrag/' . $categoryType->getCategoryType() . '/' . $packageType;
        return $currentUrl === $formUrl;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AuthenticationRequiredException
     * @throws Exception
     */
    public function add(ServerRequestInterface $request): ResponseInterface
    {
        // check for a logged-in user and exit with an exception if none found
        $session = new Session();
        $session->start();
        $this->checkAuthentication($session);

        // get the current customer ID from the session
        $customerId = $session->getValue('customer_id');
        // check if customer ID is set in the session
        $this->checkCustomerId($customerId);

        $address = $request->getParsedBody()['address'];
        $description = $request->getParsedBody()['description'];
        $this->validateFields($address, $description);
        $this->saveFieldsToSession($session, $address, $description);

        // if we got a URL key we use it. If not we will generate one
        $urlKey = $this->getUrlKey($request, $address);
        $session->setValue('url_key', $urlKey);

        // redirecting to the newly order overview page
        $redirectUrl = '/Auftrag/Zusammenfassung/' . $urlKey;
        return $this->redirectTo($redirectUrl);
    }


    private function checkAuthentication(Session $session): void
    {
        if (!$session->isLoggedIn()) {
            throw new AuthenticationRequiredException();
        }
    }

    private function checkCustomerId($customerId): void
    {
        if (!$customerId) {
            throw new RuntimeException('Customer ID not found in session.');
        }
    }

    private function validateFields(string $address, string $description): void
    {
        Validator::allOf(Validator::notEmpty(), Validator::stringType())->check($address);
        Validator::allOf(Validator::notEmpty(), Validator::stringType())->check($description);
    }

    private function saveFieldsToSession(Session $session, string $address, string $description): void
    {
        $session->setValue('address', $address);
        $session->setValue('description', $description);
    }


    private function getUrlKey(ServerRequestInterface $request, string $address): string
    {
        $urlKey = $request->getParsedBody()['url_key'] ?? '';
        if ($urlKey) {
            return trim($urlKey);
        } else {
            $slugify = new Slugify();
            return $slugify->slugify($address);
        }
    }

    private function redirectTo(string $url): ResponseInterface
    {
        return new Response(
            status: 303,
            headers: ['Location' => $url]
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     */
    public function show(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $orderRepository = new OrderRepository();
        $targetOrder = $orderRepository->findByUrlKey($arguments['titleUrlKey']);

        if (!$targetOrder) {
            $targetOrder = new Order();
        } else {
            $targetOrder->setAddress(htmlspecialchars($targetOrder->getAddress()));
            $targetOrder->setCustomerId((int)htmlspecialchars((string)$targetOrder->getCustomerId()));
            $targetOrder->setDescription(htmlspecialchars($targetOrder->getDescription()));
        }

        // we need our view class here and get the output of its render method into our response object
        $view = new OrderView();
        $response = new Response();
        $response->getBody()->write(
            $view->render(
                ['order' => $targetOrder]
            )
        );
        return $response;
    }

    public function selectPackageForm(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $session = new Session();
        $session->start();


        $categoryType = $session->getValue('category_type');
        $package = $request->getParsedBody()['package'] ?? null;

        if (!$package) {
            throw new Exception('package not found in session.');
        }
        // set the package in the session
        $session->setValue('package', $package);

        //findPackageById()
        // redirecting to the add order page
        return new Response(
            status: 303,
            headers: ['Location' => ' /Auftrag/' . $categoryType->getCategoryType() . '/' . $package]
        );
    }


    public function selectCategoryForm(ServerRequestInterface $request): ResponseInterface
    {
        // check for a logged-in user and exit with an exception if none found
        $session = new Session();
        $session->start();

        $category_id = (int)($request->getParsedBody()['category_id'] ?? null);

        if (!$category_id) {
            throw new Exception('Category type not found in form data.');
        }
        // set category_id in session
        $session->setValue('category_id', $category_id);

        $categoryRepository = new CategoryRepository();
        $categoryType = $categoryRepository->findById($category_id);
        if (!$categoryType) {
            throw new Exception('Category type not found for the given category ID.');
        }
        $session->setValue('category_type', $categoryType);

        // redirecting to the select package page
        return new Response(
            status: 303,
            headers: ['Location' => ' /Auftrag/' . $categoryType->getCategoryType()]

        );
    }

    public function sendToDatabase(ServerRequestInterface $request): ResponseInterface
    {
        // check for a logged-in user and exit with an exception if none found
        $session = new Session();
        $session->start();
        if (!$session->isLoggedIn()) {
            throw new AuthenticationRequiredException();
        }


        // get the current customer ID from the session
        $customerId = $session->getValue('customer_id');
        $category_id = $session->getValue('category_id');
        $packageType = $session->getValue('package');
        $urlKey = $session->getValue('url_key');
        $address = $session->getValue('address');
        $description = $session->getValue('description');

        // check if customer ID is set in the session
        if (!$customerId) {
            throw new Exception('Customer ID not found in session.');
        }
        if (!$category_id) {
            throw new Exception('Category ID not found in session.');
        }
        $customer = new CustomerRepository();
        $customerUsername = $customer->getByCustomerId($customerId)->getUsername();

        $categoryRepo = new CategoryRepository();
        $category_type = $categoryRepo->findById($category_id)->getCategoryType();

        // create a database entity and fill it with our validated input
        $order = new Order();
        $order->setAddress($address);
        $order->setCustomerId($customerId);
        $order->setCategoryId($category_id);
        $order->setPackage($packageType);
        $order->setDescription($description);
        $order->setUrlKey($urlKey);

        // persist in the database using our repository
        $repository = new OrderRepository();
        $repository->add($order);

        $subject = 'Neuer Auftragseingang - Kunde: ' . $customerUsername . ' - Kategorie: ' . $category_type;
        $body = "Lieber Dustin,\n\nich möchte Ihnen gerne die Details eines neuen Auftrags zur Kenntnis bringen. Nachfolgend finden Sie eine Zusammenfassung der wichtigsten Informationen:\n\n"
            . "Kategorie: " . $category_type . "\nPaket: " . $packageType . "\nAdresse: " . $address . "\nBeschreibung: " . $description . "\nE-Mail-Kontakt: " . $customerUsername . "\n\n"
            . "Wir würden uns freuen, von Ihnen bald zu hören und bedanken uns im Voraus für Ihre Bemühungen.\n\n"
            . "Mit freundlichen Grüßen,\nIhr Drohnendienstleister";        // Senden der E-Mail
        $this->sendEmail($subject, $body);


        // redirecting to the newly created blog post
        setcookie("Show-Order-Success-Toast", "true", time() + 3, "/");
        header('Location: /');
        exit;
    }

    public function sendEmail($subject, $body)
    {
        //create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // configure SMTP settings
            $mail->isSMTP();
            $mail->Host = 'mail.gmx.net';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL'];
            $mail->Password = $_ENV['EMAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';


            // set the recipient, subject, and body of the message
            $mail->setFrom($_ENV['EMAIL'], 'Sender Name');
            $mail->addAddress($_ENV['EMAIL'], 'Recipient Name');
            $mail->Subject = $subject;
            $mail->Body = $body;

            // send the email
            $mail->send();
            echo 'Message sent successfully';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    }

    public function serializeMyEntity()
    {
        $serializer = SerializerBuilder::create()->build();

        $order = new Order();

        $json = $serializer->serialize($order, 'json');
        return new JsonResponse($json);
    }
}
