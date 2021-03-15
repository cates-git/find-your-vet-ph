<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold">
                    Rate vet's service
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-rating v-model="rate.rating" color="orange"></v-rating>
                        <v-textarea
                            label="Comment"
                            auto-grow
                            rows="1"
                            v-model="rate.comment"
                        ></v-textarea>
                        <p class="red--text" v-if="error" v-html="error"></p>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Close</v-btn>
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
            rate: {
                vet_id: '',
                rating: 0   ,
                comment: '',
                appointment_id: ''
            },
            error: null
        }
    },
    methods: {
        submit () {
            this.$store.dispatch('SHOW_LOADING')
            this.error = null
            let url = this.$baseUrl + '/vet/Rate/create'
            let data = objectToformData(this.rate)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(() => {
                    this.reset()
                    this.$emit('reload-table')
                }).catch(error => {
                    this.error = error
                }).finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.rate.vet_id = ''
            this.rate.rating = 0
            this.rate.comment = ''
            this.rate.appointment_id = ''
            this.showModal = false
        },
    }
}
</script>

<style>

</style>
