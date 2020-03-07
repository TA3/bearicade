<?php

namespace Amp\Parallel\Worker\Internal;

use Amp\Parallel\Sync;
use Amp\Parallel\Worker;
use Amp\Promise;

return function (Sync\Channel $channel) use ($argv): Promise {
    if (!\defined("AMP_WORKER")) {
        \define("AMP_WORKER", "amp-worker");
    }

    if (!isset($argv[1])) {
        throw new \Error("No environment class name provided");
    }

    $className = $argv[1];

    if (!\class_exists($className)) {
        throw new \Error(\sprintf("Invalid environment class name '%s'", $className));
    }

    if (!\is_subclass_of($className, Worker\Environment::class)) {
        throw new \Error(\sprintf(
            "The class '%s' does not implement '%s'",
            $className,
            Worker\Environment::class
        ));
    }

    $environment = new $className;

    $runner = new Worker\TaskRunner($channel, $environment);

    return $runner->run();
};
