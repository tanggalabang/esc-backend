<!DOCTYPE html>
<html>

<head>
  <title>Show Files</title>
</head>

<body>
  <button id="mp3Button">Play MP3</button>
  <button id="mp4Button">Play MP4</button>
  <button id="jpgButton">View JPG</button>
  <button id="pdfButton">View PDF</button>

  <div id="mediaContainer"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#mp3Button').click(function() {
        playMedia("{{ $mp3File }}");
      });

      $('#mp4Button').click(function() {
        playVideo("{{ $mp4File }}");
      });

      $('#jpgButton').click(function() {
        viewImage("{{ $jpgFile }}");
      });

      $('#pdfButton').click(function() {
        viewPdf("{{ $pdfFile }}");
      });

      function playMedia(mediaUrl) {
        $('#mediaContainer').html('<audio controls><source src="' + mediaUrl + '" type="audio/mp3">Your browser does not support the audio element.</audio>');
      }

      function playVideo(videoUrl) {
        $('#mediaContainer').html('<video controls><source src="' + videoUrl + '" type="video/mp4">Your browser does not support the video element.</video>');
      }

      function viewImage(imageUrl) {
        $('#mediaContainer').html('<img src="' + imageUrl + '" alt="Image">');
      }

      function viewPdf(pdfUrl) {
        $('#mediaContainer').html('<iframe src="' + pdfUrl + '" style="width:100%; height:600px;" frameborder="0"></iframe>');
      }
    });
  </script>
</body>

</html>