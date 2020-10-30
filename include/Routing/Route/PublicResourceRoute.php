<?php

namespace Routing\Route;

use \App;
use BadMethodCallException;
use Routing\Route\Route;
use Http\Request;
use Http\Response;

/**
 * Serves files located in the public resource directory
 */
class PublicResourceRoute extends Route {

    /**
     * {@inheritDoc}
     */
    public function tryHandle(Request $request): ?Response {
        if($request->getMethod() === 'GET' && $request->getPath()->isPublicResource()) {
            return Response::file($request->getPath()->prepend(App::getRootDir()));
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function unparseUrl(array $params = []): string {
        throw new BadMethodCallException("PublicResourceRoute cannot be unparsed");
    }

}
