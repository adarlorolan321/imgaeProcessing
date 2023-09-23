<template>
    <div>
      <editor
        v-model="content"
        :init="editorSettings"
      />
    </div>
  </template>
  
  <script>
  import Editor from '@tinymce/tinymce-vue';
  
  export default {
    components: {
      Editor,
    },
    data() {
      return {
        content: '',
        editorSettings: {
          plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount',
          ],
          toolbar:
            'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        },
      };
    },
  };
  </script>
  