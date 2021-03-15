<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <template v-if="$store.getters.USER_DATA.type == 2">
            <v-card-title primary-title>
                <div>
                    <h3 class="headline mb-0">My Pets</h3>
                </div>
            </v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="success" @click="$refs.add_pet.showModal=true">
                    <v-icon class="mr-1">add</v-icon> Add Pet
                </v-btn>
            </v-card-actions>
        </template>
        <v-card-text class="text-xs-center" v-if="list.length === 0 || loading">
            <p class="blue--text" v-if="list.length === 0 && !loading">
                <span v-if="$store.getters.USER_DATA.type == 2">Please add pet to book an appointment with the veterinarian.</span>
                <span v-else>No pets.</span>
            </p>
            <p class="blue--text" v-else-if="loading">Loading..</p>
        </v-card-text>
        <v-card
            v-else
            class="mx-auto my-3"
            :max-width="600"
            v-for="(pet, i) in list"
            :key="i"
            >
            <v-card-actions v-if="$store.getters.USER_DATA.type == 2">
                <v-spacer></v-spacer>
                <v-btn @click="updatePet(pet)" flat class="primary white--text">
                    <v-icon class="mr-1">edit</v-icon> Edit
                </v-btn>
            </v-card-actions>
            <v-divider light></v-divider>
            <v-layout row>
                <v-flex xs7>
                    <v-card-title primary-title>
                        <div>
                            <div class="headline">{{pet.name}}</div>
                            <div>{{pet.description}}</div>
                        </div>
                    </v-card-title>
                </v-flex>
                <v-flex xs5 v-if="pet.file_url">
                    <v-img
                        :src="pet.file_url"
                        height="125px"
                        contain
                    ></v-img>
                </v-flex>
            </v-layout>
        </v-card>
        <add-pet 
          ref="add_pet" 
          @reload-table="getDataFromApi"
        ></add-pet>
        <update-pet 
          ref="update_pet" 
          @reload-table="getDataFromApi"
        ></update-pet>
    </v-card>
</template>
<script>
import AddPet from './pets/Add.vue'
import UpdatePet from './pets/Update.vue'
export default {
  data () {
    return {
      list: [],
      page: 1,
      loading: true,
    }
  },
  components: {
    AddPet,
    UpdatePet
  },
  props: ['petOwnerId'],
  mounted () {
        this.getDataFromApi()
  },
  methods: {
    getDataFromApi () {
      this.loading = true
      let url = this.$baseUrl + '/pet_owner/Pets/all'
      let data = new FormData()
      data.append('pet_owner_id', this.petOwnerId)

      this.$store.dispatch('API_POST', { url, data }, { root: true })
        .then(response => {
          this.list = response.data
        }).catch(() => {
            
        }).finally(() => this.loading = false)
    },
    updatePet (pet) {
        this.$refs.update_pet.showModal = true
        this.$refs.update_pet.update.id = pet.id
        this.$refs.update_pet.update.name = pet.name
        this.$refs.update_pet.update.description = pet.description
        this.$refs.update_pet.update.type = pet.type
        this.$refs.update_pet.update.breed = pet.breed
    }
  }
}
</script>
