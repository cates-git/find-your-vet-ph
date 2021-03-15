<template>
    <v-container fluid fill-height class="lighten-1" :class="$theme.content_background">
        <v-layout row wrap align-center justify-center>
            <v-flex xs12 md4>
                <v-card class="elevation-12">
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                v-model.trim="fields.email.value" 
                                label="Email" 
                                type="email"
                                :error="fields.email.error"
                                :error-messages="fields.email.error ? fields.email.error_msg : ''"
                            ></v-text-field>
                            <v-textarea
                                label="Message"
                                auto-grow
                                rows="2"
                                v-model="fields.message.value"
                                :error="fields.message.error"
                                :error-messages="fields.message.error ? fields.message.error_msg : ''"
                            ></v-textarea>
                        </v-form>
                        <v-btn 
                            :dark="$theme.dark" 
                            :class="$theme.background" 
                            class="font-weight-bold"
                            round 
                            large
                            @click="submit"
                        >Submit</v-btn>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { validateForm, objectToformData } from '@/helpers/form_data_helper'
export default {
    data: () => ({
        loading: false,
        fields: {
            email: {
                value: null,
                error: false,
                error_msg: ''
            },
            message: {
                value: null,
                error: false,
                error_msg: ''
            }
        },
        errorSignin: false,
    }),

    methods: {
        submit () {
            let hasEmpty = !validateForm(this.fields)
            let hasError = false
            if (!(/.+@.+/.test(this.fields.email.value)))  {
                this.fields.email.error = true
                this.fields.email.error_msg = 'Email must be valid'
                hasError = true
            }
            if (hasEmpty || hasError) {
                return
            }
            
            let url = this.$baseUrl + '/Contact_us/send_message'
            let data = objectToformData(this.fields)
            this.$store.dispatch('API_POST', { url, data }, { root: true })
        }
    }

}
</script>

<style>

</style>
