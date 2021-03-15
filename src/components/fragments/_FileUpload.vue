<template>
    <div>
        <v-btn outline color="info" @click="selectFile">
            <v-icon v-if="btnIcon">{{btnIcon}}</v-icon>
            {{btnName}}
        </v-btn>
        <input
            style="display: none"
            type="file"
            ref="select_file"
            :accept="acceptTypes"
            @change="onFilePicked"
        >
    </div>
</template>

<script>
export default {
    data () {
        return {
            file: '',
            file_url: '',
            file_name: '',
            file_uploading: false,

        }
    },
    props: {
        btnName: {
            required: false
        },
        btnIcon: {
            default: 'image',
            required: false
        },
        acceptTypes: {
            default: 'image/*',
            required: false
        }
    },
    methods: {
            
        selectFile () {
            this.$refs.select_file.value = ''
            this.$refs.select_file.click()
        },
        
        onFilePicked (e) {
            this.file_uploading = true
            const files = e.target.files
            if(files[0] !== undefined) {
                this.file_name = files[0].name
                if(this.file_name.lastIndexOf('.') <= 0) {
                    return
                }
                const fr = new FileReader ()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', () => {
                    this.file_url = fr.result
                    this.file = files[0] // this is an image file that can be sent to server...
                    this.file_uploading = false
                })
            } else {
                this.file = ''
                this.file_name = ''
                this.file_url = ''
            }
        }
    }
}
</script>

<style>

</style>
