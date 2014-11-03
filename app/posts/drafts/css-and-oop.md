---
title: CSS and OOP
author: Adam Wathan
slug: css-and-oop
date: 2014-09-22
---

@extend is a lie

Applying OOP principles to your CSS can help you write much better CSS, but there are some important differences in how you apply these principles that trip people up and can take you down the wrong path.

## @extend isn't inheritance

The `@extend` directive in SASS and LESS does not *really* extend another class. Yes, it takes what was defined in another class and makes it apply to your current class as well, but that does not make your current class a true "sub-class" of the class you are extending.

Let's look at an example:

```scss
// SCSS
.btn {
  border-radius: 4px;
}
.btn--primary {
  @extend .btn;
  background: blue;
}

// Output
.btn, .btn--primary {
  border-radius: 4px;
}
.btn--primary {
  background: blue;
}
```

At first it might seem like this is doing exactly what you need. The `.btn--primary` class gets all of the styles of the `.btn` class and none of the actual styles are duplicated in your CSS.

But in terms of OOP, the real result here is still more like a mixin or a trait, despite the fact that we aren't using a mixin in Less/Sass terms.

Imagine we had a sidebar and we needed every `.btn` in that sidebar to have square corners. We could try writing something like this:

```scss
.sidebar .btn {
  border-radius: 0;
}
```

...which generates output like this:

```scss
.btn, .btn--primary {
  border-radius: 4px;
}
.btn--primary {
  background: blue;
}
.sidebar .btn, .sidebar .btn--primary {
  border-radius: 0;
}
```

...and our markup would look like this:

```html
<div class="sidebar">
    <button class="btn">Regular button</button>
    <button class="btn--primary">Primary button</button>
</div>
```

Contrast that with doing the composition in the markup itself:

```html
<div class="sidebar">
    <button class="btn">Regular button</button>
    <button class="btn btn--primary">Primary button</button>
</div>
```

Now our SCSS and output looks like this:

```scss
// SCSS and output, no difference
.btn {
  border-radius: 4px;
}
.btn--primary {
  background: blue;
}
.sidebar .btn {
  border-radius: 0;
}
```

Four characters in the markup save us 13 characters in the source SCSS, and a whopping 39 characters in the generated CSS. This adds up big time when you add a few more button styles, or some extra button sizes.

The reason this happens is because trying to use something like the `@extend` feature is fighting against the nature of browser applies CSS rules. It generates a ton of extra selectors in the output to make up for the fact that CSS just doesn't work the way you are trying to make it work.

You end up with much more maintainable CSS if you stop thinking of minimal markup as good or pure markup. The way CSS works means you *need* to use the markup to compose your components and identify their relationships.

From the browser's perspective, an element with the  `.btn--primary` class isn't a `.btn` unless it includes the `.btn` class as well, no matter what you do in your stylesheet.

## Utility classes

I used to think they were stupid but they serve a very useful purpose. If you need to apply just one stupid reusable set of styles to something, it is much easier to add a utility class than it is to come up with a name for that thing just for the purpose of adding that utility class.

If you have an image that needs to be pulled left, `u-pullLeft` is easier and more reusable than `article-image--aside`.

You need to think of your markup as the place where inheritance and composition happens, not the stylesheet.
