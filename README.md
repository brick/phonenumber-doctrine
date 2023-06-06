brick/phonenumber-doctrine
========================

<img src="https://raw.githubusercontent.com/brick/brick/master/logo.png" alt="" align="left" height="64">

Doctrine type mappings for [brick/phonenumber](https://github.com/brick/phonenumber).

[![Build Status](https://github.com/brick/phonenumber-doctrine/workflows/CI/badge.svg)](https://github.com/brick/phonenumber-doctrine/actions)
[![Coverage Status](https://coveralls.io/repos/github/brick/phonenumber-doctrine/badge.svg?branch=master)](https://coveralls.io/github/brick/phonenumber-doctrine?branch=master)
[![Latest Stable Version](https://poser.pugx.org/brick/phonenumber-doctrine/v/stable)](https://packagist.org/packages/brick/phonenumber-doctrine)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](http://opensource.org/licenses/MIT)

Introduction
------------

This library provides a type mapping to use `PhoneNumber` objects as Doctrine entity properties.

Installation
------------

This library is installable via [Composer](https://getcomposer.org/):

```bash
composer require brick/phonenumber-doctrine
```

Requirements
------------

This library requires PHP 7.4 or later.

Project status & release process
--------------------------------

The current releases are numbered `0.x.y`. When a non-breaking change is introduced (adding new methods, optimizing existing code, etc.), `y` is incremented.

**When a breaking change is introduced, a new `0.x` version cycle is always started.**

It is therefore safe to lock your project to a given release cycle, such as `0.1.*`.

If you need to upgrade to a newer release cycle, check the [release history](https://github.com/brick/phonenumber-doctrine/releases) for a list of changes introduced by each further `0.x.0` version.

Package contents
----------------

- [PhoneNumberType](https://github.com/brick/phonenumber-doctrine/blob/master/src/Types/PhoneNumberType.php)
