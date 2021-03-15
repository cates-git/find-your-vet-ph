<template>

<div>
    <v-card v-if="info">
        <v-avatar
            :tile="false"
            size="100"
            class="ma-2"
        >
            <img v-if="info.avatar" :src="info.avatar" alt="avatar">
            <v-avatar color="blue darken-2" v-else>
                <v-icon class="white--text">person</v-icon>
            </v-avatar>
        </v-avatar>
        <div>
            <h3 class="headline mt-2">{{info.first_name + ' ' + info.last_name}}</h3>
            <div style="text-transform: none">{{info.email}}</div>
            <div style="text-transform: none">{{info.address}}</div>
        </div>
        <v-spacer></v-spacer>
        <div>
            <v-rating readonly :value="parseInt(info.rating)" color="orange"></v-rating>
            <div class="info--text" v-if="$store.getters.USER_DATA.type == $userTypes.PET_OWNER">You can only rate when your appointment with the vet is done.</div>
            <v-btn color="success" large outline v-if="$store.getters.USER_DATA.type == 2" @click="$refs.book.show = true">
                <v-icon>calendar_today</v-icon>
                Book an appointment
            </v-btn>
            <v-btn color="info" large outline @click="send">
                <v-icon>message</v-icon>
                Send Message
            </v-btn>
        </div>
        <v-card-title class="text-uppercase font-weight-bold">
        </v-card-title>
    </v-card>
    <v-tabs fixed-tabs v-model="currentTab">
        <v-tab 
            v-for="(tab, tabIndex) in tabs" 
            :key="tabIndex"
            :to="{ name: tab.link, params: { vetId: vetId } }"
            :value="tab.link"
            >
            {{ tab.name }}
        </v-tab>
    </v-tabs>
    <v-tabs-items>
        <v-tab-item>
            <router-view :vet-id="vetId" :key="$route.fullPath"></router-view>
        </v-tab-item>
    </v-tabs-items>

    <book-appointment 
        ref="book"
        :vet-id="vetId"
    ></book-appointment>

    <send-message 
        ref="message"
    ></send-message>
</div>
</template>

<script>
import BookAppointment from '@/components/pet_owner/BookAppointment.vue'
import SendMessage from '@/components/user/message/Send.vue'

export default {
    data: () => ({
        currentTab: 'Group',
        tabs: [
            { name: 'Group', link: 'Vet Group'},
            { name: 'Services', link: 'Vet Services'},
            { name: 'Promos', link: 'Vet Promos'},
            { name: 'Certificates', link: 'Vet Certificates'},
            { name: 'Reviews', link: 'Vet Reviews'}
        ],
        info: null
    }),
    components: {
        BookAppointment,
        SendMessage
    },
    created() {
        this.vetId = this.$route.params.vetId
        this.currentTab = this.$route.name
        this.getVetInfo()
    },
    methods: {
        getVetInfo () {
            this.loading = true
            let url = this.$baseUrl + '/Users/get/' + this.vetId

            this.$store.dispatch('API_GET', { url }, { root: true })
                .then(response => {
                    this.info = response.data
                })
                .catch(error => { this.$store.dispatch('SHOW_ALERT', error) })
                .finally(() => this.loading = false)
        },
        send () {            
            this.$refs.message.reply = true
            this.$refs.message.create.receiver = this.vetId
            this.$refs.message.showModal = true
        }
    }
}
</script>

<style>

</style>
