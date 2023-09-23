<template>
   
    <div class="dropzone" ref="dropzone">
        
        <div class="dz-message " v-if="type == 'file'">
            <div class="tu-uploadphoto">
            <i class="icon icon-grid"></i>
            <h5>Drag or <a style="color: #1da1f2">Click here</a> to upload photo</h5>
            </div>
         
        </div>

        <div class="dz-message" v-if="type == 'profile'">
            Drop files here or click to upload
        </div>

        <div class="fallback">
            <input name="file" type="file" />
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone'

export default {
    props: {
        type: {
            default: "file",
        },
        value: {
            default: null,
        },
        url: {
            type: String,
            default: "/media/upload",
        },
        collection: {
            type: String,
            required: true,
        },
        model: {
            type: String,
            required: true,
        },
        maxFiles: {
            type: Number,
            default: 100,
        },
        acceptedFiles: {
            type: String,
            default: "image/*",
        },
        maxFilesize: {
            type: Number,
            default: 2,
        },
    },
    watch: {
        value: function (val) {
            let myDropzone = this.myDropzone;
            if (!val) {
                myDropzone.removeAllFiles();
            }
        },
    },
    data: function () {
        return {
            myDropzone: null,
        };
    },
    mounted: function () {
        this.init();
    },
    methods: {
        init: function () {
            let self = this;

            let refDropzone = this.$refs.dropzone;

            const previewTemplate = `<div class="dz-preview dz-file-preview">
                <div class="dz-details">
                <div class="dz-thumbnail">
                    <img data-dz-thumbnail>
                    <span class="dz-nopreview">No preview</span>
                    <div class="dz-success-mark"></div>
                    <div class="dz-error-mark"></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                    <div class="progress">
                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                    </div>
                </div>
                <div class="dz-filename" data-dz-name></div>
                <div class="dz-size mb-2" data-dz-size></div>
                </div>
                </div>`;

            this.myDropzone = new Dropzone(refDropzone, {
                url: self.url,
                previewTemplate: previewTemplate,
                addRemoveLinks: true,
                maxFiles: self.maxFiles,
                acceptedFiles: self.acceptedFiles,
                maxFilesize: self.maxFilesize,
            });
            let myDropzone = this.myDropzone;
            if (self.value) {
                if (self.value instanceof Array) {
                    if (self.value.length > 0) {
                        for (
                            let index = 0;
                            index < self.value.length;
                            index++
                        ) {
                            const element = self.value[index];
                            myDropzone.emit("addedfile", element);
                            myDropzone.emit("complete", element);
                            myDropzone.emit("thumbnail", element, element.src);
                            myDropzone.files.push(element);
                        }
                    }
                } else {
                    myDropzone.emit("addedfile", self.value);
                    myDropzone.emit("complete", self.value);
                    myDropzone.emit("thumbnail", self.value, self.value.src);
                    myDropzone.files.push(self.value);
                }
            }

            myDropzone.on("addedfile", (file) => {
                var maxFiles = self.maxFiles;
                if (myDropzone.files.length > maxFiles) {
                    for (
                        let index = myDropzone.files.length - maxFiles;
                        index < myDropzone.files.length;
                        index++
                    ) {
                        var f = myDropzone.files[index];
                        myDropzone.emit("maxfilesexceeded", f);
                    }
                }
            });

            myDropzone.on("success", (file) => {
                if (file.xhr.response && typeof file.xhr.response == "string") {
                    if (self.maxFiles > 1) {
                        let fileValue = JSON.parse(JSON.stringify(self.value));
                        if (!fileValue) {
                            fileValue = [];
                        }
                        fileValue.push(JSON.parse(file.xhr.response));
                        self.$emit("input", fileValue);
                    } else {
                        self.$emit("input", JSON.parse(file.xhr.response));
                    }
                }
                self.$emit("error", null);
            });

            myDropzone.on("maxfilesexceeded", function (file) {
                myDropzone.removeFile(file);
                self.$emit("error", [
                    "File exceeds the maximum limit of " +
                        this.options.maxFiles +
                        " file" +
                        (this.options.maxFiles > 1 ? "s" : ""),
                ]);
            });

            myDropzone.on("error", (file, message) => {
                console.log(message);
                myDropzone.removeFile(file);
                let replaceString = String(message).replace(/MiB/g, "MB");
                // self.$emit('error', [replaceString])
                if (String(message).includes("File is too big")) {
                    self.$emit("error", ["File is too big"]);
                }
                if (String(message).includes("You can't upload files of this type.")) {
                    self.$emit("error", ["Invalid file type"]);
                }
                //  else {
                //     self.$emit("error", [replaceString]);
                // }
            });

            myDropzone.on("removedfile", (file) => {
                if (self.maxFiles > 1) {
                    let files = [];
                    for (
                        let index = 0;
                        index < myDropzone.files.length;
                        index++
                    ) {
                        const mdzFile = myDropzone.files[index];
                        if (mdzFile instanceof File) {
                            if (mdzFile.xhr && mdzFile.xhr.response) {
                                files.push(JSON.parse(mdzFile.xhr.response));
                            }
                        } else {
                            files.push(mdzFile);
                        }
                    }
                    self.$emit("input", files);
                } else {
                    if (myDropzone.files[0] instanceof File) {
                        self.$emit(
                            "input",
                            JSON.parse(myDropzone.files[0].xhr.response)
                        );
                    } else {
                        self.$emit("input", myDropzone.files[0]);
                    }
                }
            });

            myDropzone.on("sending", function (file, xhr, formData) {
                formData.append("collection", self.collection);
                formData.append("model", self.model);
            });
        },
    },
};
</script>
<style lang="scss">
.dz-thumbnail {
    position: relative;
     padding: .5rem 0; 
    height: 5rem;
    text-align: center;
    box-sizing: content-box;
}
.dropzone {
    width: 100%;
    position: relative;
    padding: 1.5rem;
    cursor: pointer;
    border-radius: 0.5rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}
