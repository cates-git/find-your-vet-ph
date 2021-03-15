<template>
<!--  -->
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <v-card-title primary-title class="px-0">
            <div>
                <h3 class="headline mb-0" v-if="userType == 0">Transactions</h3>
                <h3 class="headline mb-0" v-else>Appointments</h3>
                <p v-if="userType == 0">Appointments of pet owners and veterinarians</p>
            </div>
        </v-card-title>
        <v-card-actions class="pa-0">
            <v-flex sm5 md4>
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                    solo
                    class="mb-1"
                ></v-text-field>
            </v-flex>
            <v-spacer></v-spacer>
        </v-card-actions>
        <v-data-table
            :headers="headers"
            :items="list"
            :search="search"
            :loading="loading"
            class="elevation-2"
            >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.date }} {{ props.item.time }}</td>
                <td>{{ props.item.pet_name }}</td>
                <td v-if="userType < 2">
					<v-tooltip top>
						<span>View Profile</span>
						<router-link slot="activator" :to="{ name: 'Pet Owner Profile', params: { petOwnerId: props.item.pet_owner_id } }">{{ props.item.pet_owner }}</router-link>
					</v-tooltip>
				</td>
                <td v-if="userType == 2 || userType == 0">
					<v-tooltip top>
						<span>View Profile</span>
						<router-link slot="activator" :to="{ name: 'Vet Profile', params: { vetId: props.item.vet_id } }">{{ props.item.vet }}</router-link>
					</v-tooltip>
				</td>
                <td>{{ props.item.reason }}</td>
                <td>{{ status[parseInt(props.item.status)] }}
					<v-btn 
						small
						v-if="userType == 2 && parseInt(props.item.status) === 3 && props.item.rating === null" 
						@click="rateVet(props.item)"
						color="orange" 
						:class="$theme.dark_text"
						style="opacity: 1">
						<v-icon :class="$theme.dark_text">star_outline</v-icon> Rate Vet
					</v-btn>
					<div v-if="props.item.rating !== null">
						Rating
						<v-rating small color="orange" readonly :value="parseInt(props.item.rating)"></v-rating>
						<div v-if="props.item.comment">
							Comment:<br>
							- {{ props.item.comment }}
						</div>
					</div>
					<div v-if="props.item.remarks !== null">
						Remarks
						<br>- {{ props.item.remarks }}
					</div>
				</td>
                <td>{{ props.item.create_time }}</td>
                <td v-if="userType == 1">
                    <v-btn-toggle>
						<v-tooltip top v-if="props.item.status == 0">
							<span>Reject</span>
							<v-btn @click="showConfirm(props.item.id, 2)" slot="activator" color="error" style="opacity: 1">
								<v-icon :class="$theme.dark_text">close</v-icon>
							</v-btn>
						</v-tooltip>
						<v-tooltip top v-if="props.item.status == 0">
							<span>Approve</span>
							<v-btn @click="showConfirm(props.item.id, 1)" slot="activator" color="success" style="opacity: 1">
								<v-icon :class="$theme.dark_text">check</v-icon>
							</v-btn>
						</v-tooltip>
						<v-tooltip top v-if="props.item.status == 1">
							<span>Mark as done</span>
							<v-btn @click="showConfirm(props.item.id, 3)" slot="activator" color="success" style="opacity: 1">
								<v-icon :class="$theme.dark_text">check_box</v-icon>
							</v-btn>
						</v-tooltip>
                    </v-btn-toggle>
                </td>
            </template>
        </v-data-table>
		<confirm-modal ref="confirm_modal" @reload-table="getDataFromApi" @submit="submitConfirm"></confirm-modal>
		<rate-vet ref="rate_vet" @reload-table="getDataFromApi"></rate-vet>
    </v-card>
</template>
<script>
import ConfirmModal from './appointments/Confirm.vue'
import RateVet from './appointments/Rate.vue'
import { objectToformData } from '@/helpers/form_data_helper'
export default {
    data () {
      return {
		totalList: 0,
		loading: false,
        list: [],
        pagination: {},
        headers: [
			{ 
				text: 'ID', 
				sortable: true, 
				value: 'id',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Date & Time', 
				sortable: true, 
				value: 'date',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Pet', 
				sortable: true, 
				value: 'pet_name',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Pet Owner', 
				sortable: true, 
				value: 'pet_owner',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Veterinarian', 
				sortable: true, 
				value: 'vet',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Reason', 
				sortable: false, 
				value: 'reason',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Status', 
				sortable: false, 
				value: 'reason',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Date Requested', 
				sortable: true, 
				value: 'reason',
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			},
			{ 
				text: 'Actions', 
				sortable: false,
				class: this.$theme.background + ' ' + this.$theme.dark_text 
			}
		],
		search: '',
		status: {
			0: 'Pending',
			1: 'Approved',
			2: 'Rejected',
			3: 'Done'
		},
		userType: 2,
		confirm: {
			status: null,
			id: null
		},
		confirmMessage: {
			1: 'Approve appointment ?',
			2: 'Reject appointment ?',
			3: 'Mark as done?'
		}

      }
	},
	components: {
		ConfirmModal,
		RateVet
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
		this.getDataFromApi()
		this.userType = this.$store.getters.USER_DATA ? this.$store.getters.USER_DATA.type : 2
		if (this.userType == 1) { // vet
			this.headers.splice(4, 1)
		} else if (this.userType == 2) { // pet owner
			this.headers.splice(3, 1)
			this.headers.splice(7, 1)
		} else {
			this.headers.splice(8, 1)
		}
    },
    methods: {
		getDataFromApi () {
			this.loading = true
			let url = this.$baseUrl + '/pet_owner/Appointments/list'

            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
					this.list = response.data
                }).catch(() => {
                    
                }).finally(() => this.loading = false)
		},
		showConfirm (id, status) {
			this.confirm.id = id
			this.confirm.status = status
			this.$refs.confirm_modal.message = this.confirmMessage[parseInt(status)]
			this.$refs.confirm_modal.showModal = true
		},
		submitConfirm () {
			this.$store.dispatch('SHOW_LOADING')
			let url = this.$baseUrl + '/pet_owner/Appointments/update'
			this.confirm.remarks = this.$refs.confirm_modal.remarks
            let data = objectToformData(this.confirm)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.getDataFromApi()
                    this.$store.dispatch('SHOW_ALERT', response.message)
				})
				.catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
				.finally(() => { this.$store.dispatch('CLOSE_LOADING') })
		},
		reset () {
			this.confirm.id = null
			this.confirm.status = null
			this.$refs.confirm_modal.remarks = ''
			this.$refs.confirm_modal.message = null
			this.$refs.confirm_modal.showModal = false
		},
		rateVet (appointment) {
			this.$refs.rate_vet.rate.vet_id = appointment.vet_id
			this.$refs.rate_vet.rate.appointment_id = appointment.id
			this.$refs.rate_vet.error = null
			this.$refs.rate_vet.showModal = true
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
