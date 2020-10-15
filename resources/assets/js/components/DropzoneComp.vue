<template>
    <div>
        <div class="form-group">
            <vue-dropzone
                class="dropzone"
                ref="myVueDropzone"
                id="dropzone"
                :destroyDropzone="false"
                @vdropzone-sending="sending"
                @vdropzone-success="suc"
                @vdropzone-removed-file="remove"
                :include-styling="false"
                :options="dropzoneOptions"
                :useCustomSlot="true"
            >
                <div class="dropzone__prev-wrap">
                    {{$t('ob_photo1')}}
                    <span class="text-primary">{{$t('ob_photo2')}}</span>
                    {{$t('ob_photo3')}}
                </div>
            </vue-dropzone>
        </div>
    </div>
</template>

<script>
    import vue2Dropzone from "vue2-dropzone";
    import Dropzone from "dropzone";
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    Dropzone.autoDiscover = false;
    export default {
        name: "DropzoneComp",
        props:['image','title','edit'],
        data() {
            return {
                dropzoneOptions: {
                    url: "/api/images-save",
                    thumbnailWidth: 120,
                    maxFilesize: 10,
                    acceptedFiles: ".jpeg,.jpg,.png",
                    uploadMultiple: false,
                    addRemoveLinks: true,
                    dictRemoveFile: "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>",
                    dictFileTooBig: "Зображення більше ніж 10MB",
                    init:function(){
                        this.on("addedfile",function(){
                            if(this.files[1]!=null){
                                this.removeFile(this.files[0])
                            }
                        })
                    }
                }
            };
        },
        components: {
            vueDropzone: vue2Dropzone
        },
        watch:{
            image:function(newVal,oldVal){
                if(this.edit==!1)return;
                this.$refs.myVueDropzone.removeAllFiles();
                if(newVal!==""&&newVal!==null){
                    var mockFile = {
                        name: this.title,
                        type: 'image/jpeg',
                        status: Dropzone.ADDED,
                        url: newVal
                    };
                    this.$refs.myVueDropzone.dropzone.emit("addedfile", mockFile);
                    this.$refs.myVueDropzone.dropzone.emit("thumbnail", mockFile, newVal);
                    this.$refs.myVueDropzone.dropzone.files.push(mockFile);
                }
            },
            edit:function(){
                if(this.edit==!1){
                    this.$refs.myVueDropzone.removeAllFiles();
                }
            }
        },
        methods: {
            getFieled(){
                let files = this.$refs.myVueDropzone.getAcceptedFiles();
                return files;
            },
            sending(file, xhr, formData) {
                if(this.user){
                    formData.append('user_id', this.user.id);
                }
            },
            suc(file, response) {
                let files = this.$refs.myVueDropzone.getAcceptedFiles();
                if(response.suc){
                    this.$emit('SetImagesChild', response.image)
                }
            },
            remove(file, error, xhr) {
                this.$emit('SetImagesChild', "")
                axios.post("/api/images-delete", {name: file.name}).then(response => {
                    // let files = this.$refs.myVueDropzone.getAcceptedFiles();
                    // this.$emit('SetImagesChild', files)
                });
            }
        }
    };
</script>

