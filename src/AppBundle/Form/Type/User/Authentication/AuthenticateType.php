<?php

namespace AppBundle\Form\Type\User\Authentication;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

class AuthenticateType extends AbstractType
{

    protected $router;

    /**
     * AuthenticateType constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            '_username',
            TextType::class,
            array(
                'label' => 'user.email',
            )
        )->add(
            '_password',
            PasswordType::class,
            array(
                'label' => 'user.password',
            )
        )->add(
            '_remember_me',
            CheckboxType::class,
            array(
                'label' => 'user.remember_me',
                'required' => false,
            )
        )->add(
            '_csrf_token',
            HiddenType::class
        )->add(
            '_target_path',
            HiddenType::class
        )->add(
            'submit',
            SubmitType::class,
            array(
                'label' => 'login',
                'translation_domain' => 'action',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'action' => $this->router->generate('app_user_authentication_check'),
                'method' => 'POST',
                'csrf_protection' => false,
                'translation_domain' => 'label',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }

}
