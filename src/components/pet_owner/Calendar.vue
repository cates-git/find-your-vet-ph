<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4" v-if="$store.getters.USER_DATA.type == 2">
        <v-card-title primary-title>
            <div>
                <h3 class="headline mb-0">My Calendar</h3>
            </div>
        </v-card-title>
        <v-layout 
            row 
            wrap
            justify-center>
            <v-flex xs12 sm6>
                <v-date-picker
                    width="100%"
                    v-model="selectedDate"
                    :events="dates"
                    event-color="green lighten-1"
                    class="my-2"
                ></v-date-picker>
            </v-flex>
            <v-flex xs12 sm6 class="my-2 px-1">
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="heading">Appointments</h3>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text class="blue--text" v-if="!list[selectedDate] || list[selectedDate].length == 0">
                        There are no appointments for this day.
                    </v-card-text>
                    <v-card-text v-else-if="selectedDate">
                        <v-card v-for="(schedule, i) in list[selectedDate]" :key="i" class="transparent elevation-0">
                            <v-card-text class="text-xs-left py-1">
                                <v-layout>
                                    <template v-if="schedule.vet">
                                        <v-icon class="mr-1">account_circle</v-icon>
                                        <v-tooltip top>
                                            <span>View Profile</span>
                                            <router-link slot="activator" :to="{ name: 'Vet Profile', params: { vetId: schedule.vet_id } }">
                                                <span class="subheading mr-2 blue--text">{{schedule.vet}}</span>
                                            </router-link>
                                        </v-tooltip>
                                    </template>
                                    <span v-else class="info--text">The vet was deleted.</span>
                                    <span class="mr-1">·</span>
                                    <span>{{schedule.time}}</span>
                                </v-layout>
                                <div>
                                    <span class="font-weight-bold">Pet:</span> 
                                    {{schedule.pet_name}}
                                </div>
                                <div>
                                    <span class="font-weight-bold">Reason:</span> 
                                    {{schedule.reason}}
                                </div>
                                <div v-if="schedule.remarks">
                                    <span class="font-weight-bold">Remarks:</span> 
                                    {{schedule.remarks}}
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-card>
</template>
<script>
export default {
  data () {
    return {
        list: [],
        dates: null,
        selectedDate: new Date().toISOString().substr(0, 10)
    }
  },
  props: ['petOwnerId'],
  mounted () {
        this.getDataFromApi()
			this.arrayEvents = [...Array(6)].map(() => {
				const day = Math.floor(Math.random() * 30)
				const d = new Date()
				d.setDate(day)
				return d.toISOString().substr(0, 10)
			})
  },
  methods: {
    getDataFromApi () {
        this.$store.commit('PAGE_LOADING')
        let url = this.$baseUrl + '/pet_owner/Appointments/calendar'

        this.$store.dispatch('API_POST', { url }, { root: true })
            .then(response => {
                this.list = response.data.list
                this.dates = response.data.dates
            })
            .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
            .finally(() => this.$store.commit('PAGE_LOADED'))
    },
    updatePet (pet) {
        this.$refs.update_pet.showModal = true
        this.$refs.update_pet.update.id = pet.id
        this.$refs.update_pet.update.name = pet.name
        this.$refs.update_pet.update.description = pet.description
    }
  }
}
</script>
