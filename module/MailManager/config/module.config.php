<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'MailManager\Controller\Mail' => 'MailManager\Controller\MailController',
            'MailManager\Controller\Members' => 'MailManager\Controller\MembersController',
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
                        'controller' => 'MailManager\Controller\Members',
                        'action'     => 'index',
                    ),
                ),
            ),
            // URL             Page                          Action
            // -------------------------------------------------------
            // /mail           Compose mailing list message  index
            // /mail/new       Compose new email             new
            // /mail/edit/2    Edit message with id of 2     edit
            // /mail/delete/4  Delete message with id of 4   delete
            'mail' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mail[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'MailManager\Controller\Mail',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);