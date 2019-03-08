# Laravel SAML package

This package makes it possible to define multiple service provider, that could be used to authenticate your Laravel Users.   

## Installation

Install the package by running. It will automatically install the dependencies.

`composer require topredmedia/laravel-saml`

The SAMLServiceProvider should be automatically loaded by Laravel >= 5.5. 

To publish the configuration file run:

`php artisan vendor:publish --provider="TopRedMedia\SAML\SAMLServiceProvider"`

## Configuration

The configuration is done in the config/topredmedia-saml.php file. The file is structured in three parts:

### Route Prefix

The route prefix entry is appended to each route and defaults to `saml`. 

### Endpoints

It is possible to define multiple endpoints which means, that you can use the package to identify your users from different ISPs. Each endpoint is identified by a key which is also used as route part. The configuration ships with a `sample` endpoint, so for that the login route would be https://yourlaravelsite.com/saml/sample/login.

### Defaults

All settings in the default array will internally be copied to each endpoint definition. If the endpoint overwrites a key that had been defined in the default that is fine.   

## Usage

Enter the information from the desired ISP directly into the configuration file or create appropriate env variables for that. Just have a look at the sample endpoint. 

If the user successfully authenticates, the ISPController will dispatch a TopRedMedia\SAML\UserLoggedInEvent. It contains a TopRedMedia\SAML\SAMLUser object, which has all the needed information for you to check if a user exists or should be created. Just create your Listener, handle the event and you are done.  

If there are more endpoints defined, it is absolutely ok to create a listener for each endpoint and only handle the event if the endpoint is valid for your listener. If your listener was responsible for a certain event, just stop the <a href='https://laravel.com/docs/5.7/events#defining-listeners'>event propagation</a> by returning false in the listener. 

## Credits

The package was heavily influenced by the https://github.com/aacotroneo/laravel-saml2 package. Since we need multiple ISPs, we decided to do a rewrite. 
