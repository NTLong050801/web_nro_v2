$(document).ready(function () {
  if (window.location.pathname === "/" || window.location.pathname === "/home") {

    const serverModal = new bootstrap.Modal(
      document.getElementById("serverModal"),
      { backdrop: "static" }
    );
    serverModal.show();
  }


  function handleModalEvents(modalId) {
    $(`#${modalId}`).on("click", function (event) {
      if ($(event.target).hasClass("modal")) {
        $(this).modal("hide");
      }
    });
    $(`#${modalId} a`).click(function () {
      $(`#${modalId}`).modal("hide");
    });
  }

  handleModalEvents("serverModal");
  $("#openModalButton").click(function () {
    var unifiedModal = new bootstrap.Modal(
      document.getElementById("unifiedModal")
    );
    unifiedModal.show();
  });

  $("#activateAccountButton").click(function () {
    var activationModal = new bootstrap.Modal(
      document.getElementById("activationModal")
    );
    activationModal.show();
  });

  $("#DownloadButton").click(function () {
    var downloadModal = new bootstrap.Modal(
      document.getElementById("DownloadModal")
    );
    downloadModal.show();
  });
  $("#GiftcodeButton").click(function () {
    var GiftcodeModal = new bootstrap.Modal(
      document.getElementById("GiftcodeModal")
    );
    GiftcodeModal.show();
  });

  $("#confirmActivateButton").click(function (event) {
    event.preventDefault();

    const form = $("#activationForm");
    const formData = form.serialize();
    const $button = $(this);

    $button.prop("disabled", true).text("Đang kích hoạt...");

    $.post("/Api/Users/Active", formData)
      .done(function (response) {
        const message = response.message;

        if (response.status === "success") {
          toastr.success(message);
          $("#activationModal").modal("hide");
          setTimeout(() => window.location.reload(), 1000);
        } else {
          toastr.error(message);
          $button.prop("disabled", false).text("Kích hoạt ngay");
        }
      })
      .fail(function (jqXHR) {
        let errorMessage = "Vui lòng thử lại sau.";

        if (jqXHR.status === 0) {
          errorMessage = "Không thể kết nối. Vui lòng kiểm tra mạng.";
        } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
          errorMessage = jqXHR.responseJSON.message;
        }

        toastr.error(errorMessage);
        $button.prop("disabled", false).text("Kích hoạt ngay");
      })
      .always(() => {
        console.log("Yêu cầu kích hoạt đã được xử lý.");
      });
  });

  $("#cancelActivateButton").click(function (event) {
    event.preventDefault();
    $("#activationModal").modal("hide");
  });

  const tabLinks = document.querySelectorAll(
    '#modalTabs a[data-bs-toggle="tab"]'
  );
  const typingElements = document.querySelectorAll(".typing-animation");

  tabLinks.forEach((link) => {
    link.addEventListener("shown.bs.tab", () => {
      typingElements.forEach((el) => {
        el.classList.remove("typing-animation");
        void el.offsetWidth;
        el.classList.add("typing-animation");
      });
    });
  });

  grecaptcha.ready(function () {
    $("#unifiedModal form").submit(function (event) {
      event.preventDefault();
      var form = $(this);
      var formData = form.serialize();
      var action = form.closest(".tab-pane").attr("id");

      grecaptcha
        .execute("6Le1Yd0qAAAAAMDdcYPYYBT3yzfvukTPK3mzocV_", {
          action: "submit",
        })
        .then(function (token) {
          formData += `&recaptcha_token=${token}&action=${action}`;

          $.post("/Api/Auth", formData)
            .done(function (response) {
              if (response.status === "success") {
                toastr.success(response.message);
                $("#unifiedModal").modal("hide");
                setTimeout(function () {
                  window.location.reload();
                }, 2000);
              } else {
                toastr.error(response.message);
              }
            })
            .fail(function (jqXHR) {
              var errorMessage =
                jqXHR.responseJSON?.message ||
                "Vui lòng thử lại trong ít phút nữa.";
              toastr.error(errorMessage);
            });
        });
    });
  });

  $("#recover-form").on("submit", function (e) {
    e.preventDefault();
    if ($(this).get(0).checkValidity()) {
      $.ajax({
        url: "/Api/Recover",
        method: "POST",
        data: $(this).serialize(),
        success: function (response) {
          if (response.status === "success") {
            toastr.success(response.message);
            $('button[type="submit"]').prop("disabled", true);
            $('input[name="email"]').prop("disabled", true);
          } else {
            toastr.error(response.message);
          }
        },
        error: function (error) {
          toastr.error("Đã xảy ra lỗi. Vui lòng thử lại.");
        },
      });
    }
  });
});
