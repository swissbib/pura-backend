# Pura User Management

Small Back-End application to manage private users from libraries who access online resources with their SWITCH edu-id.

[Information about the Pura Project](http://www.swissbib.org/wiki/index.php?title=Private_User_Remote_Access_(Pura))

For more information, feel free to contact us at <swissbib-ub@unibas.ch>. 

## General Info

Url :

-   <https://pura.swissbib.ch> (user management by librarians)
-   <https://www.swissbib.ch/MyResearch/Pura> (registration for
    end-users)

Repositories :

-   <https://github.com/swissbib/pura-backend> (user management by
    librarians)
-   <https://github.com/swissbib/vufind> (registration for end users,
    module Swissbib, service Pura)
-   <https://github.com/swissbib/switchSharedAttributesAPIClient>
    (communication with switch api)

Public documentation : <http://www.swissbib.org/wiki/index.php?title=Private_User_Remote_Access_(Pura)>

## SWITCH API Documentation

-   <http://www.swissbib.org/wiki/index.php?title=SWITCH_edu-ID>


## Pura Databases

Pura-Back-End uses two mysql databases : 
- the main one is pura-db
- the second one is puralogin which takes care of username and passwords for the authentication on pura-back-end

You can see the schemas in the sql folder of this repository.


-   puralogin defined [here](https://github.com/swissbib/pura-backend/blob/master/config/autoload/development.local.php.dist#L38) and used for the authentication.
-   the [vufind](https://github.com/swissbib/pura-backend/blob/master/config/autoload/development.local.php.dist#L54) database. Only the tables user and pura-user are needed.

## Installation

Clone repo
``` {.bash}
git clone git@github.com:swissbib/pura-backend.git
```

Create the 2 Databases based on the schemas in the sql directory.

Adapt the configuration to use the correct databases : 
``` {.bash}
cd config/autoload
cp development.local.php.dist development.local.php
```

In development.local.php, update : 
- databases info (multiple places)
- credentials for the SWITCH API

Update dependencies
``` {.bash}
composer install
```

Run with php development server
```
php -S 0.0.0.0:8080 -t public/ public/index.php 
```

And go to <http://localhost:8080>




## Create user / password for a library

To enable Pura for a new library (let's say it has library code A999) :

Add a user for pura-back-end :

Use PHP interactive mode :

``` {.bash}
php -a
```

To encrypt the \'new_password_to_encrypt\'

``` {.php}
> echo password_hash('new_password_to_encrypt', PASSWORD_BCRYPT);
> $21ruoigdyfhngfkjjThisIsTheEncryptedPassword
```

In the database

``` {.bash}
sudo mysql
```

``` {.sql}
use puralogin;
INSERT INTO `users` VALUES ('puraA999','$21ruoigdyfhngfkjjThisIsTheEncryptedPassword','A999');
```

-   Add the role A999 to
    <https://github.com/swissbib/pura-backend/blob/master/config/autoload/zend-expressive.global.php>

