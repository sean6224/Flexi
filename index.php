<?php
require_once './vendor/autoload.php';


/*

$hookDependency = new HookDependency();

$hookDependency->addHookDependency('initialize', [], function () {
    echo "Initializing...\n";
});

$hookDependency->addHookDependency('doSomething', ['initialize'], function () {
    echo "Doing something after initialization.\n";
});

$hookDependency->addHookDependency('finalize', ['doSomething'], function () {
    echo "Finalizing after doing something.\n";
});

$hookDependency->resolveDependencies('finalize');
*/