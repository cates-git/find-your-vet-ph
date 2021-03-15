<template>
	<div>
		<section id="home">
			<v-parallax :src="require(`@/assets/images/dog.jpg`)">
				<v-layout
					row
					wrap
					align-center
				>
					<v-flex xs12 sm6>
						<h1 class="display-2 font-weight-thin mb-3">Find a Veterinarian</h1>
						<h4 class="subheading">Care for your pet.</h4>
						<v-btn
							class="my-3"
							dark
							round
							outline
							large
							:to="{ name:'Sign Up' }"
						>
							Sign Up
						</v-btn>
					</v-flex>

				</v-layout>
			</v-parallax>
		</section>

		<section id="features">      
			<v-container
				fluid
				grid-list-md 
				class="light-blue">
				<v-layout 
					row 
					wrap
					justify-center 
					style="transform: translateY(-25%); margin-bottom: -5%;">
					<v-flex 
						v-for="(feature, i) in features"
						:key="i"
						xs12 sm6 md4
						class="px-4"
						>
						<v-card 
							color="light-blue" 
							class="white--text">
							<v-card-text class="text-xs-center">
								<v-icon x-large class="white--text">{{feature.icon}}</v-icon>
							</v-card-text>
							<v-card-title primary-title class="layout justify-center">
								<div class="headline text-xs-center">{{feature.title}}</div>
							</v-card-title>
							<v-card-text>{{feature.description}}</v-card-text>
							<v-btn
								class="my-3"
								dark
								round
								outline
								large
								:to="{ name: feature.link }"
							>{{feature.button}}</v-btn>
						</v-card>
					</v-flex>
				</v-layout>
			</v-container>
		</section>

		<section id="services">
			<v-container grid-list-xl>
				<v-layout row wrap justify-center class="my-5">
					<v-flex xs12 sm8>
						<v-card class="elevation-0 transparent">
							<v-card-title primary-title class="layout">
								<v-icon x-large class="light-blue--text">info</v-icon>
								<div class="headline font-weight-black light-blue--text">
									SERVICES
								</div>
							</v-card-title>
							<v-card-text style="text-align: left">
								Whether you are a pet owner or a veterinarian. You can always use the features provided. The veterinary finder is focused on the relationship between pet owner and veterinarian. Pet owners can search for a vet through the pet's sickness and view the vet's legitimacy before booking an appointment. The veterinarians can also provide their documents to prove their legitimacy and offer their services.
							</v-card-text>
						</v-card>
					</v-flex>
					<v-flex xs12 sm4>
						<v-card class="elevation-12">
							<v-card-title primary-title class="layout justify-center">
								<div class="headline font-weight-black light-blue--text">
									<v-icon medium class="light-blue--text">build</v-icon>
									FEATURES
								</div>
							</v-card-title>
							<v-card-text>
								Enjoy the features once you have an account
							</v-card-text>
							<v-list class="transparent">
								<v-list-tile>
									<v-list-tile-action>
										<v-icon class="blue--text text--lighten-2">search</v-icon>
									</v-list-tile-action>
									<v-list-tile-content>
										<v-list-tile-title>Find a veterinarian for your pet</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
								<v-list-tile>
									<v-list-tile-action>
										<v-icon class="blue--text text--lighten-2">settings</v-icon>
									</v-list-tile-action>
									<v-list-tile-content>
										<v-list-tile-title>Veterinarians can offer services</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
								<v-list-tile>
									<v-list-tile-action>
										<v-icon class="blue--text text--lighten-2">message</v-icon>
									</v-list-tile-action>
									<v-list-tile-content>
										<v-list-tile-title>Talk to other users</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
							</v-list>
						</v-card>
					</v-flex>
				</v-layout>
			</v-container>
		</section>
	</div>
</template>
<script>
export default {
	data: () => ({
		features: [
			{ 
				title: 'Pet Owners', 
				icon: 'pets',
				button: 'SEARCH',
				link: 'Sign Up',
				description: `Search for veterinarian based on your pet's sickness.`
			},
			{ 
				title: 'Veterinarians', 
				icon: 'verified_user',
				button: 'REGISTER',
				link: 'Sign Up',
				description: `Showcase your services and promos to the pet owners.`
			},
			{ 
				title: 'Contact Us', 
				icon: 'email',
				button: 'SEND A MESSAGE',
				link: 'Sign Up',
				description: `Send inquiries to the administrator.`
			}
		]
	})  
}
</script>

<style scoped>

#home >>> .v-parallax__image {
	height: 142% !important;
	width: auto;
}
#features >>> .v-card__text {
	line-height: 24px;
}

#features >>> .v-card {
	box-shadow: none;
}

#features >>> .v-card:before,
#features >>> .v-card:after {   
	width: 98%;
	height: 90%;
	position: absolute;
	bottom: 5px;
	left: -7px;
	z-index: -1;
	content: " ";
	display: block;
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0,0,0,0)), to(rgba(0,0,0,0.7)));
	background: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.7));
	-webkit-transform: skew(3deg,0);
	transform: skew(3deg,0);
	-webkit-filter: blur(2px);
	filter: blur(2px);
}
#features >>> .v-card:after {
	transform: skew(-3deg,0);
	left:14px;
}
</style>

