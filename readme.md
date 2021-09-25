# ![RealWorld Example App](logo.png)

> ### Symfony5 codebase containing real world examples (CRUD, auth, advanced patterns, etc) that adheres to the [RealWorld](https://github.com/gothinkster/realworld) spec and API.


### [Demo](https://github.com/gothinkster/realworld)&nbsp;&nbsp;&nbsp;&nbsp;[RealWorld](https://github.com/gothinkster/realworld)


This codebase was created to demonstrate a fully fledged fullstack application built with **Symfony5** including CRUD operations, authentication, routing, pagination, and more.

We've gone to great lengths to adhere to the **Symfony5** community styleguides & best practices.

For more information on how to this works with other frontends/backends, head over to the [RealWorld](https://github.com/gothinkster/realworld) repo.

# API Swagger Documentation

We generate the swagger documentation using the docker image.

> docker pull swaggerapi/swagger-ui
> docker run -p 80:8080 -e BASE_URL=/doc -e SWAGGER_JSON=/doc/swagger.json -v absolute-path-to-doc-folder/doc:/doc swaggerapi/swagger-ui

Go to http://localhost/doc/ and search swagger.json

# How it works

> Describe the general architecture of your app here

# Getting started

Create the certificate for JWT

> bin/console lexik:jwt:generate-keypair

