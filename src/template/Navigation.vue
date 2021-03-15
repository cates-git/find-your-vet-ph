<template>
    <v-toolbar-items>
        <v-btn 
            v-for="(link, i) in links" 
            :key="i" 
            flat 
            :to="{ name: link.name }"
        >{{ link.name }}</v-btn>
    </v-toolbar-items>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    data () {
        return {

        }
    },
    computed: {
        ...mapGetters({
            links: 'USER_LINKS'
        })
    }


}
</script>