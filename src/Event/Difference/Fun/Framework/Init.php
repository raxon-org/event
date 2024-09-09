<?php

namespace Event\Raxon\Framework;

use Raxon\App;

use Raxon\Module\File;
use Raxon\Module\Route;

use Exception;

class Init {

    /**
     * @throws Exception
     */
    public static function run(App $object, $event, $options=[]): void
    {
        $flags = [];
        if(
            array_key_exists('flags', $options) &&
            is_object($options['flags']) &&
            property_exists($options['flags'], 'url') &&
            File::exist($options['flags']->url)
        ){
            $read = File::read($options['flags']->url);
            $flags = explode(PHP_EOL, $read);
            if(is_array($flags)){
                foreach($flags as $nr => $flag){
                    $flags[$nr] = trim($flag);
                    $flags[$nr] = ltrim($flags[$nr], '-');
                    if(empty($flag[$nr])){
                        unset($flags[$nr]);
                        continue;
                    }
                    $explode = explode('#', $flags[$nr], 2);
                    if(array_key_exists(1, $explode)){
                        $flags[$nr] = rtrim($explode[0]);
                    }
                    $explode = explode('//', $flags[$nr], 2);
                    if(array_key_exists(1, $explode)){
                        $flags[$nr] = rtrim($explode[0]);
                    }
                    if(substr($flags[$nr], 0, 1) === '#'){
                        unset($flags[$nr]);
                        continue;
                    }
                    if(substr($flags[$nr], 0, 2) === '//'){
                        unset($flags[$nr]);
                        continue;
                    }
                    if(empty($flag[$nr])){
                        unset($flags[$nr]);
                    }
                }
            }
            $flags = array_values($flags);
        }
        if(
            array_key_exists('flags', $options) &&
            is_object($options['flags'])
        ) {
            foreach ($options['flags'] as $flag => $value) {
                $flags[] = $flag;
            }
        }
        $url = $object->config('project.dir.data') .
            'Init' .
            $object->config('ds') .
            'Init' .
            $object->config('extension.json')
        ;
        $data = $object->data_read($url);
        if(!$data){
            return;
        }
        $list = $data->get('Init');
        foreach($list as $nr => $init){
            if(
                property_exists($init, 'name') &&
                property_exists($init, 'controller')
            ){
                if(in_array(
                    $init->name,
                    $flags,
                    true
                )){
                    $init = Route::controller($init);
                    $options['is_flag'] = true;
                    $init->controller::{$init->function}($object, $event, $options);
                }
            }
        }
    }
}
