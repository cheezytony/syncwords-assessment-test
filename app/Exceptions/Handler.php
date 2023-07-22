<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (BadRequestException $e) {
            return new Response([
                'message' => $e->getMessage()
            ], SymphonyResponse::HTTP_BAD_REQUEST);
        });
        $this->renderable(function (UnauthorizedException $e) {
            return new Response([
                'message' => $e->getMessage()
            ], SymphonyResponse::HTTP_UNAUTHORIZED);
        });
    }
}
