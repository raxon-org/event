<?php

namespace Event\Raxon\Framework;

use Raxon\App;
use Raxon\Config;
use Raxon\Module\Dir;
use Raxon\Module\File;

class Symlink {

    public static function restore(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $url = $object->config('project.dir.data') .
            'Symlink' .
            $object->config('ds') .
            'Symlink' .
            $object->config('extension.json')
        ;
        $symlink = $object->data_read($url, sha1($url));
        if($symlink && $symlink->has('Symlink')){
            $list = $symlink->get('Symlink');
            if(is_array($list)){
                foreach($list as $nr => $record){
                    if(
                        property_exists($record, 'source') &&
                        property_exists($record, 'destination')
                    ){
                        $source = $record->source;
                        $destination = $record->destination;
                        if(substr($destination, -1, 1) === '/'){
                            $destination = substr($destination, 0, -1);
                        }
                        if(File::exist($destination)){
                            File::delete($destination);
                        }
                        Dir::change(Dir::name($source));
                        $source = str_replace(Dir::name($source), '', $source);
                        $destination = str_replace(Dir::name($destination), '', $destination);
                        File::link($source, $destination);
                    }
                }
            }
        }
    }
}