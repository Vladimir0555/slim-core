<?php

namespace App\MVC\Controllers\Auth;

use \App\Core\CoreController;
use App\MVC\Entity\UserEntity;
use App\MVC\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class RegisterController
 * @package App\MVC\Controllers\Auth
 */
class RegisterController extends CoreController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request, Response $response)
    {
        $u = new User($this->container);
        $form = $this->getForm('App\Forms\RegisterForm');

        if ($request->isPost()) {
            $data = $request->getParams();
            $form->setData($data);
            $isValid = $form->isValid();
            if ($isValid) {
                $data = $form->getData();

                $ue = new UserEntity();
                $ue->exchangeArray([
                    'email' => $data['email'],
                    'created' => date('Y-m-d H:i:s'),
                    'updated' => date('Y-m-d H:i:s'),
                    'password_token' => md5(date('U') . $data['first_name'] . date('YmdHis')),
                    'token_expiration' => date('U', strtotime('now + 12 hours')),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                ]);

                $u->create($ue, true);

                $userFullName = $ue->getFirstName() . ((strlen($ue->getLastName()) > 0) ? ' ' . $ue->getLastName() : '');

                $projectName = (is_string($this->getConfig('projet_name')) ? $this->getConfig('projet_name') : '');

                try {
                    $this->sendMail([
                        'to' => $ue->getEmail(),
                        'subject' => 'Hello ' . $userFullName . '! Confirm your email. ' . $projectName,
                        'body' => $this->getEmailBody('emails\registration_confirm.twig', [
                            'password_token' => $ue->getPasswordToken()
                        ]),
                        'from_name' => 'No reply'
                    ]);

                    $this->container->flash->addMessage(
                        'alert-success',
                        '<h3>Thank You for Signing Up!</h3>' .
                        '<p>We\'ve sent you an email with a confirmation link, use it to activate your new account.</p>'
                    );

                    return $response->withRedirect($this->router->pathFor('register'));
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        return $this->view->render($response, 'auth\register\index.twig', [
            'form' => $form
        ]);
    }
}
