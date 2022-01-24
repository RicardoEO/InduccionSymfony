<?php
namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractAdmin
{
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    public function setContainer (\Symfony\Component\DependencyInjection\ContainerInterface $container) {
        $this->container = $container;
    }

    public function preUpdate($object): void {
        $uniqid = $this->getRequest()->query->get('uniqid');
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $formData = $this->getRequest()->request->get($uniqid);
        if(array_key_exists('password', $formData) && $formData['password'] !== null && strlen($formData['password']['first' ]) > 0) {
            $object->setPassword($encoder->encodePassword($formData['password']['first' ],$object->getSalt()));
        }
    }

    public function prePersist($object): void { // $object is an instance of App\Entity\User as specified in services.yaml
        $plainPassword = $object->getPassword();
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($object, $plainPassword);

        $object->setPassword($encoded);
    }

    protected function configureFormFields(FormMapper $form): void
    {
//        $form->add('email', EmailType::class);
        $form->add('email', EmailType::class, array('label' => 'Email', 'translation_domain' => 'FOSUserBundle'));
        $form->add('password', RepeatedType::class, array(
        'type' => PasswordType::class,
        'first_options' => array('label' => 'Password'),
        'second_options' => array('label' => 'Password confirmation')
    ));
        $form->add('nombre', TextType::class);
        $form->add('roles', ChoiceType::class, [
            'choices' => array_combine(User::TIPO_ROLES, User::TIPO_ROLES),
            'multiple' => true,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('nombre');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'show' => [],
                'edit' => [
                    // You may add custom link parameters used to generate the action url
                    'link_parameters' => [
                        'full' => true,
                    ]
                ],
                'delete' => [],
            ]
        ]);
        $list->addIdentifier('nombre');
        $list->add('email');
        $list->add('password');
        $list->add('roles');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('nombre');
        $show->add('email');
        $show->add('password');
        $show->add('roles');
    }

}