<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <v-card-actions class="px-0 pb-0">
            <v-flex sm5 md6>
                <v-text-field
                    v-model="keyword"
                    solo
                    label="Type keyword to find vet"
                    append-icon="search"
                    @click:append="searchVet"
                ></v-text-field>
            </v-flex>
        </v-card-actions>
        <template v-if="submittedKeyword">
            <v-card-title class="px-0 pt-0">
                <div>
                    <h3 class="headline mb-0">Search for '{{ submittedKeyword }}'</h3>
                    <div>Veterinarians</div>
                </div>
            </v-card-title>

            <p v-if="loading">Searching..</p>

            <p v-else-if="list.length == 0 && !loading">No Vet Found.</p>

            <v-list two-line class="elevation-1" v-else>
                <template v-for="(item, index) in list">
                    <v-list-tile
                        :key="index"
                        avatar
                        ripple
                        :to="{name: 'Vet Profile', params: {vetId: item.id}}"
                        >
                        <v-list-tile-avatar>
                            <img v-if="item.avatar" :src="item.avatar">
                            <v-icon class="grey" v-else>person</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title><span class="blue--text">{{ item.vet_name }}</span> ({{item.email}})</v-list-tile-title>
                            <v-list-tile-sub-title>{{ item.name }}</v-list-tile-sub-title>
                            <v-list-tile-sub-title>{{ item.description }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-rating small readonly :value="parseInt(item.rating)" color="orange"></v-rating>
                    </v-list-tile>
                </template>
            </v-list>
        </template>
    </v-card>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'
export default {
    data () {
        return {
            loading: false,
            keyword: '',
            default_avatar: require(`@/assets/images/dog.jpg`)
        }
    },
    computed: {
        ...mapGetters({
            submittedKeyword: '$_search/SUBMITTED_KEYWORD',
            list: '$_search/SEARCHED_VETS',
            totalList: '$_search/TOTAL_SEARCHED',
            page: '$_search/CURRENT_PAGE'
        })
    },
    mounted () {
        this.keyword = this.submittedKeyword
    },
    methods: {
        ...mapMutations({
            setSubmittedKeyword: '$_search/SUBMITTED_KEYWORD',
            setList: '$_search/SEARCHED_VETS',
            setTotalList: '$_search/TOTAL_SEARCHED',
            setPage: '$_search/CURRENT_PAGE'
        }),
        searchVet () {
            this.loading = true
            this.setSubmittedKeyword(this.keyword)

            let url = this.$baseUrl + '/vet/Search'
            let data = new FormData()
            data.append('keyword', this.submittedKeyword)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.setList(response.data.list)
                }).catch(() => {
                    
                }).finally(() => this.loading = false)
            
        }
    }
}
</script>

<style>

</style>
