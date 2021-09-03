# Daytrading journal API
[![Build Status](https://travis-ci.org/rickintveld/daytrading-journal-api.svg?branch=master)](https://travis-ci.org/rickintveld/daytrading-journal-api)
[![codecov](https://codecov.io/gh/rickintveld/daytrading-journal-api/branch/master/graph/badge.svg?token=IMGRFFXCAK)](https://codecov.io/gh/rickintveld/daytrading-journal-api)


*Day trading journal API for keeping track of the profits.*

#### Start the application
To run the application, you will need to have ddev installed locally.

Run `brew install drud/ddev/ddev` to install.

Execute `ddev start` in the root of the application folder to start up the application.

#### Start the consumer
While the application is running, execute `ddev ssh` to access the web container.

Then you should execute `php bin/console messenger:consume async -vv` to start the consumer.

#### Endpoints

| Description       | Route              | Payload                                                 | Request |
| ------------------|:--------------:    | :-----------------------------------------------------: | :------:|
| All active users  | `/users`           |                                                         | `GET`   |
| All blocked users | `/users/blocked`   |                                                         | `GET`   |
| All removed users | `/users/removed`   |                                                         | `GET`   |
| A single user     | `/user`            | `identifier`                                            | `GET`   |
| Create a user     | `/user/create`     | `email`, `firstName`, `lastName`, `password`, `capital` | `POST`  |
| Update a user     | `/user/update`     | `email`, `firstName`, `lastName`, `password`, `capital` | `POST`  |
| Block a user      | `/user/block`      | `identifier`                                            | `POST`  |
| Remove a user     | `/user/remove`     | `identifier`                                            | `POST`  |
| Restore a user    | `/user/restore`    | `identifier`                                            | `POST`  |
| Add profit        | `/profit/add`      | `userId`, `profit`                                      | `POST`  |
| Withdraw profit   | `/profit/withdraw` | `userId`, `amount`                                      | `POST`  |

#
##### Tests

To execute the test you have to access the web container first, `ddev ssh`. Then you should execute the php unit command `./bin/phpunit`
