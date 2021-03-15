<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="600px">
            <v-card>
                <v-card-title class="font-weight-bold text-uppercase info white--text">
                    Message
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text style="max-height: 320px;">
                    {{message.message}}
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Close</v-btn>
                    <v-btn color="success" v-if="message.sender != $store.getters.USER_DATA.id" @click="reply">Reply</v-btn>
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
            message: ''
        }
    },
    methods: {
        reply () {
            this.showModal = false
            this.$emit('reply', this.message)
        }
    }
}
</script>

<style>

</style>
