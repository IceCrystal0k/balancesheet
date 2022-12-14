"use strict";

//
// SweetAlert2 Initialization
//
if (typeof swal !== 'undefined') {
    // Set Defaults
    swal.mixin({
        width: 400,
        heightAuto: false,
        padding: '2.5rem',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonColor: null,
        cancelButtonClass: 'btn btn-secondary',
        cancelButtonColor: null
    });
}
