<?php

if (!function_exists("ad")){
    function ad(string $slug, string $belongsTo, $belongsToID, string $displayOn = 'all', array $postIDs = []){

        if(isset($_GET['ads']) && $_GET['ads'] == 1){
            if(\Illuminate\Support\Facades\Auth::guard('admin')->check()){
                $html = "<div class='ad-placeholder' ";
                $html .= "style='width: 150px;
                              color: #fff;
                              background-color: red;
                              text-align: center;
                              padding-top: 60px;
                              padding-bottom: 60px;
                              display: block;
                              margin: 0 auto;
                              margin-bottom: 30px;
                              clear: both;'>";

                $html .= "$slug - $belongsTo - $belongsToID";
                $html .= "</div>";
                return $html;
            }
        }

        $ad = \Plugins\Accio\AdsManager\Models\Ad::getFromCache()
            ->where("slug", $slug)
            ->where("belongsTo", $belongsTo)
            ->where("belongsToID", $belongsToID)
            ->where("displayOn", $displayOn);

        if(count($postIDs)){
            $ad->whereIn('postIDs', $postIDs);
        }

        $ad = $ad->first();

        if($ad && count($ad) && $ad->embed != ""){
            $html = '<span data-slug="' . $slug . '" data-id="" class="ad" id="' . $belongsTo . '-' . $belongsToID . '-' . $slug . '">';
            $html .= $ad->embed;
            $html .= '</span>';
            return $html;
        }
        return "";
    }
}