<?php

namespace Modes\Framework\Http;

use League\Container\Container;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundException;
use Modes\Framework\Http\Responses\NotAllowedMethodResponse;
use Modes\Framework\Http\Responses\NotFountResponse;
use Modes\Framework\Routing\RouterInterface;

class  Kernel
{
    public function __construct(
        private RouterInterface $router,
        private Container $container
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            [$handler, $vars] = $this->router->dispatch($request, $this->container);
            return call_user_func_array($handler, $vars);
        } catch (NotFoundException $exception) {
            return call_user_func_array(
                [new NotFountResponse, 'index'],
                ['message' => $exception->getMessage()]
            );
        } catch (MethodNotAllowedException $exception) {
            return call_user_func_array(
                [new NotAllowedMethodResponse, 'index'],
                [
                    'message' => $exception->getMessage(),
                    'allowedMethods' => $exception->allowedMethods
                ]
            );
        } catch (\Throwable $exception) {
            return new Response(content: $exception->getMessage(), statusCode: 500);
        }
    }
}