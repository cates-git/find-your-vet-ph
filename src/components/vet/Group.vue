<template>
<div>
    <v-card class="elevation-0 transparent mt-2 mb-4 mx-4">
        <add-post 
            :max-width="cardMaxWidth" 
            @reload-posts="getDataFromApi" 
            :user-group-id="userGroupId"
        ></add-post>
        
        <v-card
            class="mx-auto my-3"
            :max-width="cardMaxWidth"
            v-for="(post, i) in list"
            :key="i"
            >
            <v-card-actions>
                <v-list-tile class="grow">
                    <v-list-tile-avatar color="grey">
                        <v-img
                            class="elevation-6"
                            v-if="post.avatar"
                            :src="post.avatar"
                        ></v-img>
                        <v-icon v-else>person</v-icon>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>{{post.user_name}}</v-list-tile-title>
                        <v-list-tile-sub-title class="info--text">
                            {{post.email}}
                        </v-list-tile-sub-title>
                    </v-list-tile-content>

                    <v-layout
                        align-center
                        justify-end
                        >
                        <v-icon class="mr-1">access_time</v-icon>
                        <span class="subheading mr-2">{{post.create_time}}</span>
                    </v-layout>
                </v-list-tile>
            </v-card-actions>
            <v-card-text class="text-xs-left" v-if="post.text">
                {{post.text}}
            </v-card-text>
            <v-img
                v-if="post.file_url"
                :src="post.file_url"
                contain
                class="grey darken-4"
            ></v-img>
                    
            <v-card-actions>
                <v-list-tile class="grow">
                    <v-btn flat small outline color="success" v-show="!post.showComments" @click="writeComment(post.id)">
                        <v-icon class="mr-1">edit</v-icon> Comment
                    </v-btn>
                    <v-layout
                        align-center
                        justify-end>
                        <v-tooltip top v-if="post.comments.length > 0">
                            <span>{{ post.showComments ? 'Hide Comments' : 'View Comments' }}</span>
                            <v-btn flat slot="activator" @click="post.showComments = !post.showComments">
                                <v-icon class="mr-1" color="info">{{ post.showComments ? 'keyboard_arrow_up' : 'comment' }}</v-icon>
                                <span class="subheading">{{post.comments.length}}</span>
                            </v-btn>
                        </v-tooltip>
                        <template v-else>
                            <v-icon class="mr-1" color="info">comment</v-icon>
                            <span class="subheading">{{post.comments.length}}</span>
                        </template>
                    </v-layout>
                </v-list-tile>
            </v-card-actions>
            <v-divider></v-divider>
            <v-slide-y-transition>
                <v-card-text v-show="post.showComments" class="pa-0">                    
                    <div class="pa-2 text-uppercase font-weight-bold text-xs-left">Comments</div>
                    <v-card v-for="(comment, i) in post.comments" :key="i" class="transparent elevation-0">
                        <v-card-text class="text-xs-left py-1">
                            <v-layout>
                                <span class="subheading mr-2 blue--text">{{comment.user_name}}</span>
                                <span class="mr-1">·</span>
                                <span style="font-size: 12px; line-height: 22px">{{comment.create_time}}</span>
                            </v-layout>
                            <span>{{comment.comment}}</span>
                            <v-divider></v-divider>
                        </v-card-text>
                    </v-card>
                    <v-card-actions>
                        <v-btn flat small outline color="success" @click="writeComment(post.id)">
                            <v-icon class="mr-1">edit</v-icon> Comment
                        </v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-slide-y-transition>
        </v-card>
    </v-card>
    <write-comment 
        ref="comment"
        @reload="getDataFromApi"
    ></write-comment>
</div>
</template>

<script>
import AddPost from './group/Post.vue'
import WriteComment from './group/WriteComment.vue'
export default {
    data () {
        return {
            cardMaxWidth: 600,
            list: [],
            totalList: 0,
            page: 1,
            userGroupId: 0,
            showComments: false,
            comments: [],
            postId: 0,
            error: null
        }
    },
    components: {
        AddPost,
        WriteComment
    },
    created () {
        if (this.$route.name == 'Pet Owner Group') {
            this.userGroupId = this.$route.params.petOwnerId
        } else if (this.$route.name == 'Vet Group') {
            this.userGroupId = this.$route.params.vetId
        }
    },
    mounted () {
        this.getDataFromApi()
    },
    methods: {
        getDataFromApi () {
            this.loading = true
            let url = this.$baseUrl + '/Posts/all'
            let data = new FormData()
            if (this.$route.name == 'Pet Owner Group') {
                data.append('user_group_id', this.$route.params.petOwnerId)
            } else if (this.$route.name == 'Vet Group') {
                data.append('user_group_id', this.$route.params.vetId)
            }

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.list = response.data
                })
                .catch(error => { this.error = error })
                .finally(() => this.loading = false)
        },
        writeComment (post_id)
        {
            this.showComments = false
            this.$refs.comment.comment = ''
            this.$refs.comment.showModal = true
            this.$refs.comment.create.post_id = post_id
        }
    }
}
</script>

<style>

</style>
