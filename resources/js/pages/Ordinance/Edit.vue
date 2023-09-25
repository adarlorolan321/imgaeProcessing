<script setup>
import Layout from "@/layouts/default.vue";
import { useTheme } from "vuetify";
import Tesseract from "tesseract.js";
import { createWorker } from "tesseract.js";
import { computed, ref, onMounted } from "vue";
import { PDFDocument, StandardFonts } from "pdf-lib";
import axios from "axios";
import { useForm, usePage } from "@inertiajs/vue3";
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

const data = computed(() => usePage().props.data);
const handleImageUpload = (event) => {
  image.value = event.target.files[0];
  imagename.value = event.target.files[0].name;
  form.description.value = "";
};





async function performOCR() {
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
}

const form = useForm({
  status: data.value.status,
  title: data.value.title,
  description: data.value.description,
  date: data.value.date,
  term: data.value.term,
  ordinance_no: data.value.ordinance_no,
  photo: JSON.parse(data.value.photo),
});

const submit = () => {
  refForm?.value?.validate().then((res) => {
    const { valid: isValid } = res;
    if (isValid) {
      form.patch(route("ordinances.update", data.value.id), {
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
      isLoaded: false,
      editorSettings: {
        plugins: [
          "advlist",
          "autolink",
          "lists",
          "link",
          "image",
          "charmap",
          "preview",
          "anchor",
          "searchreplace",
          "visualblocks",
          "code",
          "fullscreen",
          "insertdatetime",
          "media",
          "table",
          "help",
          "wordcount",
        ],
        toolbar:
          "undo redo | blocks | " +
          "bold italic backcolor | alignleft aligncenter " +
          "alignright alignjustify | bullist numlist outdent indent | " +
          "removeformat | help",
        content_style:
          "body { font-family:Helvetica,Arial,sans-serif; font-size:16px }",
      },
    };

  },
  mounted() {
    setTimeout(() => {
        this.isLoaded = true;
    }, 3000)
    
  }
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
          <VSelect
            type="status"
            disabled
            :items="['New', 'Old']"
            v-model="form.status"
            :rules="[requiredValidator, alphaDashValidator]"
            label="Is this a new Resolution?"
            :error-messages="form.errors.status"
          />
        </v-col>
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
            type="ordinance_no"
            v-model="form.ordinance_no"
            :rules="[requiredValidator, alphaDashValidator]"
            label="Ordinance No."
            :error-messages="form.errors.ordinance_no"
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

        <v-col cols="12" v-if="form.status === 'New'">
          <editor v-model="form.description" :init="editorSettings" />
        </v-col>
        <v-col cols="12" v-else>
          <VCard>
            <Dropzone
              v-if="isLoaded"
              class="bg-white p-8 rounded-lg shadow-lg mb-2"
              collection="ordinances"
              :url="route('api.media.upload')"
              :value="form.photo"
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
          </VCard>
        </v-col>

        <VBtn class="ml-auto" type="submit"> Add </VBtn>
      </VForm>
    </v-container>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/libs/apex-chart.scss";
</style>
