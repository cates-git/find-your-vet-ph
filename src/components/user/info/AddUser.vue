<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold success white--text">
                    Add Admin User
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-text-field 
                            label="First Name"
                            v-model.trim="create.first_name"
                        ></v-text-field>
                        <v-text-field 
                            label="Last Name"
                            v-model.trim="create.last_name"
                        ></v-text-field>
                        <v-text-field 
                            label="Email"
                            v-model.trim="create.email"
                        ></v-text-field>
                        <v-text-field 
                            label="Password"
                            type="password"
                            v-model.trim="create.password"
                        ></v-text-field>
                        <p class="red--text" v-if="error" v-html="error"></p>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="success" @click="submit">Create</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import { objectToformData } from '@/helpers/form_data_helper'
export default {
    data () {
        return {
            showModal: false,
            create: {
                first_name: '',
                last_name: '',
                email: '',
                password: ''
            },
            error: null
        }
    },
    methods: {
        submit () {
            if (!this.create.first_name || !this.create.last_name || !this.create.email || !this.create.password) {
                this.error = 'Fill in the fields'
                return
            }
            
            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/Users/create_admin'
            let data = objectToformData(this.create)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$store.dispatch('SHOW_SUCCESS', response.message)
                    this.$emit('reload-table')
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.create.first_name = ''
            this.create.last_name = ''
            this.create.email = ''
            this.create.password = ''
            this.showModal = false
        }
    }
}
</script>

<style>

</style>
