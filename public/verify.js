jQuery(document).ready(function ($) {
  const $verifyBtn = $("#tcm-verify-btn");
  const $certInput = $("#tcm-cert");
  const $result = $("#tcm-result");

  if ($("#tcm-cert").val().trim() !== "") {
    $("#tcm-verify-btn").trigger("click");
  }

  function showMessage(message, color = "black") {
    $result.html(`<p style="color:${color}">${message}</p>`);
  }

  function renderCertificate(res) {
    return `
      <div class="tcm-certificate-result">
        <h3>Certificate Verified</h3>

        <p><strong>Name:</strong> ${res.name}</p>

        <p><strong>Courses:</strong> ${res.courses.join(", ")}</p>

        <p><strong>Certificate:</strong> ${res.cert}</p>

        <div class="tcm-qr">
          <img src="${res.qr}" alt="Certificate QR Code">
        </div>

        <br>

        <a class="tcm-download-btn" href="${res.pdf}" target="_blank">
          Download Certificate PDF
        </a>
      </div>
    `;
  }

  $verifyBtn.on("click", function () {
    const cert = $certInput.val().trim();

    if (!cert) {
      showMessage("Please enter a certificate number.", "red");
      return;
    }

    showMessage("Verifying certificate...");

    $verifyBtn.prop("disabled", true);

    $.ajax({
      url: tcm_ajax.url,
      method: "POST",
      data: {
        action: "tcm_verify",
        cert: cert,
      },
      timeout: 15000,

      success: function (res) {
        if (res && res.status === "found") {
          $result.html(renderCertificate(res));
        } else {
          showMessage("Certificate not found.", "red");
        }
      },

      error: function (xhr, status) {
        let message = "Something went wrong. Please try again.";

        if (status === "timeout") {
          message = "Request timed out. Please check your internet connection.";
        }

        showMessage(message, "red");
      },

      complete: function () {
        $verifyBtn.prop("disabled", false);
      },
    });
  });
});
