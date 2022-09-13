


"use strict";

function setTheme(data) {
    const theme = $(data).children().attr('class');
    const type = theme.split(" ");
    const exp = (d => d.setFullYear(d.getFullYear() + 1))(new Date)
    document.cookie = (type[1] === 'feather-moon') ? 'theme=dark; expires=Thu, 01 Jan 2026 00:00:00 UTC; path=/' : 'theme=light; expires=Thu, 01 Jan 2026 00:00:00 UTC; path=/';
}

function toast(type, head, text) {
    toastr[type](text, head, {
        closeButton: true,
        tapToDismiss: false,
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2000,
        rtl: isRtl,
        progressBar: true,
    });
}


function sendReport(error) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    blockUI();
    $.ajax({
        type: "post",
        url: "http://127.0.0.1:8000/admin/miscellaneous/report-error",
        data: {
            error: error,
            from: location.href
        },
        success: function (response) {
            unblockUI();
            snb('success', 'Success', 'Report has been sent.');
            console.log(response);
        },
        error: function (response) {
            unblockUI();
            console.log(response);
            snb('error', 'Error',
                'There was an error while sending report. please contact the development team.');
            // console.log(response);
        }
    });
}


function blockUI(message = null) {
    $.blockUI({
        message:
            message ?? '<div class="d-flex justify-content-center align-items-center"><p class="mr-50 mb-0">Please wait...</p> <div class="spinner-grow spinner-grow-sm text-white" role="status"></div> </div>',
        css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
        },
        overlayCSS: {
            opacity: 0.5
        }
    });
}

function unblockUI() {
    $.unblockUI();
}



function rebound({
    selector = null,
    data = null,
    method = "POST",
    route = null,
    reset = true,
    reload = false,
    successCallback = null,
    errorCallback = null,
    loader = null,
    returnData = false,
}) {
    if (selector === null && data === null) {
        toast('error', 'Error', 'Please set the selector or data');
        return false
    }
    if (route == null) {
        toast('error', 'Error', 'Please set the route');
        return false
    }
    if (selector !== null) {
        var form = $(selector)[0];
        var formData = new FormData(form);
    }
    if (data !== null) {
        var formData = new FormData();

        $.each(data, function (key, value) {
            formData.append(key, value);
            console.log(key, value);
        });

    }

    const btn = $(selector).find('button[type="submit"]');
    const btn_text = $(btn).text();
    $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    blockUI(loader);
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    console.log(formData);
    $.ajax({
        type: method,
        url: route,
        processData: (data.length > 0) ? true : false,
        contentType: (data.length > 0) ? true : false,
        data: data ?? formData,
        success: function (response) {
            $(btn).html(btn_text);
            console.log(response);
            if (selector !== null) {
                $(selector).removeClass('was-validated');
                if (reset) {
                    $(selector)[0].reset();
                    $(selector).trigger("reset");
                    $(`form#${$(selector).attr('id')} select, form input[type=checkbox]`).trigger("change");
                    $(selector).find('.custom-file-label').html('Choose file');
                }

                $(selector).closest('.modal').modal('hide');
            }
            unblockUI();
            if (method == "get" || method == "GET") { } else {
                snb((response.type) ? response.type : 'success', response.header, response.message);
                if ($.fn.DataTable && response.table !== undefined) {
                    $('#' + response.table).DataTable().ajax.reload();
                }
            }

            if (reload || response.reload) {
                location.reload();
            }
            if (successCallback !== null) {
                successCallback.apply(null, arguments);
            }
            if (method == "get" || method == "GET" || returnData) {
                return response;
            }

            return true
        },
        error: function (xhr, status, error) {
            unblockUI();
            $(btn).html(btn_text);
            if (xhr.status == 422) {
                $.each(xhr.responseJSON.errors, function (key, item) {
                    snb('error', 'Error', item[0]);
                    console.log(item);
                });
            } else if (xhr.status == 500) {
                snb('error', 'Error500', error);
                // console.error(xhr.responseJSON.errors);
                report(xhr.responseJSON);
            } else {
                report(xhr.responseJSON);
                snb('error', 'Error', error);
                // console.error(xhr.responseJSON.errors);
            }


            if (errorCallback !== null) {
                errorCallback.apply(null, arguments);
            }

            return false;
        }
    });
}
