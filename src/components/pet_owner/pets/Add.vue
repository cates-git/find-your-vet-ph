<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold">
                Add Pet
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <p class="red--text" v-if="error" v-html="error"></p>
                        <v-text-field 
                            label="Name of pet"
                            v-model="create.name"
                        ></v-text-field>
                        <v-textarea
                            label="Describe your pet"
                            auto-grow
                            rows="1"
                            v-model="create.description"
                        ></v-textarea>
                        <v-text-field 
                            label="Type of pet"
                            v-model="create.type"
                        ></v-text-field>
                        <v-text-field 
                            label="Breed"
                            v-model="create.breed"
                        ></v-text-field>
                        
                        <v-text-field 
                            label="Add pet's photo" 
                            v-if="isMounted"
                            v-model="$refs.file_upload.file_name"
                            @click='$refs.file_upload.selectFile' 
                            prepend-icon='image'
                        ></v-text-field>
                        <file-upload 
                            style="display: none"
                            ref="file_upload"
                        ></file-upload>
                        <v-img
                            v-if="isMounted && $refs.file_upload.file_url"
                            :src="$refs.file_upload.file_url"
                            contain
                        >
                            <v-progress-circular 
                                v-if="$refs.file_upload.file_uploading"
                                :indeterminate="true" 
                                :size="24" 
                                color="grey darken-5" 
                            ></v-progress-circular>
                        </v-img>
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
            create: {
                file: '',
                name: '',
                description: '',
                type: '',
                breed: ''
            },
            file_uploading: false,
            isMounted: false,
            error: null
        }
    },
    mounted() {
        this.isMounted = true
    },
    methods: {
        submit () {            
            if (!this.create.name || !this.create.description) {
                this.error = 'Please fill in the fields'
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/pet_owner/Pets/create'
            this.create.file = this.$refs.file_upload.file
            let data = objectToformData(this.create)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$emit('reload-table')
                    this.$store.dispatch('SHOW_ALERT', response.message)
                }).catch(error => {
                    this.error = error
                }).finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.create.name = ''
            this.create.description = ''
            this.create.file = ''
            this.$refs.file_upload.file_name = ''
            this.$refs.file_upload.file_url = ''
            this.showModal = false
        }
    }
}
</script>

<style>

</style>
