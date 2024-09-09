<?php

namespace Event\Raxon\Org\System;

use Event\Raxon\Org\Framework\Email;

use Raxon\Org\App;

use Raxon\Org\Module\Stream\Notification;

use Exception;

class Update {

    /**
     * @throws Exception
     */
    public static function notification(App $object, $event, $options=[]): void
    {
        $action = $event->get('action');
        if (!empty($options['notification'])) {
            Notification::clean(
                $object,
                $action,
                $options
            );
            $is_new = Notification::is_new(
                $object,
                $action,
                $options,
                $config,
                $tokens
            );
            if (
                $is_new &&
                $config !== false
            ) {
                Notification::create(
                    $object,
                    $action,
                    $config,
                    $tokens,
                    $options['notification']
                );
                Email::queue(
                    $object,
                    $action,
                    $options
                );
            }
        }
    }
}