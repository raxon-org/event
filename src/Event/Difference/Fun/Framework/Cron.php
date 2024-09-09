<?php

namespace Event\Raxon\Org\Framework;

use Raxon\Org\App;
use Raxon\Org\Config;

use Raxon\Org\Module\Dir;
use Raxon\Org\Module\File;

use Exception;

class Cron {

    /**
     * @throws Exception
     */
    public static function install(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $environment = $object->config('framework.environment');
        switch($environment){
            case 'development':
            case 'staging':
            case 'production':
            case 'test':
            case 'replica':
                $source = $object->config('project.dir.data') . 'Cron' . $object->config('ds') . 'Cron.' . $environment;
            break;
            default:
                $source = $object->config('project.dir.data') . 'Cron' . $object->config('ds') . 'Cron.development';
        }
        if(!file::exist($source)){
            return;
        }
        $dir = '/etc/cron.d/';
        if(!Dir::is($dir)){
            Dir::create($dir, Dir::CHMOD);
        }
        $destination = $dir . 'vps-cron';
        if(File::exist($destination)){
            File::delete($destination);
        }
        File::copy($source, $destination);
        $command = 'chmod 644 ' . $destination;
        exec($command);
        $command = 'crontab ' . $destination;
        exec($command);
        $command = 'touch /var/log/cron.log';
        exec($command);
    }

    /**
     * @throws Exception
     */
    public static function start(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $command = $object->config('service.cron.start');
        exec($command);
    }

    /**
     * @throws Exception
     */
    public static function restart(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $command = $object->config('service.cron.restart');
        exec($command);
    }
}