<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Controller;

use Exception;
use Laminas\Diactoros\Response;
use Maurerd\Webentwicklung\Model\EnumCustomerType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Maurerd\Webentwicklung\Http\Session;
use Maurerd\Webentwicklung\Model\Entities\Customer;
use Maurerd\Webentwicklung\Model\Repositories\CustomerRepository;
use Maurerd\Webentwicklung\View\Auth\Login as LoginView;
use Maurerd\Webentwicklung\View\Auth\Register as RegisterView;


/**
 * A basic controller for handling login/logout functionality
 */
class AuthController
{
    /**
     * @var Session
     */
    protected Session $session;

    /**
     *
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->session->start();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function loginForm(ServerRequestInterface $request): ResponseInterface
    {
        // render the form
        $view = new LoginView();
        $response = new Response();
        $response->getBody()->write($view->render([]));
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function registerForm(ServerRequestInterface $request): ResponseInterface
    {
        // render the form
        $view = new RegisterView();
        $response = new Response();
        $response->getBody()->write($view->render([]));
        return $response;
    }



    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->validateUsernamePassword($body);

        $customerRepository = new CustomerRepository();
        // test if user exists
        $user = $customerRepository->getByUsername($body['username']);

        if (!$user instanceof Customer) {
            return $this->loginFailedResponse();
        }

        $password = $body['password'];
        $hash = $user->getPassword();

        // test if the password is correct
        if (!password_verify($password, $hash)) {
            return $this->loginFailedResponse();
        }

        // test if the password needs rehash
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $rehashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if (!$rehashedPassword) {
                throw new Exception('Could not update user to rehash password');
            }
            $user->setPassword($rehashedPassword);
            $customerRepository->update($user);
        }
        // login SUCCESSFUL
        $this->session->login($user->getId());
        if ($this->session->isLoggedIn()) {
            return $this->loginSuccessResponse();
        } else {
            // login failed
            return $this->loginFailedResponse();
        }
    }

    private function validateUsernamePassword(array $body): void
    {
        Validator::key('username', Validator::allOf(
            Validator::notEmpty(),
            Validator::stringType(),
            Validator::email()
        ))->key('password', Validator::allOf(
            Validator::notEmpty(),
            Validator::stringType()
        ))->check($body);
    }

    /**
     * Returns a response for a successful login attempt.
     *
     * @return ResponseInterface
     */
    private function loginSuccessResponse(): ResponseInterface
    {
        setcookie("Show-Login-Success-Toast", "true", time() + 3, "/");
        return new Response(status: 301, headers: ['Location' => '/']);
    }

    /**
     * Returns a response for a failed login attempt.
     *
     * @return ResponseInterface
     */
    private function loginFailedResponse(): ResponseInterface
    {
        setcookie("Show-Login-Failed-Toast", "true", time() + 3, "/auth/login/form");
        return new Response(status: 302, headers: ['Location' => '/auth/login/form']);
    }


    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        // Validate form data
        $this->validateUsernamePassword($body);
        //Validate customer type
        $enumCustomerType = new EnumCustomerType();
        Validator::allOf(
            Validator::notEmpty(),
            Validator::in($enumCustomerType->getValues())
        )->check($request->getParsedBody()['customer_type']);

        $username = $body['username']; // coming from our form via $_POST/$_REQUEST
        $password = $body['password']; // coming from our form via $_POST/$_REQUEST
        $customerType = $body['customer_type']; // coming from our form via $_POST/$_REQUEST


        // check if user already exists
        $customerRepository = new CustomerRepository();
        $user = $customerRepository->getByUsername($username);
        if ($user instanceof Customer) {
            setcookie("Show-Registration-Failed-Toast", "true", time() + 3, "/auth/register/form");
            return $this->redirect('/auth/register/form');
        }

        // hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if (!$hashedPassword) {
            throw new Exception('Could not hash password');
        }

        // create new customer
        $customer = new Customer();
        $customer->setUsername($username);
        $customer->setPassword($hashedPassword);
        $enumCustomerType->setValues($customerType);
        $customer->setCustomerType($enumCustomerType);

        // insert customer into database
        $customerRepository->insert($customer);

        // registration SUCCESSFUL
        $this->session->login($customer->getId());
        setcookie("Show-Registration-Success-Toast", "true", time() + 3, "/");
        return $this->redirect('/');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function logout(ServerRequestInterface $request): ResponseInterface
    {
        $this->session->logout();
        setcookie("Show-Logout-Success-Toast", "true", time() + 3, "/");
        return $this->redirect('/');
    }

    /**
     * Redirect to a given URL
     *
     * @param string $url The URL to redirect to
     * @return ResponseInterface
     */
    protected function redirect(string $url): ResponseInterface
    {
        return new Response(
            status: 303,
            headers: ['Location' => $url]
        );
    }

    // for the reset password implementation (not implemented yet completely)
    public function resetPassword(ServerRequestInterface $request): ResponseInterface
    {
        // validate the password
        Validator::allOf(
            Validator::notEmpty(),
            Validator::stringType(),
            Validator::length(8)
        )->check($request->getParsedBody()['password']);

        // get the user ID from the session
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];
        $customerId = $this->session->getValue($username);

        // update the user's password in the database
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->getByCustomerId($customerId);
        if (!$customer instanceof Customer) {
            throw new Exception('Could not find user');
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$password) {
            throw new Exception('Could not hash password');
        }

        $customer->setPassword($password);
        $customerRepository->update($customer);

        // redirect to the login page
        $this->session->login($customer->getId());
        $response = new Response("", 301, ['Location' => '/login']);
        $response->getBody()->write('great success');
        return $response;
    }

}
