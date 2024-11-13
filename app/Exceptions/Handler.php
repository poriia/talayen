<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\OrderException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof OrderException) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

        return parent::render($request, $exception);
    }
}
