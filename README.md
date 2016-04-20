runcommand/query-debug
======================

Find the query causing your performance issues.

[![Build Status](https://travis-ci.org/runcommand/query-debug.svg?branch=master)](https://travis-ci.org/runcommand/query-debug)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using


~~~
wp query-debug [--url=<url>] [--format=<format>]
~~~

Executes a request to WordPress to identify which queries are run, and
how long they took. Useful for taking a peek into which pages might be
having performance issues.

```
$ wp query-debug --url=http://wordpress-develop.dev/2016/04/14/hello-world/ --format=summary
Loading http://wordpress-develop.dev/2016/04/14/hello-world/ executed 28 queries in 0.006749 seconds.
```

**OPTIONS**

	[--url=<url>]
		Execute a request against a specified URL. Defaults to 'domain.com/'

	[--format=<format>]
		Render results in a specific format.
		---
		default: table
		options:
		  - table
		  - summary # Summary including number of queries and total time.
		  - json
		  - yaml
		  - count # Total number of queries.
		---



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/query-debug`

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/runcommand/query-debug/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
