<template>
    <v-container fluid fill-height class="lighten-1" :class="$theme.content_background">
        <v-layout row wrap align-center justify-center>
            <v-flex xs12 md4>
                <router-view :key="$route.fullPath"></router-view>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>

export default {
    
}
</script>

<style>

</style>
