#About this project

This project is intended to demostrate the code skills for my job application.

##Step 1: Setup docker

1. Create a new folder, then pull Laradock to this folder:  
`git clone https://github.com/pyyick/codetest`

2. Start the container  
`docker-compose up -d nginx mysql phpmyadmin redis workspace`

##Step 2: Install laravel

1. Back to the folder created in step 1.1, create another folder called `www`, and pull the content from my repo:  
`git clone https://github.com/pyyick/codetest_www www`

2. Enter the workspace container by the following command:  
`cd laradock;
docker-compose exec workspace bash;`

3. Run the composer to install the packages:  
`composer install`

4. Run the npm to install frontend packages:  
`npm install`

##Step 3: Database migration and server up

1. Enter the mysql container by the following command:  
`docker-compose exec mysql bash;`

2. Login through the following account and the password is `secret`:  
`mysql -u default -p`

3. Run the following command to create schema for laravel instance:  
`create database exercise;`

4. Go back to workspace container and run this command to execute laravel migration:  
`php artisan migrate`

5. Start the server by the following command:  
`php artisan serve`

8. You've done. Go to browser and enter the following URL to start

http://localhost:8000/

#Explaination
##Database
I used Laravel migrate to create the table and data. The given CSV was placed into `storage/app` folder.

Files created/modified:
- database/migrations/2020_02_04_034705_create_firstname_table.php
- app/FirstName.php

##Search

Just type in a name then click the search, very simple.

Files created/modified:
- app/Http/Controllers/SearchController.php
- resources/js/components/Search.vue
- routes/api.php
- resources/views/app.blade.php 

##Import

Upload a CSV file and output another file.

Files created/modified:
- app/Http/Controllers/FileController.php 
- app/Http/Controllers/SearchController.php
- resources/js/components/Search.vue
- resources/js/components/Treatment.vue
- routes/web.php
