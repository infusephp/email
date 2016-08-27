email
=====

[![Build Status](https://travis-ci.org/infusephp/email.svg?branch=master&style=flat)](https://travis-ci.org/infusephp/email)
[![Coverage Status](https://coveralls.io/repos/infusephp/email/badge.svg?style=flat)](https://coveralls.io/r/infusephp/email)
[![Latest Stable Version](https://poser.pugx.org/infuse/email/v/stable.svg?style=flat)](https://packagist.org/packages/infuse/email)
[![Total Downloads](https://poser.pugx.org/infuse/email/downloads.svg?style=flat)](https://packagist.org/packages/infuse/email)
[![HHVM Status](http://hhvm.h4cc.de/badge/infuse/email.svg?style=flat)](http://hhvm.h4cc.de/package/infuse/email)

Mailer module for Infuse Framework

## Installation

Install the package with [composer](http://getcomposer.org):

```
composer require infuse/email
```

Add the email service to your config.php

```php
'services' => [
	// ...
	'mailer' => 'Infuse\Email\MailerService'
	// ...
]
```