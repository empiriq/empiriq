<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

interface RunnableInterface
{
    public function run(): PromiseInterface;
}
