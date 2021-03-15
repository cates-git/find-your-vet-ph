<template>
	<div>
		<welcome v-if="!USER_DATA" />
		<!-- <admin-home v-if="USER_DATA && USER_DATA.type==0" />
		<vet-home v-if="USER_DATA && USER_DATA.type==1" />
		<pet-owner-home v-if="USER_DATA && USER_DATA.type==2" /> -->
	</div>
</template>
<script>
import { mapGetters } from 'vuex';
export default {
	data: () => ({
		
	}),
	components: {
		Welcome: () => import('@/components/home/Welcome.vue'),
		// AdminHome: () => import('@/components/home/AdminHome.vue'),
		// VetHome: () => import('@/components/home/VetHome.vue'),
		// PetOwnerHome: () => import('@/components/home/PetOwnerHome.vue')
	},
	computed: {
		...mapGetters({
			USER_DATA: 'USER_DATA'
		})
	},
	
}
</script>

<style scoped>

</style>

