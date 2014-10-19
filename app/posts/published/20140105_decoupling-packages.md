---
title: Decoupling your packages from your framework
author: Adam Wathan
slug: decoupling-your-packages-from-your-framework
date: 2014-01-05
---

Recently, there was a bit of a debate on Twitter about the value of framework agnostic packages vs. the effort required to build them.

I thought this might be a good opportunity to go through a real world example of *how* to make a package framework agnostic, and explain why this is a desireable characteristic even if you don't care about ever using the package outside of your favorite framework.

> Most of these examples need some more error checking that has been omitted for brevity, and the post is still too long.

## Our subject

I released a [form-building package](https://github.com/adamwathan/form) not too long ago that works as an alternative for the native form builder that ships with Laravel 4.

It had a couple of features that would depend on functionality from outside the form package itself:

- Remembering old input a user had entered
- Retrieving and displaying error messages for each field

So at the simplest level, we would need two methods within the form builder:

1. `getOldInput($key)`
2. `getError($key)`

## The fastest implementation

When I built this package, it was to use it in a Laravel project. So what's the quickest way we could get this working in Laravel? 

Well for getting old input, Laravel very conveniently provides this:

`Session::oldInput($key)`

Perfect, right?

So we have an implementation of our first method:

~~~language-php
public function getOldInput($key)
{
    return Session::getOldInput($key);
}
~~~

What about errors? Well in Laravel, you can use `withErrors($errors)` to redirect, well, with errors, and those errors will be available in the session under the `errors` key. So we can whip up an implementation of `getError` like so:

~~~language-php
public function getError($key)
{
    return Session::get('errors')->first($key);
}
~~~

But we all know this sucks, because now you can't mock the Session class since it's hardcoded into the form builder.

> I realize you can still mock this specific class due to some IOC and Facade magic in Laravel, but that's a bit besides the point.

## Something a little better

A better option would be to inject the Session object into the form builder. This way we can at least easily swap it out with a mock so we can actually test this thing.

~~~language-php
class FormBuilder
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getOldInput($key)
    {
        return $this->session->getOldInput($key);
    }

    public function getError($key)
    {
        return $this->session->get('errors')->first($key);
    }
}
~~~

## There's still a problem

This class is still tightly coupled to Laravel, because it depends very specifically on Laravel's session class (`Illuminate\Session\Store` in this specific case.) So if you wanted to use this form building class in another framework, you'd have to pull in the entire Laravel session package, which also depends on these packages:

- `illuminate/cache`
- `illuminate/cookie`
- `illuminate/encryption`
- `illuminate/support`
- `symfony/http-foundation`

So now you have to bootstrap all of these different packages just to be able to use this basic form building class in another framework.

This is insane.

## But that's not even the worst part

The worst part is that we haven't even identified the correct abstractions here.

Why does the form builder care about the session? It shouldn't need the session or even care what a session is. 

What it really needs is somewhere to get old input, and somewhere to get errors. Maybe it ultimately gets them from the session, maybe it doesn't. It shouldn't care.

## So what do we do?

We inject what the form builder actually needs, abstracted behind an interface.

~~~language-php
class FormBuilder
{
    protected $errors;
    protected $oldInput;

    public function __construct(ErrorStoreInterface $errors, OldInputStoreInterface $oldInput)
    {
        $this->errors = $errors;
        $this->oldInput = $oldInput;
    }

    public function getOldInput($key)
    {
        return $this->oldInput->getOldInput($key);
    }

    public function getError($key)
    {
        return $this->errors->getError($key);
    }
}
~~~

...and here's our interfaces:

~~~language-php
interface ErrorStoreInterface
{
    public function getError($key);
}
~~~

~~~language-php
interface OldInputStoreInterface
{
    public function getOldInput($key);
}
~~~

Now we can easily write implementations of these interfaces that use Laravel's session functionality:

~~~language-php
class LaravelErrorStore implements ErrorStoreInterface
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getError($key)
    {
        return $this->session->get('errors')->first($key);
    }
}
~~~

~~~language-php
class LaravelOldInput implements OldInputInterface
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getOldInput($key)
    {
        return $this->session->getOldInput($key);
    }
}
~~~

Set these up in a service provider exactly like we would've had to do when just injecting the Session class, and we are in business.

## Usage outside of Laravel

Now we can use this class anywhere, even in the shittiest old legacy PHP project ever with implementations like this:

~~~language-php
class ShittyLegacyOldInput implements OldInputInterface
{
    public function getOldInput($key)
    {
        return $_SESSION['old_input'][$key];
    }
}
~~~

## Caveats

Obviously there are situations where this is either impossible or not worth it.

If you are writing something that is meant to integrate very tightly with some highly opinionated existing part of the framework you are using, you obviously can't make the whole package framework agnostic without making it less useful.

## The point

The real take-away here is that our package would've been framework agnostic from the beginning if we had identified the right abstractions in the first place.

Thoughtful, decoupled code is always as framework agnostic as possible automatically. 

Asking yourself, "how can I make this package framework agnostic?" is the wrong approach. Just follow the same SOLID object oriented principles you should be following already and your code is going to be as decoupled as it can be, which will make your package framework agnostic if it ever could've been.
