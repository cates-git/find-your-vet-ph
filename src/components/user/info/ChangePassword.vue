<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold red white--text">
                    Change Password
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-text-field 
                            label="Current Password"
                            type="password"
                            v-model.trim="update.current_password"
                        ></v-text-field>
                        <v-text-field 
                            label="New Password"
                            type="password"
                            v-model.trim="update.new_password"
                        ></v-text-field>
                        <v-text-field 
                            label="Confirm New Password"
                            type="password"
                            v-model.trim="update.confirm"
                        ></v-text-field>
                        <p class="red--text" v-if="error" v-html="error"></p>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="success" @click="submit">Save</v-btn>
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
            update: {
                current_password: '',
                new_password: '',
                confirm: ''
            },
            error: null
        }
    },
    methods: {
        submit () {
            if (!this.update.current_password || !this.update.new_password || !this.update.confirm) {
                this.error = 'Please fill in the fields'
                return
            }

            if (this.update.new_password !== this.update.confirm) {
                this.error = 'Confirm does\'nt match the password'
                return
            }
            
            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/Account/change_password'
            let data = objectToformData(this.update)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$store.dispatch('SHOW_SUCCESS', response.message)
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.update.current_password = ''
            this.update.new_password = ''
            this.update.confirm = ''
            this.showModal = false
        }
    }
}
</script>

<style>

</style>
