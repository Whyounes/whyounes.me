---
title: Putting your Laravel controllers on a diet
author: Adam Wathan
slug: putting-your-laravel-controllers-on-a-diet
date: 2013-10-02
---

Imagine you're building a web application that needs to allow new users to register *(crazy idea right?)*.

You setup a route to a controller action that renders the beautiful registration form you've created, and now you need to use the data from that form to create the new user.

You might start off with something like this:
~~~language-php
// UserController.php

// Create a new user based on form input
public function store()
{
	$user = new User(Input::all());
	$user->save();

	return View::make('account-created');
}
~~~

But then you think, *"Crap, what if something goes wrong when trying to save the new user?"*, so you add in a little error checking...
~~~language-php
// UserController.php

// Create a new user based on form input
public function store()
{
	$user = new User(Input::all());

	if ( ! $user->save()) {
		return Redirect::to('/users/create')->with('message', 'Something went wrong!');
	}

	return View::make('account-created');
}
~~~

You give the account registration page to a co-worker to try out and that smartass tries to register without supplying a password.

*"Fuck, form validation..."*

Next thing you know you end up with this:

~~~language-php
// UserController.php

// Create a new user based on form input
public function store()
{
	$input = Input::all();

	$rules = array (
		'email' => array('required', 'email', 'unique:users'),
		'password' => array('required', 'confirmed', 'min:6'),
		'first_name' => array('required'),
		'last_name' => array('required'),
		'date_of_birth' => array('required', 'date'),
	);

	$validation = Validator::make($input, $rules);

	if ($validation->fails()) {
		return Redirect::to('/users/create')->withErrors($validation)->withInput();
	}

	$user = new User($input);

	if ( ! $user->save()) {
		return Redirect::to('/users/create')->with('message', 'Something went wrong!');
	}

	return View::make('account-created');
}
~~~

This sucks.

The reason it sucks is because your controller is jammed full of logic that it really shouldn't give a shit about.

The biggest pitfall inexperienced developers fall into when learning to use a framework like Laravel is putting their code in the wrong place. It's way too common for people to associate the word "model" with "an object that is represented by a row in my database table."

The reality is that your "model" is your *real* application. You're allowed to create classes that don't correspond to tables, really!

So what can we do with this newfound knowledge? Well let's check out the controller again and see if we can find a way to clean up that mess...

##Extracting a Class

The first thing that sticks out to me is this whole validation section:

~~~language-php
$input = Input::all();

$rules = array (
	'email' => array('required', 'email', 'unique:users'),
	'password' => array('required', 'confirmed', 'min:6'),
	'first_name' => array('required'),
	'last_name' => array('required'),
	'date_of_birth' => array('required', 'date'),
);

$validation = Validator::make($input, $rules);

if ($validation->fails()) {
	return Redirect::to('/users/create')->withErrors($validation)->withInput();
}
~~~

That's a lot of overhead to have to deal with in the controller. So let's think about what we're actually trying to do here...

1. We're getting the input from the form
2. We're defining the validation rules for the form
3. We check to see if the form is valid

All this stuff is related to the form! So where the hell is our *UserRegistrationForm* class?

Let's create a new class for our form and put it in our models directory. Something like this should get us started...

~~~language-php
class UserRegistrationForm
{
	private $rules = array (
		'email' => array('required', 'email', 'unique:users'),
		'password' => array('required', 'confirmed', 'min:6'),
		'first_name' => array('required'),
		'last_name' => array('required'),
		'date_of_birth' => array('required', 'date'),
	);

	private $attributes;
	private $validation;

	public function __construct(array $attributes)
	{
		$this->attributes = $attributes;
	}

	public function isInvalid()
	{
		return ! $this->isValid();
	}

	public function isValid()
	{
		$this->validation = Validator::make($this->attributes, $this->rules);
		return $this->validation->passes();
	}

	public function getValidation()
	{
		return $this->validation;
	}
}
~~~

So now we've got a class that represents the registration form. It can tell you whether or not it's valid, and let you know what the problems are if the validation doesn't pass.

Great! Let's refactor the controller a little bit and see what we've got...

~~~language-php
// UserController.php

// Create a new user based on form input
public function store()
{
	$form = new UserRegistrationForm(Input::all());

	if ($form->isInvalid()) {
		return Redirect::to('/users/create')->withErrors($form->getValidation())->withInput();
	}

	$user = new User(Input::all());

	if ( ! $user->save()) {
		return Redirect::to('/users/create')->with('message', 'Something went wrong!');
	}

	return View::make('account-created');
}
~~~

Isn't that way better? If we want to get *really* crazy *(and you aren't one of those anti-active record purists)*, we could take it even further:

~~~language-php
// UserRegistrationForm.php

class UserRegistrationForm
{
	// ...

	public function save()
	{
		if ($this->isInvalid()) {
			return false;
		}

		$user = new User($this->attributes);

		return $user->save();
	}

	// ...
}


// UserController.php

public function store()
{
	$form = new UserRegistrationForm(Input::all());

	if ( ! $form->save()) {
		return Redirect::to('/users/create')->withErrors($form->getValidation())->withInput();
	}

	return View::make('account-created');
}
~~~

Pretty clean right?

Of course, this exact refactoring isn't necessarily perfect and it won't always be the right solution, but hopefully it gives you some ideas and helps break down that "model = database" barrier a little bit.
