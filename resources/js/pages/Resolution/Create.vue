s

<script setup>
import Layout from "@/layouts/default.vue";
import { useTheme } from "vuetify";
import Tesseract from "tesseract.js";
import { createWorker } from "tesseract.js";
import { ref } from "vue";
import { PDFDocument, StandardFonts } from "pdf-lib";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import textEditor from "../components/textEditor.vue";
import Dropzone from "../components/Dropzone.vue";
const vuetifyTheme = useTheme();
const currentTheme = vuetifyTheme.current.value.colors;

const image = ref(null);
const extractedText = ref(null);
const imagename = ref(null);
const textSize = 35;
const progress = ref(null);
const refForm = ref();
const scan = ref(false);
const isPerformingOCR = ref(false);
const handleImageUpload = (event) => {
  image.value = event.target.files[0];
  imagename.value = event.target.files[0].name;
  form.description.value = "";
};

const performOCR = async () => {
  if (!image) {
    return;
  }
  isPerformingOCR.value = true;
  const worker = await createWorker({
    logger: (m) => {
      if (m.progress) progress.value = (m.progress * 100).toFixed(0);

      console.log(m);
    },
  });

  (async () => {
    await worker.loadLanguage("eng+tgl");
    await worker.initialize("eng+tgl");
    const {
      data: { text },
    } = await worker.recognize(image.value);
    form.description = text;
    console.log(text);
    isPerformingOCR.value = false;
    await worker.terminate();
  })();
};
const generatePDF = async () => {
  const formData = new FormData();
  formData.append("text", form.description);

  const response = await axios.post("/convert-to-pdf", formData, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
    responseType: "arraybuffer", // To handle binary response
  });

  if (response.status === 200) {
    // Handle success, e.g. show a download link
    const pdfBlob = new Blob([response.data], { type: "application/pdf" });
    const url = URL.createObjectURL(pdfBlob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "generated_pdf.pdf";
    a.click();
  } else {
    // Handle error
    console.error("PDF generation failed");
  }
};

const downloadPDF = (pdfBytes) => {
  const blob = new Blob([pdfBytes], { type: "application/pdf" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `${imagename.value}.pdf`;
  link.click();
};

const form = useForm({
  title: null,
  description: null,
  date: null,
  term: null,
  photo: [],
});

const submit = () => {
  refForm?.value?.validate().then((res) => {
    const { valid: isValid } = res;
    if (isValid) {
      form.post("/resolutions", {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (data) => {
          console.log(data);
        },
      });
    }
  });
};
</script>
<script>
import Editor from "@tinymce/tinymce-vue";
export default {
  components: {
    Editor,
  },
  name: "Staff",
  layout: (h, page) => h(Layout, [page]),
  data() {
    return {
      content: "",
      editorSettings: {
        plugins: [
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste code help wordcount",
        ],
        toolbar:
          "undo redo | formatselect | " +
          "bold italic backcolor | alignleft aligncenter " +
          "alignright alignjustify | bullist numlist outdent indent | " +
          "removeformat | help",
      },
    };
  },
};
</script>

<template>
  <VRow class="match-height">
    <v-container class="mx-5">
      <v-row>
        <VCol cols="12">
          <h1>Resolution</h1>
        </VCol>
      </v-row>

      <VForm ref="refForm" @submit.prevent="submit">
        <v-col cols="12">
          <VTextField
            type="title"
            v-model="form.title"
            :rules="[requiredValidator, alphaDashValidator]"
            label="Title"
            :error-messages="form.errors.title"
          />
        </v-col>
        <v-col cols="12">
          <VTextField
            type="date"
            v-model="form.date"
            :rules="[requiredValidator, alphaDashValidator]"
            label="Date"
            :error-messages="form.errors.date"
          />
        </v-col>
        <v-col cols="12">
          <VTextField
            v-model="form.term"
            :rules="[requiredValidator, alphaDashValidator]"
            label="Term"
            :error-messages="form.errors.term"
          />
        </v-col>

        <v-col cols="12">
          <editor v-model="form.description" :init="editorSettings" />
        </v-col>

        <Dropzone
          class="bg-white p-8 rounded-lg shadow-lg mb-2"
          collection="services"
          :url="route('api.media.upload')"
          v-model="form.photo"
          @input="
            ($event) => {
              form.photo = $event;
              form.clearErrors('photo');
            }
          "
          @error="
            ($event) => {
              if ($event && $event[0]) {
                form.setError('photo', $event[0]);
              }
            }
          "
          model="Resolution\Resolution"
          message="Drop files here or click to upload profile photo"
          acceptedFiles="image/jpeg,image/png"
        ></Dropzone>

        <VBtn class="ml-auto" type="submit"> Add </VBtn>
      </VForm>
    </v-container>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/libs/apex-chart.scss";
</style>
