<?php

namespace App\Admin;

use App\Entity\Post;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CommentAdmin extends AbstractAdmin {
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'nombre',
        ]);
        $form->add('post', EntityType::class, [
            'class' => Post::class,
            'choice_label' => 'titulo',
        ]);
        $form->add('nombre', TextType::class);
        $form->add('comentario', TextType::class, [
            'constraints' => [
                new Length(['max' => 255])
            ]
        ]);
        $form->add('web', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'delete' => [],
            ]
        ]);
        $list->addIdentifier('nombre');
        $list->add('comentario');
        $list->add('user', null, [
            'label' => 'Autor',
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {

    }
}