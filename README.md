[![Latest Stable Version](http://poser.pugx.org/mamluk/kipchak/v)](https://packagist.org/packages/mamluk/kipchak) [![Total Downloads](http://poser.pugx.org/mamluk/kipchak/downloads)](https://packagist.org/packages/mamluk/kipchak) [![Latest Unstable Version](http://poser.pugx.org/mamluk/kipchak/v/unstable)](https://packagist.org/packages/mamluk/kipchak) [![License](http://poser.pugx.org/mamluk/kipchak/license)](https://packagist.org/packages/mamluk/kipchak) [![PHP Version Require](http://poser.pugx.org/mamluk/kipchak/require/php)](https://packagist.org/packages/mamluk/kipchak)

<img src=".mamluk/logo.svg" alt="Kipchak by Mamluk" title="Kipchak by Mamluk - an API Toolkit" />

# Kipchak by Mamluk

## What Kipchak is?

Kipchak is an API Development Kit (or ADK) to rapidly build APIs. It's built on years of experience
and managing APIs in production environments that handle thousands of concurrent requests. This kit has
been put together to make it easy for our engineers to build and maintain such APIs. 
It's built on top of the <a href="https://www.slimframework.com/" target="_blank">Slim Framework</a> and 
may be described as a packaged (and opinionated) implementation of Slim. 
It does not take anything away from Slim and you can still use anything within Slim with Kipchak.

## OK, that's enough. How do I see it in action?
This repository is for the core ADK. To get started with Kipchak and see it in action, 
head over to https://github.com/mam-luk/kipchak-template.

## Not so fast, I want to learn more. What Kipchak is not?
Kipchak is not a framework like Symfony or Laravel. In fact, it's built on the 
<a href="https://www.slimframework.com/" target="_blank">Slim Framework</a>. Why? 
Because Slim is fast and efficient and has a much lower footprint (memory, processing and size) than Symfony 
and Laravel, which, whilst having their merits, have a much larger memory and processing footprint. 
Kipchak, however, borrows libraries from Symfony and Laravel to enable the ADK.

## OK, so it's Slim. What do I get by using it?
You get everything that we need to use at Mamluk and Islamic Network to build APIs:

* Super easy config management
* Consistent bootstrapping, dependency and middleware injection
* Seamless upgrades (unless core routing in a Slim upgrade changes)
* Consistent upgrades - we always need to update multiple APIs, so there will be a way forward
* The following components pre-installed, and enabled / disabled via YAML files:
  * Symfony Cache, pre-setup for Memached and File System Caching (Redis coming soon, perhaps)
  * Configurable HTTP caching headers
  * Doctrine DBAL and ORM, to connect to and deal with multiple RDBMS (yes, we thought through this with Propel, Atlas and Eloquent too, but settled on Doctrine. We'll share why at some point.)
  * A CouchDB Client for integration with CouchDB (a reliable, distributed, eventually consistent NoSQL database)
  * Session Handling within Memcached or CouchDB
  * OAuth 2 based authentication and authorisation with JWKS
  * Key based authentication
  * Consistent error and exception handling
  * Laravel's HTTP client bundled in (which is a breath of fresh air if you use Guzzle regularly)
  * Monolog for consistent logging
  * A posture on how to handle routes, controllers, models and the general layout of your application
  * Generation of OpenAPI specifications for your APIs from your code (using Swagger PHP)
  * Object Mapping / Data Transfer Objects (with Valinor) 
  * Pre-configured testing tools (work in progress)

## Where can I find Documentation how to use Kipchak?

On https://github.com/mam-luk/kipchak-template.

## Great. Why on earth is this ADK called Kipchak? And who and what is a Mamluk?

This will be explained soon. It's more important to move Kipchak out of Alpha first.

## Credits and Thanks

* Slim Framework
* Symfony (for Symfony Cache and Doctrine)
* Laravel for the HTTP client
* Monolog
* Team CuyZ for Valinor (Object Mapping / Data Trasnfer Objects)
* Robert Allen for the Swagger PHP library
* Byran Horna for Slim Session

For a full list of packages within Kipchak, see https://github.com/mam-luk/kipchak/blob/master/composer.json#L14.

