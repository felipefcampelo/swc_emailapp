# README
## How to use this repository

### Clone the repository to your local machine
```
git clone https://github.com/felipefcampelo/swc_emailapp.git
```

### Run Composer
```
composer install && composer update
```

### Install NPM dependencies
```
npm install
```

### Implement the assets folder using gulp. Just type the command:
```
gulp
```

### Create a MySQL Database
You can create the database with the name you want, just need to be sure to put the correct name in the .env file

### Create and configure the .env file
You will see in the root folder the file .env.template. Just rename it removing the '.template' termination and after this change its contents with the information that you will use to send emails and the database information.

I recommend the use of Mailtrap to test the email tool but feel free to use the SMTP configuration you want.

### Run the migrations (We are using Phinx to manage the migrations and seeders)
```
./vendor/bin/phinx migrate
```

### Run the seeders (It's optional. Just do this if you want to have some data in the database to see something in the Sent Emails page)
```
./vendor/bin/phinx seed:run
```

### Run the project
you can create a vhost to run this project or just run using the built-in server that exists in the PHP.

If you want to create a vhost, the recommended configuration is:
```
<VirtualHost swcemailapp.local:80>
    ServerAdmin swcemailapp.local
    DocumentRoot "path/to/the/project/folder/public"
    ServerName swcemailapp.local
    ErrorLog "logs/swcemailapp-error.log"
    CustomLog "logs/swcemailapp-access.log" common
</VirtualHost>
```

If you are using Windows OS, don't forget to add in the 'hosts' file the entry to run your vhost, and feel free to use the name you want in the vhost.

Now, if you want to run in localhost using a built-in server in PHP, just make the commands:
```
cd path/to/project/folder
```
```
php -S localhost:8000 -t public
```
Just enter http://localhost:8000, and things will be up and running.