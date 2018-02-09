# Snowtricks 

Snowtricks is a symfony 4 project for the Openclassrooms "Application developer" path. This project is intended to become a community website, fully user-driven. Snowtricks is a powerfull tool to discover and learn freestyle snowboarding.   

**TravisCI**

[![Build Status](https://travis-ci.org/Chekaviah/snowtricks.svg?branch=master)](https://travis-ci.org/Chekaviah/snowtricks)

**SensioLabsInsight**

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/00b46792-eadd-4bac-8a59-0caa3332f78a/big.png)](https://insight.sensiolabs.com/projects/00b46792-eadd-4bac-8a59-0caa3332f78a)


## Requirements 
- PHP >= 7.1
- MySQL >= 5.7.11
- [Composer](https://getcomposer.org/)
- [Symfony application requirements](https://symfony.com/doc/current/reference/requirements.html)


## Installation 
1. Clone the master branch
1. Install dependencies `composer install`
1. Copy the .env.dist file to .env and edit configuration for mailer and database
1. Create database `bin/console doctrine:schema:create`
1. Load data fixtures `bin/console doctrine:fixtures:load -n`
1. Run PHP's built-in Web Server `bin/console server:run`
1. Navigate to [localhost:8000](http://localhost:8000)


## Tests
1. Create tests database `bin/console doctrine:schema:create --env=test`
1. Load tests data fixtures `bin/console doctrine:fixtures:load --env=test -n`
1. Run units and functionals tests `bin/phpunit`
