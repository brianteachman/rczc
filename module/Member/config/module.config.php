<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Member\Controller\Members' => 'Member\Controller\MembersController',
        ),
    ),
    'router' => array(
        'routes' => array(
            // URL                Page                          Action
            // -------------------------------------------------------
            // /members           Home (list of member)         index
            // /members/add       Add new member                add
            // /members/edit/2    Edit member with an id of 2   edit
            // /members/delete/4  Delete member with id of 4    delete
            'members' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/members[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Member\Controller\Members',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'member' => __DIR__ . '/../view',
        ),
    ),
);