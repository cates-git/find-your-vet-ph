<template>
    <v-dialog
        width="400"
        persistent
        value="true"
        >
        <v-card>
            <v-card-title class="white--text" :class="$store.state.modal.bg_header"
            >Notice</v-card-title>

            <v-card-text v-html="$store.state.modal.body"></v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    flat
                    @click="$store.commit('SHOW_MODAL_ALERT', false)"
                >Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
export default {
    data () {
        return {
            
        }
    }
}
</script>

<style>

</style>