.dz-preview {
    position: relative;
    vertical-align: top;
    margin: 1.15rem 0 0 1.5rem;
    background: #fff;
    font-size: 0.8125rem;
    box-sizing: content-box;
    cursor: default;
    max-width: 153px;
}

.dropzone > * {
    // flex-basis: 25%; /* Set each column to occupy 25% of the container width */
    box-sizing: border-box; /* Include padding in the width calculation */
    padding: 0.5rem; /* Adjust padding as desired */
}
.dz-size {
    padding: 0.3rem 0.625rem 0.625rem 0.625rem;
    font-size: 0.6875rem;
    font-style: italic;
}
.dz-filename {
    position:initial;
    width: 100%;
    /* overflow: hidden; */
    padding:0;
    background: transparent;
    word-wrap: break-word;
    line-height: 16px;
    
    white-space: break-spaces; 
    /* text-overflow: ellipsis; */
}
.dz-filename:hover {
    white-space: unset;
    text-overflow: inherit;
    word-wrap: break-word;
}
.dz-message {
    margin: 3rem 0 3rem;
    font-weight: 500;
    text-align: center;
}
.dz-remove {
    position: absolute;
    color: red;
    bottom: 0px;
    left: 25%;
    display: block;
    text-align: center;
    padding: 0.375rem 0;
    font-size: 0.75rem;
}
.dz-remove:hover, .dz-remove:focus {
    color: red;
}


.dropzone-profile-photo .dz-preview {
    margin: 0px;
}



.dropzone-profile-photo .dz-remove {
    color: #ea5455 !important;
}
.tu-uploadphoto img {
    border-radius: 10px;
    margin-bottom: 20px;
    max-width: 135px !important;
    min-width: 135px !important;
    object-fit: cover;
}

</style>
