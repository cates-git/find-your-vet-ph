<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4" v-if="$store.getters.USER_DATA.type == $userTypes.ADMIN">
        <v-card-title primary-title class="px-0">
            <div>
                <h3 class="headline mb-0">Pet Owners</h3>
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
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>
                    <v-tooltip top>
                        <span>View Profile</span>
                        <v-btn 
                            @click="$router.push({ name: 'Pet Owner Profile', params: { petOwnerId:props.item.id } })"
                            slot="activator" 
                            color="info" fab small dark class="ma-1">
                            <v-icon>person</v-icon> 
                        </v-btn>
                    </v-tooltip>
                    <v-tooltip top>
                        <span>Delete</span>
                        <v-btn 
                            @click="confirmDelete(props.item.id)"
                            slot="activator" 
                            color="red" fab small dark class="ma-1">
                            <v-icon>delete</v-icon> 
                        </v-btn>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>
		<modal-confirm v-if="$store.state.modal.SHOW_MODAL_CONFIRM">
			<v-btn slot="submit" color="red" dark @click="continueDelete">
				Continue
			</v-btn>
		</modal-confirm>
    </v-card>
</template>
<script>
export default {
    data () {
        return {
            search: '',
            totalList: 0,
            list: [],
            loading: false,
            pagination: {},
            headers: [
                { 
                    text: 'ID', 
                    sortable: true, 
                    value: 'id',
                    class: this.$theme.background + ' ' + this.$theme.dark_text 
                },
                { 
                    text: 'Name', 
                    value: 'name',
                    sortable: true, 
                    class: this.$theme.background + ' ' + this.$theme.dark_text 
                },
                { 
                    text: 'Email',  
                    value: 'email',
                    sortable: false, 
                    class: this.$theme.background + ' ' + this.$theme.dark_text 
                },
                { 
                    text: 'Action',
                    sortable: false, 
                    class: this.$theme.background + ' ' + this.$theme.dark_text 
                }
            ],
            deleteId: null
        }
    },
    created () {
        this.getDataFromApi()
    },
    methods: {
        getDataFromApi () {
            this.loading = true
            let url = this.$baseUrl + '/Users/all'
            let data = new FormData()
            data.append('type', 2) // pet owner

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.list = response.data
                }).catch(() => {
                    
                }).finally(() => this.loading = false)
        },
		confirmDelete (id) {
			this.deleteId = id
			this.$store.dispatch('SHOW_CONFIRM', 'Pet owner and everything that is linked to the user will be deleted permanently and cannot be undone. Continue ?')
		},
        continueDelete () {
            if (!this.deleteId) {
				this.$store.dispatch('SHOW_ALERT', 'There was problem encountered while processing. Please try again.')
                return
            }

            this.$store.dispatch('CLOSE_CONFIRM')
            this.$store.dispatch('SHOW_LOADING')

            let url = this.$baseUrl + '/Users/delete/' + this.deleteId

            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
                    this.deleteId = null
                    this.getDataFromApi()
                    this.$store.dispatch('SHOW_SUCCESS', response.message)
                })
                .catch(error => this.$store.dispatch('SHOW_ALERT', error))
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        }
    }
}
</script>