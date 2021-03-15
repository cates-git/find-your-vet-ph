<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="400px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold red white--text" >
                    Confirm
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    Please confirm if the veterinarian has paid the registration fee and has sent the image of the official receipt through email to activate vet's account.
                </v-card-text>
                <p class="red--text" v-if="error">{{error}}</p>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="success" :loading="loading" @click="$emit('submit', vet_id)">Confirm</v-btn>
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
            vet_id: 0,
            loading: false,
            error: null
        }
    },
}
</script>

<style>

</style>
