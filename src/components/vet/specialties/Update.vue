<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold info white--text">
                    Update Specialty
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <p class="red--text" v-if="error" v-html="error"></p>
                        <v-text-field 
                            label="Name of pet's sickness"
                            v-model="update.name"
                        ></v-text-field>
                        <v-textarea
                            label="Description of sickness"
                            auto-grow
                            rows="1"
                            v-model="update.description"
                        ></v-textarea>
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
            update: {
                id: 0,
                name: '',
                description: ''
            },
            error: null
        }
    },
    methods: {
        submit () {
            if (!this.update.name || !this.update.description) {
                this.error = 'Please fill in the fields'
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/vet/Specialties/update'
            let data = objectToformData(this.update)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    if (response.status) {
                        this.reset()
                        this.$emit('reload-table')
                        this.$store.dispatch('SHOW_ALERT', response.message)
                    }
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.showModal = false
            this.error = null
            this.update.id = 0
            this.update.name = ''
            this.update.description = ''
        }
    }
}
</script>

<style>

</style>
