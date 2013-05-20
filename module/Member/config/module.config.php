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
            // /members           Home (list of members)        index
            'members' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/members',
                    'defaults' => array(
                        'controller' => 'Member\Controller\Members',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // URL                Page                          Action
                    // -------------------------------------------------------
                    // /members/add       Add new member                add
                    'add' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action'     => 'add',
                            ),
                        ),
                    ),
                    // URL                Page                          Action
                    // -------------------------------------------------------
                    // /members/edit/2    Edit member with an id of 2   edit
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
                    // URL                Page                          Action
                    // -------------------------------------------------------
                    // /members/delete/4  Delete member with id of 4    delete
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action'     => 'delete',
                            ),
                        ),
                    ),
                    // URL                Page                          Action
                    // -------------------------------------------------------
                    // /members/roles     list member role/s            roles
                    'roles' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/roles',
                            'defaults' => array(
                                'action'     => 'roles',
                            ),
                        ),
                    ),
                    // URL                    Page                          Action
                    // --------------------------------------------------------------
                    // /members/roles/edit/4  Delete member with id of 4    edit-role
                    'role-edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/roles/edit[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action'     => 'roles-edit',
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
            'member' => __DIR__ . '/../view',
        ),
    ),
);