<template>
    <div class="container w-100 mx-5">
      <div class="mb-3">
        <input type="file" class="form-control" @change="handleImageUpload" />
      </div>
      <div class="mb-3">
        <button class="btn btn-primary" @click="performOCR">Perform OCR</button>
      </div>
      <div class="mb-3">
        <textarea class="form-control" v-model="extractedText" rows="10" cols="100"></textarea>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        image: null,
        extractedText: "",
      };
    },
    methods: {
      handleImageUpload(event) {
        this.image = event.target.files[0];
      },
      async performOCR() {
        if (!this.image) {
          return;
        }
  
        const formData = new FormData();
        formData.append("file", this.image);
        formData.append("apikey", "K85505726188957"); // Replace with your OCR.Space API key
  
        try {
          const response = await axios.post(
            "https://api.ocr.space/parse/image",
            formData
          );
          if (
            response.data.ParsedResults &&
            response.data.ParsedResults.length > 0
          ) {
            this.extractedText = response.data.ParsedResults[0].ParsedText;
          } else {
            this.extractedText = "No text found.";
          }
        } catch (error) {
          console.error("OCR error:", error);
        }
      },
    },
  };
  </script>
  
  <style scoped></style>
  