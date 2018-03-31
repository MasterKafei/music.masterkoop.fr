<?php

namespace AppBundle\Controller\User;

use AppBundle\Form\Type\User\Authentication\AuthenticateType;
use AppBundle\Service\Util\Console\Model\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends Controller
{

    public function authenticateAction(Request $request)
    {
        /** @var AuthenticationUtils $authenticationUtils */
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        if (null !== $error) {
            var_dump($error->getMessage());
            $this->get('app.util.console')->add('user.authentication.login.bad_credentials', Message::TYPE_DANGER);
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(AuthenticateType::class);
        $form->get('_username')->setData($lastUsername);
        $form->get('_csrf_token')->setData(
            $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
        );

        return $this->render(
            '@Page/User/Authentication/authenticate.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Virtual action handled by Symfony to check login
     */
    public function checkAction()
    {
        throw new \RuntimeException('Should never be called');
    }

    /**
     * Virtual action handled by Symfony to logout
     */
    public function logoutAction()
    {
        throw new \RuntimeException('Should never be called');
    }
}
