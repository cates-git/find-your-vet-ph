<template>
    <v-card class="elevation-0 transparent text-xs-left  mt-2 mb-4 mx-4" v-if="$store.getters.USER_DATA.type == $userTypes.SUPER">
        <v-card-title primary-title class="px-0">
            <div>
                <h3 class="headline mb-0">Admin Users</h3>
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
            <v-btn color="success" @click="addAdmin">Add Admin</v-btn>
        </v-card-actions>
        <v-data-table
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
                <!-- <td>
                    <v-tooltip top>
                        <span>Update User</span>
                        <v-btn 
                            @click="editAdmin(props.item)"
                            slot="activator" 
                            color="info" fab small dark class="ma-1">
                            <v-icon>edit</v-icon> 
                        </v-btn>
                    </v-tooltip>
                </td> -->
            </template>
        </v-data-table>
        

        <add-user ref="create" @reload-table="getDataFromApi"></add-user>
        <!-- <update-user ref="update" @reload-table="getDataFromApi"></update-user> -->
    </v-card>
</template>
<script>
// import UpdateUser from '@/components/user/info/UpdateUser.vue'
import AddUser from '@/components/user/info/AddUser.vue'
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
                // { 
                //     text: 'Action', 
                //     sortable: false, 
                //     class: this.$theme.background + ' ' + this.$theme.dark_text 
                // }
            ]
        }
    },
    components: {
        AddUser,
        // UpdateUser
    },
    created () {
        this.getDataFromApi()
    },
    methods: {
        getDataFromApi () {
            this.loading = true
            let url = this.$baseUrl + '/Users/all'
            let data = new FormData()
            data.append('type', 0) // admin

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => { this.list = response.data })
                .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
                .finally(() => this.loading = false)
        },
        // editAdmin (info) {
		// 	this.$refs.update.reset()
		// 	this.$refs.update.showModal = true
		// 	this.$refs.update.update.id = info.id
		// 	this.$refs.update.update.first_name = info.first_name
		// 	this.$refs.update.update.last_name = info.last_name
		// 	this.$refs.update.update.email = info.email
		// 	this.$refs.update.update.password = ''
        // },
        addAdmin () {
			this.$refs.create.reset()
			this.$refs.create.showModal = true
			this.$refs.create.create.first_name = ''
			this.$refs.create.create.last_name = ''
			this.$refs.create.create.email = ''
			this.$refs.create.create.password = ''
        }
    }
}
</script>