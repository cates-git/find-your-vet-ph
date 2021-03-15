<template>
    <v-card class="elevation-12">
        <v-card-title primary-title>
            <div style="width: 100%">
                <h3 class="headline font-weight-black" :class="$theme.text">CREATE AN ACCOUNT</h3>
            </div>
        </v-card-title>
        <v-card-text>
            <v-form class="layout row wrap justify-center">
                <v-text-field
                    v-for="(field, i) in create"
                    :key="i"
                    class="flex"
                    :class="field.class"
                    v-model.trim="field.value" 
                    :label="field.label" 
                    :type="field.type"
                    :error="field.error"
                    :error-messages="field.error ? field.error_msg : ''"
                ></v-text-field>
            </v-form>
        </v-card-text>
        <div class="px-3">
            <p class="font-weight-bold text-xs-left ma-0">Sign up as a: </p>
            <v-radio-group 
                v-model="type" 
                row 
                class="mt-1">
                <v-radio 
                    off-icon="radio_button_unchecked" 
                    on-icon="radio_button_checked"
                    color="primary" 
                    label="Pet Owner" 
                    value="2"
                ></v-radio>
                <v-radio 
                    off-icon="radio_button_unchecked" 
                    on-icon="radio_button_checked"
                    color="primary" 
                    label="Veterinarian" 
                    value="1"
                ></v-radio>
            </v-radio-group>
        </div>
        <v-card-actions>
            <v-btn 
                flat 
                :class="$theme.text" 
                style="text-transform: unset;"
                class="font-weight-bold"
                :to="{ name: 'Sign In' }"
            >Sign in instead</v-btn>
            <v-spacer></v-spacer>
            <v-btn 
                :dark="$theme.dark" 
                :class="$theme.background" 
                class="font-weight-bold"
                large
                @click="submit"
            >Sign Up</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { validateForm, objectToformData } from '@/helpers/form_data_helper'
export default {
    data: () => ({
        loading: false,
        create: {
            first_name: {
                label: 'First name',
                value: '',
                type: 'text',
                error: false,
                error_msg: '',
                class:"sm12 md6 pr-2"
            },
            last_name: {
                label: 'Last name',
                value: '',
                type: 'text',
                error: false,
                error_msg: '',
                class:"sm12 md6 pl-2"
            },
            address: {
                label: 'Address',
                value: '',
                type: 'text',
                error: false,
                error_msg: '',
                class:"sm12 md12"
            },
            email: {
                label: 'Email',
                value: '',
                type: 'email',
                error: false,
                error_msg: '',
                class:"sm12 md12"
            },
            password: {
                label: 'Password',
                value: '',
                type: 'password',
                error: false,
                error_msg: '',
                class:"sm12 md6 pr-2"
            },
            confirm: {
                label: 'Confirm',
                value: '',
                type: 'password',
                error: false,
                error_msg: '',
                class:"sm12 md6 pl-2"
            }
        },
        type: '2',
        errorCreate: false
    }),

    watch: {
        
    },

    methods: {
        submit () {
            let hasEmpty = !validateForm(this.create)
            let hasError = false
            let confirmError = this.create.confirm.value.length > 0 && this.create.confirm.value !== this.create.password.value
            if (confirmError) {
                this.create.confirm.error = true
                this.create.confirm.error_msg = 'Confirm does\'nt match the password'
                hasError = true
            }

            if (!(/.+@.+/.test(this.create.email.value)))  {
                this.create.email.error = true
                this.create.email.error_msg = 'Email must be valid'
                hasError = true
            }
            if (hasEmpty || hasError) {
                return
            }
            this.$store.dispatch('SHOW_LOADING')
            let url = this.$baseUrl + '/account/create'
            let data = objectToformData(this.create)
            data.append('type', this.type)
            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.$store.dispatch('SET_USER_DATA', response.data, { root: true })
                    this.$router.push({ name: 'Profile' })
                })
                .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        },  
    }

}
</script>

<style>

</style>
