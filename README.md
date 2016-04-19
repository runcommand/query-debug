runcommand/query-debug
======================

Find the query causing your performance issues.


Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using


~~~
wp query-debug [--format=<format>]
~~~

Executes a request to WordPress to identify which queries are run, and
how long they took. Useful for taking a peek into which pages might be
having performance issues.

**OPTIONS**

	[--format=<format>]
		Render results in a specific format.
		---
		default: table
		options:
		  - table
		  - json
		  - yaml
		  - count
		---



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/query-debug`

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/runcommand/query-debug/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
