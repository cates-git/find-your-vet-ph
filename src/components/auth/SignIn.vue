<template>
    <v-card class="elevation-12">
        <v-card-text>
            <v-form>
                <v-text-field
                    v-for="(field, i) in signin"
                    :key="i"
                    :prepend-icon="field.icon" 
                    v-model.trim="field.value" 
                    :label="field.label" 
                    :type="field.type"
                    :error="field.error"
                    :error-messages="field.error ? field.error_msg : ''"
                ></v-text-field>
            </v-form>
            <v-btn 
                :dark="$theme.dark" 
                :class="$theme.background" 
                class="font-weight-bold"
                round 
                large
                @click="submit"
            >Sign In</v-btn>
            <v-spacer class="mt-3"></v-spacer>
            <v-btn flat :class="$theme.text" :to="{ name: 'Sign Up' }">CREATE AN ACCOUNT</v-btn>
        </v-card-text>
    </v-card>
</template>

<script>
import { validateForm, objectToformData } from '@/helpers/form_data_helper'
export default {
    data: () => ({
        loading: false,
        signin: {
            email: {
                icon: 'person',
                label: 'Email',
                value: null,
                type: 'email',
                error: false,
                error_msg: ''
            },
            password: {
                icon: 'lock',
                label: 'Password',
                value: null,
                type: 'password',
                error: false,
                error_msg: ''
            }
        },
        errorSignin: false,
    }),

    methods: {
        submit () {
            let hasEmpty = !validateForm(this.signin)
            let hasError = false
            if (!(/.+@.+/.test(this.signin.email.value)))  {
                this.signin.email.error = true
                this.signin.email.error_msg = 'Email must be valid'
                hasError = true
            }
            if (hasEmpty || hasError) {
                return
            }
            this.$store.dispatch('SHOW_LOADING')
            let url = this.$baseUrl + '/Account/sign_in'
            let data = objectToformData(this.signin)
            this.$store.dispatch('API_POST', { url, data }, { root: true })
                .then(response => {
                    this.$store.dispatch('SET_USER_DATA', response.data, { root: true })
                    if (response.data.type == 2) {
                        this.$router.push({ name: 'Find Vet' })
                    } else if (response.data.type == 1) {
                        this.$router.push({ name: 'Appointments' })
                    } else if (response.data.type == 0) {
                        this.$router.push({ name: 'Transactions' })
                    } else if (response.data.type == -1) {
                        this.$router.push({ name: 'Admin Users' })
                    }
                })
                .catch(error => {
                    if (error.email) {
                        this.signin.email.error = true
                        this.signin.email.error_msg = error.email
                    }
                    if (error.password) {
                        this.signin.password.error = true
                        this.signin.password.error_msg = error.password
                    }
                })
                .finally(() => this.$store.dispatch('CLOSE_LOADING'))
        }
    }

}
</script>

<style>

</style>
