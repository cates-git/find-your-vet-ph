<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <v-card-title primary-title>
            <div>
                <h3 class="headline mb-0">Posts</h3>
                <div>Your posts in vet's group</div>
            </div>
        </v-card-title>
        <v-container
            fluid
            grid-list-lg
        >
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="blue-grey darken-2" class="white--text">
                    <v-card-title primary-title>
                        <div>
                        <div class="headline">Unlimited music now</div>
                        <span>Listen to your favorite artists and albums whenever and wherever, online and offline.</span>
                        </div>
                    </v-card-title>
                    <v-card-actions>
                        <v-btn flat dark>Listen now</v-btn>
                    </v-card-actions>
                    </v-card>
                </v-flex>

                <v-flex xs12>
                    <v-card color="cyan darken-2" class="white--text">
                    <v-layout>
                        <v-flex xs5>
                        <v-img
                            src="https://cdn.vuetifyjs.com/images/cards/foster.jpg"
                            height="125px"
                            contain
                        ></v-img>
                        </v-flex>
                        <v-flex xs7>
                        <v-card-title primary-title>
                            <div>
                            <div class="headline">Supermodel</div>
                            <div>Foster the People</div>
                            <div>(2014)</div>
                            </div>
                        </v-card-title>
                        </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="pa-3">
                        Rate this album
                        <v-spacer></v-spacer>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                    </v-card-actions>
                    </v-card>
                </v-flex>

                <v-flex xs12>
                    <v-card color="purple" class="white--text">
                    <v-layout row>
                        <v-flex xs7>
                        <v-card-title primary-title>
                            <div>
                            <div class="headline">Halycon Days</div>
                            <div>Ellie Goulding</div>
                            <div>(2013)</div>
                            </div>
                        </v-card-title>
                        </v-flex>
                        <v-flex xs5>
                        <v-img
                            src="https://cdn.vuetifyjs.com/images/cards/halcyon.png"
                            height="125px"
                            contain
                        ></v-img>
                        </v-flex>
                    </v-layout>
                    <v-divider light></v-divider>
                    <v-card-actions class="pa-3">
                        Rate this album
                        <v-spacer></v-spacer>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                        <v-icon>star_border</v-icon>
                    </v-card-actions>
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
      loading: true,
      pagination: {},
      headers: [
        { 
          text: 'ID', 
          sortable: false, 
          class: this.$theme.background + ' ' + this.$theme.dark_text 
        },
        { 
          text: 'Name', 
          sortable: false, 
          class: this.$theme.background + ' ' + this.$theme.dark_text 
        },
        { 
          text: 'Description',  
          sortable: false, 
          class: this.$theme.background + ' ' + this.$theme.dark_text 
        },
        { 
          text: 'Type of pet', 
          sortable: false, 
          class: this.$theme.background + ' ' + this.$theme.dark_text 
        },
        { 
          text: 'Actions', 
          sortable: false, 
          class: this.$theme.background + ' ' + this.$theme.dark_text 
        }
      ],
    }
  },
  watch: {
    pagination: {
      handler () {
        this.getDataFromApi()
      },
      deep: true
    }
  },
  mounted () {
    if (this.$store.getters.USER_DATA.type == 0) {
      this.headers = [
        { text: 'ID', value: 'id', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
        { text: 'Name', value: 'name', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
        { text: 'Description', value: 'description', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
        { text: 'Type of pet', value: 'pet_type', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
        { text: 'Veterinarian', value: 'vet', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
        { text: 'Actions', value: 'actions', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text }
      ]
    }
  },
  methods: {
    getDataFromApi () {
      this.loading = true
      let url = this.$baseUrl + '/pet/Pets/list'
      const { page } = this.pagination
      let data = new FormData()
      data.append('page', page)

      this.$store.dispatch('API_POST', { url, data }, { root: true })
        .then(response => {
          this.list = response.data.list
          this.totalList = response.data.total
        }).catch(() => {
            
        }).finally(() => this.loading = false)
    },
    updatePet (pet) {
      this.$refs.update_pet.showModal = true
      this.$refs.update_pet.update.id = pet.id
      this.$refs.update_pet.update.name = pet.name
      this.$refs.update_pet.update.description = pet.description
      this.$refs.update_pet.update.pet_type_id = pet.pet_type_id
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
