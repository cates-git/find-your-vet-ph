<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold success white--text">
                    Add Document
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-text-field 
                            label="Select File" 
                            v-model="file_name"
                            @click='selectFile' 
                            prepend-icon='attach_file'
                        ></v-text-field>
                        <input
                            style="display: none"
                            type="file"
                            ref="select_file"
                            @change="onFilePicked"
                        >
                        <v-textarea
                            label="Description"
                            auto-grow
                            rows="1"
                            v-model="create.description"
                        ></v-textarea>
                        <p class="red--text" v-if="error" v-html="error"></p>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Close</v-btn>
                    <v-btn color="success" outline @click="submit">Save</v-btn>
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
                description: ''
            },
            file_name: '',
            error: null
        }
    },
    methods: {
        submit () {
            if (!this.create.file || !this.create.description) {
                this.error = 'Please fill in the fields'
                return
            }
            
            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/vet/Documents/create'
            let data = objectToformData(this.create)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$emit('reload-table')
                    this.$store.dispatch('SHOW_SUCCESS', response.message)
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.file_name = ''
            this.create.file = ''
            this.create.description = ''
            this.showModal = false
            this.$refs.select_file.value = ''
        },

        selectFile () {
            this.$refs.select_file.click()
        },
		
		onFilePicked (e) {
            const files = e.target.files
			if(files[0] !== undefined) {
				this.file_name = files[0].name
				if(this.file_name.lastIndexOf('.') <= 0) {
					return
				}
				const fr = new FileReader ()
				fr.readAsDataURL(files[0])
				fr.addEventListener('load', () => {
					this.file_url = fr.result
                    this.create.file = files[0]
				})
			} else {
                this.create.file = ''
				this.file_name = ''
				this.file_url = ''
			}
		}
    }
}
</script>

<style>

</style>
