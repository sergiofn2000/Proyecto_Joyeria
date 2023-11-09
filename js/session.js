
// Inicialización de variables
let sessionStarted = false
let session = {}
let token = localStorage.sessionToken

let request = {}

let destination = '';

// Función para establecer los parámetros de la sesión
function setSessionData(data) {
    sessionStarted = true
    session = data
    if (data.token) {
        token = data.token
        localStorage.sessionToken = data.token
    }

    $(document).trigger("setSessionData", [sessionStarted, session])

    if (destination != '') {
        
        $.router.go(destination)
        destination = ''
    }
    
}

// Función para iniciar sesión con token
function tokenLogin() {
    if (token) {
        request = {
            type: 'tokenLogin',
            data: {
                token: token,
            }
        }
        $.ajax({
            method: 'POST',
            url: '/Php/login.php',
            data: request,
            success: (response) => {
                response = JSON.parse(response)
                if (response.status == 'success' && response.data) {
                    setSessionData(response.data)
                } else if (response.status == 'error' && response.error) {
                    console.error(response.error)
                    $(document).trigger("setSessionData", [false, {}])
                } else {
                    $(document).trigger("setSessionData", [false, {}])
                }
            },
            error: err => console.error(err)
        })
    }
}

// Función para iniciar sesión con credenciales
function credentialLogin(user, pass, remember) {
    request = {
        type: 'credentialLogin',
        data: {
            user: user,
            pass: pass,
            remember: remember
        }
    }
    $.ajax({
        method: 'POST',
        url: '/Php/login.php',
        data: request,
        success: (response) => {
            response = JSON.parse(response)
            if (response.status == 'success' && response.data) {
                setSessionData(response.data)
                $('#loginModal').modal('hide')
            } else if (response.status == 'error' && response.error) {
                clearModalForms()
                console.error(response.error)
            } else if (response.status == 'denied') {
                clearModalForms()
                $('#loginAlert').removeClass('d-none').removeClass('alert-success').addClass('alert-danger').html(response.message)           
            }
        },
        error: err => console.error(err)
    })
}

// Función para cerrar sesión
function logout() {
    let request = {type:'logout'}
    $.ajax({
        method: 'POST',
        url: '/Php/cuenta.php',
        data: request,
        success: (response) => {
            response = JSON.parse(response)
            if (response.status == 'success') {
                sessionStarted = false
                session = {}
                token = undefined
                localStorage.sessionToken = undefined
                $(document).trigger("setSessionData", [false, {}])
                window.location.href = '/'
            }
        }
    })
}

