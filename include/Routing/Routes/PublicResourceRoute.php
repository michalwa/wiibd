<?php

namespace Routing\Routes;

use \App;
use Routing\Route;
use Http\Request;
use Http\Response;

/**
 * Serves files located in the public resource directory
 */
class PublicResourceRoute extends Route {

    public function tryHandle(Request $request): ?Response {
        $app = App::get();
        if($request->getMethod() === 'GET' && $request->getPath()->isPublicResource($app)) {
            return Response::file($request->getPath()->prepend($app->getRootDir()));
        }

        return null;
    }

}
