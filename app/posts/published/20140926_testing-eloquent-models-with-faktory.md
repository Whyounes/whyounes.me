---
title: Testing Eloquent Models with Faktory
author: Adam Wathan
slug: testing-eloquent-models-with-faktory
date: 2014-09-26
---

Eloquent is an [ActiveRecord][1] implementation, which means a lot of the time the behavior you're adding to your models needs to hit the database to work correctly.

Imagine you a `Customer` model who has many `Orders`, and you need a way to get the orders for that customer that haven't been shipped yet.

The implementation could be as simple as this:

<pre><code class="language-php">&lt;?php

// Customer.php
public function getOpenOrders()
{
    return $this-&gt;orders()-&gt;whereNull('date_shipped')-&gt;get();
}
</code></pre>

But how would you write a test to make sure you are getting the correct orders back?

## Unit Testing

If you try to test this in isolation, you might try to stub the `orders` relationship:

<pre><code class="language-php">&lt;?php

// CustomerTest.php
public function test_it_can_retrieve_open_orders()
{
    $open_orders = M::mock('OrderCollection');
    $customer = M::mock('Customer[orders]');
    $customer-&gt;shouldReceive('orders-&gt;whereNull-&gt;get')-&gt;andReturn($open_orders);

    $this-&gt;assertEquals($open_orders, $customer-&gt;getOpenOrders());
}
</code></pre>

This test passes, but if you think about it, you're really not testing that anything actually *works*. All you're doing is taking the implementation that you *expect* to work, and duplicating it in your test!

In fact, this test passes even if `orders()` hasn't been defined on `Customer`. It also passes if the logic is incorrect, as long as you just put the same incorrect logic in both the Customer and the test.

We need to know that when we ask for open orders, we get back the orders that *really are* still open.

## Functional Testing

If you want to make sure you're actually getting back the right orders, you need to hit the database.

> *Laravel makes it really easy to setup an in-memory SQLite database for tests, [here's an example][2].*

The approach I like to use goes like this:

1.  Setup some shipped orders and some unshipped orders
2.  Save those orders to a customer
3.  Ask that customer for their open orders
4.  Verify that the orders that come back are the ones we expect

So you might end up with a test that looks something like this:

<pre><code class="language-php">&lt;?php

// CustomerTest.php
public function setUp()
{
    parent::setUp();
    Artisan::call('migrate');
}

public function test_it_can_retrieve_open_orders()
{
    Eloquent::unguard();

    $shipped_order_1 = new Order([
        'shipping_address' =&gt; '123 Fake St.',
        'shipping_city' =&gt; 'Fakeville',
        'shipping_province' =&gt; 'Ontario',
        'shipping_country' =&gt; 'Canada',
        'shipping_postal_code' =&gt; 'ABC 123',
        'date_shipped' =&gt; new DateTime('5 days ago'),
    ]);

    $shipped_order_2 = new Order([
        'shipping_address' =&gt; '123 Fake St.',
        'shipping_city' =&gt; 'Fakeville',
        'shipping_province' =&gt; 'Ontario',
        'shipping_country' =&gt; 'Canada',
        'shipping_postal_code' =&gt; 'ABC 123',
        'date_shipped' =&gt; new DateTime('3 days ago'),
    ]);

    $unshipped_order = new Order([
        'shipping_address' =&gt; '123 Fake St.',
        'shipping_city' =&gt; 'Fakeville',
        'shipping_province' =&gt; 'Ontario',
        'shipping_country' =&gt; 'Canada',
        'shipping_postal_code' =&gt; 'ABC 123',
        'date_shipped' =&gt; null,
    ]);

    $customer = Customer::create([
        'first_name' =&gt; 'John',
        'last_name' =&gt; 'Doe',
        'email' =&gt; 'example@example.com',
        'phone' =&gt; '555 555 5555',
    ]);

    $customer-&gt;orders()-&gt;saveMany([
        $shipped_order_1,
        $shipped_order_2,
        $unshipped_order
    ]);

    $open_orders = $customer-&gt;getOpenOrders();

    $this-&gt;assertTrue($open_orders-&gt;contains($unshipped_order));
    $this-&gt;assertFalse($open_orders-&gt;contains($shipped_order_1));
    $this-&gt;assertFalse($open_orders-&gt;contains($shipped_order_2));
}
</code></pre>

Well that felt excessive. 40+ lines of setup for 3 assertions? There must be a better way...

## Faktory

The thing that sucks about all that setup is that you really only care about the `date_shipped` field on the orders. But since you need to save these orders to our test database, you need to make sure you're providing valid values for every field or you're going to hit an error when you try to save the records.

The solution to this problem is to use factories to generate the objects for you.

### Factories?

Think of factories as little helpers that can spit out your Eloquent models in their minimally valid state.

They also let you easily specify the attributes that are actually relevant to your test. This has a big advantage over just using seed data or fixtures, as it keeps all of the details important to your test together in one place. This makes it really easy for someone reading your test to see the whole picture and understand what you're trying to test.

### Defining factories

In the test above, there's a lot of details about orders and customers that aren't relevant to what's being tested. You can trim a lot of the cruft by defining factories that fill in the irrelevant details for you.

The factories are going to look like this:

<pre><code class="language-php">&lt;?php

Faktory::define(['order', 'Order'], function ($f) {
    $f-&gt;shipping_address = '123 Fake St.';
    $f-&gt;shipping_city = 'Fakeville';
    $f-&gt;shipping_province = 'Ontario';
    $f-&gt;shipping_country = 'Canada';
    $f-&gt;shipping_postal_code = 'ABC 123';
});

Faktory::define(['customer', 'Customer'], function ($f) {
    $f-&gt;first_name = 'John';
    $f-&gt;last_name = 'Doe';
    $f-&gt;email = 'example@example.com';
    $f-&gt;phone = '555 555 5555';
});

</code></pre>

### Updating the test

Using Faktory, the test ends up looking like this:

<pre><code class="language-php">&lt;?php

// CustomerTest.php
public function setUp()
{
    parent::setUp();
    Artisan::call('migrate');
    Eloquent::unguard();
}

public function test_it_can_retrieve_open_orders()
{
    $customer = Faktory::create('customer');
    $shipped_order1 = Faktory::create('order', ['date_shipped' =&gt; new DateTime('5 days ago')]);
    $shipped_order2 = Faktory::create('order', ['date_shipped' =&gt; new DateTime('3 days ago')]);
    $unshipped_order = Faktory::create('order', ['date_shipped' =&gt; null]);

    $customer-&gt;orders()-&gt;save([$shipped_order1, $shipped_order2, $unshipped_order]);

    $open_orders = $customer-&gt;getOpenOrders();

    $this-&gt;assertTrue($open_orders-&gt;contains($unshipped_order));
    $this-&gt;assertFalse($open_orders-&gt;contains($shipped_order1));
    $this-&gt;assertFalse($open_orders-&gt;contains($shipped_order2));
}
</code></pre>

Using Faktory, you can cut out any unnecessary details in our test, while also making it clear and explicit what details actually matter to what you're testing. You also now have all of the information about minimally valid orders and customers encapsulated into one place, so if that ever changes, updating the tests is going to be trivial.

To find out more about Faktory, check out [the documentation][3] on GitHub.

 [1]: http://www.martinfowler.com/eaaCatalog/activeRecord.html
 [2]: https://gist.github.com/adamwathan/459254b8bf210bcaeeca
 [3]: https://github.com/adamwathan/faktory/
