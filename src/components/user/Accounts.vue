<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4" v-if="$store.getters.USER_DATA.type == $userTypes.ADMIN">
        <v-card-title primary-title class="px-0">
            <div>
                <h3 class="headline mb-0">Accounts</h3>
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
            
            <v-flex sm5 md4>
            <v-select
                :items="types"
                item-text="name"
                item-value="id"
                label="Select account type"
                append-icon="arrow_drop_down"
                v-model="submitSelectedType"
            >
            </v-select>
            </v-flex>
            <v-btn color="success" @click="getDataFromApi">Search</v-btn>
        </v-card-actions>
        
        <v-data-table
            v-if="selectedType == 1"
            :headers="headers"
            :items="list"
            :search="search"
            :loading="loading"
            class="elevation-2"
            >
            <template slot="items" slot-scope="props" :to="{name: 'Vet Profile'}">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>
                    <div v-if="props.item.expired" class="red--text">EXPIRED</div>
                    <div v-if="props.item.registration_time">
                        Date activated: {{props.item.registration_time}}
                    </div>
                    <div v-if="props.item.expiration_time">
                        Date expiration: {{props.item.expiration_time}}
                    </div>
                    <div v-if="!props.item.registration_time" class="red--text">Registration fee not yet paid</div>
                    <v-btn v-if="props.item.expired || !props.item.registration_time" @click="confirmActivate(props.item.id)" color="success" small dark class="ma-1">
                        <v-icon>check</v-icon> Activate
                    </v-btn>
                </td>
                <td>
                    <v-tooltip top>
                        <span>View Profile</span>
                        <v-btn 
                            @click="$router.push({ name: 'Vet Profile', params: { vetId:props.item.id } })"
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
        
        <v-data-table
            v-if="selectedType == 2"
            :headers="p_headers"
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
        
        <confirm-modal 
            ref="confirm"
            @submit="activate"
        ></confirm-modal>
		<modal-confirm v-if="$store.state.modal.SHOW_MODAL_CONFIRM">
			<v-btn slot="submit" color="red" dark @click="continueDelete">
				Continue
			</v-btn>
		</modal-confirm>
    </v-card>
</template>
<script>
import ConfirmModal from '@/components/vet/list/Confirm.vue'
export default {
    data () {
        return {
            types: [
                { id: 1, name: 'Veterinarian'},
                { id: 2, name: 'Pet Owner'}
            ],
            submitSelectedType: 1,
            selectedType: 1,
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
                    text: 'Status', 
                    value: 'expired',
                    sortable: false, 
                    class: this.$theme.background + ' ' + this.$theme.dark_text 
                },
                { 
                    text: 'Action', 
                    sortable: false, 
                    class: this.$theme.background + ' ' + this.$theme.dark_text 
                }
            ],
            p_headers: [
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
    components: {
        ConfirmModal
    },
    created () {
        this.getDataFromApi()
    },
    methods: {
        getDataFromApi () {
            this.loading = true
            let url = this.$baseUrl + '/Users/all'
            let data = new FormData()
            data.append('type', this.submitSelectedType)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => { 
                    this.list = response.data 
                    this.selectedType = this.submitSelectedType
                })
                .catch(() => { })
                .finally(() => this.loading = false)
        },
        confirmActivate (vet_id) {
            this.$refs.confirm.vet_id = vet_id
            this.$refs.confirm.showModal = true
        },
        activate (vet_id) {
            this.$refs.confirm.loading = true

            let url = this.$baseUrl + '/vet/Registration/create'
            let data = new FormData()
            data.append('vet_id', vet_id)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(() => {
                    this.$refs.confirm.error = null
                    this.$refs.confirm.vet_id = 0
                    this.$refs.confirm.showModal = false
                    this.getDataFromApi()
                })
                .catch(error => { this.$refs.confirm.error = error })
                .finally(() => { this.$refs.confirm.loading = false })
        },
		confirmDelete (id) {
			this.deleteId = id
			this.$store.dispatch('SHOW_CONFIRM', 'User account and everything that is linked to the user will be deleted permanently and cannot be undone. Continue ?')
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