// Función para registro
function singupUser(user, mail, name, dateOfBirth, password, confirmPassword, accept) {
    let request = {
        data: {
            user: user,
            mail: mail,
            name: name,
            date_of_birth: dateOfBirth,
            pass: password,
            confirm_pass: confirmPassword,
            check: accept
        },
        type: 'singup'
    }
    $.ajax({
        method: 'POST',
        url: '/Php/registro.php',
        data: request,

        success: (response) => {
            response = JSON.parse(response)
            if(response.status == 'success'){
                clearModalForms()
                $('#singupAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html("Su registro fue exitoso. Se ha enviado un mensaje a su dirección de correo electrónico para activar la cuenta. Si no lo encuentra, espere unos minutos y revise la carpeta de SPAM.")
            }else{
                $('#singupAlert').html(response.message).removeClass('d-none').addClass('alert-danger').removeClass('alert-success')
            }
        },

        error: (err) => console.error(err)
    })
}

// Función para contraseña olvidada
function forgotPassword(mail) {
    let request = {
        data: {
            mail: mail
        },
        type: 'requestResetMail'
    }
    $.ajax({
        method: 'POST',
        url: '/Php/resetpassword.php',
        data: request,

        success: response => {
            response = JSON.parse(response)
            if (response.status == 'success') {
                clearModalForms()
                $('#forgotAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html("Se le ha enviado un correo electrónico para cambiar su contraseña. Si no lo encuentra, espere unos minutos y revise la carpeta de SPAM.")
            } else {
                $('#forgotAlert').html(response.message).removeClass('d-none').addClass('alert-danger').removeClass('alert-success')
            }
        },

        error: err => console.error(err)
    })
}

// Función para contraseña olvidada - Cambiar contraseña
function changeForgottenPassword(token, pass, confirmPass) {
    let request = {
        data: {
            token: token,
            pass: pass,
            confirm_pass: confirmPass
        },
        type: 'changePassword'
    }
    $.ajax({
        method: 'POST',
        url: '/Php/resetpassword.php',
        data: request,

        success: response => {
            response = JSON.parse(response)
            if (response.status == 'success') {
                $('#changeModal').modal('hide')
                $('#loginModal').modal('show')
                $('#loginAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html("Se ha cambiado la contraseña con éxito.")
            } else {
                $('#changeAlert').html(response.message).removeClass('d-none').addClass('alert-danger').removeClass('alert-success')
            }
        },

        error: err => console.error(err)
    })
}

// Función para activar cuenta
function activateAccount(token) {
    let request = {
        type: 'activateAccount',
        data: {
            token: token
        }
    }
    $.ajax({
        method: 'POST',
        url: '/Php/registro.php',
        data: request,
        success: response => {
            response = JSON.parse(response)
            if (response.status == 'success') {
                $('#loginModal').modal('show')
                $('#loginAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html("Su cuenta ha sido activada con éxito. Introduzca sus credenciales para iniciar sesión.")
            } else if (response.status == 'denied') {
                $('#loginModal').modal('show')
                $('#loginAlert').removeClass('d-none').addClass('alert-danger').removeClass('alert-success').html(response.message)
            }   
            $('#loginModal').modal('show')
        },
        error: err => console.error(err)
    })
}

// Función para modificar cuenta
function modifyAccount (mail, name, dateOfBirth) {
    let request = {
        type: 'editAccountData',
        data: {
            name: name,
            mail: mail,
            date_of_birth: dateOfBirth
        }
    }
    $.ajax({
        method: 'POST',
        url: '/Php/cuenta.php',
        data: request,
        success: response => {
            response = JSON.parse(response)
            if (response.status == 'success') {
                $('#accountAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html("Se ha enviado un correo a su dirección de correo electrónico para confirmar los cambios.")
            } else {
                $('#accountAlert').removeClass('d-none').addClass('alert-danger').removeClass('alert-success').html(response.message)
            }
        },
        error: err => console.error(err)
    })
}

// Funcion para confirmar modificaciones en la cuenta
function confirmAccountModification (token) {
    let request = {
        type: 'confirmEditAccountData',
        data: {
            token: token
        }
    }
    $.router.go('/account')
    $.ajax({
        method: 'POST',
        url: '/Php/cuenta.php',
        data: request,
        success: response => {
            response = JSON.parse(response)
            if (response.status == 'success') {
                $('#accountAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html(response.message)
                if (response.data) {
                    setSessionData(response.data)
                }
            } else {
                $('#accountAlert').removeClass('d-none').addClass('alert-danger').removeClass('alert-success').html(response.message)
            }
        },
        error: err => console.error(err)
    })
}

// Funcion para consultar estado de la sesión
function getSessionData() {
    let status = [sessionStarted, session]
    return status
}

// Consulta inicial de sesión
request = {
    type: 'getSessionInfo'
}
$.ajax({
    method: 'POST',
    url: '/Php/cuenta.php',
    data: request,
    success: (response) => {
        response = JSON.parse(response)
        if (response.status == 'success' && response.data) {
            setSessionData(response.data)
        } else if (response.status == 'error' && response.error) {
            console.error(response.error)
        } else if (response.status == 'denied' && token) {
            tokenLogin()
        } else {
            $(document).trigger("setSessionData", [false, {}])
        }
    },
    error: err => console.error(err)
})