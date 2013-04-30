<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Mailer\Controller\Mail' => 'Mailer\Controller\MailController',
        ),
    ),
    'router' => array(
        'routes' => array(
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
                        'controller' => 'Mailer\Controller\Mail',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'email/group-email' => __DIR__ . '/../view/email/group-email.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);