---
title: Test Coverage with Code Climate and Travis CI
author: Adam Wathan
slug: test-coverage-code-climate-travis-ci
date: 2014-12-28
---

This weekend I decided to add test coverage analysis for a few of my projects. I was already using [Travis CI](http://travis-ci.org) for running my tests and [CodeClimate](http://codeclimate.com) for code quality analysis, so I thought I would try and set it up with the tools I was already using.

If you're unfamiliar, test coverage analysis just tells you what percentage of your code is actually executed when you run your test suite. It's not necessarily an indicator that your tests are any good, but it can be helpful for tracking down untested code.

CodeClimate and Travis CI both have documentation for setting this up, but both of them left out enough important steps, or were just wrong in enough ways that I thought I'd go over what I had to do to actually get it working.

### 1. Find your CodeClimate repository token

This is literally all you need to do with CodeClimate.

Open your repository in CodeClimate and look for the button in the right hand column that says *"Set Up Test Coverage"*:

![CodeClimate Set Up Test Coverage](/img/codeclimate-setup-coverage.png)

This will take you to an instructions page where you'll find your token, which is buried in the instruction steps.

Look for a line that looks something like this:

~~~language-bash
$ CODECLIMATE_REPO_TOKEN=y0ur53cr37t0k3n
~~~

You want to grab everything after the equals sign and keep it handy.

### 2. Add your CodeClimate repository token to Travis CI

This is where the other instructions start getting things wrong. Even the docs at Travis CI will tell you to add this to your `.travis.yml` file.

*Do not.*

It's a secret token, don't commit it to your repo!

Instead, add it as an environment variable to the Travis build:

![Travis CI Environment Variables](/img/travis-ci-environment-vars.png)

### 3. Add Code Climate's test reporter package to your project

To get this setup, just run the following on the command line from your project root:

~~~language-bash
composer require codeclimate/php-test-reporter --dev
~~~

### 4. Update your `phpunit.xml` to generate a code coverage report

Add this section to your `phpunit.xml`:

~~~language-xml
<phpunit>
    ...
    <logging>
        <log type="coverage-clover" target="build/logs/clover.xml"/>
    </logging>
    ...
</phpunit>
~~~

If you want to try this locally, you'll need XDebug installed. Neither CodeClimate or Travis CI mention this in their docs :)

### 5. Create a script for sending coverage information to CodeClimate

If you try to follow the CodeClimate or Travis CI instructions, you'll find that things just straight up don't work.

After a bit of log diving, I found out it's because of [this SSL certificate error](https://github.com/codeclimate/php-test-reporter#known-issue-ssl-certificate-error).

Unfortunately, the workaround mentioned there *doesn't work either*. Adding those lines to your `.travis.yml` file results in a syntax error. [Try it yourself](http://lint.travis-ci.org/).

The workaround for the workaround is to save those lines to a separate script.

Save this as `codeclimate.sh` in your project root:

~~~bash
#!/usr/bin/env sh

php vendor/bin/test-reporter --stdout > codeclimate.json
curl -X POST -d @codeclimate.json -H 'Content-Type: application/json' -H 'User-Agent: Code Climate (PHP Test Reporter v0.1.1)' https://codeclimate.com/test_reports
~~~

### 6. Make that script executable

I always forget this step.

From your project root, run this command:

~~~bash
chmod +x ./codeclimate.sh
~~~

### 6. Run the script after your tests finish

Once your tests have finished running, you need to send that coverage report off to CodeClimate. That's what our `codeclimate.sh` script is for.

To run it after your tests, add it as an `after_script` to your `.travis.yml`. Here's what mine looks like:

~~~bash
language: php

php:
    - 5.3
    - 5.4
    - 5.5

install: composer install --dev

after_script: ./codeclimate.sh
~~~

And that's it! Now any time you push a build to Travis CI, it'll automatically report code coverage statistics to Code Climate.
