<template>
    <v-card class="elevation-0 transparent mt-2 mb-4 mx-4">
        <v-card-title primary-title class="px-0">
            <div class="text-xs-left">
                <h3 class="headline mb-0">Profile</h3>
                <div>{{ $userTypes[USER_DATA.type] }}</div>
            </div>
        </v-card-title>
        <v-list two-line id="profile" class="elevation-2" v-if="loaded">
            <v-list-tile 
                avatar
                ripple
                @click="changePhoto"
                >
                <v-list-tile-content>
                    <v-list-tile-sub-title class="text-uppercase">Photo</v-list-tile-sub-title>
                    <v-list-tile-title>Add a photo to personalize your account</v-list-tile-title>
                </v-list-tile-content>
                <v-list-tile-avatar
                    :tile="false"
                    size="60"
                    color="grey lighten-4"
                    v-if="$store.getters.USER_DATA.avatar"
                >
                    <img :src="$store.getters.USER_DATA.avatar">
                </v-list-tile-avatar>
            </v-list-tile>
            <v-divider class="mx-3"></v-divider>
            <v-list-tile>
                <v-list-tile-content>
                    <v-list-tile-sub-title class="text-uppercase">Name 
                        <v-btn color="info" flat @click="updateName">Edit</v-btn>
                    </v-list-tile-sub-title>
                    <v-list-tile-title>{{ USER_DATA.first_name + ' ' + USER_DATA.last_name }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-divider class="mx-3"></v-divider>
            <v-list-tile>
                <v-list-tile-content>
                    <v-list-tile-sub-title class="text-uppercase">Address</v-list-tile-sub-title>
                    <v-list-tile-title>{{ USER_DATA.address }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-divider class="mx-3"></v-divider>
            <v-list-tile>
                <v-list-tile-content>
                    <v-list-tile-sub-title class="text-uppercase">Email</v-list-tile-sub-title>
                    <v-list-tile-title>{{ USER_DATA.email }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-divider class="mx-3"></v-divider>
            <v-list-tile>
                <v-list-tile-content>
                    <v-list-tile-sub-title class="text-uppercase">Password
                        <v-btn color="info" flat @click="changePassword">Change</v-btn>
                    </v-list-tile-sub-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-divider class="mx-3"></v-divider>
            <v-list-tile>
                <v-list-tile-content>
                    <v-list-tile-sub-title class="text-uppercase">Date Registered</v-list-tile-sub-title>
                <v-list-tile-title>{{ user.create_time }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-divider class="mx-3" v-if="USER_DATA.type == 1"></v-divider>
            <v-list-tile class="note" v-if="USER_DATA.type == 1">
                <v-list-tile-content v-if="!registered">
                    <v-list-tile-sub-title class="text-uppercase red--text">
                        Your Account is not yet activated
                    </v-list-tile-sub-title>
                    <p>Please pay your registration fee to activate your account. 
                        <v-btn flat color="info" @click="viewHow">How?</v-btn>
                    </p>
                </v-list-tile-content>
                <v-list-tile-content v-else-if="registered && !expired">
                    <v-list-tile-sub-title class="text-uppercase blue--text">Your Account is activated</v-list-tile-sub-title>
                    <p>Expiration: {{expiration}}</p>
                </v-list-tile-content>
                <v-list-tile-content v-else>
                    <v-list-tile-sub-title class="text-uppercase red--text">
                        Your Account has been expired ({{registration}})
                    </v-list-tile-sub-title>
                    <p>Please pay your registration fee to renew your account.
                        <v-btn flat color="info" @click="viewHow">How?</v-btn>
                    </p>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
        <update-photo 
            ref="photo"
        ></update-photo>
        <payment-steps
            ref="steps"
        ></payment-steps>
        <update-name
            ref="update_name"
        ></update-name>
        <change-password
            ref="change_password"
        ></change-password>
    </v-card>
</template>

<script>
import UpdatePhoto from '@/components/user/PhotoModal.vue'
import UpdateName from '@/components/user/info/UpdateName.vue'
import ChangePassword from '@/components/user/info/ChangePassword.vue'
import PaymentSteps from '@/components/user/PaymentSteps.vue'
import { mapGetters } from 'vuex';

export default {
    data: () => ({
        user: {},
        registered: false,
        expiration: null,
        registration: null,
        expired: false,
        loaded: false
    }),
    components: {
        UpdatePhoto,
        UpdateName,
        ChangePassword,
        PaymentSteps
    },
    created () {
        this.getUserData()
        if (this.USER_DATA.type == 1) {
            this.getRegistration()
        }
    },
    computed: {
        ...mapGetters({
            USER_DATA: 'USER_DATA'
        })
    },
    methods: {
        getUserData () {
            let url = this.$baseUrl + '/Account/get'
            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
                    this.user = response.data
                    this.loaded = true
                })
        },
        getRegistration () {
            let url = this.$baseUrl + '/vet/Registration/get'
            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
                    if (response.data.registration_time) {
                        this.registration = response.data.registration_time
                        this.expiration = response.data.expiration_time
                        this.expired = response.data.expired
                        this.registered = true
                    } else {
                        this.expiration = response.data.expiration_time
                        this.registration = response.data.registration_time
                        this.registered = false
                    }
                })
                .catch(() => {

                })
        },
        changePhoto () {
            this.$refs.photo.reset()
            this.$refs.photo.showModal = true
        },
        updateName () {
            this.$refs.update_name.reset()
            this.$refs.update_name.showModal = true
        },
        changePassword () {
            this.$refs.change_password.reset()
            this.$refs.change_password.showModal = true
        },
        viewHow () {
            this.$refs.steps.showModal = true
        }
        
    }
}
</script>

<style scoped>
#profile >>> .note .v-list__tile {
    height: auto;
}
</style>
