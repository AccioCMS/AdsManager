<?php

namespace Plugins\Accio\AdsManager;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Accio\App\Interfaces\PluginInterface;
use Accio\App\Traits\PluginTrait;
use Symfony\Component\Console\Command\Command;

class Plugin implements PluginInterface {
    use PluginTrait;

    /**
     * Saves post data
     * @var object $modelMetaData
     */
    private $modelMetaData;

    /**
     * SEO Settings
     * @var array $settings
     */
    private $settings;

    /**
     * The model where we will get meta data from 
     * @var object $model
     */
    private $model;

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register(){

    }

    /**
     * @param array $data from request
     * @param object $model saved data ( post object )
     */
    public function store($data, $post){

    }


    /**
     * Do something after all plugins have been loaded,
     *
     * @return void
     */
    public function boot(){

    }

    /**
     * @param Command $command
     * @return bool
     */
    public function install(Command $command){
        if(!Schema::hasTable('accio_ads_manager')) {
            Schema::create('accio_ads_manager', function($table)  {
                $table->increments("adID");
                $table->string("title", 70);
                $table->string("slug", 45)->nullable();
                $table->string("belongsTo", 30);
                $table->integer("belongsToID");
                $table->string("displayOn", 30);
                $table->text("embed")->nullable();
                $table->json("postIDs")->nullable();
                $table->timestamps();
            });
        }
        return true;
    }
}