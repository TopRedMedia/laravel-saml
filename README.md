# Laravel SAML SP package

This package makes it possible to define multiple service provider, that could be used to authenticate your Laravel Users.   

## Installation

Install the Package

`composer require topredmedia/laravel-saml`

The SAMLServiceProvider should be automatically loaded by Laravel >= 5.5. To publish the configuration file run:

`php artisan vendor:publish --provider="TopRedMedia\SAML\SAMLServiceProvider"`

## Configuration

The configuration is done in the config/topredmedia-saml.php file. The file is structured in three parts:

### Route Prefix

The route prefix entry is appended to each route and defaults to `saml`. 

### Endpoints

It is possible to define multiple endpoints which means, that you can use the package to identify your users from different ISPs. Each endpoint is identified by a key which is also used as route part. So for a endpoint with the key 

### Defaults   

## Usage

## Credits
