<template>
    <v-card
        class="mx-auto my-3"
        :max-width="maxWidth">
        <v-toolbar card dark :class="$theme.background">
            <v-toolbar-title>Create Post</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
            <v-textarea
                label="Say something"
                auto-grow
                rows="1"
                v-model="create.text"
            ></v-textarea>
            <v-img
                v-if="create.file"
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

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-btn outline color="info" @click="selectFile">
                <v-icon>image</v-icon>
                Add Image
            </v-btn>
            <input
                style="display: none"
                type="file"
                ref="select_file"
                accept="image/*"
                @change="onFilePicked"
            >

            <v-spacer></v-spacer>
            
            <v-btn
                color="success"
                depressed
                @click="submit"
                :disabled="posting || (!create.text && !create.file)"
                >
                <v-progress-circular 
                    v-if="posting"
                    :indeterminate="true" 
                    :size="24" 
                    color="grey lighten-2" 
                    class="mr-3"
                ></v-progress-circular>
                Post
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { objectToformData } from '@/helpers/form_data_helper'
export default {
    data () {
        return {
            create: {
                file: '',
                text: ''
            },
            file_name: '',
            file_url: '',
            file_uploading: false,
            posting: false
        }
    },
    props: ['maxWidth', 'userGroupId'],
    methods: {
        submit () {
            this.posting = true
            let url = this.$baseUrl + '/Posts/create'
            let data = objectToformData(this.create)
            if (this.$route.name != 'Group') {
                data.append('user_group_id', this.userGroupId)
            }

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset(true)
                    this.$emit('reload-posts')
                    this.$store.dispatch('SHOW_ALERT', response.message)
                })
                .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
                .finally(() => { this.posting = false })
        },
        reset (all) {
            this.$refs.select_file.value = ''
            this.create.file = ''
            this.file_name = ''
            this.file_url = ''
            if (all) {
                this.create.text = ''
            }
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
                    this.create.file = files[0]
                    this.file_uploading = false
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
