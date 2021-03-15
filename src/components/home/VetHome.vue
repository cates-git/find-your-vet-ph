<template>
    <v-card class="elevation-0 transparent mt-2 mb-4 mx-4">
        <v-card
            class="mx-auto my-3"
            max-width="800">
            <v-toolbar card dark :class="$theme.background">
                <v-toolbar-title>Post</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-text-field 
                    label="Title"
                    v-model="create.title"
                ></v-text-field>
                <v-textarea
                    label="Description"
                    auto-grow
                    rows="2"
                    v-model="create.description"
                ></v-textarea>
                <v-img
                    v-if="create.file_url"
                    src="https://picsum.photos/350/165?random"
                    height="125"
                    contain
                    class="grey darken-4"
                ></v-img>

            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-btn outline color="info" @click="selectFile">
                    <v-icon>image</v-icon>
                    Add Image
                </v-btn>
                <input
                    style="display: none"
                    type="file"
                    ref="select_file"
                    accept="image/*"
                    @change="onFilePicked"
                >

                <v-spacer></v-spacer>
                
                <v-btn
                    color="success"
                    depressed
                    >
                    <v-progress-circular :indeterminate="false" :size="24" color="grey lighten-5" class="mr-3"></v-progress-circular>
                        Post
                </v-btn>
            </v-card-actions>
        </v-card>
        <v-card
            class="mx-auto my-3"
            max-width="800"
        >
            <v-img
              src="https://picsum.photos/350/165?random"
              height="125"
              contain
              class="grey darken-4"
            ></v-img>
            <!-- <v-img
            src="https://cdn.vuetifyjs.com/images/cards/desert.jpg"
            aspect-ratio="2.75"
            ></v-img> -->
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
    </v-card>
</template>

<script>
export default {
    data () {
        return {
            showModal: false,
            create: {
                file: '',
                title: '',
                description: ''
            },
            file_name: '',
            file_url: ''
        }
    },
    methods: {
        selectFile () {
            this.$refs.select_file.click()
        },
		
		onFilePicked (e) {
            const files = e.target.files
            this.create.file = files[0]
            this.file_name = files[0] !== undefined ? files[0].name : ''
            // this.file_url = files[0] !== undefined ? files[0].name : ''
		}
    }
}
</script>

<style>

</style>
