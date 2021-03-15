<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <v-card-title primary-title class="pb-0 px-0">
            <div>
                <h3 class="headline mb-0">Specialties on Pet's Sickness</h3>
                <div>Pet owners can easily find you through your expertise in pet's sickness</div>
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
            <v-btn color="success" @click="$refs.create_specialty.showModal=true">
                Add Pet's Sickness
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
                <td v-if="$store.getters.USER_DATA.type == 1">
                    <v-btn-toggle>
                        <v-btn @click="updateSpecialty(props.item)" color="primary" style="opacity: 1">
                            <v-icon color="white">edit</v-icon>
                        </v-btn>
                        <v-btn @click="confirmDelete(props.item.id)" color="error" style="opacity: 1">
                            <v-icon color="white">delete</v-icon>
                        </v-btn>
                    </v-btn-toggle>
                </td>
            </template>
        </v-data-table>

        <add-specialty ref="create_specialty" @reload-table="getDataFromApi"></add-specialty>
        <update-specialty ref="update" @reload-table="getDataFromApi"></update-specialty>
		<modal-confirm v-if="$store.state.modal.SHOW_MODAL_CONFIRM">
			<v-btn slot="submit" color="red" dark @click="continueDelete">
				Continue
			</v-btn>
		</modal-confirm>
    </v-card>
</template>
<script>
import AddSpecialty from './specialties/Add.vue'
import UpdateSpecialty from './specialties/Update.vue'
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
					text: 'Actions', 
					sortable: false, 
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				}
			],
			deleteId: null
		}
	},
	components: {
		AddSpecialty,
		UpdateSpecialty
	},
	mounted () {
		this.getDataFromApi()
		// if (this.$store.getters.USER_DATA.type == 0) {
		// 	this.headers = [
		// 		{ text: 'ID', value: 'id', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
		// 		{ text: 'Name', value: 'name', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
		// 		{ text: 'Description', value: 'description', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
		// 		{ text: 'Veterinarian', value: 'vet', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text },
		// 		{ text: 'Actions', value: 'actions', sortable: false, class: this.$theme.background + ' ' + this.$theme.dark_text }
		// 	]
		// }
	},
	methods: {
		getDataFromApi () {
			this.loading = true
			let url = this.$baseUrl + '/vet/Specialties/list'

			this.$store.dispatch('API_POST', { url }, { root: true })
				.then(response => {
					this.list = response.data.list
				}).catch(() => {
						
				}).finally(() => this.loading = false)
		},
		updateSpecialty (info) {
			this.$refs.update.reset()
			this.$refs.update.showModal = true
			this.$refs.update.update.id = info.id
			this.$refs.update.update.name = info.name
			this.$refs.update.update.description = info.description
		},
		confirmDelete (id) {
			this.deleteId = id
			this.$store.dispatch('SHOW_CONFIRM', 'Specialty will be deleted. Continue ?')
		},
        continueDelete () {
            if (!this.deleteId) {
				this.$store.dispatch('SHOW_ALERT', 'There was problem encountered while processing. Please try again.')
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/vet/Specialties/delete/' + this.deleteId

            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
                    if (response.status) {
                        this.deleteId = null
                        this.getDataFromApi()
						this.$store.dispatch('CLOSE_CONFIRM')
                        this.$store.dispatch('SHOW_ALERT', response.message)
                    }
                })
                .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        }
	}
}
</script>

<style scoped>
/* .v-btn--floating {
    height: 36px;
    width: 36px;
} */
</style>
