<template>
  <v-container class="mx-5">
    <v-row>
      <v-col cols="12">
        <input type="file" @change="handleImageUpload" label="Upload Image" />
      </v-col>
    </v-row>


  
    <VProgressCircular
      :rotate="360"
      :size="70"
      :width="6"
      :model-value="progress"
      color="primary"
    >
      {{ progress }}
    </VProgressCircular>
    
    <v-row class="mt-4">
      <v-col cols="12" sm="6">
        <v-btn color="primary" @click="performOCR" :disabled="isPerformingOCR">
          Perform OCR
        </v-btn>
        <v-progress-circular
          v-if="isPerformingOCR"
          indeterminate
          size="24"
          color="primary"
        ></v-progress-circular>
      </v-col>
      <v-col cols="12" sm="6">
        <v-btn color="success" @click="generatePDF" :disabled="!extractedText">
          Generate PDF
        </v-btn>
      </v-col>
    </v-row>
    <v-row class="mt-4">
      <v-col cols="12">
        <v-textarea v-model="extractedText" label="Extracted Text" rows="10" />
      </v-col>
    </v-row>
  </v-container>
</template>
<script setup>
import Tesseract from "tesseract.js";
import { createWorker } from "tesseract.js";
import { ref } from "vue";
import { PDFDocument, StandardFonts } from "pdf-lib";
import axios from "axios";

const image = ref(null);
const extractedText = ref(null);
const imagename = ref(null);
const textSize = 35;
const progress = ref(null);
const isPerformingOCR = ref(false);
const handleImageUpload = (event) => {
  image.value = event.target.files[0];
  imagename.value = event.target.files[0].name;
  extractedText.value = "";
};

const performOCR = async () => {
  if (!image) {
    return;
  }
  isPerformingOCR.value = true;
  const worker = await createWorker({
    logger: (m) => {
      if(m.progress)
      progress.value = (m.progress * 100).toFixed(0) 
     
      console.log(m)},
  });

  (async () => {
    await worker.loadLanguage("eng+tgl");
    await worker.initialize("eng+tgl");
    const {
      data: { text },
    } = await worker.recognize(image.value);
    extractedText.value = text;
    console.log(text);
    isPerformingOCR.value = false;
    await worker.terminate();
  })();
};
const generatePDF = async () => {
  const formData = new FormData();
  formData.append('text', extractedText.value);

  const response = await axios.post('/convert-to-pdf', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    },
    responseType: 'arraybuffer' // To handle binary response
  });

  if (response.status === 200) {
    // Handle success, e.g. show a download link
    const pdfBlob = new Blob([response.data], { type: 'application/pdf' });
    const url = URL.createObjectURL(pdfBlob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'generated_pdf.pdf';
    a.click();
  } else {
    // Handle error
    console.error('PDF generation failed');
  }


  // if (!extractedText.value) {
  //   return;
  // }

  // const pdfDoc = await PDFDocument.create();
  // const courierFont = await pdfDoc.embedFont(StandardFonts.Courier);
  // const page = pdfDoc.addPage();
  // const { width, height } = page.getSize();

  // // Draw a string of text toward the top of the page
  // const fontSize = 10;
  // page.drawText(extractedText.value, {
  //   x: 50,
  //   y: height - 4 * fontSize,
  //   size: fontSize,
  //   font: courierFont,
    
  // });

  // const pdfBytes = await pdfDoc.save();
  // downloadPDF(pdfBytes);
};

const downloadPDF = (pdfBytes) => {
  const blob = new Blob([pdfBytes], { type: "application/pdf" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `${imagename.value}.pdf`;
  link.click();
};
</script>

<style scoped></style>
