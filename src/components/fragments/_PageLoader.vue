<template>
    <v-progress-linear
        v-if="$store.state.page_loading"
        style="position: absolute"
        indeterminate
        :color="$theme.background"
        class="ma-0 mb--1"
    ></v-progress-linear>
</template>

<script>
export default {
    
}
</script>
