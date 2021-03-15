<template>
    <v-footer id="footer" style="height:auto">
        <v-layout row wrap>
            <v-flex class="pa-2 grey darken-3">
                <div class="white--text ml-3 text-center" v-if="!$store.getters.USER_DATA">
                    <v-btn 
                        v-for="(link, i) in this.$store.state.links" 
                        :key="i" 
                        flat 
                        :to="{ name: link.name }"
                        class="light-blue--text text--lighten-1"
                    >{{ link.name }}</v-btn>
                </div>
                <hr class="grey darken-1" v-if="!$store.getters.USER_DATA">
                <div class="white--text ml-3 text-xs-left">
                    <div class="pt-2">{{ $app.name }} &copy; {{ new Date().getFullYear() }}. All rights reserved.</div>
                </div>
            </v-flex>
        </v-layout>
    </v-footer>
</template>
<script>
export default {
    data () {
        return {
            links: [
                { name: 'Home' },
                { name: 'Contact Us' },
                { name: 'Sign In' } 
            ]
        }
    }
}
</script>

<style scoped>
#footer >>> .v-btn--active:before, 
#footer >>> .v-btn--active {
    background-color: unset !important;
}
</style>