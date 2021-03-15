<template>
    <v-layout row justify-center>
        <v-dialog v-model="show" persistent scrollable width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold success white--text">
                    Book an Appointment
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <p class="red--text" v-if="error" v-html="error"></p>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-menu
                                ref="date"
                                :close-on-content-click="false"
                                v-model="showDatePicker"
                                :nudge-right="40"
                                :return-value.sync="book.date"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                                >
                                <v-text-field
                                    slot="activator"
                                    v-model="book.date"
                                    label="Select date"
                                    prepend-icon="event"
                                    readonly
                                ></v-text-field>
                                <v-date-picker v-model="date" no-title scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="showDatePicker = false">Cancel</v-btn>
                                    <v-btn flat color="primary" @click="$refs.date.save(date)">OK</v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-spacer></v-spacer>
                        <v-flex xs6>
                            <v-menu
                                ref="time"
                                :close-on-content-click="false"
                                v-model="showTimePicker"
                                :nudge-right="40"
                                :return-value.sync="book.time"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                                >
                                <v-text-field
                                    slot="activator"
                                    v-model="book.time"
                                    label="Select time"
                                    prepend-icon="access_time"
                                    readonly
                                ></v-text-field>
                                <v-time-picker
                                    v-if="showTimePicker"
                                    v-model="time"
                                    full-width
                                    @change="$refs.time.save(time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-flex>
                        
                        <v-flex xs12>
                            <v-autocomplete
                                :items="pets"
                                item-text="name"
                                item-value="id"
                                label="Select pet for an appointment"
                                append-icon="arrow_drop_down"
                                v-model="book.pet_id"
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea
                                label="Description"
                                auto-grow
                                rows="1"
                                v-model="book.reason"
                            ></v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="show = false">Cancel</v-btn>
                    <v-btn color="success" outline @click="submit">Book</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import { objectToformData } from '@/helpers/form_data_helper'
export default {
    data() {
        return {
            show: false,
            pets: [],
            time: null,
            date: null,
            showDatePicker: false,
            showTimePicker: false,
            book: {
                pet_id: null,
                time: null,
                date: null,
                reason: null,
                vet_id: null
            },
            error: null
        }
    },
    props: ['vetId'],
    created () {
        this.getPets()
    },
    methods: {    
        getPets () {
            let url = this.$baseUrl + '/pet_owner/Pets/all'            
            this.$store.dispatch('API_GET', { url }, { root: true })
                .then(response => {
                    this.pets = response.data
                }).catch(() => {
                    
                }).finally(() => {})
        }, 
        submit () {
            if (!this.book.pet_id || !this.book.date || !this.book.time || !this.book.reason) {
                this.error = 'Please fill in the fields'
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null
            this.book.vet_id = this.vetId
            let url = this.$baseUrl + '/pet_owner/Appointments/create'
            let data = objectToformData(this.book)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.show = false
                    this.$store.dispatch('SHOW_ALERT', response.message)
                }).catch(error => {
                    this.error = error
                }).finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.book.pet_id = null
            this.book.time = null
            this.book.date = null
            this.book.reason = null
            this.time = null
            this.date = null
        }

    }
}
</script>

<style>

</style>
