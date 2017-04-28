$(function() {
  $(document).ready(function () {

    $(".image-modal-open").click(function () {
      var img = $(this)[0];

      var modal = document.getElementById('image-modal');
      var modalImg = document.getElementById("modal-image-placeholder");

      modal.style.display = "block";
      modalImg.src = img.src;

      var closeModal = document.getElementsByClassName("close")[0];
      closeModal.onclick = function () {
        modal.style.display = "none";
      }
    });
  });
});