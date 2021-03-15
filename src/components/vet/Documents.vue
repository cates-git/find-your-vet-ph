<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4">
        <v-card-title primary-title class="pb-0 px-0">
            <div>
                <h3 class="headline mb-0">Certificates & Documents</h3>
                <div>Upload any documents that validates your legitimacy</div>
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
            <v-btn color="success" @click="$refs.create.showModal=true">
                Add Document
            </v-btn>
        </v-card-actions>
        <v-data-table
            :headers="headers"
            :items="list"
            :search="search"
            :loading="loading"
            class="elevation-2"
            >
            <template slot="headers"></template>
            <template slot="items" slot-scope="props">
                <td>{{ props.item.id }}</td>
                <td><a :href="props.item.file_url" target="__blank">{{ props.item.file_name }}</a></td>
                <td>{{ props.item.description }}</td>
                <td>
                    <v-btn-toggle>
                        <v-btn @click="updateDocument(props.item)" color="primary" style="opacity: 1">
                            <v-icon class="white--text">edit</v-icon>
                        </v-btn>
                        <v-btn @click="confirmDelete(props.item.id)" color="error" style="opacity: 1">
                            <v-icon :class="$theme.dark_text">delete</v-icon>
                        </v-btn>
                    </v-btn-toggle>
                </td>
            </template>
        </v-data-table>

        <add-document ref="create" @reload-table="getDataFromApi"></add-document>
        <update-document ref="update" @reload-table="getDataFromApi"></update-document>
        <modal-confirm v-if="$store.state.modal.SHOW_MODAL_CONFIRM">
            <v-btn slot="submit" color="red" dark @click="continueDelete">
                Continue
            </v-btn>
        </modal-confirm>
    </v-card>
</template>
<script>
import AddDocument from './documents/Add.vue'
import UpdateDocument from './documents/Update.vue'
export default {
	data () {
		return {
			search: '',
			list: [],
			loading: true,
			pagination: {},
			headers: [
				{ 
					text: 'ID', 
					value: 'id',
					sortable: true, 
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'File', 
					value: 'file_name',
					sortable: false, 
					class: this.$theme.background + ' ' + this.$theme.dark_text 
				},
				{ 
					text: 'Description', 
					value: 'description',
					sortable: false, 
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
		AddDocument,
		UpdateDocument
	},
	mounted () {
		this.getDataFromApi()
	},
	methods: {
		getDataFromApi () {
			this.loading = true
			let url = this.$baseUrl + '/vet/Documents/all'

			this.$store.dispatch('API_POST', { url }, { root: true })
				.then(response => {
					this.list = response.data
				}).catch(() => {
						
				}).finally(() => this.loading = false)
		},
		updateDocument (doc) {
            this.$refs.update.reset()
            this.$refs.update.showModal = true
            this.$refs.update.update.id = doc.id
            this.$refs.update.update.description = doc.description
		},
		confirmDelete (id) {
			this.deleteId = id
			this.$store.dispatch('SHOW_CONFIRM', 'Document will be deleted. Continue ?')
		},
        continueDelete () {
            if (!this.deleteId) {
				this.$store.dispatch('SHOW_ALERT', 'There was problem encountered while processing. Please try again.')
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/vet/Documents/delete/' + this.deleteId

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
#profile >>> .note .v-list__tile {
		height: auto;
}
.v-btn--floating {
		height: 36px;
		width: 36px;
}
</style>
