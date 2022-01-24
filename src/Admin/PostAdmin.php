<?php
namespace App\Admin;

use App\Entity\Comment;
use App\Entity\User;
use DateTime;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\FileType;

final class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        if ($this->isCurrentRoute('create')) {
            $form->add('titulo', TextType::class);
            $form->add('descripcion', TextType::class, [
                'constraints' => [
                    new Length(['max' => 1000])
                ]
            ]);
            $form
                ->add('file', FileType::class, [
                    'required' => false
                ]);
            $form->add('createdAt', DateType::class, [
                'years' => range(
                    (new DateTime())->format('Y'),
                    (new DateTime())->format('Y')
                ),
                'months' => range(
                    (new DateTime())->format('m'),
                    (new DateTime())->format('m')
                ),
                'days' => [(new DateTime())->format('d')],
            ]);
            $form->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nombre',
            ]);
            $form->add('tags', \Symfony\Component\Form\Extension\Core\Type\CollectionType::class, [
                'allow_add' => true
            ]);
        }

        if ($this->isCurrentRoute('edit')) {
            $form->tab('Post');
            $form->with('Post');
            $form->add('titulo', TextType::class);
            $form->add('descripcion', TextType::class);
            $form->add('createdAt', DateType::class);
            $form->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nombre',
            ]);
            $form->add('tags', \Symfony\Component\Form\Extension\Core\Type\CollectionType::class, [
                'allow_add' => true
            ]);
            $form->end();
            $form->end();

            $form->tab('Comentarios');
            $form->with('Administrador de comentarios');
            $form->add('comments', CollectionType::class, [
                'disabled' => false,
                'required'      => false,
                'label'         => 'my_galleries_media_label',
                'btn_add' => false,
                'type_options'  => [
                    'delete' => true,
                ],
            ], [
                'edit'          => 'inline', // or standard
                'inline'        => 'table',  // or standard
                'sortable'      => 'id',     // by any field in your entity
            ])
            ;
            $form->end();
            $form->end();
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('titulo');
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
        $list->addIdentifier('titulo');
        $list->add('descripcion');
        $list->add('createdAt', null, [
            'label' => 'Fecha de Publicación',
            'format' => 'd/m/Y'
        ]);
        $list->add('user', null, [
            'label' => 'Autor',
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('titulo');
    }

    public function toString(object $object): string
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : 'Post'; // shown in the breadcrumb on the create view
    }

    public function prePersist(object $object): void
    {
        $this->manageFileUpload($object);
    }

    public function preUpdate(object $object): void
    {
        $this->manageFileUpload($object);
    }

    private function manageFileUpload(object $object): void
    {
//        if ($object->getFile()) {
//            $object->refreshUpdated();
//        }
    }
}