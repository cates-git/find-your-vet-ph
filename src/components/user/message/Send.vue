<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold success white--text">
                    Send Message
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-autocomplete
                            v-if="$store.getters.USER_DATA.type == $userTypes.PET_OWNER && !reply"
                            :items="petOwners"
                            item-text="name"
                            item-value="id"
                            label="To"
                            append-icon="arrow_drop_down"
                            v-model="create.receiver"
                        >
                        </v-autocomplete>
                        
                        <v-textarea
                            label="Message"
                            auto-grow
                            rows="1"
                            v-model.trim="create.message"
                        ></v-textarea>
                        <p class="red--text" v-if="error" v-html="error"></p>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="success" @click="submit">Send</v-btn>
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
                message: '',
                receiver: 0
            },
            error: null,
            petOwners: [],
            admin: {
                name: 'Admin',
                id: 0
            },
            reply: false
        }
    },
    watch: {
        showModal (show) {
            if (show) {
                if (this.$store.getters.USER_DATA.type == this.$userTypes.PET_OWNER) {
                    this.getpetOwners()
                } else {
                    this.petOwners = []
                }
            }
        }
    },
    methods: {
        submit () {
            if (!this.create.message) {
                this.error = 'Please enter your message'
                return
            }
            
            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/Message/create'
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
            this.create.message = ''
            this.create.receiver = 0
            this.showModal = false
        },

        getpetOwners () {
            this.loading = true
            let url = this.$baseUrl + '/Users/all'
            let data = new FormData()
            data.append('type', 2) // pet owner

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => { 
                    let receivers = response.data
                    receivers.push({ id: 0, name: 'Send message to admin' })
                    this.petOwners = receivers
                })
                .catch(() => { })
                .finally(() => this.loading = false)
        }
    }
}
</script>

<style>

</style>
