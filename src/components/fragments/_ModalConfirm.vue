<template>
    <v-dialog
        width="400"
        persistent
        value="true"
        >
        <v-card>
            <v-card-title class="red white--text">
                Confirm
            </v-card-title>

            <v-card-text v-html="$store.state.modal.body"></v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <slot name="cancel">
                    <v-btn
                        color="primary"
                        flat
                        @click="$store.commit('SHOW_MODAL_CONFIRM', false)"
                    >Cancel</v-btn>
                </slot>
                <slot name="submit"></slot>
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
