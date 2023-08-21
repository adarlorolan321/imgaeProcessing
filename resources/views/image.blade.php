<!DOCTYPE html>
<html>
<head>
  <title>OCR and Text Conversion</title>
</head>
<body>
  <input type="file" id="imageInput">
  <button id="convertButton">Convert to Text</button>
  <textarea id="outputText" rows="10" cols="40"></textarea>
  <a id="downloadLink" style="display: none;">Download Text</a>
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.2.2/dist/tesseract.min.js"></script>
  <script>
    const imageInput = document.getElementById('imageInput');
    const convertButton = document.getElementById('convertButton');
    const outputText = document.getElementById('outputText');
    const downloadLink = document.getElementById('downloadLink');

    imageInput.addEventListener('change', handleImageChange);
    convertButton.addEventListener('click', convertToText);

    let selectedImage = null;

    function handleImageChange(event) {
      selectedImage = event.target.files[0];
      outputText.value = '';
      downloadLink.style.display = 'none';
    }

    async function convertToText() {
      if (!selectedImage) {
        return;
      }

      Tesseract.recognize(
        selectedImage,
        'eng', // Language code for English
        { logger: info => console.log(info) }
      ).then(({ data: { text } }) => {
        outputText.value = text;
        downloadLink.href = createTextFile(text);
        downloadLink.style.display = 'inline';
      });
    }

    function createTextFile(text) {
      const blob = new Blob([text], { type: 'text/plain' });
      const url = URL.createObjectURL(blob);
      return url;
    }
  </script>
</body>
</html>
