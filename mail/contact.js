$(function () {
    $("#contactForm input, #contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
            // Manejo de errores si es necesario
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();

            var name = $("input#name").val();
            var email = $("input#email").val();
            var subject = $("input#subject").val();
            var message = $("textarea#message").val();
            var $this = $("#sendMessageButton");
            
            $this.prop("disabled", true); // Desactivar el botón de envío temporalmente

            $.ajax({
                url: "contact.php",
                type: "POST",
                data: { name: name, email: email, subject: subject, message: message },
                dataType: "json",
                success: function (response) {
                    $('#success').html("<div class='alert alert-success'>");
                    $('#success > .alert-success')
                        .html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>")
                        .append("<strong>" + response.message + "</strong>")
                        .append("</div>");

                    $('#contactForm').trigger("reset"); // Limpiar formulario
                },
                error: function (xhr) {
                    var response = JSON.parse(xhr.responseText);
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger')
                        .html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>")
                        .append("<strong>Error: " + response.message + "</strong>")
                        .append("</div>");

                    $('#contactForm').trigger("reset");
                },
                complete: function () {
                    setTimeout(function () {
                        $this.prop("disabled", false); // Reactivar botón de envío
                    }, 1000);
                }
            });
        },
        filter: function () {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function (e) {
        e.preventDefault();
        $(this).tab("show");
    });
});

$('#name').focus(function () {
    $('#success').html('');
});
