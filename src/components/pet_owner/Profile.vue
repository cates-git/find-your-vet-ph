<template>

<div>    
    <v-card class="" v-if="info">
        <v-avatar
            :tile="false"
            size="100"
            class="ma-2"
        >
            <img v-if="info.avatar" :src="info.avatar" alt="avatar">
            <v-avatar color="blue darken-2" v-else>
                <v-icon class="white--text">person</v-icon>
            </v-avatar>
        </v-avatar>
        <div>
            <h3 class="headline mt-2">{{info.first_name + ' ' + info.last_name}}</h3>
            <div style="text-transform: none">{{info.email}}</div>
            <div style="text-transform: none">{{info.address}}</div>
        </div>
        <!-- <v-card-title class="text-uppercase font-weight-bold">
        </v-card-title> -->
    </v-card>
    <v-tabs fixed-tabs v-model="currentTab">
        <v-tab 
            v-for="(tab, tabIndex) in tabs" 
            :key="tabIndex"
            :to="{ name: tab.link, params: { petOwnerId: petOwnerId } }"
            :value="tab.link"
            >
            {{ tab.name }}
        </v-tab>
    </v-tabs>
    <v-tabs-items>
        <v-tab-item>
            <router-view :pet-owner-id="petOwnerId" :key="$route.fullPath"></router-view>
        </v-tab-item>
    </v-tabs-items>
</div>
</template>

<script>
export default {
    data: () => ({
        currentTab: 'Pets',
        tabs: [
            { name: 'Group', link: 'Pet Owner Group'},
            { name: 'Pets', link: 'Pet Owner Pets'}
        ],
        info: null
    }),
    created() {
        this.petOwnerId = this.$route.params.petOwnerId
        this.currentTab = this.$route.name
        this.getInfo()
    },
    methods: {
        getInfo () {
            this.loading = true
            let url = this.$baseUrl + '/Users/get/' + this.petOwnerId

            this.$store.dispatch('API_GET', { url }, { root: true })
                .then(response => {
                    this.info = response.data
                })
                .catch(error => {
                    this.$store.dispatch('SHOW_ERROR', error)
                })
                .finally(() => this.loading = false)
        }
    }
}
</script>

<style>

</style>
