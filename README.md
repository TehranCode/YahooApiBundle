# YahooApiBundle
Symfony2 Yahoo Oauth + OpenID This Bundle is a wrapper around [alexandreeffetb/yos-social-php5](https://github.com/alexandreeffetb/yos-social-php5)
# Progress Sheet
This is under construction so Don't use it or at least Debug it first!
# Requirements
	[alexandreeffetb/yos-social-php5](https://github.com/alexandreeffetb/yos-social-php5)
# Installation
### Use Composer to get the repository
```
$ composer require TehranCode/YahooApiBundle
```

### Setting up the bundle
A) Enable the bundles in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new TehranCode\YahooApiBundle\TehranCodeYahooApiBundle(),
    );
}
```

B) Configure the TehranCodeYahooApiBundle

```php
// app/config/config.yml

tehran_code_yahoo_api:
    application_id:       %Your_Application_ID%
    consumer_key:         %Your_Application_consumer_key%
    consumer_secret:      %Your_Application_consumer_secret%
    callback_url:         %Your_Application_callback_url%
```