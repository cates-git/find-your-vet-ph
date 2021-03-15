<template>
    <div>
        <v-toolbar 
            id="header"
            app 
            :color="$theme.background" 
            :dark="$theme.dark"
            :fixed="false">

            <v-toolbar-side-icon 
                v-if="USER_DATA" 
                @click.stop="$store.commit('SHOW_SIDE_NAV', !SHOW_SIDE_NAV)"
            >
                <v-icon>list</v-icon>
            </v-toolbar-side-icon>

            <v-toolbar-title
                class="headline" >
                {{ name }}
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <navigation/>

            <!-- more icon, for log out/profile-->
            <v-menu 
                bottom 
                left 
                v-if="USER_DATA"
            >
                <v-btn slot="activator" :dark="$theme.dark" icon >
                    <v-icon>more_vert</v-icon>
                </v-btn>

                <v-list>
                    <v-list-tile @click="$router.push({ name: 'Home' })">
                        <v-list-tile-action>
                        <v-icon>account_circle</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Profile</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile @click="$router.push({ name: 'Test' })">
                        <v-list-tile-action>
                            <v-icon>power_settings_new</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Log out</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>
        
        <side-nav 
        ref="side_nav"
        v-if="USER_DATA" />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Navigation from './Navigation'
import SideNav from './SideNav'

export default {
    name: 'Header',
    components: {
        Navigation,
        SideNav
    },
    data () {
        return {
        name: 'FindYourVet.ph',
        logged_in: false
        }
    },
    computed: {
        ...mapGetters({
            USER_DATA: 'USER_DATA',
            SHOW_SIDE_NAV: 'SHOW_SIDE_NAV'
        })
    }
}
</script>

<style scoped>
    /* background-color: #03a9f4!important; */
#header >>> .v-toolbar__content {
    border-bottom: 4px solid #0288D1!important;
}
#header >>> .v-btn--active:before, 
#header >>> .v-btn--active {
    background-color: #0288D1!important;
}
</style>
