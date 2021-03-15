<template>
    <v-card class="elevation-0 transparent mt-2 mb-4 mx-4" v-if="$store.getters.USER_DATA.type == $userTypes.ADMIN">
        <v-card-title primary-title class="px-0">
            <div class="text-xs-left">
                <h3 class="headline mb-0">Manage Registration Fee</h3>
                <div>Set up the account where the vet's will deposit and amount that they will pay.</div>
            </div>
        </v-card-title>
        <v-card-actions v-if="!edit">
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="edit=true">Edit</v-btn>
        </v-card-actions>
        <v-card class="elevation-2">
            <v-card-text>
                <v-form>
                    <v-text-field 
                        label="Bank Account"
                        v-model="reg.account"
                        :disabled="!edit"
                        class="blue--text"
                    ></v-text-field>
                    <v-text-field 
                        label="Amount"
                        v-model="reg.amount"
                        :disabled="!edit"
                        class="blue--text"
                    ></v-text-field>
                    <v-text-field 
                        label="Email"
                        v-model="reg.email"
                        :disabled="!edit"
                        class="blue--text"
                    ></v-text-field>
                    <p class="red--text" v-if="error" v-html="error"></p>
                </v-form>
            </v-card-text>
            <v-card-actions v-if="edit">
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="reset">Cancel</v-btn>
                <v-btn color="success" @click="submit">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-card>
</template>

<script>
import { objectToformData } from '@/helpers/form_data_helper'
export default {
    data () {
        return {
            reg: {
                account: '',
                amount: '',
                email: '',
                id: ''
            },
            saved: {
                account: '',
                amount: '',
                email: ''
            },
            error: null,
            edit: false
        }
    },
    created () {
        this.$store.dispatch('SHOW_LOADING')
        let url = this.$baseUrl + '/Users/registration_fee'
        this.$store.dispatch('API_GET', { url }, { root: true })
            .then(response => {
                this.reg.id = response.data.id
                this.reg.account = response.data.account
                this.reg.amount = response.data.amount
                this.reg.email = response.data.email
                this.saved.account = response.data.account
                this.saved.amount = response.data.amount
                this.saved.email = response.data.email
            }).catch(error => {
                this.error = error
            }).finally(() => this.$store.dispatch('CLOSE_LOADING'))
    },
    methods: {
        submit () {
            if (!this.reg.account || !this.reg.amount || !this.reg.email) {
                this.error = 'Please fill in the fields'
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null
            let url = this.$baseUrl + '/Users/update_registration_fee'
            let data = objectToformData(this.reg)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.edit = false
                    this.error = null
                    this.saved.account = this.reg.account
                    this.saved.amount = this.reg.amount
                    this.saved.email = this.reg.email
                    this.$store.dispatch('SHOW_ALERT', response.message)
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },

        reset () {
            this.error = null
            this.reg.account = this.saved.account
            this.reg.amount = this.saved.amount
            this.reg.email = this.saved.email
            this.edit = false
        }
    }
}
</script>
<style scoped>
form >>> .theme--light.v-input--is-disabled input {
    color: inherit!important;
}
</style>
