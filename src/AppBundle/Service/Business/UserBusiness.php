<?php
/**
 * Created by PhpStorm.
 * User: TheMa
 * Date: 17/02/2018
 * Time: 16:00
 */

namespace AppBundle\Service\Business;

use AppBundle\Entity\User;
use AppBundle\Service\Util\AbstractContainerAware;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserBusiness extends AbstractContainerAware
{
    /**
     * Generate a new token for a defined User
     *
     * @param User $user    : User to change the token from
     * @param bool $persist : Should changes be persisted ?
     *
     * @return string : Generated token
     */
    public function generateToken(User $user, $persist = true)
    {
        $token = $this->container->get('app.util.token_generator')->generateToken();
        $user->setToken($token);

        if ($persist) {
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $token;
    }

    /**
     * Authenticate current user as the defined User
     *
     * @param User $user : User to authenticate as
     */
    public function authenticateUser(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_secured_area', serialize($token));
    }

    /**
     * Hash a password with hash strategy defined in security
     *
     * @param User $user
     *
     * @return string
     * @throws \Exception
     */
    public function hashPassword(User $user)
    {
        if (null === $user->getPlainPassword()) {
            throw new \Exception('Plain password can\'t be null');
        }

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);

        return $password;
    }

    /**
     * Get current authenticated user
     *
     * @return User
     */
    public function getCurrentUser()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}