<template>
    <div>
        <v-card
            class="mx-auto my-3"
            max-width="800"
        >
            <v-img
            src="https://cdn.vuetifyjs.com/images/cards/desert.jpg"
            aspect-ratio="2.75"
            ></v-img>
            <v-card-text class="headline font-weight-bold">
            "Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as well."
            </v-card-text>

            <v-card-actions>
                <v-list-tile class="grow">
                    <v-list-tile-avatar color="grey darken-3">
                        <v-img
                            class="elevation-6"
                            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                        ></v-img>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>Evan You</v-list-tile-title>
                    </v-list-tile-content>

                    <v-layout
                    align-center
                    justify-end
                    >
                    <v-icon class="mr-1">access_time</v-icon>
                        <span class="subheading mr-2">Jan 3, 2019</span>
                    </v-layout>
                </v-list-tile>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>
