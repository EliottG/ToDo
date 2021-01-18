ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Pre-requisites

 - PHP 7.4 or more
 - MySQL 5.7 or more
 - Composer
 - Technical Requirements <a target="_blank" href="https://symfony.com/doc/current/setup.html#technical-requirements">symfony</a>
 - Git

## How to install

1 - Clone the repository in your local server folder
> git clone https://github.com/EliottG/ToDo.git

2 - Create a .env.local file from .env and configure BDD

3 - Install all dependencies
> composer install

4 - Create the database
> php bin/console d:d:c

5 - Load database schema
> symfony console  --env=test doctrine:schema:update -f

6 - Load fixtures in database
> php bin/console doctrine:fixtures:load -n

7 - Now you can run the app
> symfony server:start


