<template>
    <div class="componentsWs">
        <!-- TITLE -->
        <div class="page-title">
            <div class="title_left">
                <h3 class="pull-left">{{ trans.__pluginTitle}}</h3>
            </div>
        </div>
        <!-- TITLE END -->
        <div class="clearfix"></div>

        <div class="row">

                <div class="col-lg-8 col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ trans.__formCreateTitle }}</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            <!-- Loading component -->
                            <spinner v-if="spinner" :width="'40px'" :height="'40px'" :border="'10px'"></spinner>

                            <form class="form-horizontal form-label-left clearfix" id="store" v-if="!spinner">

                                <div class="form-group" id="form-group-title">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">{{trans.__title}}</label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" class="form-control" id="title" v-model="form.title" @change="generateSlug(form.title)">
                                        <!--<div class="alert" v-if="StoreResponse.errors.title" v-for="error in StoreResponse.errors.title">{{ error }}</div>-->
                                    </div>
                                </div>

                                <div class="form-group" id="form-group-slug">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">{{trans.__slug}}</label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" class="form-control" id="slug" v-model="form.slug" @dblclick="isSlugReadonly = false" :readonly="isSlugReadonly">
                                        <!--<div class="alert" v-if="StoreResponse.errors.slug" v-for="error in StoreResponse.errors.slug">{{ error }}</div>-->
                                    </div>
                                </div>

                                <div class="form-group" id="form-group-embed">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="embed">{{trans.__embed}}</label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <textarea rows='5' class="form-control" id="embed" v-model="form.embed"></textarea>
                                        <!--<div class="alert" v-if="StoreResponse.errors.title" v-for="error in StoreResponse.errors.title">{{ error }}</div>-->
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10 col-sm-10 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 nop">

                                        <div class="col-md-6 col-sm-6 col-xs-12 nop m-b-20" id="form-group-belongsTo">
                                            <label class="col-lg-12 col-md-12">{{ trans.__belongsTo }}</label>
                                            <div class="col-lg-12 col-md-12">
                                                <multiselect
                                                        v-model="form.belongsTo"
                                                        :options="belongsToOptions"
                                                        label="name"
                                                        track-by="value"
                                                        select-label=""
                                                        deselect-label=""
                                                        :allow-empty="false"
                                                        :placeholder="trans.__belongsTo"
                                                        @input="form.postIDs = []"
                                                        @select="populateBelongsToIDOptions($event)"></multiselect>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-12 nop m-b-20" id="form-group-belongsToID">
                                            <label class="col-lg-12 col-md-12">{{ trans.__belongsToID }}</label>
                                            <div class="col-lg-12 col-md-12">
                                                <multiselect
                                                        v-if="form.belongsTo.value == 'post-type'"
                                                        :disabled="!Object.keys(belongsToIDOptions).length"
                                                        v-model="form.belongsToID"
                                                        :options="belongsToIDOptions"
                                                        label="name"
                                                        track-by="value"
                                                        select-label=""
                                                        deselect-label=""
                                                        :placeholder="trans.__belongsToID"
                                                        @input="form.postIDs = []"
                                                        :allow-empty="false"></multiselect>

                                                <multiselect
                                                        v-else
                                                        :disabled="!Object.keys(belongsToIDOptions).length"
                                                        v-model="form.belongsToID"
                                                        :options="belongsToIDOptions"
                                                        group-values="options"
                                                        group-label="group"
                                                        track-by="value"
                                                        label="name"
                                                        select-label=""
                                                        deselect-label=""
                                                        :placeholder="trans.__belongsToID"
                                                        :allow-empty="false"></multiselect>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-12 nop m-b-20" id="form-group-displayOn">
                                            <label class="col-lg-12 col-md-12">{{ trans.__displayOn }}</label>
                                            <div class="col-md-12 col-sm-12">
                                                <multiselect
                                                        v-model="form.displayOn"
                                                        track-by="value"
                                                        :options="displayOnOptions"
                                                        label="label"
                                                        select-label=""
                                                        deselect-label=""
                                                        @input="form.postIDs = []"
                                                        :placeholder="trans.__displayOn"
                                                        :allow-empty="false"></multiselect>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-12 nop m-b-20" id="form-group-postIDs" v-if="form.belongsToID && form.displayOn.value == 'single'">
                                            <label class="col-lg-12 col-md-12">{{ trans.__postIDs }}</label>
                                            <div class="col-md-12 col-sm-12">
                                                <multiselect
                                                        v-model="form.postIDs"
                                                        id="postIDs"
                                                        label="name"
                                                        track-by="value"
                                                        placeholder="Type to search"
                                                        open-direction="bottom"
                                                        :options="postListsOptions"
                                                        :multiple="true"
                                                        :searchable="true"
                                                        :loading="arePostsLoading"
                                                        :internal-search="false"
                                                        :clear-on-select="false"
                                                        :close-on-select="true"
                                                        :options-limit="300"
                                                        :limit="3"
                                                        :max-height="600"
                                                        :show-no-results="false"
                                                        select-label=""
                                                        deselect-label=""
                                                        @search-change="searchPosts($event)">
                                                    <template slot="clear" slot-scope="props">
                                                        <div class="multiselect__clear"></div>
                                                    </template>
                                                    <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
                                                </multiselect>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2 col-sm-offset-2 col-xs-offset-0">
                                        <button type="button" class="btn btn-primary" @click="store">{{ trans.__saveBtn }}</button>
                                        <router-link class="btn btn-info" tag="a" :to="{ name: 'list' }">{{trans.__cancelBtn}}</router-link>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return{
                'trans': {},
                'spinner': true,
                'arePostsLoading': false,
                'isSlugReadonly': true,
                'postTypeList': [],
                'belongsToOptions': [
                    {
                        'name': 'Post Type',
                        'value': "post-type"
                    },
                    {
                        'name': 'Categories',
                        'value': 'category'
                    },
                ],
                'belongsToIDOptions': [],
                'displayOnOptions': [{label:'Main' , value: 'main'}, {label:'Posts' , value: 'posts'}, {label:'Single' , value: 'single'}, {label:'All' , value: 'all'}],
                'postListsOptions': [],
                'form': {
                    'adID': 0,
                    'title': '',
                    'slug': '',
                    'embed': '',
                    'belongsTo': '',
                    'belongsToID': '',
                    'displayOn': [],
                    'postIDs': [],
                }
            }
        },

        created(){
            // translations
            this.trans = {
                __title: this.__('base.title'),
                __slug: this.__('base.slug'),
                __pluginTitle: this.__('Accio.AdsManager.base.pluginTitle'),
                __formCreateTitle: this.__('Accio.AdsManager.base.formCreateTitle'),
                __formUpdateTitle: this.__('Accio.AdsManager.base.formUpdateTitle'),
                __postIDs: this.__('Accio.AdsManager.base.postIDs'),
                __embed: this.__('Accio.AdsManager.base.embed'),
                __rules: this.__('Accio.AdsManager.base.rules'),
                __belongsTo: this.__('Accio.AdsManager.base.belongsTo'),
                __belongsToID: this.__('Accio.AdsManager.base.belongsToID'),
                __displayOn: this.__('Accio.AdsManager.base.displayOn'),
                __saveBtn: this.__('base.saveBtn'),
                __cancelBtn: this.__('base.cancelBtn'),
            };

            // get post-types
            let promise = this.$http.get(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/json/post-type/get-all')
                .then((resp) => {
                    this.postTypeList = resp.body.data;
                }).then((resp) => {
                    if(this.$route.params.adID !== undefined){
                        this.$http.get(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/plugins/accio/ads-manager/details/'+this.$route.params.adID)
                            .then((resp) => {
                                let ad = resp.body;
                                this.form.title = ad.title;
                                this.form.slug = ad.slug;
                                this.form.embed = ad.embed;
                                this.form.postIDs = ad.postIDs;

                                this.populateVModels(ad);

                                this.spinner = false;

                            });
                    }else{
                        this.spinner = false;
                    }
                });
        },

        methods:{
            /**
             * Makes store request in the backend
             */
            store(){
                this.$http.post(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/plugins/accio/ads-manager/store', this.form)
                    .then((resp) => {
                        if(resp.body.code == 200){
                            this.noty("success", resp.body.message);
                            this.$router.push({ name: "update", params: {adID: resp.body.id}});
                        }else{
                            this.noty("error", resp.body.message);
                        }
                    });
            },

            /**
             * Makes ajax request in the backend to generate a unique slug
             * @param title of the ad
             */
            generateSlug(title){
                if(title && !this.form.slug){
                    let id = 0;
                    if(this.$route.params.id !== undefined){
                        id = this.$route.params.adID;
                    }

                    this.$http.get(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/plugins/accio/ads-manager/generate-slug/'+title+'/'+id)
                        .then((resp) => {
                            this.form.slug = resp.body;
                        });
                }
            },

            /**
             * Used when belongsTo changes
             * @param event the value of the selected belongsTo
             */
            populateBelongsToIDOptions(event){
                this.form.belongsToID = '';
                this.belongsToIDOptions = [];

                if(event.value == "post-type"){
                    for(let k in this.postTypeList){
                        this.belongsToIDOptions.push({
                            name: this.postTypeList[k].name,
                            value: this.postTypeList[k].postTypeID,
                        });
                    }
                }else if(event.value == "category"){
                    this.belongsToIDOptions = [];
                    for(let k in this.postTypeList){
                        let tmpOptions = [];
                        for(let catKey in this.postTypeList[k].categories){
                            tmpOptions.push({
                                name: this.postTypeList[k].categories[catKey].title[this.$route.params.lang],
                                value: this.postTypeList[k].categories[catKey].categoryID,
                            });
                        }

                        this.belongsToIDOptions.push({
                            group: this.postTypeList[k].name,
                            options: tmpOptions
                        });
                    }
                }
            },

            /**
             * Used to populate v-models in update
             * @param ad object
             */
            populateVModels(ad){
                for(let k in this.displayOnOptions){
                    if(this.displayOnOptions[k].value == ad.displayOn){
                        this.form.displayOn = this.displayOnOptions[k];
                    }
                }

                if(ad.belongsTo == "post-type"){
                    this.form.belongsTo = {name: "Post Type", value: "post-type"};
                    this.populateBelongsToIDOptions(this.form.belongsTo);
                    for(let k in this.belongsToIDOptions){
                        if(this.belongsToIDOptions[k].value == ad.belongsToID){
                            this.form.belongsToID = this.belongsToIDOptions[k];
                        }
                    }

                }else if(ad.belongsTo == "category"){
                    this.form.belongsTo = {name: "Category", value: "category"};
                    this.populateBelongsToIDOptions(this.form.belongsTo);

                    for(let k in this.postTypeList){
                        for(let catKey in this.postTypeList[k].categories){
                            console.log("ID:: "+ad.belongsToID, this.postTypeList[k].categories[catKey].categoryID);
                            if(this.postTypeList[k].categories[catKey].categoryID == ad.belongsToID){
                                this.form.belongsToID = {
                                    name: this.postTypeList[k].categories[catKey].title[this.$route.params.lang],
                                    value: this.postTypeList[k].categories[catKey].categoryID
                                };
                            }
                        }
                    }
                }
            },


            /**
             * Search posts from DB
             * @param term string search term
             */
            searchPosts(term){
                // get post type slug from postTypeID
                let selectedPostTypeID = this.form.belongsToID.value;
                let postTypeList = this.postTypeList;
                let selectedPostTypeSlug = "";
                for(let k in postTypeList){
                    if(postTypeList[k].postTypeID == selectedPostTypeID){
                        selectedPostTypeSlug = postTypeList[k].slug;
                        break;
                    }
                }

                // search posts in DB and populate the postListOptions for the multi-select
                this.postListsOptions = [];
                if(term && selectedPostTypeSlug){
                    this.arePostsLoading = true;
                    this.$http.get(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/json/posts/search/'+selectedPostTypeSlug+'/'+term)
                        .then((resp) => {
                            let result = [];
                            let list = resp.body.data;
                            for(let k in list){
                                result.push({
                                    name: list[k].title,
                                    value: list[k].postID
                                });
                            }
                            this.postListsOptions = result;
                            this.arePostsLoading = false;
                        }, error => {
                            console.log(error);
                        });
                }
            },

            /**
             * Call the translate action of vuex
             * @param key label key
             * @returns {computed.getTranslation}
             * @private
             */
            __(key){
                this.$store.dispatch('__', key);
                return this.getTranslation;
            },

            /**
             * Pop notification
             * @param type
             * @param message
             */
            noty(type, message){
                // noty notification
                new Noty({
                    type: type,
                    layout: 'bottomLeft',
                    text: message,
                    timeout: 3000,
                    closeWith: ['button']
                }).show();
            }
        },

        computed:{
            // get base path
            basePath(){
                return this.$store.getters.get_base_path;
            },

            getTranslation(){
                // returns translated value
                return this.$store.getters.get_translation;
            },
        },
    }
</script>