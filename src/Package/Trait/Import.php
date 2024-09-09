<?php
namespace Package\Raxon\Event\Trait;

use Raxon\App;

use Raxon\Module\Core;
use Raxon\Module\File;

use Raxon\Node\Model\Node;

use Exception;
trait Import {

    public function role_system(): void
    {
        $object = $this->object();
        $package = $object->request('package');
        if($package){
            $node = new Node($object);
            $node->role_system_create($package);
        }
    }

    /**
     * @throws Exception
     */
    public function event_action(): void
    {
        $object = $this->object();
        $package = $object->request('package');
        if($package){
            $options = App::options($object);
            $class = 'System.Event.Action';
            $options->url = $object->config('project.dir.vendor') .
                $package . '/Data/' .
                $class .
                $object->config('extension.json')
            ;
            $node = new Node($object);
            $response = $node->import($class, $node->role_system(), $options);
            $node->stats($class, $response);
        }

    }

    /**
     * @throws Exception
     */
    public function event(): void
    {
        $object = $this->object();
        $package = $object->request('package');
        if($package){
            $options = App::options($object);
            $class = 'System.Event';
            $options->url = $object->config('project.dir.vendor') .
                $package . '/Data/' .
                $class .
                $object->config('extension.json')
            ;
            $node = new Node($object);
            $response = $node->import($class, $node->role_system(), $options);
            $node->stats($class, $response);
        }

    }
}