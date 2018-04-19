<?php

namespace Plugins\Accio\AdsManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Ad extends Model{
    /**
     * @var string table name
     */
    protected $table = "accio_ads_manager";

    /**
     * @var string primary ID name
     */
    protected $primaryKey = "adID";

    /**
     * @var array
     */
    protected $fillable = ['title', 'slug', 'belongsTo', 'belongsToID', 'displayOn', 'embed', 'postIDs'];

    protected $casts = [
        'postIDs' => 'object'
    ];

    public static function getFromCache(){
        if(!Cache::has("accio_ads_manager")){
            $ads = self::all();
            Cache::forever("accio_ads_manager", $ads);

            return $ads;
        }

        return Cache::get("accio_ads_manager");
    }

}