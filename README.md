<p align="center"><img src="https://symfony.com/images/logos/header-logo.svg"></p>

# **BRETTINGHAMS DASHBOARD**

BRETTINGHAM DASHBOARD private application with user account functionality on the foundation of the Symfony 5 framework.

## Theme Demo
![Alt Demo](public/dist/img/demo.png?raw=true "Demo")

**[Template Demo](https://adminlte.io/themes/v3/index3.html#)**


# **Features**
- Administration Dashboard 
- Responsive Layout
- Bootstrap 4
- USER/ROLES CRUD 
- Password reset and send email, with link to reset the password
- Authentication system
- Translation functionality (Easy to set up whatever language you need/use)
- Generate Content Element for Typo3
- Generate Plugin typo3
- Generate Provider Extension
- Code exemple 
- Generate Typo3 Project

# **Requirements**
- DDEV
- Symfony >5.*



# **SETUP**
1 - ddev config :

~~~
    ddev config
~~~

2 - ddev start
~~~
    ddev start
~~~

3 - Create scheme using migration command:
~~~
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
~~~


**ENJOY**