<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <p class="text-xs-center blue--text" v-if="list.length === 0 && !loading">No Certificates.</p>
        <v-container
            v-else
            fluid
            grid-list-lg
            >
            <v-layout row wrap>

                <v-flex xs12 v-for="(item, i) in list" :key="i" class="my-2">
                    <v-card color="grey lighten-2">
                    <v-layout>
                        <v-flex xs5 v-if="item.file_url">
                            <v-img
                                
                                :src="item.file_url"
                                height="125px"
                                contain
                            >
                                <v-container fill-height text-xs-center>
                                    <v-layout align-center>
                                        <v-tooltip top style="margin: auto">
                                            <span>Open in new tab</span>
                                            <v-icon 
                                                slot="activator"
                                                @click="openFile(item.file_url)" 
                                                
                                            >open_in_new</v-icon>
                                        </v-tooltip>
                                    </v-layout>
                                </v-container>
                            </v-img>
                        </v-flex>
                        <v-flex xs7>
                        <v-card-title primary-title>
                            <div>
                                <div>{{item.description}}</div>
                            </div>
                        </v-card-title>
                        </v-flex>
                    </v-layout>
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
    props: {
        vetId: {
            default: '0',
            type: String
        }
    },
  watch: {
  },
  mounted () {
    this.getDataFromApi()
  },
  methods: {
    getDataFromApi () {
      this.loading = true
      let url = this.$baseUrl + '/vet/Documents/all'

      let data = new FormData()
      data.append('vet_id', this.vetId)

      this.$store.dispatch('API_POST', { url, data }, { root: true })
        .then(response => {
          this.list = response.data
        }).catch(() => {
            
        }).finally(() => this.loading = false)
    },
    openFile (file_url) {
        window.open(file_url, '__blank')
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
