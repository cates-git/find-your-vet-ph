<template>
    <v-navigation-drawer
        v-model="showSideNav"
        hide-overlay
        :class="{ 'left-side': showSideNav }"
    >
        <v-toolbar flat>
            <v-list>
                <v-list-tile>
                    <v-list-tile-title class="title">
                        Application
                    </v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-toolbar>
        
        <v-divider></v-divider>

        <v-list dense class="pt-0">
            <v-list-tile
                v-for="link in links"
                :key="link.name"
                :to="{ name: link.name }"
            >
                <v-list-tile-action>
                    <v-icon>{{ link.icon }}</v-icon>
                </v-list-tile-action>

                <v-list-tile-content>
                    <v-list-tile-title>{{ link.name }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data () {
        return {
            showSideNav: true,
            links: [
                { name: 'Profile', icon: 'account_circle' },
                { name: 'Services', icon: 'settings' },
                { name: 'Appointments', icon: 'calendar_today' }
            ]
        }
    },
    computed: {
        ...mapGetters({
        USER_DATA: 'USER_DATA'
        })
    }
}
</script>