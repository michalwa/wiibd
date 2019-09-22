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

    public function tryHandle(App $app, Request $request): ?Response {
        if($request->getPath()->isPublicResource($app)) {
            return Response::file($request->getPath()->rooted($app));
        }

        return null;
    }

}
