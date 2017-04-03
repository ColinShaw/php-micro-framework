# PHP Micro Framework

This is an extension to my prior [PHP Mini Framework](https://github.com/ColinShaw/php-mini-framework) 
that cleaves some functionality but leaves you with a perfectly good loader, router and response 
handler that includes headers, all in less than 60 lines and 1250 bytes (the Mini framework was just 
too much)!  And that is with really, really fluffy formatting.  This is just another go at making 
writing PHP code simpler and faster to deploy when you have to touch the stuff (New slogan: "PHP: Because 
sometimes you just have to.").  Minimal is the way to go!

To use this, first clone the project.  Update the `.htaccess` as needed to meet the needs
of your base project.  Add your own handlers in one or more directories.  What tends to be handy is to put
your support classes, like database connectivity, in one place, and your process code in 
a separate place.  It is helpful for configurations and whatnot if your process code
extends a parent class that loads the configs and whatever else is useful so that the 
sub-classes have access to it.  You can organize this around all sorts of organizational
patterns: MVC, MVVM, RALPH, whatever.  Define some routes that will invoke these.  

The simplest route you can make is probably a test route:

```
    '/test$/i' => function() {
        return 'Yep';
    }
```

That's right, routes are regular expressions.  If you need simple regular expressions simplified 
at the expense of your application speed, you really should learn how to use them better.  If you 
want one or more variables passed through the route, do something like this:

````
    '/test\/(\S+)\/(\S+)$/i' => function($v1, $v2) {
        return $v1 . ' - ' . $v2;
    }

````

These are obviously alphanumeric, but you would use `\d+` or whatever you need for your 
specific routes.  You will notice that the `$header` is a public variable.  There is reason for this, and that is
so that you can inject it into your code and change the headers.  If you are making a JSON API 
then this is not really all that useful, but if you intend to have download endpoints or serve
page content, then you can easily change it.  Don't forget to dereference it so that your code
affects the actual variable.  An example route doing this might be:

```
   '/test\/(\S+)$/' => function($value) {
       return (new Test($this))->doSomething($value);
   }

```

Pass the current object to the Test constructor.  Since `$header` is public you can then
manipulate it.  Since you are using the Test class, it will need to be loaded.  If it is in a `code`
subdirectory, for example, then just add `code` to the `$include_paths` array and it will be loaded.

That's it, you can add to it whatever you need.  Organize your code the way _you_ want it, use whatever
design pattern is right for _your_ project.  With a few short lines and a little tiny bit of thought
you can easily make a system that solves most all problems you might have.  Yes, you can have middleware, 
just write it yourself.  Yes, you can inject dependencies, just build a relevant container.  All very 
simple stuff.  Do it yourself so you know how each bit of your code works.
