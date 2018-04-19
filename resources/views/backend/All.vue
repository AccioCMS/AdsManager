<template>
    <div class="componentsWs">
        <!-- TITLE -->
        <div class="page-title">
            <div class="title_left">
                <h3 class="pull-left">{{trans.__pluginTitle}}</h3>
                <router-link class="btn btn-primary pull-left addBtnMain" tag="a" :to="{ name: 'create' }">{{trans.__addBtn}}</router-link>
            </div>

            <div class="title_right">
                <!-- Simple search component -->
            </div>

        </div>
        <!-- TITLE END -->
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{trans.__listTableTitle}}</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                            <thead>
                                <tr class="tableHeader">
                                    <th>#</th>
                                    <th id="name">{{trans.__title}}</th>
                                    <th id="slug">{{trans.__slug}}</th>
                                    <th>{{trans.__action}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td colspan="6" v-if="spinner"><spinner :width="'40px'" :height="'40px'" :border="'10px'"></spinner></td>
                            </tr>
                                <tr v-for="(item, index) in list" v-if="!spinner">
                                    <td>{{ item.adID }}</td>
                                    <td>{{ item.title }}</td>
                                    <td>{{ item.slug }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <router-link class="btn btn-primary" tag="button" :to="{ name: 'update', params: { adID: item.adID } }">{{trans.__updateBtn}}</router-link>
                                            <button type="button" class="btn btn-primary" @click="toggleActionBar(index)" :id="'toggleListBtn'+item.adID">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="lists-action-bar-dropdown" v-if="index === openedItemActionBar">
                                                <li><a style="cursor:pointer" :id="'deleteItemBtn'+item.adID" @click="deleteItem(item.adID, index)">{{trans.__deleteBtn}}</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

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
                list: [],
                openedItemActionBar: -1,
                spinner: true,
            }
        },
        created(){
            // translations
            this.trans = {
                __addBtn: this.__('base.addBtn'),
                __deleteBtn: this.__('base.deleteBtn'),
                __updateBtn: this.__('base.updateBtn'),
                __action: this.__('base.action'),
                __title: this.__('base.title'),
                __slug: this.__('base.slug'),
                __listTableTitle: this.__('Accio.AdsManager.base.listTableTitle'),
                __pluginTitle: this.__('Accio.AdsManager.base.pluginTitle'),
            };
        },

        mounted(){
            this.getList();
        },

        methods: {

            getList(){
                this.$http.get(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/plugins/accio/ads-manager/get-all')
                    .then((resp) => {
                        this.list = resp.body.data;
                        this.spinner = false;
                    });
            },
            /**
             * Delete
             * @param adID
             * */
            deleteItem(adID){
                this.openedItemActionBar = -1;
                this.$http.get(this.basePath+'/'+this.$route.params.adminPrefix+'/'+this.$route.params.lang+'/plugins/accio/ads-manager/delete/'+adID)
                    .then((resp) => {
                        console.log("delete ad", resp);
                        if(resp.body.code == 200){
                            this.noty("success", resp.body.message);
                            this.getList();
                        }else{
                            this.noty("error", resp.body.message);
                        }
                    });
            },

            /**
             * toggle the action bar dropdown of a item
             * @param index of the list
             */
            toggleActionBar(index){
                if(this.openedItemActionBar == index){
                    this.openedItemActionBar = -1;
                }else{
                    this.openedItemActionBar = index;
                }
            },

            /**
             * Call the translate action of vuex
             *
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
        }
    }
</script>

<style scoped>

</style>