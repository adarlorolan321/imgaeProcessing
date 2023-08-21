
<template>
  <div class="container w-100 mx-5">
    <input type="file" @change="handleImageUpload" />
    <button class="btn btn-primary" @click="performOCR">Perform OCR</button>
    <br />
    <br />
   
    <textarea class="form-control" v-model="extractedText" rows="10" cols="100"></textarea>
  </div>
</template>

<script setup>
import Tesseract from 'tesseract.js';
import { createWorker } from 'tesseract.js';
import { ref } from 'vue';

const image = ref(null);
const extractedText = ref(null);

const handleImageUpload = (event) => {
  image.value = event.target.files[0];
  extractedText = '';
};

const performOCR = async () => {
  if (!image) {
    return;
  }

  
const worker = await createWorker({
  logger: m => console.log(m)
});

(async () => {
  await worker.loadLanguage('eng');
  await worker.initialize('eng');
  const { data: { text } } = await worker.recognize(image.value);
  extractedText.value = text;
  console.log(text);
  await worker.terminate();
})();
};
</script>

<style scoped></style>