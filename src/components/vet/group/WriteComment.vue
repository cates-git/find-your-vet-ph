<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold success white--text">
                    Write a comment
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    <v-form>
                        <v-textarea
                            label="Comment"
                            auto-grow
                            rows="1"
                            v-model.trim="create.comment"
                        ></v-textarea>
                        <p class="red--text" v-if="error" v-html="error"></p>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="success" @click="submit">Comment</v-btn>
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
                comment: '',
                post_id: 0
            },
            error: null,
        }
    },
    methods: {
        submit () {
            if (!this.create.comment) {
                this.error = 'Please enter your comment'
                return
            }
            
            this.$store.dispatch('SHOW_LOADING')
            this.error = null

            let url = this.$baseUrl + '/Posts/comment'
            let data = objectToformData(this.create)

            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.reset()
                    this.$store.dispatch('SHOW_SUCCESS', response.message)
                    this.$emit('reload')
                })
                .catch(error => { this.error = error })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },
        reset () {
            this.error = null
            this.create.comment = ''
            this.create.post_id = 0
            this.showModal = false
        }
    }
}
</script>

<style>

</style>
