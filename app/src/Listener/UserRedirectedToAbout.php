<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2013-2024 Alexander Weissman & Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\App\Listener;

use Psr\EventDispatcher\StoppableEventInterface;
use UserFrosting\Sprinkle\Core\Event\Contract\RedirectingEventInterface;
use UserFrosting\Sprinkle\Core\Util\RouteParserInterface;

/**
 * Set redirect to index.
 */
class UserRedirectedToAbout
{
    public function __construct(
        protected RouteParserInterface $routeParser,
    ) {
    }

    /**
     * @param RedirectingEventInterface&StoppableEventInterface $event
     */
    public function __invoke($event): void
    {
        $path = $this->routeParser->urlFor('about');
        $event->setRedirect($path);
        $event->isPropagationStopped();
    }
}
