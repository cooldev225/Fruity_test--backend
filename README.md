# Fruity_test--backend

# clone the repository
```
git clone https://github.com/cooldev225/Fruity_test--backend.git
```

# install PHP, symfony, symfony-cli, composer

```
cd Fruit_test--backend
```

# install vendors
```
composer install
```

# edit .env file
```
DATABASE_URL="mysql://<username>:<password>@127.0.0.1:3306/<database_name>?serverVersion=mariadb-10.4.27"
MAILER_DSN=smtp://user:pass@smtp.example.com:port
```

# create database
```
php ./bin/console doctrine:database:create
```

# run migration
```
php ./bin/console doctrine:migrations:migrate
```

# fetch data from the https://fruityvice.com/ and save them to the database
```
php ./bin/console app:save-data
```

# run server
```
symfony server:start
```
