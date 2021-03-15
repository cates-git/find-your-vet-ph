<template>
    <v-layout row justify-center>
        <v-dialog v-model="showModal" scrollable max-width="400px">
            <v-card>
                <v-card-title class="text-uppercase font-weight-bold red white--text" >
                    Confirm
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <span v-html="message"></span>
                    <v-textarea
                        label="Add Remarks"
                        auto-grow
                        rows="1"
                        v-model="remarks"
                    ></v-textarea>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="showModal = false">Cancel</v-btn>
                    <v-btn color="red white--text" @click="$emit('submit')">Continue</v-btn>
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
            message: '',
            remarks: ''
        }
    },
    methods: {
    }
}
</script>

<style>

</style>
