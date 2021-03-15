<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <p class="text-xs-center blue--text" v-if="list.length === 0">No reviews yet.</p>
        <v-container
            v-else
            fluid
            grid-list-lg
            class="px-2 py-0"
        >
            <v-layout row wrap>
                <v-flex xs12 v-for="(rate, i) in list" :key="i">
                    <v-card
                      class="mx-auto"
                      :max-width="600"
                      >
                        <v-card-actions>
                            <span v-if="$store.getters.USER_DATA.type != 1" >{{rate.pet_owner}}</span>
                            <router-link v-else :to="{ name: 'Pet Owner Profile', params: { petOwnerId: rate.pet_owner_id } }">{{rate.pet_owner}}</router-link>
                            <v-spacer></v-spacer>
                            <v-rating readonly :value="parseInt(rate.rating)" small color="orange"></v-rating>
                        </v-card-actions>
                        <v-divider light></v-divider>
                        <v-card-title primary-title v-if="rate.comment">
                            {{rate.comment}}
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>
<script>
export default {
  data () {
    return {
      totalList: 0,
      list: [],
      loading: true
    }
  },
  props: ['vetId'],
  components: {
    
  },
  watch: {

  },
  created () {
    this.getDataFromApi()
  },
  methods: {
    getDataFromApi () {
      this.loading = true
      let url = this.$baseUrl + '/vet/Rate/all'
      let data = new FormData()
      data.append('vet_id', this.vetId)

      this.$store.dispatch('API_POST', { url, data }, { root: true })
        .then(response => {
            this.list = response.data
        }).catch(() => {
            
        }).finally(() => this.loading = false)
    }
  }
}
</script>

<style scoped>
#profile >>> .note .v-list__tile {
    height: auto;
}
.v-btn--floating {
    height: 36px;
    width: 36px;
}
</style>
