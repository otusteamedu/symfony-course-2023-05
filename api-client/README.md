# OpenAPIClient-php

This is an awesome app!


## Installation & Usage

### Requirements

PHP 7.4 and later.
Should also work with PHP 8.0.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new OpenAPI\Client\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$user_id = 135; // string | ID пользователя
$count = 135; // string | ID пользователя

try {
    $apiInstance->getAppApiGetfeedV1Getfeed($user_id, $count);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getAppApiGetfeedV1Getfeed: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://localhost*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*DefaultApi* | [**getAppApiGetfeedV1Getfeed**](docs/Api/DefaultApi.md#getappapigetfeedv1getfeed) | **GET** /api/v1/get-feed | 
*DefaultApi* | [**apiDocAreaGet**](docs/Api/DefaultApi.md#apidocareaget) | **GET** /api/doc/{area} | 
*DefaultApi* | [**apiV1AddFollowersPost**](docs/Api/DefaultApi.md#apiv1addfollowerspost) | **POST** /api/v1/add-followers | 
*DefaultApi* | [**apiV1GetUsersByQueryGet**](docs/Api/DefaultApi.md#apiv1getusersbyqueryget) | **GET** /api/v1/get-users-by-query | 
*DefaultApi* | [**apiV1GetUsersByQueryWithAggregationGet**](docs/Api/DefaultApi.md#apiv1getusersbyquerywithaggregationget) | **GET** /api/v1/get-users-by-query-with-aggregation | 
*DefaultApi* | [**apiV1GetUsersWithAggregationGet**](docs/Api/DefaultApi.md#apiv1getuserswithaggregationget) | **GET** /api/v1/get-users-with-aggregation | 
*DefaultApi* | [**apiV1TokenPost**](docs/Api/DefaultApi.md#apiv1tokenpost) | **POST** /api/v1/token | 
*DefaultApi* | [**apiV1TweetGet**](docs/Api/DefaultApi.md#apiv1tweetget) | **GET** /api/v1/tweet | 
*DefaultApi* | [**apiV1TweetPost**](docs/Api/DefaultApi.md#apiv1tweetpost) | **POST** /api/v1/tweet | 
*DefaultApi* | [**apiV1UploadPost**](docs/Api/DefaultApi.md#apiv1uploadpost) | **POST** /api/v1/upload | 
*DefaultApi* | [**apiV1UserAsyncPost**](docs/Api/DefaultApi.md#apiv1userasyncpost) | **POST** /api/v1/user/async | 
*DefaultApi* | [**apiV1UserCreateUserGet**](docs/Api/DefaultApi.md#apiv1usercreateuserget) | **GET** /api/v1/user/create-user | 
*DefaultApi* | [**apiV1UserCreateUserPost**](docs/Api/DefaultApi.md#apiv1usercreateuserpost) | **POST** /api/v1/user/create-user | 
*DefaultApi* | [**apiV1UserDelete**](docs/Api/DefaultApi.md#apiv1userdelete) | **DELETE** /api/v1/user | 
*DefaultApi* | [**apiV1UserGet**](docs/Api/DefaultApi.md#apiv1userget) | **GET** /api/v1/user | 
*DefaultApi* | [**apiV1UserIdDelete**](docs/Api/DefaultApi.md#apiv1useriddelete) | **DELETE** /api/v1/user/{id} | 
*DefaultApi* | [**apiV1UserPatch**](docs/Api/DefaultApi.md#apiv1userpatch) | **PATCH** /api/v1/user | 
*DefaultApi* | [**apiV1UserPost**](docs/Api/DefaultApi.md#apiv1userpost) | **POST** /api/v1/user | 
*DefaultApi* | [**apiV1UserUpdateUserIdGet**](docs/Api/DefaultApi.md#apiv1userupdateuseridget) | **GET** /api/v1/user/update-user/{id} | 
*DefaultApi* | [**apiV1UserUpdateUserIdPatch**](docs/Api/DefaultApi.md#apiv1userupdateuseridpatch) | **PATCH** /api/v1/user/update-user/{id} | 
*DefaultApi* | [**apiV2UserByLoginUserLoginGet**](docs/Api/DefaultApi.md#apiv2userbyloginuserloginget) | **GET** /api/v2/user/by-login/{user_login} | 
*DefaultApi* | [**apiV2UserGet**](docs/Api/DefaultApi.md#apiv2userget) | **GET** /api/v2/user | 
*DefaultApi* | [**apiV2UserPatch**](docs/Api/DefaultApi.md#apiv2userpatch) | **PATCH** /api/v2/user | 
*DefaultApi* | [**apiV2UserPost**](docs/Api/DefaultApi.md#apiv2userpost) | **POST** /api/v2/user | 
*DefaultApi* | [**apiV2UserUserIdDelete**](docs/Api/DefaultApi.md#apiv2useruseriddelete) | **DELETE** /api/v2/user/{user_id} | 
*DefaultApi* | [**apiV3UserDelete**](docs/Api/DefaultApi.md#apiv3userdelete) | **DELETE** /api/v3/user | 
*DefaultApi* | [**apiV3UserGet**](docs/Api/DefaultApi.md#apiv3userget) | **GET** /api/v3/user | 
*DefaultApi* | [**apiV3UserPatch**](docs/Api/DefaultApi.md#apiv3userpatch) | **PATCH** /api/v3/user | 
*DefaultApi* | [**apiV3UserPost**](docs/Api/DefaultApi.md#apiv3userpost) | **POST** /api/v3/user | 
*DefaultApi* | [**apiV4UsersFormatGet**](docs/Api/DefaultApi.md#apiv4usersformatget) | **GET** /api/v4/users.{format} | 
*DefaultApi* | [**apiV4UsersPost**](docs/Api/DefaultApi.md#apiv4userspost) | **POST** /api/v4/users | 
*DefaultApi* | [**apiV5UsersPost**](docs/Api/DefaultApi.md#apiv5userspost) | **POST** /api/v5/users | 

## Models

- [ApiV1AddFollowersPostRequest](docs/Model/ApiV1AddFollowersPostRequest.md)
- [ApiV1TweetPostRequest](docs/Model/ApiV1TweetPostRequest.md)
- [ApiV4UsersPostRequest](docs/Model/ApiV4UsersPostRequest.md)

## Authorization
Endpoints do not require authorization.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `1.0.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
