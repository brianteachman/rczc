# Red Cedar Zen - Sangha Software

Introduction
------------
This is a simple member management and email announcement application written using [Zend Framework 2]().


Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git@github.com:brianteachman/rczc.git
    cd rczc
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

You would then invoke `composer` to install dependencies per the previous
example.

Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!
