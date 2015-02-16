---
title: Active Repository is an anti-pattern
author: Adam Wathan
slug: active-repository-is-an-antipattern
date: 2015-02-14
---

Imagine you have `Posts` with `Comments`, and you need to add a new comment to a post.

## Active Record

Using Active Record, you'd write something like this:

~~~language-php
$post->addComment($comment);
~~~

This would set the foreign key on the comment, and save it to the database immediately.

## Data Mapper/Repositories

Using a Data Mapper implementation might look more like this:

~~~language-php
$post->addComment($comment);

// Some time later...
$postRepository->save($post);
~~~

The main difference here is that initially the comment would only be associated with the post *in memory*,
and saving the post through the repository would then save the comment and set any foreign keys.

## Active Record-backed Repositories

Lately it's become fashionable to create repositories that delegate to Active Record in an attempt to decouple the consuming code, while still getting some of the other Active Record productivity benefits.

~~~language-php
class PostRepository
{
    public function find($id)
    {
        return Post::find($id);
    }
    
    public function save($post)
    {
        return $post->save();
    }
}
~~~

This breaks down really badly when you need to work with relationships.

An Active Record `Post` has no `protected $comments = []` property.
Adding a comment saves it to the database right away. This is fundamental to how Active Record works.

This leads people down the path of managing relationships using the repository:

~~~language-php
$postRepository->addCommentToPost($comment, $post);
~~~

This is a fundamentally flawed, leaky abstraction.

Instead of just adding a comment to a post, you have to *explicitly* reach to the persistence layer to do it.

So trying to move all database access to the repositories comes at the expense of the rest of your application needing to know that the *only* way to establish a relationship is through a repository.

You would *never* write code like this with a real Data Mapper implementation.

## Just use Active Record

For this reason alone, I consider an Active Record-backed repository to be the worst of both worlds, not a convenient middleground. Using Active Record as intended is much more expressive.

~~~language-php
$post->addComment($comment);
~~~

If you really need to separate your domain model from your persistence layer *(most projects I've worked on honestly haven't benefited from it)*,
you are *much* better off using a real Data Mapper implementation than you are hiding record retrieval behind an Active Record-backed repository.

## Tips for using Active Record well

1. Keep all database access inside of your Active Record models. Don't call `$posts->comments()->save($comment)` outside of the Post class. Create a method like `addComment($comment)` that encapsulates it.

2. Save right away. With Active Record, the database is always the source of truth.
    
    If you have a `promoteToAdmin()` method on your `User` class, it should invoke `save()` internally. Try to avoid explicitly saving outside of the model.

3. Repositories can sometimes be useful for *retrieval only*. Active Record implementations usually use static methods to query the database, so sometimes it's helpful to wrap those methods to simplify testing.

    If you choose to do this, don't think of it as "separating your database access from your domain logic", that's not what we're trying to do. Instead, just think of it as trying to improve the testability of static calls. Your models should still save themselves.

