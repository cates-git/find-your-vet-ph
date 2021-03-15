<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold success white--text">
                    Add Promo or Service 
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-text-field 
                            label="Name of promo"
                            v-model="create.name"
                        ></v-text-field>
                        <v-textarea
                            label="Description"
                            auto-grow
                            rows="1"
                            v-model="create.description"
                        ></v-textarea>
                        <v-text-field 
                            label="Price"
                            v-model="create.price"
                        ></v-text-field>
                        <v-text-field 
                            label="Add image" 
                            v-model="file_name"
                            @click='selectFile' 
                            prepend-icon='image'
                        ></v-text-field>
                        <input
                            style="display: none"
                            type="file"
                            ref="select_file"
                            accept="image/*"
                            @change="onFilePicked"
                        >
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
            create: {
                name: '',
                description: '',
                price: '',
                file: '',
                promo: 1
            },
            file_name: '',
            error: null
        }
    },
    methods: {
        submit () {
            if (!this.create.name || !this.create.description) {
                this.error = 'Please fill in the fields'
                return
            }

            this.$store.dispatch('SHOW_LOADING')
            this.error = null
            let url = this.$baseUrl + '/vet/Services/create'
            let data = objectToformData(this.create)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$emit('reload')
                    this.$store.dispatch('SHOW_ALERT', response.message)
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.create.name = ''
            this.create.description = ''
            this.create.price = ''
            this.create.file = ''
            this.showModal = false
        },

        selectFile () {
            this.$refs.select_file.click()
        },
		
		onFilePicked (e) {
            const files = e.target.files
            this.create.file = files[0]
            this.file_name = files[0] !== undefined ? files[0].name : ''
		}
    }
}
</script>

<style>

</style>
