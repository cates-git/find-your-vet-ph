<template>
    <v-card class="elevation-0 transparent mt-2 mb-4 mx-4">
        <v-card-title primary-title class="px-0">
            <h3 class="headline mb-0">Messages</h3>
            <v-spacer></v-spacer>
            <v-btn 
                class="ma-0"
                color="info" 
                @click="sendMessage" 
                v-if="$store.getters.USER_DATA.type != 0"
                >
                <v-icon class="mr-1">message</v-icon> 
                {{$store.getters.USER_DATA.type == $userTypes.PET_OWNER ? 'Send Message' : 'Send Message to Admin'}} 
            </v-btn>
        </v-card-title>
        <p v-if="loading" class="info--text">Getting messages..</p>
        <p v-else-if="!loading && list.length === 0" class="primary--text">No messages.</p>
        <v-list v-else two-line class="elevation-2">
            <template v-for="(item, index) in list">
                <v-list-tile
                    :key="item.create_time"
                    avatar
                    ripple
                    @click="read(item)"
                    >
                    <v-list-tile-avatar>
                        <img v-if="item.avatar" :src="item.avatar" title="Avatar">
                        <v-icon v-else>account_circle</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>
                            <span v-if="!item.receiver && item.sender == $store.getters.USER_DATA.id">me, Admin</span>
                            <span v-else-if="item.sender == $store.getters.USER_DATA.id">me, {{ item.receiver_name }}</span>
                            <span v-else>{{ item.sender_name }}</span>
                        </v-list-tile-title>
                        <v-list-tile-sub-title class="primary--text">
                            <span v-if="!item.receiver && item.sender == $store.getters.USER_DATA.id">you sent to admin</span>
                            <span v-else-if="item.sender == $store.getters.USER_DATA.id">{{ item.receiver_email }}</span>
                            <span v-else>{{ item.sender_email }}</span>
                        </v-list-tile-sub-title>
                        <v-list-tile-sub-title>{{ item.message }}</v-list-tile-sub-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                        <v-list-tile-action-text>{{item.create_time}}</v-list-tile-action-text>
                        <v-icon
                            v-if="item.sender == $store.getters.USER_DATA.id"
                            color="grey lighten-1"
                            >
                            call_made
                        </v-icon>
                        <v-icon
                            v-else
                            color="grey lighten-1"
                            >
                            call_received
                        </v-icon>
                    </v-list-tile-action>
                </v-list-tile>
                <v-divider
                    inset
                    v-if="index + 1 < list.length"
                    :key="index"
                ></v-divider>
            </template>
        </v-list>
        <read-message
            ref="read"
            @reply="send"
        ></read-message>
        <send-message 
            ref="message"
            @reload-table="getDataFromApi"
        ></send-message>
    </v-card>
</template>

<script>
import SendMessage from '@/components/user/message/Send.vue'
import ReadMessage from '@/components/user/message/Read.vue'
  export default {
    data () {
      return {
        list: [],
        loading: false
      }
    },
    components: {
        SendMessage,
        ReadMessage
    },
    created() {
      this.getDataFromApi()
    },

    mounted () {

    },

    methods: {
        getDataFromApi () {
            this.loading = true
            let url = this.$baseUrl + '/Message/all'

            this.$store.dispatch('API_POST', { url }, { root: true })
                .then(response => {
                    this.list = response.data
                })
                .catch(() => {})
                .finally(() => this.loading = false)
        },
        read (message) {
            this.$refs.read.message = message
            this.$refs.read.showModal = true
        },
        send (message) {
            this.$refs.message.reply = true
            this.$refs.message.showModal = true
            this.$refs.message.create.receiver = message.sender
        },
        sendMessage () {
            this.$refs.message.reply = false
            this.$refs.message.showModal = true
            this.$refs.message.create.receiver = 0
        }
    }
  }
</script>

<style>

</style>
