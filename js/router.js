let firstCheck = false

let routes = [
    {
        route: '/',
        file: 'home.html',
        perms: 0,
    },
    {
        route: '/contact',
        file: 'contact.html',
        perms: 0,
    },
    {
        route: '/shop',
        file: 'shop.html',
        perms: 0,
    },
    {
        route: '/cart',
        file: 'carrito.html',
        perms: 0,
        customFunction: data => mostrar_carrito()

    },
    {
        route: '/account',
        file: 'cuenta.html',
        perms: 1,
        customFunction: data => {
            $("#accountAlert").addClass("d-none").removeClass('alert-success').addClass('danger-success')
            DisableEditing()
        }
    },
    {
        route: '/wishlist',
        file: 'wishlist.html',
        perms: 1,
        customFunction: data => window.setTimeout(mostrar_wishlist(), 100)
    },
    {
        route: '/activateaccount/:token',
        perms: 0,
        customFunction: data => {
            activateAccount(data.token)
            $.router.go('/')
        }
    },
    {
        route: '/resetpassword/:token',
        perms: 0,
        customFunction: data => {
            $.router.go('/')
            $('#changeModal').modal('show')
            $('#changeToken').val(data.token)
        }
    },
    {
        route: '/privacy-policy',
        file: 'privacy-policy.html',
        perms: 0
    },
    {
        route: '/cookies',
        file: 'cookies.html',
        perms: 0
    },
    {
        route: '/product/:productId',
        file: 'productos-detalles.html',
        perms: 0,
        customFunction: data => getProductDetails(data.productId)
    },
    {
        route: '/confirmaccountchanges/:token',
        perms: 1,
        customFunction: data => confirmAccountModification(data.token)
    },
    {
        route: '/proceedpayment/:session',
        perms: 1,
        customFunction: data => {
            let request = {
                type: 'validatePayment',
                data: {
                    session_id: data.session
                }
            }

            $.ajax({
                method: 'POST',
                url: '/Php/checkout.php',
                data: request,
                success: response => {
                    response = JSON.parse(response)
                    $.router.go('/account')
                    if (response.status == 'success') {
                        $('#accountAlert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').html(response.message)
                    } else {
                        $('#accountAlert').removeClass('d-none').addClass('alert-danger').removeClass('alert-success').html(response.message)
                    }
                },
                error: err => console.error(err)
            })
        }
    }
]

let actualPerms = getSessionData()[0] ? 1 : 0
$(document).on("setSessionData", (event, sessionStarted) => {
    actualPerms = sessionStarted ? 1 : 0
    if (!firstCheck) {
        firstCheck = true
        let ready = 0

        routes.forEach(element => {
            if (element.file) {
                $('.main-section').append(`<section data-s-route="${element.route}" class="d-none"></section>`)
                $(`section[data-s-route="${element.route}"]`).load(`/pages/${element.file}`, () => ready ++ )
            } else ready ++
        })

        function untilReady () {
            if (ready < routes.length) setTimeout(untilReady, 100)
            else {
                routes.forEach(element => {
                    $.router.add(element.route, (data) => {
                        if (element.perms > actualPerms) {
                            destination = document.location.href
                            $.router.go('/')
                            if (element.perms == 1) {
                                $('#loginAlert').removeClass('d-none').addClass('alert-danger').removeClass('alert-success').html("Debe iniciar sesión para acceder.")
                                $('#loginModal').modal('show')
                            }
                            return;
                        }
        
                        if (element.file) {
                            $('section').addClass('d-none')
                            $(`section[data-s-route="${element.route}"]`).removeClass('d-none')
                        }
                        if (element.customFunction) element.customFunction(data)
                        
                        
                    })
                });

                $(document).on("click", '*[data-route]', function (event) {
                    event.preventDefault()
                    $.router.go($(this).data('route'))
        
                })
        
                $.router.check()
        
                // Error 404
                function checkNotExisting() {
                    let currentroute = window.location.pathname
                    let parameters = $.router.currentParameters
        
                    let result = routes.find(element => {
        
                        let elementroute = element.route
                        Object.keys(parameters).forEach(key => elementroute = elementroute.replace(`:${key}`, parameters[key]))
                        return elementroute == currentroute
                    })
        
                    if (!result) {
                        $("section").addClass('d-none')
                        $("#404").removeClass('d-none')
                    }
                }
        
                $.router.go = (function () {
                    var cached_function = $.router.go;
                    return function () {
                        cached_function.apply(this, arguments);
                        checkNotExisting();
                    };
                })();
        
                checkNotExisting()
                $(document).trigger('RouterLoaded')
            }
        }

        untilReady()
    }
})