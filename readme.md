#Base Ipea

Projeto base para estrutura de sites com cms integrado

Back-end: PHP com Framework Laravel 5.2
Front-end: HTML5/CSS3, LESS, jQuery, Bootstrap, AngularJS
Banco de Dados: PostgreSQL

##Instruções de Instalação

- Executar os comandos:

$ git clone git@bitbucket.org:clandevelop/baseipea.git

$ cd baseipea

$ composer install

- Criar um banco de dados no postgre com o nome baseipea

- Renomear o arquivo .env.example para .env e configurar as variáveis de banco de dados

- Executar o comando abaixo para criar as tabelas no banco de dados

$ php artisan migrate

- Para visualisar o site no navegador através de htt://localhost:800 deve executar o comando:

$ php artisan serve

##Requisitos

Composer: https://getcomposer.org/

# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
