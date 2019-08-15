# Invite!

A simple project to invite people, and get invited.

## Getting Started

### Prerequisites

A basic knowledge of Symfony 3

### Installing

You may clone this repository, and run composer install

The user management (Register, Login, Logout) is handled by the FOSUserBundle
The templates were not modified there.

To create the DB Schema, run:
```
php bin/console doctrine:schema:update --force
```

To seed the database with 4 users and a set of invitations, run:
````
php bin/console doctrine:fixtures:load
````

This will create 4 users (user0, user1, etc...) with password: invite

## Running the tests

The App has a single controller: the InviteContoller

Each user is identified by its id + token pair

Integration Tests cover the invitation status management of the InvitationController

To run the tests:

```
./vendor/bin/simple-phpunit
```

## Front end

The /invitations page shows all the invitations of a given user, colored according to the invitation status

The filter, as required in the exercise, was not implemented for time reasons

## Authors

* **Juan Pablo**