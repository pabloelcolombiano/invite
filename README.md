# Invite!

A simple project to invite people, and get invited.

## Getting Started

### Prerequisites

A basic knowledge of Symfony 3

### Installing

You may clone this repository, and run composer install

The user management (Register, Login, Logout) is handled by the FOSUserBundle
The templates were not modified there.

## Running the tests

The App has a single controller: the InviteContoller

Each user is identified by its id + token pair

Integration Tests cover the invitation status management of the InvitationController

To run the tests:

```
./vendor/bin/simple-phpunit
```

## Authors

* **Juan Pablo**