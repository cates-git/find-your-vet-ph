<template>
    <v-layout fill-height v-if="USER_DATA">
        <v-flex sm3>
            <v-navigation-drawer
                v-model="$store.state.show_side_nav"
                :stateless="!$store.state.mobile"
                :absolute="$store.state.mobile"
                :temporary="$store.state.mobile"
            >
                <v-list>
                    <v-list-tile avatar>
                        <v-list-tile-avatar v-if="USER_DATA.avatar">
                            <img :src="USER_DATA.avatar" alt="avatar">
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title class="subheading">{{USER_DATA.first_name + ' ' + USER_DATA.last_name}}</v-list-tile-title>
                            <v-list-tile-sub-title class="body-1">{{USER_DATA.email}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
                <v-list id="profile-links" subheader>
                    <template v-for="(link, i) in links">   
                        <v-divider v-if="link.divider" :key="i"></v-divider>                    
                        <v-list-tile v-else :key="link.name" :to="{ name: link.name }">
                            <v-list-tile-action>
                                <v-icon>{{ link.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-title>{{ link.name }}</v-list-tile-title>
                        </v-list-tile>
                    </template>
                </v-list>
            </v-navigation-drawer>
        </v-flex>
        <v-flex sm9 xs12>
            <v-layout column>
                <page-loader></page-loader>
                <router-view :key="$route.fullPath"></router-view>                
            </v-layout>
        </v-flex>
        <v-snackbar
            v-if="ERR_MSG"
            value="true"
            color="error"
            :timeout="0"
            >
            {{ ERR_MSG }}
        </v-snackbar>
    </v-layout>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    props: ['mobile'],
    data: () => ({
        links: [],
        width: 300
    }),
    created () {
        let userType = parseInt(this.USER_DATA.type)
        if (userType === this.$userTypes.VET) { // vet
            this.links = [
                {
                    name: 'Appointments',
                    icon: 'calendar_today'
                },
                {
                    name: 'Certificates & Documents',
                    icon: 'folder'
                },
                {
                    name: 'Specialties',
                    icon: 'stars'
                },
                {
                    name: 'Services',
                    icon: 'settings'
                },
                {
                    name: 'Promos',
                    icon: 'stars'
                },
                {
                    name: 'Group',
                    icon: 'group'
                },
                { divider: true },
                {
                    name: 'Personal Info',
                    icon: 'account_circle'
                },
                {
                    name: 'Messages',
                    icon: 'message'
                }
            ]
        } else if (userType === this.$userTypes.PET_OWNER) { // pet owner
            this.links = [
                {
                    name: 'Find Vet',
                    icon: 'search'
                },
                {
                    name: 'Appointments',
                    icon: 'calendar_today'
                },
                {
                    name: 'Calendar',
                    icon: 'calendar_today'
                },
                {
                    name: 'Pets',
                    icon: 'pets'
                },
                {
                    name: 'Group',
                    icon: 'group'
                },
                { divider: true },
                {
                    name: 'Personal Info',
                    icon: 'account_circle'
                },
                {
                    name: 'Messages',
                    icon: 'message'
                }
            ]
        } else if (userType === this.$userTypes.ADMIN) {
            this.links = [
                {
                    name: 'Personal Info',
                    icon: 'account_circle'
                },
                {
                    name: 'Messages',
                    icon: 'message'
                },
                { divider: true },
                {
                    name: 'Accounts',
                    icon: 'supervised_user_circle'
                },
                // {
                //     name: 'Pet Owners',
                //     icon: 'pets'
                // },
                // {
                //     name: 'Veterinarians',
                //     icon: 'supervised_user_circle'
                // },
                {
                    name: 'Transactions',
                    icon: 'calendar_today'
                },
                { divider: true },
                {
                    name: 'Manage Registration',
                    icon: 'settings'
                },
            ]
        } else if (userType === this.$userTypes.SUPER) {
            this.links = [
                {
                    name: 'Personal Info',
                    icon: 'account_circle'
                },
                {
                    name: 'Admin Users',
                    icon: 'supervised_user_circle'
                }
            ]
        }
    },
    computed: {
        ...mapGetters({
            USER_DATA: 'USER_DATA',
            ERR_MSG: 'GET_ERR_MSG'
        })
    },
    methods: {

    }
}
</script>

<style scoped>
@media (min-width: 600px) {
    .flex.sm3 {
        -ms-flex-preferred-size: 20%;
        flex-basis: 20%;
        max-width: 20%;
    }
    .flex.sm9 {
        -ms-flex-preferred-size: 80%;
        flex-basis: 80%;
        max-width: 80%;
    }
}
#profile-links >>> .v-list__tile--active {
    background-color: #e1f5fe!important;
}
.v-navigation-drawer {
    transition: none !important;
}

.lightbox {
    box-shadow: 0 0 20px inset rgba(0, 0, 0, 0.2);
    background-image: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 72px);
}
</style>
