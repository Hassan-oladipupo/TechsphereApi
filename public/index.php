<?php

use App\Kernel;

require_once 'C:\Users\hp\TechsphereApi\vendor\autoload.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
