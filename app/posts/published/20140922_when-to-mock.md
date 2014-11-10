---
title: When to mock
author: Adam Wathan
slug: when-to-mock
date: 2014-09-22
---

Test doubles come in a ton of different flavors.

*Dummies, fakes, mocks, stubs, spies, oh my!*

But I don't think it needs to be this complicated. In my mind, there's really only two categories of test doubles.

### Stubs &rarr; Simple doubles

Any test double that just sits in for a collaborator and returns canned results is firmly in the *stub* category.

*Dummies* are the `/dev/null` of the test double world. They take whatever you throw at them, deposit it directly into a black hole and return null. Firmly in the stub category.

*Stubs* are preprogrammed to return certain results to certain calls. A little smarter than a dummy, but still *(as you might have guessed by the name)* a stub.

A stub can do nothing but provide indirect input to your test.

### Mocks &rarr; Observable doubles

A test double that allows you to observe its behavior is a mock. *Mocks* and *spies* both fall into to this category.

Mocks let you *set expectations* about what methods should be called and with what parameters, so that you can verify that those expectations are met.

While stubs can only provide indirect input to your tests, mocks actually have the power to trigger a test failure if an expected call isn't made.

With a tool like PHPSpec/Prophecy, this is the difference between a message that `willReturn()` something, and a message that `shouldBeCalled()`.

## Types of messages

Understanding when you need to set a mock expectation comes down to understanding the type of message you're sending to a collaborator.

**Queries** are messages that return a result and don't affect the state of the system. Think of queries as *asking a question*.

If I have an `Order`, I should be able to ask it for its `totalPrice()` as many times as I want without affecting the system. It's not going to create an entry in the database, or send someone an email. Query messages just answer a question; they don't have any side effects.

**Commands** are messages that invoke an action, and they shouldn't return a meaningful value. Think of commands as *giving an instruction*.

If I tell a `ShippingService` to ship an order, it might update the `date_shipped` field on that order to the current date and time. If I tell it to ship the same order again, that field is going to change accordingly. So our command message *does* have side effects, but we never cared about the return value.

## Don't mock queries

We can call a query any number of times without causing any change. It doesn't matter if we call it 100 times or 0 times.

Since a query doesn't change the state of the system, it doesn't matter to us if it does or doesn't get called. Setting expectations in our test that certain questions get asked is *test over specification*, and binds us to a specific implementation with no additional benefit.

Let's say our `Order` has a collection of `OrderItems`, and we want to test that the `totalPrice()` method correctly tallies up all of the prices. If each `OrderItem` has its own `getPrice()` method, then our test doubles just need to *stub* that method.

We only care that `totalPrice()` returns the price we expect. *How* it gets the right price doesn't matter. There could be 10 different ways of calculating it. Confirming in our test that a *specific* way was used doesn't add any value.

So if you're doubling a collaborator that needs to answer questions, just *stub* those methods, don't set mock expectations.

## Do mock commands

Only set a mock expectation when you really need to prove that a particular message is being sent.

Since only commands can change the state of the system, they're the only messages that need to be verified.

Let's say our `ShippingService` now needs to trigger a notification email any time an order is shipped. So we inject a `Mailer` instance that has a `sendMail()` method.

Since `sendMail()` is a command message, it's the perfect candidate for a mock expectation. We could test the `ShippingService` with a real `Mailer` instance by checking the contents of an inbox after we ship an order, but that would be testing the `Mailer`, not the `ShippingService`.

So instead we can use a test double for our `Mailer`, and set a mock expectation that proves that `sendMail()` gets called when we ship an order.

## Rules of thumb

1. Avoid creating methods that are both queries and commands. Any time you find yourself wanting to *mock* something that returns a meaningful value, treat it as a code smell.

2. If you're testing a query, you shouldn't need to set any mock expectations. A query method should only ask its collaborators questions, not give them instructions. Stubs are your weapon of choice here.

3. If you're testing a command, you *might* need to set mock expectations. Only set a mock expectation if the command you're testing triggers *another* command in a collaborating object. Mocks should only be used if it's the only way you can prove something does what it's supposed to do.
