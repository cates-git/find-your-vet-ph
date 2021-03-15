<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="500px">
            <v-card>
                <v-card-title class="font-weight-bold text-uppercase info white--text">
                    How to pay
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="text-xs-left">
                    1. Deposit the registration fee (<span class="red--text">{{amount}}</span>) to the admin's account (<span class="red--text">{{account}}</span>)<br/> 
                    2. Send a photo of the official receipt of the transaction via email (send it to: <span class="red--text">{{email}}</span>) <br/>
                    3. Wait for the admin to activate your account

                    <div class="mt-2 blue--text">
                        Note: <br>
                        The registration fee is valid for one year.
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
export default {
    data () {
        return {
            showModal: false,
            account: '',
            amount: '',
            email: ''
        }
    },
    created () {
        let url = this.$baseUrl + '/Users/registration_fee'
        this.$store.dispatch('API_POST', { url }, { root: true })
            .then(response => {
                if (!response.data) return
                this.account = response.data.account
                this.amount = response.data.amount
                this.email = response.data.email
            })
    }
}
</script>

<style>

</style>
