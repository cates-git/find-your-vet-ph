<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold" :class="$theme.background + ' ' + $theme.dark_text">
                    Upload Photo
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <p class="red--text" v-if="error" v-html="error"></p>

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
                            accept="image/*"
                            @change="onFilePicked"
                        >
                        
                        <v-img
                            v-if="file"
                            :src="file_url"
                            contain
                        >
                            <v-progress-circular 
                                v-if="file_uploading"
                                :indeterminate="true" 
                                :size="24" 
                                color="grey darken-5" 
                            ></v-progress-circular>
                        </v-img>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="success" outline :disabled="!file" @click="submit">Save</v-btn>
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
            file: '',
            file_url: '',
            file_name: '',
            error: null
        }
    },
    methods: {
        submit () {
            if (!this.file) {
                this.error = 'Please select file'
                return
            }
            
            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/Users/update_photo'
            let data = new FormData()
            data.append('file', this.file)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$store.dispatch('CHECK_SESSION', this.$baseUrl)
                    this.showModal = false
                    this.$store.dispatch('SHOW_ALERT', response.message)
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.file_url = '',
            this.file_name = '',
            this.file = ''
        },

        selectFile () {
            this.$refs.select_file.click()
        },
		
		onFilePicked (e) {
            this.file_uploading = true
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
                    this.file = files[0] // this is an image file that can be sent to server...
                    this.file_uploading = false
				})
			} else {
                this.file = ''
				this.file_name = ''
				this.file_url = ''
			}
		}
    }
}
</script>

<style>

</style>
