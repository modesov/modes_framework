<?php

namespace Modes\Framework\Http;

use League\Container\Container;
use League\Container\Exception\NotFoundException;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundRouteException;
use Modes\Framework\Http\Responses\NotAllowedMethodResponse;
use Modes\Framework\Http\Responses\NotFountResponse;
use Modes\Framework\Routing\RouterInterface;

class  Kernel
{
    private string $appEnv;

    public function __construct(
        private RouterInterface $router,
        private Container $container
    )
    {
        try {
            $appEnv = $this->container->get('APP_ENV');
        } catch (NotFoundException) {
            $appEnv = 'local';
        }
        $this->appEnv = $appEnv;
    }

    public function handle(Request $request): Response
    {
        try {
            [$handler, $vars] = $this->router->dispatch($request, $this->container);
            return call_user_func_array($handler, $vars);
        } catch (\Exception $exception) {
            return $this->createExceptionResponse($exception);
        }
    }

    private function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array($this->appEnv, ['local', 'tests'])) {
            // TODO
            throw $exception;
        }

        if ($exception instanceof NotFoundRouteException) {
            return call_user_func_array(
                [new NotFountResponse, 'index'],
                ['message' => $exception->getMessage()]
            );
        }

        if ($exception instanceof MethodNotAllowedException) {
            return call_user_func_array(
                [new NotAllowedMethodResponse, 'index'],
                [
                    'message' => $exception->getMessage(),
                    'allowedMethods' => $exception->allowedMethods
                ]
            );
        }

        return new Response(content: $exception->getMessage(), statusCode: 500);
    }


}