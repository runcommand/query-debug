runcommand/query-debug
======================

Find the query causing your performance issues.

[![Build Status](https://travis-ci.org/runcommand/query-debug.svg?branch=master)](https://travis-ci.org/runcommand/query-debug)

Quick links: [Using](#using) | [Installing](#installing) | [Support](#support)

## Using

~~~
wp query-debug [--url=<url>] [--format=<format>]
~~~

Executes a request to WordPress to identify which queries are run, and
how long they took. Useful for taking a peek into which pages might be
having performance issues.

```
$ wp query-debug --url=http://wp.dev/2016/04/14/hello-world/ --format=summary
Loading http://wp.dev/2016/04/14/hello-world/ executed 28 queries in 0.006749 seconds.
```

Use the `--debug` flag to inspect the main query and rendered theme template:

```
$ wp query-debug --url=http://wp.dev/2016/04/14/hello-world/ --debug
Debug (query-debug): Main WP_Query: is_single, is_singular
Debug (query-debug): Theme template: twentyfifteen/single.php
```

**OPTIONS**

	[--url=<url>]
		Execute a request against a specified URL. Default to the home URL.

	[--format=<format>]
		Render results in a specific format.
		---
		default: summary
		options:
		  - summary # Summary including number of queries and total time.
		  - table # List of all queries.
		  - json
		  - yaml
		  - count # Total number of queries.
		---

## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/query-debug`.

## Support

This package is free for anyone to use. Support is available to paying [runcommand](https://runcommand.io/) customers.

Think you’ve found a bug? Before you create a new issue, you should [search existing issues](https://github.com/runcommand/sparks/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version. Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/runcommand/sparks/issues/new) with description of what you were doing, what you saw, and what you expected to see.

Want to contribute a new feature? Please first [open a new issue](https://github.com/runcommand/sparks/issues/new) to discuss whether the feature is a good fit for the project. Once you've decided to work on a pull request, please include [functional tests](https://wp-cli.org/docs/pull-requests/#functional-tests) and follow the [WordPress Coding Standards](http://make.wordpress.org/core/handbook/coding-standards/).

Github issues are meant for tracking bugs and enhancements. For general support, email [support@runcommand.io](mailto:support@runcommand.io).


