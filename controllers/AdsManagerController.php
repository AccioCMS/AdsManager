<?php

namespace Plugins\Accio\AdsManager\Controllers;

use App\Http\Controllers\MainPluginsController;
use App\Models\Post;
use App\Models\PostType;
use Illuminate\Database\Eloquent\Collection;
use Plugins\Accio\AdsManager\Models\Ad;
use Illuminate\Http\Request;

class AdsManagerController extends MainPluginsController{


    /**
     * Store ad in database
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function store(Request $request){
        $request->validate([
            'title' => 'required|max:50',
            'slug' => 'required|max:50',
            'belongsTo.value' => 'required',
            'belongsToID.value' => 'required',
            'displayOn.value' => 'required',
        ]);


        $request->slug = $this->generateSlug("", $request->slug, $request->adID);

        $ad = new Ad();
        $ad->title = $request->title;
        $ad->slug = $request->slug;
        $ad->belongsTo = $request->belongsTo['value'];
        $ad->belongsToID = $request->belongsToID['value'];
        $ad->displayOn = $request->displayOn['value'];
        $ad->embed = $request->embed;

        $postIDs = [];
        foreach($request->postIDs as $post){
            $postIDs[] = $post['value'];
        }
        $ad->postIDs = $postIDs;

        if($ad->save()){
            return $this->response('Data saved', 200, $ad->adID);
        }

        return $this->response('Data couldn\'t be saved. Please try again later', 500);
    }

    /**
     * Get ad object
     * @param $lang
     * @param $adID
     * @return array
     */
    public function details($lang, $adID){
        $ad = Ad::find($adID);
        if($ad){

            if($ad->belongsTo == 'post-type' && $ad->displayOn == 'single'){
                $tmpPostIDs = [];
                $postType = PostType::where("postTypeID", $ad->belongsToID)->first();
                $posts = (new Post())->setTable($postType->slug)->whereIn('postID', $ad->postIDs)->get();
                foreach ($posts as $post){
                    $tmpPostIDs[] = [
                        'name' => $post->title,
                        'value' => $post->postID,
                    ];
                }
                $ad->postIDs = $tmpPostIDs;
            }

            return $ad;
        }
        return $this->response('No ad with id : '. $adID, 404);
    }

    /**
     * @return Collection paginate data of ad table
     */
    public function getAll(){
        return Ad::paginate(50);
    }

    /**
     * @param $lang
     * @param $adID
     * @return array
     */
    public function delete($lang, $adID){
        $isDeleter = Ad::findOrFail($adID)->delete();
        if($isDeleter){
            return $this->response('Ad is deleted');
        }
        return $this->response('Ad could not be deleted', 500);
    }

    /**
     * Generates the slug
     *
     * @param $lang
     * @param $slug
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function generateSlug($lang, $slug, $id){
        return str_slug($slug, "_");
    }
}
