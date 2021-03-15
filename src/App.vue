<template>
	<v-app id="app">
		<!-- header -->
		<v-toolbar 
			id="header"
			app 
			:color="$theme.background" 
			:dark="$theme.dark"
			:fixed="false">

				<!-- v-if="mobile && USER_DATA"  -->
			<v-toolbar-side-icon 
				v-if="USER_DATA && $store.state.mobile"
				@click.stop="$store.commit('SHOW_SIDE_NAV', !$store.state.show_side_nav)"
			></v-toolbar-side-icon>
			
			<v-toolbar-title
				class="headline">
				{{ $app.name }}
			</v-toolbar-title>

			<v-spacer></v-spacer>

			<navigation/>

			<v-menu 
				bottom 
				left 
				v-if="USER_DATA"
				avatar
				id="right-menu"
			>
				<!-- <v-avatar slot="activator" v-if="USER_DATA.avatar" >
					<img :src="USER_DATA.avatar"
					contain :aspect-ratio="1.7" alt="avatar">
				</v-avatar> -->
				<v-avatar slot="activator"  color="light-blue darken-3">
					<span class="white--text subheading text-uppercase">
						{{USER_DATA.first_name.charAt(0)}}
					</span>
				</v-avatar>

				<v-list>
					<v-list-tile @click="$router.push({ name: 'Profile' })">
                        <v-list-tile-action>
                            <v-icon>account_circle</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Profile</v-list-tile-title>
                        </v-list-tile-content>
					</v-list-tile>
					<v-list-tile @click="signOut">
                        <v-list-tile-action>
                            <v-icon>power_settings_new</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Sign out</v-list-tile-title>
                        </v-list-tile-content>
					</v-list-tile>
				</v-list>
			</v-menu>
		</v-toolbar>
		<!-- ./ header -->
		
		<v-content>
			<v-fade-transition leave-absolute>
				<router-view></router-view>
			</v-fade-transition>
		</v-content>

		<Footer />
		
		<!-- modal components -->
		<ModalLoader v-if="$store.state.modal.SHOW_MODAL_LOADING"/>
		<ModalAlert v-if="$store.state.modal.SHOW_MODAL_ALERT"/>
	</v-app>
</template>

<script>
import { mapGetters } from 'vuex';

import Footer from './template/Footer'
import Navigation from './template/Navigation'

export default {
	name: 'App',
	components: {
		Footer,
		Navigation
	},
	data () {
		return {
			isMounted: false
		}
	},
		
	created() {
		this.windowResize()
		window.addEventListener('resize', this.windowResize)
	},

	destroyed() {
		window.removeEventListener('resize', this.windowResize)
	},
	mounted () {
        this.isMounted = true;
	},
	computed: {
		...mapGetters({
			USER_DATA: 'USER_DATA'
		})
	},
	methods: {
		signOut () {
			this.$store.dispatch('SHOW_LOADING')
			this.$store.dispatch('SIGN_OUT', this.$baseUrl)
                .then(() => {
					this.$store.dispatch('CLOSE_LOADING')
					this.$router.push({ name: 'Sign In' })
				})
                .catch(() => {})
		},
		windowResize() {
			let mobile = window.innerWidth < 720
			this.$store.commit('MOBILE', mobile)
			this.$store.commit('SHOW_SIDE_NAV', !mobile)
		}
	}
}
</script>
<style>
#app {
	font-family: 'Roboto', 'Avenir', Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #2c3e50;
}
#header .v-toolbar__content {
		border-bottom: 4px solid #0288D1!important;
}
#header .v-btn--active:before, 
#header .v-btn--active {
		background-color: #0288D1!important;
}
main.v-content .v-content__wrap .right-side {
	width: calc(100% - 300px);
	float: right;
}
#right-menu .v-avatar {
	height: 40px!important;
	width: 40px!important;
}
</style>
