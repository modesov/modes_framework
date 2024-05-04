<?php

namespace Modes\Framework\Http;

use League\Container\Exception\NotFoundException;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundRouteException;
use Modes\Framework\Http\Middlewares\RequestHandlerInterface;
use Modes\Framework\Http\Responses\NotAllowedMethodResponse;
use Modes\Framework\Http\Responses\NotFountResponse;
use Modes\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;
use Modes\Framework\Http\Exceptions\NotFoundException as NotFound404Exception;

class  Kernel
{
    private string $appEnv;

    public function __construct(
<<<<<<< HEAD
        private RouterInterface $router,
=======
>>>>>>> 7e1ed4d (implement registration authentication)
        private ContainerInterface $container,
        private RequestHandlerInterface $requestHandler,
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
            return $this->requestHandler->handle($request);
        } catch (\Exception $exception) {
            return $this->createExceptionResponse($exception);
        }
    }

    public function terminate(Request $request, Response $response): void
    {
        $request->getSession()?->clearFlash();
    }

    private function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array($this->appEnv, ['local', 'tests'])) {
            // TODO
            throw $exception;
        }

        if (
            $exception instanceof NotFoundRouteException
            || $exception instanceof NotFound404Exception
        ) {
            $response = new NotFountResponse();

            if (is_subclass_of($response, AbstractController::class)) {
                $response->setContainer($this->container);
            }

            return call_user_func_array(
                [$response, 'index'],
                ['message' => $exception->getMessage()]
            );
        }

        if ($exception instanceof MethodNotAllowedException) {
            $response = new NotAllowedMethodResponse();

            if (is_subclass_of($response, AbstractController::class)) {
                $response->setContainer($this->container);
            }

            return call_user_func_array(
                [$response, 'index'],
                [
                    'message' => $exception->getMessage(),
                    'allowedMethods' => $exception->allowedMethods
                ]
            );
        }

        return new Response(content: $exception->getMessage(), statusCode: 500);
    }


}