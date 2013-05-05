<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Mailer\Controller\Mail' => 'Mailer\Controller\MailController',
        ),
    ),
    'router' => array(
        'routes' => array(
            // Literal route named "blog", with child routes
            'mail' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/mail',
                    'defaults' => array(
                        'controller' => 'Mailer\Controller\Mail',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'member' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/member[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action'     => 'member',
                            ),
                        ),
                    ),
                    'review' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/review[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action'     => 'review',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action'     => 'edit',
                            ),
                        ),
                    ),
                    'default' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'mailer' => __DIR__ . '/../view',
            'email' => __DIR__ . '/../view',
        ),
    ),
);