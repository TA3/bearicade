# Changelog

## 1.2.0 (2017-08-03)

* Feature / Fix: Forward compatibility with future EventLoop v1.0 and v0.5 and
  cap small timeout values for legacy EventLoop
  (#26 by @clue)

  ```php
  // now works across all versions
  Block\sleep(0.000001, $loop);
  ```

* Feature / Fix: Throw `UnexpectedValueException` if Promise gets rejected with non-Exception
  (#27 by @clue)

  ```php
  // now throws an UnexceptedValueException
  Block\await(Promise\reject(false), $loop);
  ```

* First class support for legacy PHP 5.3 through PHP 7.1 and HHVM
  (#24 and #25 by @clue)

* Improve testsuite by adding PHPUnit to require-dev and
  Fix HHVM build for now again and ignore future HHVM build errors
  (#23 and #24 by @clue)

## 1.1.0 (2016-03-09)

* Feature: Add optional timeout parameter to all await*() functions
  (#17 by @clue)

* Feature: Cancellation is now supported across all PHP versions
  (#16 by @clue)

## 1.0.0 (2015-11-13)

* First stable release, now following SemVer
* Improved documentation

> Contains no other changes, so it's actually fully compatible with the v0.3.0 release.

## 0.3.0 (2015-07-09)

* BC break: Use functional API approach instead of pseudo-OOP.
  All existing methods are now exposed as simple functions.
  ([#13](https://github.com/clue/php-block-react/pull/13))
  ```php
// old
$blocker = new Block\Blocker($loop);
$result = $blocker->await($promise);

// new
$result = Block\await($promise, $loop);
```

## 0.2.0 (2015-07-05)

* BC break: Rename methods in order to avoid confusion.
  * Rename `wait()` to `sleep()`.
    ([#8](https://github.com/clue/php-block-react/pull/8))
  * Rename `awaitRace()` to `awaitAny()`.
    ([#9](https://github.com/clue/php-block-react/pull/9))
  * Rename `awaitOne()` to `await()`.
    ([#10](https://github.com/clue/php-block-react/pull/10))

## 0.1.1 (2015-04-05)

* `run()` the loop instead of making it `tick()`.
  This results in significant performance improvements (less resource utilization) by avoiding busy waiting
  ([#1](https://github.com/clue/php-block-react/pull/1))

## 0.1.0 (2015-04-04)

* First tagged release
