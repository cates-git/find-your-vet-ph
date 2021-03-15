<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <template v-if="$store.getters.USER_DATA.type != 1">
            <p class="blue--text text-xs-center" v-if="list.length === 0 && !loading">No promos.</p>
            <v-container
                fluid
                grid-list-md
                class="pa-0">
                <v-layout 
                    row 
                    wrap
                    justify-center>
                    <v-flex v-for="(item, i) in list" :key="i" sm6 md4>
                        <v-card>
                            <v-img
                            v-if="item.file_url"
                            :src="item.file_url"
                            aspect-ratio="1.75"
                            ></v-img>
                            <v-card-text primary-title>
                                <h3 class="headline mb-0">{{ item.name }}</h3>
                                {{ item.description }}
                                <div class="green--text">{{ item.price }}</div>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </template>
        <template v-else>
            <v-card-title primary-title class="pb-0 px-0">
                <div>
                    <h3 class="headline mb-0">Promos</h3>
                </div>
            </v-card-title>
            <v-card-actions class="px-0">
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
                <v-btn color="success" @click="addService()">
                    Add Promo
                </v-btn>
            </v-card-actions>
            <v-data-table
                :headers="headers"
                :items="list"
                :loading="loading"
                :search="search"
                class="elevation-2"
                >
                <template slot="headers"></template>
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.description }}</td>
                    <td>{{ props.item.price }}</td>
                    <td>
                        <img style="max-height: 70px" :src="props.item.file_url"/>
                    </td>
                    <td v-if="$store.getters.USER_DATA.type == 1">
                        <v-btn-toggle>
                            <v-btn @click="updateService(props.item)" color="primary" style="opacity: 1">
                                <v-icon color="white">edit</v-icon>
                            </v-btn>
                            <v-btn @click="confirmDelete(props.item.id)" color="error" style="opacity: 1">
                                <v-icon color="white">delete</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </td>
                </template>
            </v-data-table>

            <add-service ref="create_specialty" @reload="getDataFromApi"></add-service>
            <update-service ref="update" @reload="getDataFromApi"></update-service>
            <modal-confirm v-if="$store.state.modal.SHOW_MODAL_CONFIRM">
                <v-btn slot="submit" color="red" dark @click="continueDelete">
                    Continue
                </v-btn>
            </modal-confirm>
        </template>
    </v-card>
</template>
<script>
import AddService from './services/Add.vue'
import UpdateService from './services/Update.vue'
export default {
	data () {
		return {
			search: '',
			list: [],
			loading: true,
			headers: [
				{ 
					text: 'ID', 
					sortable: true, 
					value: 'id',
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'Name', 
					sortable: true, 
					value: 'name',
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'Description',  
					sortable: false, 
					value: 'description',
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'Price',  
					sortable: false, 
					value: 'price',
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'Image',  
					sortable: false, 
					value: 'file_url',
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'Actions', 
					sortable: false, 
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				}
			],
			deleteId: null
		}
	},
	props: ['vetId'],
	components: {
		AddService,
		UpdateService
	},
	mounted () {
		this.getDataFromApi()
	},
	methods: {
		getDataFromApi () {
			this.loading = true
			let url = this.$baseUrl + '/vet/Services/all'
			let data = new FormData()
			data.append('vet_id', this.vetId)
			data.append('promo', 1)

			this.$store.dispatch('API_POST', { url, data }, { root: true })
				.then(response => {
					this.list = response.data
				}).catch(() => {
						
				}).finally(() => this.loading = false)
        },
        addService() {
            this.$refs.create_specialty.create.promo = 1
            this.$refs.create_specialty.showModal = true
        },
		updateService(info) {
			this.$refs.update.reset()
			this.$refs.update.showModal = true
			this.$refs.update.update.id = info.id
			this.$refs.update.update.name = info.name
			this.$refs.update.update.description = info.description
			this.$refs.update.update.price = info.price
			this.$refs.update.update.file = ''
		},
		confirmDelete (id) {
			this.deleteId = id
			this.$store.dispatch('SHOW_CONFIRM', 'Promo will be deleted. Continue ?')
		},
        continueDelete () {
            if (!this.deleteId) {
				this.$store.dispatch('SHOW_ALERT', 'There was problem encountered while processing. Please try again.')
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/vet/Services/delete/' + this.deleteId

            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
                    if (response.status) {
                        this.deleteId = null
                        this.getDataFromApi()
						this.$store.dispatch('CLOSE_CONFIRM')
                        this.$store.dispatch('SHOW_SUCCESS', response.message)
                    }
                })
                .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
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
