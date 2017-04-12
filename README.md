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



### Practical Use Notes

You can always take it or leave it with some the (scant) features.  If you are writing a JSON API then
you can dispense with the header stuff altogether and just send a static header.  This will save you some
time, LOC and complexity.  It has proven to be a good and quick starting point for a lot of applications
with varying complexity from simple to modest.  If you are clever with your route implementation then
you could even use it for a fairly complex application.  

If you are needing to produce templated HTML, you might consider using a templating package like 
[Mustache](https://mustache.github.io/) or similar.  Or you might recall that PHP itself is a templating
language.  Sure, sure, maybe you don't like the syntax, but your code really should depend on templating
that much anyway, so just get off it.  If you do need templating, it is a snap to include this type of 
content and change the headers appropriately.  What's more, you can very easily adapt this to being an
intelligent asset control system.  Very minimal development needed for this, and you get a lot of benefits
that are curiously not found in almost any of the large PHP frameworks, some of the old `asset()` wrapping
in Laravel and Symfony's `Ascetic` being about the only things that have this.

This has actually been a very useful little tool that has found itself into numerous projects where 
rapid development and good long-term maintainability by being able to know easily determine what the
code does are key points of the project.  Even people who have long been fans of other frameworks and 
tools are easy to get onboard.  Some of the reasons for this are there are no dependencies, you have
to really understand what the code does, you have great freedom to make the code exactly what you want
it to be, and it is so simple there is no clutter and nothing that you don't know how or why it works.  

In one environment where we use this, we have a great system that expands on this basic idea, adding 
templated templates, automatic CSRF synchronization tokens, a cache-busting mechanism based on 
the git branch so that tagged releases automatically force reload of the more brittle assets, some
very convenient base classes to subclass for implementation, and other stuff.  A person can take the
base project, configure it, and have functioning code with interface and backend elements in about 5 
minutes.  That's right, functioning for both sides in under 5 minutes.  With no unusual binary 
dependencies, minimal configuration, and minimal code.  The variant of the framework that does all 
this is right at 400 lines and less than 10k.  

If you ever look at my other repositories, you may note that I generally go completely off on PHP 
since it is an inconsistent and basically crappy language, the code one sees in the community 
is generally not just bad, the habits formed with people who use it are bad, etc.  I mean, it really 
is just the worst.  But the fact is, there is a lot of dreary legacy code that has been written in
PHP, a lot of infrastructure that is centered around PHP, and it is not often realistic to move off 
it so much as incrementally improve it.  Making a thoughtful, simple collection of tools that meet 
the needs of requirements can help make the best of the situation.
